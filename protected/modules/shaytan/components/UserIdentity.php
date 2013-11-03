<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    /**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
        /**
         * @var User $user
         */
        $user = User::model()->findOne(array('username' => $this->username));

		if (!isset($user))
			return ($this->errorCode = static::ERROR_USERNAME_INVALID);
		else if (!$user->checkPassword($this->password))
            return ($this->errorCode = static::ERROR_PASSWORD_INVALID);
		else {
            return ($this->errorCode = static::ERROR_NONE);
        }

	}
}