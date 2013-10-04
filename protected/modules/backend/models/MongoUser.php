<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alegz
 * Date: 12/2/12
 * Time: 3:11 PM
 */
class MongoUser extends EMongoDocument
{
    const TYPE = 'Mongo';

    /**
     * @var string
     * @desc e-mail пользователя, он же используется как логин
     */
    public $email;

    /**
     * @var string
     * @desc Пароль, ну тут всё понятно
     */
    public $password;

    /**
     * @var string
     * @desc Грубо говоря ключ для формирования пароля
     */
    public $salt;

    /**
     * @var string
     * @desc Имя пользователя
     */
    public $name;

    /**
     * @var role
     * @desc Роль пользователя
     */
    public $role;

    /**
     * @var int
     * @desc телефон пользователя в международном формате
     */
    public $phone;

    /**
     * @var array
     * @desc Список электронных контактов типо icq, jabber и прочее
     * IM - Instant Messanger
     */
    public $IMContacts = array();

    /**
     * @var array
     * @desc Список страничек в соц.сетях. SN - Social Networks
     */
    public $SNContacts = array();

    /**
     * @var string
     * @desc Ключ авторизции, обновляетс каждый запрос
     */
    public $authKey;

    /**
     * @var string
     * @desc Паследняя дата обновления данных пользователя.
     */
    public $lastUpdate;

    /**
     * @var string
     * @desc Паследний запрос на авторизацию
     */
    public $lastAuthRequest;

    /**
     * @param string $className
     * @return EMongoDocument
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string
     */
    public function getCollectionName()
    {
        return 'users';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('email, password, salt, name, phone', 'required'),
            array('email', 'email', 'allowEmpty' => false),
            array('name, password, salt', 'safe'),
        );
    }

    /**
     * @param $password
     */
    public function setPassword($password)
    {
        $this->salt = uniqid();
        $this->password = sha1($this->salt.$password);
    }

    public function updateAuthKey()
    {
        $this->authKey = uniqid();
        $this->lastAuthRequest = time();

        return $this->authKey;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function getById($id)
    {
        return $this->findByAttributes(array('email' => $id));
    }
}
