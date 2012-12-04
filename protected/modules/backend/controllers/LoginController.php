<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alegz
 * Date: 12/2/12
 * Time: 4:01 AM
 */

class LoginController extends Controller
{
    public $layout = '//layouts/backend';

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

        if (isset($_POST['MongoLoginForm']))
            $model->attributes = $_POST['MongoLoginForm'];

        $model->validate();

        foreach ($model->getErrors() as $error)
        {
            //  var_dump($error);
        }

        $this->render('authForm',
        array(
            'model' => $model
        ));
    }
}
