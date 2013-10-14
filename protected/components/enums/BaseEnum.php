<?php
/**
 * @author: Rustam Akhmedov
 * Date: 2/5/13
 * Time: 3:57 PM
 *
 */
class BaseEnum
{
	protected static $_enums = array();

	public static function getClass()
	{
		return get_called_class();
	}

	/**
	 * Returns array of $constName => $constValue
	 * @static
	 * @return array
	 */
	public static function getEnums()
	{
		$className = get_called_class();
		if (!isset(self::$_enums[$className])) {
			$class = new ReflectionClass($className);
			return self::$_enums[$className] = $class->getConstants();
		} else
			return self::$_enums[$className];
	}

	public static function getUiEnums()
	{
		$data = array_flip(self::getEnums());
		foreach ($data as &$value)
			$value = ucwords(strtolower(str_replace("_", " ", $value)));
		return $data;
	}

	public static function getLabel($value)
	{
		$labels = array_flip(self::getEnums());
		if (isset($labels[$value])) {
			return get_called_class() . '.' . $labels[$value];
		} else {
			return null;
		}

	}

	public static function getUiLabel($value)
	{
		if ($value === null)
			return null;

		$labels = array_flip(self::getEnums());

		if (isset($labels[$value]))
			return ucwords(strtolower(str_replace("_", " ", $labels[$value])));
		else
			return null;
	}

	/**
	 * User Friendly - $constValue => Translated $constKey
	 * @static
	 * @param string $scope - Language file
	 * @return array
	 */
	public static function getEnumsView($scope = 'app')
	{
		$class = get_called_class();
		return array_map(
			function ($string) use ($scope, $class) {
				return Yii::t($scope, $class . '.' . $string);
			},
			array_flip(self::getEnums())
		);
	}
}
