<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alegz
 * Date: 12/1/12
 * Time: 10:08 AM
 * To change this template use File | Settings | File Templates.
 */
class MongoUserIdentity extends CUserIdentity
{
    public $authKey;

    public function authenticate()
    {
        /**
         * @var MongoUser $user
         */
        $user = MongoUser::model()->getById($this->getId());

        if (!isset($user))
        {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
            $this->errorMessage = 'Не верный логин.';
        }
        else if ($user->password != sha1($user->salt.$this->password))
        {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
            $this->errorMessage = 'Не верный пароль.';
        }
        else
        {
            $this->errorCode = self::ERROR_NONE;

            $this->authKey = $user->updateAuthKey();
            $user->save();
        }

        return !$this->errorCode;
    }
}
