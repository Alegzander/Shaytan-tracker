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
		'ext.yii-mongoDbSuite.*',
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
        'backend' => array(
        ),
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
            'class' => 'WebUser',
            'authMethods' => array(
                'Mongo'
            )
		),
        'authManager' => array(
            'class' => 'CMongoDbAuthManager',
            'mongoConnectionId' => 'mongodb',
            'authFile' => 'auth_manager'
        ),
        'session' => array('class' => 'CHttpSession'),
		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName' => false,
            'caseSensitive' => false,
			'rules'=>array(
                /*Правила для поиска*/
                'search/<search:(.*)>/<sort>/<order>' => '/site/index',
                'search/<search:(.*)>/<sort>' => '/site/index',
                'search/<search:(.*)>' => '/site/index',
                'search' => '/site/index',
                /*Правила для поиска*/
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
            'server' => '127.0.0.1',
            'port' => 9323,
            'maxQueryTime' => 3000,
            'enableProfiling'=>0,
            'enableResultTrace'=>0,
            'fieldWeights' => array(
                'name' => 10000,
                'keywords' => 100,
            ),

        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning, info, trace',
				),
                /*array(
                    'class' => 'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
                ),*/
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

        'messages' => array(
            'class' => 'EMongoMessageSource',
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
        'authDuration' => 1209600,
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
        'search' => array(
            /**
             * Здесь передаются допустимые способы сортровки.
             * Этот список отображается на главной странице сверху
             * над пагинатором.
             * Здесь задаётся в виде следующего массива.
             * array(
             *  array('[label]', '[field]', [use])
             * )
             * label - Имя сортировки которое будет отображаться на главной,
             * field - поле по которому будет производится сортировка,
             */
            'allowedOrderList' => array(
                array(Yii::t('app', 'дате'), '_id'),
                array(Yii::t('app', 'раздающим'), 'peers.numSeeders'),
                array(Yii::t('app', 'скачивающим'), 'peers.numLeachers'),
                array(Yii::t('app', 'количествам скачиваний'), 'downloaded'),
                array(Yii::t('app', 'размерам'), 'totalSize'),
                array(Yii::t('app', 'названию'), 'name'),
            )
        ),
		'displayTorrents' => 100,
	),
);