<?php
defined('DS') || define('DS', DIRECTORY_SEPARATOR); //Alias for simplicity

return CMap::mergeArray(
	require(__DIR__. DS . 'web.application.php'),
	array(
		'components'=>array(
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
		),
	)
);
