<?php
/**
 * Ключ -e означабщий параметр environment
 */
$options = 'e:';

/**
 * Извлекаем параметры из списка аргументов командной строки
 */
$params = getopt($options);

/**
 * Задали значение по умолчанию
 */
$environment = 'localhost';

/**
 * Если задали environment применяем его.
 */
if (isset($params, $params['e']))
	$environment = $params['e'];

/**
 * Определяем debug параметр если нужно, в случае с production средой его нужно выключить,
 * ну и смотрим чтоб левый environment не подсунули
 */
switch ($environment)
{
	case 'production':
		define('YII_DEBUG', false);
		break;
	case 'development':
		break;
	default:
		$environment = 'localhost';
		break;
}

/**
 * Здесь уже идёт особое шаманство, но к сожалению лучше пока не нашёл.
 * Суть в том что консольное приложение yii не умеет работать с таким инструментом php для командной строки как
 * getopt и набор параметро берёт из суперглобального массива $_SERVER['argv'] за счёт чего ему требуется
 * жёсткий порядок указания параметров, к тому же вызов
 * yiic -e production migrate create some_migrateion
 * или даже
 * yiic migrate create some_migrateion
 * вызывает смуту у приложения, в первом случае в обще откажется работать.
 *
 * Чтобы решить эту проблему и была возможность указывать параметр через ключ -e (привет рельсам!), а не таким путём
 * ENVIRONMENT=production yiic migrate up
 * был сделан этот костылёк.
 *
 * Суть его заключается в том что он определяет параметры которые ему могут быть переданы для getopt, находит их и
 * исключает из массива $_SERVER['argv'], в результате чего консольное приложение считает что всё в порядке и читает
 * только параметры предназначенные ей.
 */

/**
 * Параметры у нас 1-но символьные поэтому регулярка вытаскивает все латинские символы
 */
preg_match_all('/[A-z]/', $options, $matches);
/**
 * Запоминаем найденные параметры
 */
$paramNames = $matches[0];
/**
 * Массив параметров которые мы передадим приложению
 */
$commandArgs = array();

/**
 * Переменная для дропа параметра, сюда передаётся его номер
 */
$drop = null;

for ($i = 0; $i < count($_SERVER['argv']); $i++)
{
	/**
	 * Если цикл дошёл до параметра который не нужно включать в массив параметров,
	 * то переходим к следующему элементу
	 */
	if (isset($drop) && $drop == $i)
		continue;

	/**
	 * Для удобства передал значение в переменную
	 */
	$argument = $_SERVER['argv'][$i];

	/**
	 * Убираю - обозначающий ключ, так же помечаю у кого я его убрал
	 */
	if (mb_substr($argument, 0, 1) == '-')
	{
		$argument = mb_substr($argument, 1);
		$croped = true;
	}
	else if (isset($croped))
	{
		unset($croped);
	}

	/**
	 * Если наткнулись на getopt параметр, указываем что следующий надо дропнуть
	 * потому что это значение параметра, и отправляем цикл на следующий круг.
	 */
	if (in_array($argument, $paramNames))
	{
		$drop = $i+1;
		continue;
	}

	/**
	 * Если элемент не является нашим аргументом но у него отсекали минус в начале, возвращаем на место.
	 */
	if (isset($croped) && $croped === true)
		$argument = '-'.$argument;

	/**
	 * Сохраняем "правильный параметр" в список
	 */
	array_push($commandArgs, $argument);
}

/**
 * Присваиваем набор правильных параметров $_SERVER['argv']
 */
$_SERVER['argv'] = $commandArgs;

$yiic=dirname(__DIR__).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'yii'.DIRECTORY_SEPARATOR.'yiic.php';

/**
 * Наименование данного файла зависит от переменной окружения environment.
 * Будьте внимательны при работе с консольным приложением, если переменная не задана, она
 * принимает значение localhost
 */
$config=dirname(__FILE__).DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'console.'.$environment.'.php';

require_once($yiic);
