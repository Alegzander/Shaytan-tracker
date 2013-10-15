<?php
/**
 * User: alegz
 * Date: 10/9/13
 * Time: 11:47 PM
 */

class TorrentController extends BaseController {
    public function actionCreate(){
        $form = new CreateTorrentForm();
        $post = \Yii::app()->request->getPost(get_class($form));

        $form->setAttributes($post);

        if (isset($post) && $form->validate()){
            //TODO create new torrent
        }

        $this->render('create', array('formModel' => $form));
    }
}