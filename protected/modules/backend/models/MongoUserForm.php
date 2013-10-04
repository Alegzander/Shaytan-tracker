<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alegz
 * Date: 12/4/12
 * Time: 10:14 PM
 */
class MongoUserForm extends CFormModel
{
    /**
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $captcha;

    /**
     * @var string
     */
    public $rememberMe;

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('login, password, captcha', 'required'),
            array('login', 'email'),
            array('rememberMe', 'boolean', 'allowEmpty' => true),
            array('captcha', 'captcha', 'allowEmpty' => false),
        );
    }

    public function attributeLabels()
    {
        return array(
            'login' => Yii::t('app', 'E-mail'),
            'password' => Yii::t('app', 'Пароль'),
            'captcha' => Yii::t('app', 'Каптча'),
            'rememberMe' => Yii::t('app', 'Запомнить меня')
        );
    }
}
