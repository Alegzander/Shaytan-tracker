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
         * @var Staff $user
         */
        $user = Staff::model()->findByPk($this->username);

		if (!isset($user))
			return static::ERROR_USERNAME_INVALID;
		else if (!$user->checkPassword($this->password))
            return static::ERROR_PASSWORD_INVALID;
		else
            return static::ERROR_NONE;
	}
}