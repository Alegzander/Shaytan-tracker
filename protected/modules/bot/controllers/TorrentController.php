<?php
/**
 * User: alegz
 * Date: 11/15/13
 * Time: 9:02 PM
 */

class TorrentController extends BaseController {
    public function actionCreate(){
        $this->setPageTitle(\Yii::t('app', 'Upload torrent.'));
        $form = new CreateTorrentForm();
        $post = \Yii::app()->request->getPost(get_class($form));
        \Yii::app()->session->add('isBot', true);

        if (!isset($post))
            throw new CHttpException(418, \Yii::t('error', 'You\'re doing it wrong.'));

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

                if (isset($this->zone)){
                    $torrentMeta->limitToZone = intval($form->limitToZone);
                    $torrentMeta->zoneId = $this->zone->getPrimaryKey();
                }

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

                if ($torrent->hasErrors() || $torrentMeta->hasErrors()){
                    throw new CHttpException(520, \Yii::t('error', 'Server-side error. Try again later.'));
                } else if ($form->hasErrors()) {
                    throw new CHttpException(462, CJSON::encode($form->getErrors()));
                }
            } else {
                throw new CHttpException(461, \Yii::t('error', 'Torrent already exists'));
            }

            unlink($form->torrent->getTempName());
        }

        $this->renderPartial('//json', array('data' => array('result' => 'success', 'message' => 'OK')));
    }
}