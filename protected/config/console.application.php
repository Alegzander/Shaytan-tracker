<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__DIR__),
	'name'=>'My Console Application',

	// preloading 'log' component
	'preload'=>array('log'),

    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.components.enums.*',
        'application.helpers.*',
        'application.extensions.giix-components.*',
        'application.extensions.MongoYii.*',
        'application.extensions.MongoYii.validators.*',
        'application.extensions.MongoYii.behaviors.*'
    ),

	// application components
	'components'=>array(
        'db'=>array('connectionString' => 'sqlite:'.dirname(__DIR__).DS.'data'.DS.'migrations.db',),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
        'authManager'  => array(
            'class'           => 'EMongoAuthManager',
            'connectionID'    => 'mongodb',
        ),
	),
    'params' => array(
        'allowEditExpire' => 1800,
    )
);