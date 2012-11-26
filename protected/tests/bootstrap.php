<?php

// change the following paths if necessary
$yiit=dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'yii'.DIRECTORY_SEPARATOR.'yiit.php';
$config=dirname(__DIR__).DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'test.php';

require_once($yiit);
require_once(__DIR__.DIRECTORY_SEPARATOR.'WebTestCase.php');

Yii::createWebApplication($config)->run();