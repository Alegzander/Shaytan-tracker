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

        if (isset($post) && $form->validate()){
            //TODO create new torrent
        }

        AssetsHelper::register(array('/js/torrent/torrent.js'));

        $this->render('create', array('formModel' => $form));
    }
}