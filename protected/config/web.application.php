<?php
$vendor = dirname(dirname(__DIR__)) . DS . 'vendor';

Yii::setPathOfAlias('composer', $vendor);

return array(
	'basePath'=>dirname(__DIR__),
	'name'=>'My Web Application',
    'theme' => 'twitter-bootstrap',

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
        'ext.giix-components.*'
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'password',
			'ipFilters'=>array('127.0.0.1','::1'),
            'generatorPaths' => array(
                'ext.giix-core'
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
        'session' => array(
            'class' => 'CHttpSession',
            'autoStart' => true
        ),
		'user'=>array(
            'class'           => 'CWebUser',
            'allowAutoLogin'  => true,
            'stateKeyPrefix'  => 'client',
            'autoUpdateFlash' => false,
            'loginUrl'        => '/admin/login'
		),
        'authManager'  => array(
            'class'           => 'srbac.components.SDbAuthManager',
            'connectionID'    => 'db',
            'itemTable'       => 'auth_item',
            'assignmentTable' => 'auth_assignment',
            'itemChildTable'  => 'auth_item_child',
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
        )
	),
);