<?php

return CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(
		'components'=>array(
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
			'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__DIR__).DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'testdrive.db',
			),
			'mongodb' => array(
				'class'            => 'EMongoDB',
				'connectionString' => 'mongodb://127.0.0.1',
				'dbName'           => 'shaytan',
				'fsyncFlag'        => true,
				'safeFlag'         => true,
				'useCursor'        => false
			),
		),
	)
);
