<?php
defined('DS') || define('DS', DIRECTORY_SEPARATOR);

// change the following paths if necessary
$yii=dirname(__DIR__).DS.'lib'.DS.'yii'.DS.'yii.php';
$config=dirname(__DIR__).DS.'protected'.DS.'config'.DS.'main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();
