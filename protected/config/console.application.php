<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

	// preloading 'log' component
	'preload'=>array('log'),

    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.components.enums.*',
        'application.helpers.*',
        'application.extensions.giix-components.*'
    ),

	// application components
	'components'=>array(
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
            'class'           => 'application.modules.srbac.components.SDbAuthManager',
            'connectionID'    => 'db',
            'itemTable'       => 'auth_item',
            'assignmentTable' => 'auth_assignment',
            'itemChildTable'  => 'auth_item_child',
        ),
	),
);