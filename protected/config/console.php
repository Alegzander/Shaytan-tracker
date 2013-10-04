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
        'application.modules.backend.models.*',
        'application.modules.backend.components.*'
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
    'params'=>array(
        'categories' => require_once(__DIR__.DIRECTORY_SEPARATOR."categories.php"),
        'tmpDir'	 => dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR."tmp",
        // this is used in contact page
        'baseUrl' => 'http://tracker.loc',
        'domain' => 'tracker.loc',
        'adminEmail'=>'anime@anime.uz',
        'interval' => 600,
        'min_interval' => 300,
        'numwant' => 50,
        'forcePrivate' => true,
    )
);