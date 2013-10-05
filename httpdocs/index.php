<?php
defined('DS') || define('DS', DIRECTORY_SEPARATOR);

$environment = 'localhost';

//In this place your web environment depends on domain under witch your application is working
//Default value is localhost.
switch ($_SERVER["SERVER_NAME"])
{
    //Switching off debugging for production server
    case 'myawesomefreetracker.com':
        $environment = "production";
        define('YII_DEBUG',false);
        define('YII_TRACE_LEVEL', 0);
        break;

    //Doing nothing for dev debugging will be switched on later
    case 'dev.myawesomefreetracker.com':
        $environment = "development";
        break;
}

//Switching on debugging if constants  wasn't declared before.
defined('YII_DEBUG') || define('YII_DEBUG',true);
defined('YII_TRACE_LEVEL') || define('YII_TRACE_LEVEL',3);

$yii=dirname(__DIR__).DS.'lib'.DS.'yii'.DS.'yii.php';
$config=dirname(__DIR__).DS.'protected'.DS.'config'.DS.'web.'.$environment.'.php';

require_once($yii);
Yii::createWebApplication($config)->run();
