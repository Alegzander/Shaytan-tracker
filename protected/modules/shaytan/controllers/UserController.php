<?php
/**
 * User: alegz
 * Date: 10/28/13
 * Time: 3:15 AM
 */

class UserController extends ShaytanController{
    public function actionCreate(){
        $this->setPageTitle('Add new user');
        $user = new User();
        $post = \Yii::app()->request->getPost(get_class($user));

        $user->setAttributes($post);
        $user->setAttribute('updater', \Yii::app()->user->getId());
        $user->setPassword('password', $post['password']);

        if (isset($post) && $user->validate()) {
            $user->save();

            \Yii::app()->user->setFlash(TbAlert::TYPE_SUCCESS, \Yii::t('alert', 'User successfully added.'));

            $this->redirect($this->createUrl('view', array('id' => $user->getAttribute($user->primaryKey()))));
        }

        $user->password = '';

        $this->render('create', array('model' => $user));
    }
} 