<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alegz
 * Date: 12/8/12
 * Time: 2:15 AM
 */
class MongoUserAddForm extends CFormModel
{
    public $email;

    public $password;

    public $confirmPassword;

    public $name;

    public $phone;

    public $roles;

    public $socialNetworks = array();

    public $IM = array();

    public function rules()
    {
        return array(
            array('email, password, confirmPassword, name, roles, phone', 'required'),
            array('email', 'email', 'allowEmpty' => false),
            array('email', 'checkIfExists'),
            array('password', 'compare', 'allowEmpty' => false, 'compareAttribute' => 'confirmPassword'),
            array('roles', 'checkRoles'),
            array('name', 'safe'),
            array('phone', 'match',
                'pattern' => '/^[0-9]{9,12}$/'
            )
        );
    }

    public function attributeLabels()
    {
        return array(
            'email' => Yii::t('app', 'E-mail'),
            'password' => Yii::t('app', 'Пароль'),
            'confirmPassword' => Yii::t('app', 'Повтор пароля'),
            'name' => Yii::t('app', 'Имя (Ф.И.О) пользователя'),
            'roles' => Yii::t('app', 'Роли пользователя'),
            'phone' => Yii::t('app', 'Номер телефона'),
            'socialNetworks' => Yii::t('app', 'Аккаунты в осц. сетях'),
            'IM' => Yii::t('app', 'Аккаунты в IM (ICQ, Jabber, и.т.д.)')
        );
    }

    public function checkRoles()
    {
        $baseRoles = $this->getRoles();

        if (isset($this->roles) && is_array($this->roles) && count($this->roles) > 0)
        {
            foreach ($this->roles as $key => $role)
            {
                if (array_key_exists($role, $this->getRoles()) === false)
                {
                    $this->addError('roles', Yii::t('app', 'Роли {role} не существует, выберите пожалуйста из списка существующую роль',
                        array('{role}' => $role)));
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    public function checkIfExists()
    {
        $user = MongoUser::model()->findByAttributes(array('email' => $this->email));

        if (isset($user))
        {
            $this->addError('email', Yii::t('app', 'Пользователь с email-ом {email} уже существует.',
                array('{email}' => $this->email)));

            return false;
        }

        return true;
    }

    public function getRoles()
    {
        $roles = array();

        foreach (Yii::app()->authManager->getAuthItems(CAuthItem::TYPE_ROLE) as $roleName => $role)
            $roles[$roleName] = $role->description;

        return $roles;
    }
}
