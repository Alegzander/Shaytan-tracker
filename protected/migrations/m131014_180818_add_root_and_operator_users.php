<?php
defined('NL') || define('NL', "\n");

class m131014_180818_add_root_and_operator_users extends CDbMigration
{
	public function up()
	{
        \Yii::import('application.modules.shaytan.models.Staff');

        $root = new Staff();
        $root->username = 'root';
        $root->email = 'root@mailserver.com';
        $root->setPassword('shaytan');
        $root->suspend = EnabledState::DISABLED; //Means that user is enabled
        $root->updater = 'system';

        if (!$root->save()){
            var_dump($root->getErrors());
            throw new CException('Could not create root user.');
        }

        echo 'Added root user.', NL;

        $operator = new Staff();
        $operator->username = 'operator';
        $operator->email = 'operator@mailserver.com';
        $operator->setPassword('shaytan-tracker');
        $operator->suspend = EnabledState::DISABLED;
        $operator->updater = 'system';

        if (!$operator->save()){
            var_dump($root->getErrors());
            throw new CException('Could not create operator user.');
        }

        echo 'Added operator user.', NL;
        echo 'Operation finished.', NL;
	}

	public function down()
	{
		echo __CLASS__.' does not support migration down.'.NL;
		return false;
	}
}