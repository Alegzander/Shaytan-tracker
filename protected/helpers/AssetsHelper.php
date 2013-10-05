<?php
/**
 * @author: Yevgeny Skuridin
 */
class AssetsHelper
{
	/**
	 * Publish static files to assets and register them
	 *
	 * @param  array $array Array with files
	 * @return boolean
	 */
	public static function register($array)
	{
		if (!is_array($array)) return false;
		foreach ($array as $item) self::checkAndPublish($item);
	}

	/**
	 * Check file extension, publish to assets and register it
	 *
	 * @param  string $url File url
	 * @return boolean
	 */
	private static function checkAndPublish($url)
	{
		$cs   = Yii::app()->getClientScript();
		$am   = Yii::app()->getAssetManager();
		$sass = Yii::app()->sass;
		$url  = Yii::app()->theme->getBasePath() . $url;
		if (!file_exists($url)) return false;
		$info = pathinfo($url);

		switch (strtolower($info['extension'])) {
			case 'css':
				return $cs->registerCssFile($am->publish($url));
				break;
			case 'js':
				return $cs->registerScriptFile(
					$am->publish($url),
					Yii::app()->clientScript->coreScriptPosition);
				break;
			case 'scss':
				return $sass->registerFile($url);
			default:
				return false;
				break;
		}
	}

	/**
	 * Register image asset
	 *
	 * @param $url string Path to image
	 * @return string Asset string
	 */
	public static function registerImage($url)
	{
		try {
			return Yii::app()->assetManager->publish(Yii::app()->theme->getBasePath() . '/images' . $url);
		} catch (Exception $e) {
			return '';
		}
	}

	/**
	 * Register bootstrap datepicker
	 *
	 * @param string $selector
	 */
	public static function registerDatePicker($selector = '.datepicker')
	{
		Yii::app()->bootstrap->registerAssetCss('bootstrap-datepicker.css');
		Yii::app()->bootstrap->registerAssetJs('bootstrap.datepicker.js');
		Yii::app()->bootstrap->registerDatePicker($selector);
	}

	public static function registerMultiSelect($selector = '.multipleSelect')
	{
		$cs = Yii::app()->clientScript;
		$cs->packages = array(
			'multiselect' => array(
				'baseUrl' => Yii::app()->theme->baseURL . '/vendor/multiselect/',
				'js' => array('jquery.multiselect.min.js'),
				'css' => array('jquery.multiselect.css'),
				'depends' => array('jquery', 'jquery.ui'),
			)
		);
		$cs->registerPackage('multiselect');
		$cs->registerScript('multiselect', "jQuery('$selector').multiselect();");
	}
}
