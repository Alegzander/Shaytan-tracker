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

    public function actionView(){
        $id = \Yii::app()->request->getQuery('id');
        $user = User::model()->findByPk($id);

        if (!isset($user))
            throw new CHttpException(403, \Yii::t('error', 'Invalid id "{id}".', array('{id}' => $id)));

        $this->render('view', array('user' => $user));
    }
} 