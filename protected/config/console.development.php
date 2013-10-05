<?php
/**
 * User: alegz
 * Date: 10/5/13
 * Time: 11:55 AM
 */
defined('DS') || define('DS', DIRECTORY_SEPARATOR); //Alias for simplicity

return CMap::mergeArray(
    require('console.application.php'),
    array(
        'components' => array(
            'db'=>array(
                'connectionString' => 'sqlite:'.dirname(__DIR__).DS.'data'.DS.'testdrive.db',
            ),
            /*
            'db'=>array(
                'connectionString' => 'mysql:host=localhost;dbname=testdrive',
                'emulatePrepare' => true,
                'username' => 'root',
                'password' => '',
                'charset' => 'utf8',
            ),
            */
        )
    )
);