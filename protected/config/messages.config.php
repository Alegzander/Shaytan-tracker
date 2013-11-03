<?php
/**
 * This is the configuration for generating message translations
 * for the Yii framework. It is used by the 'yiic message' command.
 */
defined('DS') || define('DS', DIRECTORY_SEPARATOR);
return array(
	'sourcePath'=>dirname(__DIR__),
	'messagePath'=> dirname(__DIR__).DS.'messages',
	'languages'=>array('en_US', 'ru_RU', 'uz_UZ'),
	'fileTypes'=>array('php'),
	'overwrite'=>true,
	'exclude'=>array(
		'.svn',
		'.gitignore',
		'yiilite.php',
		'yiit.php',
		'/i18n/data',
		'/messages',
		'/vendors',
		'/web/js',
	),
);
