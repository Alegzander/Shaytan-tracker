<?php

Yii::import('application.models._base.BaseUser');

class Staff extends EMongoDocument
{
    public $username;
    public $password;
    public $email;
    public $salt;
    public $suspend;
    public $updater;
    public $date_created;
    public $date_updated;

    /**
     * @return User
     */
    public static function model() {
		return parent::model(__CLASS__);
	}

    public function collectionName(){
        return 'staff';
    }

    public function behaviors(){
        return array(
            'EMongoTimestampBehaviour' => array(
                'class' => 'EMongoTimestampBehaviour',
                'createAttribute' => 'dateCreated',
                'updateAttribute' => 'dateUpdated',
                'notOnScenario' => array('search'),
                'timestampExpression' => 'time()',
                'setUpdateOnCreate' => true
            )
        );
    }

    /**
     * @param $password
     * @desc method for changing password
     */
    public function setPassword($password)
    {
        $this->salt     = sha1(time() . uniqid(date('c')));
        $this->password = sha1($this->email . $this->salt . $password);
    }

    /**
     * @param $password
     * @return bool
     * @desc method to compare password with one in database
     */
    public function checkPassword($password)
    {
        return (sha1($this->email . $this->salt . $password) == $this->password);
    }

    /**
     * @param int $length
     * @return string
     * @desc Password generation
     */
    public function pwgen($length = 12)
    {
        $lString = 'abcdefghijklmnopqrstuvwxyz';
        $uString = 'ZYXWVUTSRQPONMLKJIHGFEDCBA';
        $numbers = '1234567890';
        $sings   = '/*-+=_)(&^%$#@!"\'';

        $stringTypes = array($lString, $uString, $numbers, $sings);

        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $randType  = rand(0, (count($stringTypes) - 1));
            $randIndex = rand(0, (strlen($stringTypes[$randType]) - 1));
            $result .= substr($stringTypes[$randType], $randIndex, 1);
        }

        $this->setPassword($result);

        return $result;
    }
}