<?php

class m121201_213421_create_user_roles extends CDbMigration
{
	public function safeUp()
	{
        /**
         * @var CAuthManager $manager
         */
        $manager = Yii::app()->authManager;

        //Базовые операции
        $manager->createOperation("create");
        $manager->createOperation("view");
        $manager->createOperation("delete");
        $manager->createOperation("edit");
        $manager->createOperation("configure");
        $manager->createOperation("save");
        $manager->createOperation("send");

        $manager->save();
	}

	public function safeDown()
	{

	}
}