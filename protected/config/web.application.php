<?php

return array(
	'basePath'=>dirname(__DIR__),
	'name'=>'My Web Application',
    'theme' => 'twitter-bootstrap',

	// preloading 'log' component
	'preload'=>array('log', 'bootstrap'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',

	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
        'srbac' => array(
            'userclass'            => 'User', //default: User
            'userid'               => 'id', //default: userid
            'username'             => 'username', //default:username
            'delimeter'            => '@', //default:-
            'debug'                => false, //default :false
            'pageSize'             => 10, // default : 15
            'superUser'            => 'Authority', //default: Authorizer
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
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
        'bootstrap' => array(
            'class'         => 'ext.YiiBooster.components.Bootstrap',
            'responsiveCss' => false,
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
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);