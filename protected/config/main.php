<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__DIR__),
	'name'=>'Anime Tracker',
	'theme' => 'default',
	'sourceLanguage' => 'ru',
	'language' => 'ru',

	// preloading 'log' component
	'preload'=>array(
		'log',
	),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'ext.YiiMongoDbSuite.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'myshitypassword',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
			'generatorPaths' => array(
				'ext.YiiMongoDbSuite.gii',
			)
		),
        'backend',
	),

	// application components
	'components'=>array(
		'mail' => array(
				'class' => 'ext.yii-mail.YiiMail',
				'transportType' => 'php',
				'viewPath' => 'application.views.mail',
				'logging' => true,
				'dryRun' => true
		),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format

		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName' => false,
            'caseSensitive' => false,
			'rules'=>array(
                'page/<page>' => 'site/index/page/<page>',
                'section/<section>'  =>  'site/section/view/<section>',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                'torrent/view/<id>' => 'torrent/view/id/<id>',
                'torrent/download/<id>' => 'torrent/download/id/<id>',
			),
		),
		// uncomment the following to use a MySQL database
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
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),

        'sphinx' => array(
            'class' => 'extension.DGSphinxSearch.DHSphinxSearch',
            'port' => 9323,

        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				/*array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning, info, trace',
				),*/
                array(
                    'class' => 'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
                ),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
		'request'=>array(
				'enableCookieValidation'=>true,
		),
		'assetManager'=>array(
				'basePath'=>realpath(dirname(dirname((__DIR__))).'/httpdocs/assets'),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		'resources' => array(
			'resources' => array()
		),
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
        /**
         * @var array
         * WARNING!!!
         * No keys should be set.
         * Use like list/
         */
        'extraTrackers' => array(
            //"http://re-tracker.uz/announce"
        ),
		'displayTorrents' => 100,
	),
);