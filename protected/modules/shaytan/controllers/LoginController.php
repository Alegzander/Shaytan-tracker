<?php
/**
 * User: alegz
 * Date: 10/27/13
 * Time: 6:59 PM
 */

class LoginController extends ShaytanController {
    public $defaultAction = 'authenticate';

    public function actions(){
        return array(
            'captcha'=>array(
                'class'=>'CaptchaAction',
//                'backColor'=>0xFFFFFF,
                'transparent' => true,
                'paranoid' => true
            ),
        );
    }

    public function actionAuthenticate(){
        if (!\Yii::app()->user->getIsGuest())
            $this->redirect('/shaytan/default/index');

        $this->layout = '/layouts/main';
        $this->setPageTitle('Shaytan very secret control panel.');
        $form = new LoginForm();
        $post = \Yii::app()->request->getPost(get_class($form));

        $form->setAttributes($post);

        if (isset($post) && $form->validate()){
            $form->login();
            $this->redirect($this->createUrl('/shaytan/default/index'));
        }

        $this->render('authenticate', array('model' => $form));
    }

    public function actionLogout(){
        \Yii::app()->user->logout();
        $this->redirect($this->createUrl('/shaytan/login/authenticate'));
    }
} 