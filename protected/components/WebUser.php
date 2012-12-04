<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alegz
 * Date: 12/3/12
 * Time: 7:52 PM
 * To change this template use File | Settings | File Templates.
 */
class WebUser extends CWebUser
{
    public $authMethods = array();

    /**
     * @param mixed $id
     * @param array $states
     * @param bool $fromCookie
     * @return bool|void
     */
    public function beforeLogin($id, array $states, $fromCookie)
    {
        if ($fromCookie !== true)
            return true;

        if (!isset($state['type'], $state['key']))
            return false;

        //Вытаскиваем тип авторизации
        $class = $states['type'].'User';

        $user = $class::model()->getById($id);

        if (!isset($user))
            return false;

        if ($state['key'] != $user->getAuthKey())
            return false;

        return true;
    }
}
