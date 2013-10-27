<?php
/**
 * User: alegz
 * Date: 10/27/13
 * Time: 6:59 PM
 */

class LoginController extends ShaytanController {
    public function actions(){
        return array(
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=>0xFFFFFF,
            ),
        );
    }

    public function actionAuthenticate(){
        $this->layout = '/layouts/main';
        $this->setPageTitle('Shaytan very secret control panel.');
        $form = new LoginForm();
        $post = \Yii::app()->request->getPost(get_class($form));

        $form->setAttributes($post);

        if (isset($post) && $form->validate()){
            $form->login();
            $this->redirect($this->createUrl('/shaytan/default/index'));
        }

        \Yii::app()->clientScript->registerScript('refresh-captcha',
            $this->renderPartial('_refresh-captcha-js', array('imageCssPath' => 'div.captcha div.widget img'), true));
        $this->render('authenticate', array('model' => $form));
    }

    public function actionLogout(){
        \Yii::app()->user->logout();
        $this->redirect($this->createUrl('/shaytan/login/authenticate'));
    }
} 