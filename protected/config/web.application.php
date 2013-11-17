<?php
$vendor = dirname(dirname(__DIR__)) . DS . 'vendor';

Yii::setPathOfAlias('composer', $vendor);

return array(
	'basePath'=>dirname(__DIR__),
	'name'=>'My tracker',
    'theme' => 'twitter-bootstrap',
    'sourceLanguage' => '00_00',

	// preloading 'log' component
	'preload'=>array('log', 'bootstrap'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
        'application.models.forms.*',
		'application.components.*',
        'application.components.enums.*',
        'application.helpers.*',
        'composer.richthegeek.phpsass.*',
        'application.modules.srbac.controllers.SBaseController', // SRBAC
        'ext.giix-components.*',

        'application.extensions.MongoYii.*',
        'application.extensions.MongoYii.validators.*',
        'application.extensions.MongoYii.behaviors.*',
        'application.modules.shaytan.models.User'
	),

	'modules'=>array(
        'shaytan',
        'bot',

		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'password',
			'ipFilters'=>array('127.0.0.1','::1'),
            'generatorPaths' => array(
                'ext.giix-core',
                'bootstrap.gii'
            )
		),
        'srbac' => array(
            'userclass'            => 'User', //default: User
            'userid'               => 'id', //default: userid
            'username'             => 'username', //default:username
            'delimeter'            => '@', //default:-
            'debug'                => false, //default :false
            'pageSize'             => 10, // default : 15
            'superUser'            => 'root', //default: Authorizer
            'alwaysAllowed'        => require(dirname(__DIR__).DS.'modules'.DS.'srbac'.DS.'components'.DS.'allowed.php'),
            'userActions'          => array('Show', 'View', 'List'), //default: array()
            'listBoxNumberOfLines' => 15, //default : 10
            'imagesPath'           => 'srbac.images', // default: srbac.images
            'imagesPack'           => 'tango', //default: noia
            'iconText'             => true, // default : false
            'alwaysAllowedPath'    => 'srbac.components', // default: srbac.components
        ),
	),

	// application components
	'components'=>array(
        'search' => array(
            'class' => 'ext.yii-sphinx.components.DGSphinxSearch',
            'server' => '127.0.0.1',
            'port' => 9312,
            'maxQueryTime' => 3000,
            'enableProfiling'=>0,
            'enableResultTrace'=>0,
            'fieldWeights' => array(
                'name' => 10000,
                'keywords' => 100,
            ),
        ),
        'session' => array(
            'class' => 'CHttpSession',
            'autoStart' => true
        ),
		'user'=>array(
            'class'           => 'CWebUser',
            'allowAutoLogin'  => true,
            'stateKeyPrefix'  => 'client',
            'autoUpdateFlash' => false,
            'loginUrl'        => '/shaytan/login'
		),
        'authManager'  => array(
            'class'           => 'EMongoAuthManager',
            'connectionID'    => 'mongodb',
        ),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName' => false,
			'rules'=>array(
				'<controller:\w+>/<id:[0-9a-fA-F]{24}>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:[0-9a-fA-F]{24}>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
        'bootstrap' => array(
            'class'         => 'ext.YiiBooster.components.Bootstrap',
            'responsiveCss' => false,
        ),
        'sass'         => array(
            'class' => 'ext.Sass',
            'style' => 'compact',

        ),
        'clientScript' => array(
            'coreScriptPosition' => CClientScript::POS_END,
            'defaultScriptPosition' => CClientScript::POS_END,
            'defaultScriptFilePosition' => CClientScript::POS_END,
        ),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'root@mailserver.com',

        //Is made like calling createUrl method 1st - path, 2nd GET params
        'rulesUrl' => array(
            '/index/page',
            array('view' => 'rules')
        ),
        'supportedLanguages' => array(
            'en_US' => \Yii::t('config', 'English'),
            'ru_RU' => \Yii::t('config', 'Russian'),
            'uz_UZ' => \Yii::t('config', 'Uzbek')
        ),
        'torrent' => array(
            'allowNoAnnounce' => true,
        ),
        'defaultLanguage' => 'en_US',
        'cookieExpire' => (60*60*24*365),
        'allowEditExpire' => 1800,
	),
);