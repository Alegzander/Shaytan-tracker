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
            $torrent->meta = new TorrentMeta();

            $torrentData = Lightbenc::bdecode_file($form->torrent->getTempName());

            if (isset($torrentData)){
                foreach ($torrentData as $key => $value)
                    if (array_key_exists($key, $torrent->getAttributes()))
                        $torrent->setAttribute(Torrent::normalizeKeyName($key), $value);
            }

            $torrent->validate();

            die(CVarDumper::dump($torrent->attributes, 100, true));

            unlink($form->torrent->getTempName());
        }

        AssetsHelper::register(array('/js/torrent/torrent.js'));

        $this->render('create', array('formModel' => $form));
    }
}