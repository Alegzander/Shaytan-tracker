<?php
/**
 * User: alegz
 * Date: 10/29/13
 * Time: 8:31 AM
 */

class NetworkController extends ShaytanController {
    public function actionCreate(){
        $network = new Network();
        $post = \Yii::app()->request->getPost(get_class($network));

        $network->setAttributes($post);

        if (isset($post) && $network->validate()){
            $network->save();
            $this->redirect($this->createUrl('/shaytan/network/view', array('id' => $network->name)));
        }

        if (is_array($network->zones))
            $network->zones = implode(', ', $network->zones);

        $this->render('create', array('model' => $network));
    }
} 