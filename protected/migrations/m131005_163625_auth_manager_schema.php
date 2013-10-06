<?php

class m131005_163625_auth_manager_schema extends CDbMigration
{
    public function up()
    {
        $transaction = $this->getDbConnection()->beginTransaction();

        try{
            $sql = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.__CLASS__.'.sql');
            $this->getDbConnection()->createCommand($sql)->execute();
            $transaction->commit();
        } catch (CDbException $error){
            $transaction->rollback();
            throw $error;
        }
    }

    public function down()
    {
        echo __CLASS__." does not support migration down.\n";
        return false;
    }
}