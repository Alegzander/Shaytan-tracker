<?php
/**
 * User: alegz
 * Date: 10/9/13
 * Time: 11:47 PM
 */

class TorrentController extends BaseController {
    public function actionCreate(){
        $this->setPageTitle(\Yii::t('app', 'Upload torrent.'));
        $form = new CreateTorrentForm();
        $post = \Yii::app()->request->getPost(get_class($form));

        $form->setAttributes($post);
        $form->torrent = CUploadedFile::getInstance($form, 'torrent');

        if (isset($post) && $form->validate()){
            //TODO create new torrent
            $torrent = new Torrent();
            $torrentMeta = new TorrentMeta();
            $tags = explode(',', $form->tags);

            $torrentData = Lightbenc::bdecode_file($form->torrent->getTempName());

            if (isset($torrentData)){
                foreach ($torrentData as $key => $value){
                    $attributeName = Torrent::normalizeKeyName($key);
                    if (array_key_exists($attributeName, $torrent->getAttributes()))
                        $torrent->setAttribute($attributeName, $value);
                }
            }

            $hash = base64_encode(sha1(Lightbenc::bencode($torrent->info), true));

            if ($torrentMeta->count(array('hash' => $hash)) === 0){
                $torrentMeta->hash = $hash;
                $torrent->info['pieces'] = base64_encode($torrent->info['pieces']);
                $torrentMeta->name = !empty($form->name) ? $form->name : $torrent->info['name'];
                $torrentMeta->description = intval($form->descriptionFromFile) === EnabledState::DISABLED ? $form->description : $torrent->comment;
                $torrentMeta->informationUrl = $form->informationUrl;
                $torrentMeta->hidden = intval($form->hidden);
                $torrentMeta->tags = $tags;

                if (isset($torrent->info['files']) && is_array($torrent->info['files']))
                    foreach ($torrent->info['files'] as $file)
                        $torrentMeta->size += intval($file['length']);
                else
                    $torrentMeta->size = intval($torrent->info['length']);

                if (!$torrent->save())
                    $form->addError('torrent', \Yii::t('error', 'Could not save torrent file.'));

                if (!$torrent->hasErrors())
                    $torrentMeta->torrentId = $torrent->getAttribute($torrent->primaryKey());

                if (!$torrentMeta->save()){
                    $form->addError('torrent', \Yii::t('error', 'Failed to validate meta data.'));

                    if (!$torrent->getIsNewRecord())
                        $torrent->delete();
                }

                foreach ($tags as $tagName){
                    $tag = Tag::model()->findOne(array('tag' => $tagName));

                    if (!isset($tag))
                        $tag = new Tag();

                    $tag->tag = $tagName;

                    array_push($tag->torrents, $torrentMeta->torrentId);

                    $tag->save();
                    unset($tag);
                }

                if (!$torrent->hasErrors() && !$torrentMeta->hasErrors() && !$form->hasErrors()){
                    $expireTime = \Yii::app()->getParams()->allowEditExpire;
                    \Yii::app()->session->add(strval($torrentMeta->torrentId), time()+$expireTime);

                    $this->redirect($this->createUrl('/torrent/view', array('id' => $torrentMeta->torrentId)));
                }
            } else {
                $form->addError('torrent', \Yii::t('error', 'Torrent was already uploaded early.'));
            }

            unlink($form->torrent->getTempName());
        }

        AssetsHelper::register(array('/js/torrent/torrent.js'));

        //Forming tag list
        /**
         * @var Tag[] $tags
         */
        $tags = Tag::model()->findAll();
        $tagList = array();

        foreach ($tags as $item){
            array_push($tagList, $item->tag);
        }

        $this->render('create', array('formModel' => $form, 'tagList' => $tagList));
    }

    public function actionView(){
        $id = \Yii::app()->request->getQuery('id');

        if (($torrent = Torrent::model()->findByPk($id)) === null)
            throw new CHttpException(403, \Yii::t('error', 'Invalid id "{id}" or torrent is blocked.', array(
                '{id}' => $id
            )));

        $editAllowed = ( $expireTime = \Yii::app()->session->get(strval($torrent->getPrimaryKey())) ) !== null
            && time() <= $expireTime;

        $tags = Tag::model()->findAll();
        $tagList = array();

        foreach ($tags as $item){
            array_push($tagList, $item->tag);
        }

        $this->render('view', array('torrent' => $torrent, 'tagList' => $tagList, 'editAllowed' => $editAllowed));
    }

    public function actionUpdate(){
        if (!\Yii::app()->request->getIsAjaxRequest())
            throw new CHttpException(403);

        $id = \Yii::app()->request->getPost('pk');
        $name = \Yii::app()->request->getPost('name');
        $value = \Yii::app()->request->getPost('value');

        $torrentMeta = TorrentMeta::model()->findByPk($id);

        if (!isset($torrentMeta))
            throw new CHttpException(403);

        if (strcmp($name, 'tags') === 0){
            $oldTagList = $torrentMeta->tags;
            $newTagList = $value;
        }

        $torrentMeta->setAttribute($name, $value);

        if (!$torrentMeta->save(true, array($name))){
            throw new CHttpException(418, $torrentMeta->getError($name));
        } else {
            if (isset($oldTagList, $newTagList)){
                foreach ($oldTagList as $tag){
                    if (!in_array(trim($tag), $newTagList))
                        TagHelper::removeTorrentFromTag($tag, $torrentMeta->torrentId);
                }

                unset($tag);

                foreach ($newTagList as $tag){
                    if (!in_array($tag, $oldTagList)){
                        if (($tagModel = Tag::model()->findByTag($tag)) === null){
                            $tagModel = new Tag;
                            $tagModel->tag = $tag;
                        }

                        array_push($tagModel->torrents, $torrentMeta->torrentId);
                        $tagModel->save();
                    }
                }
            }
        }

        \Yii::app()->session->add(strval($torrentMeta->torrentId), time()+\Yii::app()->getParams()->allowEditExpire);
    }

    public function actionDelete(){
        $id = \Yii::app()->request->getQuery('id');

        if (($torrent = Torrent::model()->findByPk($id)) === null)
            throw new CHttpException(403, \Yii::t('error', 'Invalid id "{id}".', array('{id}' => $id)));

        TagHelper::removeTorrent($torrent->getPrimaryKey());

        \Yii::app()->session->remove(strval($torrent->getPrimaryKey()));
        $torrentMeta = $torrent->meta;
        $torrentMeta->delete();
        $torrent->delete();

        $this->redirect('/site/index');
    }
}