<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alegz
 * Date: 12/2/12
 * Time: 4:01 AM
 */

class LoginController extends BController
{
    public $layout = '//layouts/backend';

    /*public function accessRules()
    {
        return array(
            array(
                'allow',
                'actions' => array('logout'),
                'users' => array('@')
            ),
            array(
                'deny',
                'actions' => array('authenticate'),
                'users' => array('@')
            )
        );
    }*/

    public function actions()
    {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction'
            )
        );
    }

    public function actionAuthenticate()
    {
        $model = new MongoUserForm();

        if (isset($_POST['MongoUserForm']))
        {
            $model->attributes = $_POST['MongoUserForm'];

            if ($model->validate())
            {
                $identity = new MongoUserIdentity($model->login, $model->password);

                $fields = array(
                    $identity::ERROR_USERNAME_INVALID => 'login',
                    $identity::ERROR_PASSWORD_INVALID => 'password'
                );

                $identity->authenticate();

                if($identity->errorCode != $identity::ERROR_NONE)
                {
                    $model->addError($fields[$identity->errorCode], $identity->errorMessage);
                }
                else
                {
                    $duration = 0;

                    if ($model->rememberMe)
                        if (isset(Yii::app()->getParams()->authDuration))
                            $duration = Yii::app()->getParams()->authDuration;
                        else
                            $duration = 1209600;//14 дней

                    file_put_contents('/tmp/shit', $duration);

                    Yii::app()->user->login($identity, $duration);
                    Yii::app()->user->setState('key', $identity->authKey);

                    $this->redirect(Yii::app()->user->returnUrl);
                }
            }
        }

        $labelList = $model->attributeLabels();

        $this->render('authForm',
        array(
            'model' => $model,
            'label' => $labelList
        ));
    }

    public function actionLogout()
    {
        if (Yii::app()->user->isGuest)
        {
            $this->redirect('/');
        }
        else
        {
            Yii::app()->user->logout();
            $this->redirect('/backend/login/authenticate');
        }
    }
}
