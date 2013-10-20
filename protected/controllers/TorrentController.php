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
                $torrentMeta->remake = intval($form->remake);
                $torrentMeta->tags = $tags;

                if (isset($torrent->info['files']) && is_array($torrent->info['files']))
                    foreach ($torrent->info['files'] as $file)
                        $torrentMeta->size += intval($file['length']);

                if (!$torrent->save())
                    $form->addError('torrent', \Yii::t('error', 'Could not save torrent file.'));

                if (!$torrent->hasErrors())
                    $torrentMeta->torrentId = $torrent->getAttribute($torrent->primaryKey());

                if (!$torrentMeta->save()){
                    $form->addError('torrent', \Yii::t('error', 'Failed to validate meta data.'));
                    $torrent->delete();
                }

                $this->redirect($this->createUrl('view', array('id' => $torrentMeta->torrentId)));
            } else {
                $form->addError('torrent', \Yii::t('error', 'Torrent was already uploaded early.'));
            }

            unlink($form->torrent->getTempName());
        }

        AssetsHelper::register(array('/js/torrent/torrent.js'));

        $this->render('create', array('formModel' => $form));
    }

    public function actionView(){
        $id = \Yii::app()->request->getQuery('id');

        if (($torrent = Torrent::model()->findByPk($id)) === null)
            throw new CHttpException(403, \Yii::t('error', 'Invalid id "{id}" or torrent is blocked.', array(
                '{id}' => $id
            )));
    }
}