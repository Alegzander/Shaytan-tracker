<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

	// preloading 'log' component
	'preload'=>array('log'),

    // autoloading model and component classes
    'import'=>array(
        'application.models.*',
        'application.components.*',
        'ext.YiiMongoDbSuite.*',
    ),

	// application components
	'components'=>array(
        'db'=>array(
            'connectionString' => 'sqlite:'.dirname(__DIR__).'/data/testdrive.db',
        ),
        'mongodb' => array(
            'class'            => 'EMongoDB',
            'connectionString' => 'mongodb://127.0.0.1',
            'dbName'           => 'shaytan',
            'fsyncFlag'        => true,
            'safeFlag'         => true,
            'useCursor'        => false
        ),
        'authManager' => array(
            'class' => 'CMongoDbAuthManager',
            'mongoConnectionId' => 'mongodb',
            'authFile' => 'auth_manager'
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
);