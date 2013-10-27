<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
    public $username;
    public $password;
    public $rememberMe;
    public $captcha;

    private $identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            // username and password are required
            array('username, password, captcha', 'required'),
            // rememberMe needs to be a boolean
            array('rememberMe', 'boolean'),
            // password needs to be authenticated
            array('password', 'authenticate'),
            array('captcha', 'captcha', 'allowEmpty' => false)
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'rememberMe' => \Yii::t('form-label', 'Remember me next time'),
            'username' => \Yii::t('form-label', 'Login'),
            'password' => \Yii::t('form-label', 'password'),
            'captcha' => \Yii::t('form-label', 'Captcha')
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->identity = new UserIdentity($this->username, $this->password);

            $authStatus = $this->identity->authenticate();

            if ($authStatus === UserIdentity::ERROR_USERNAME_INVALID)
                $this->addError('username', 'Incorrect username.');
            else if ($authStatus === UserIdentity::ERROR_PASSWORD_INVALID)
                $this->addError('password', 'Incorrect password.');
        }
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function login()
    {
        if ($this->identity === null) {
            $this->identity = new UserIdentity($this->username, $this->password);
            $this->identity->authenticate();
        }

        if ($this->identity->errorCode === UserIdentity::ERROR_NONE) {
            $duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
            Yii::app()->user->login($this->identity, $duration);
            return true;
        } else
            return false;
    }
}
