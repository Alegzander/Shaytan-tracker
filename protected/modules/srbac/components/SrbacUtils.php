<?php
/**
 * User: alegz
 * Date: 3/27/13
 * Time: 7:06 PM
 */

Yii::import("srbac.components.Helper");

class SrbacUtils
{
	public $delimiter;
	private $controller;

	public function __construct()
	{
		$this->delimiter = Helper::findModule('srbac')->delimeter;
		$this->controller = \Yii::app()->controller;
	}

	/**
	 * @param        $contPath
	 * @param string $module
	 * @param string $subdir
	 * @param array  $controllers
	 * @return array
	 */
	public function scanDir($contPath, $module = "", $subdir = "", $controllers = array())
	{
		$handle = opendir($contPath);
		while (($file = readdir($handle)) !== false) {
			$filePath = $contPath . DIRECTORY_SEPARATOR . $file;
			if (is_file($filePath)) {
				if (preg_match("/^(.+)Controller.php$/", basename($file))) {
					if ($this->extendsSBaseController($filePath)) {
						$controllers[] = (($module) ? $module . $this->delimiter : "") .
							(($subdir) ? $subdir . "." : "") .
							str_replace(".php", "", $file);
					}
				}
			} else if (is_dir($filePath) && $file != "." && $file != "..") {
				$controllers = $this->scanDir($filePath, $module, $file, $controllers);
			}
		}
		return $controllers;
	}

	/**
	 * @param $controller
	 * @return bool
	 */
	public function extendsSBaseController($controller)
	{
		$c = basename(str_replace(".php", "", $controller));
		if (!class_exists($c, false)) {
			include_once $controller;
		} else {

		}
		$cont = new $c($c);

		if ($cont instanceof SBaseController) {
			return true;
		}
		return false;
	}

	/**
	 * @return array
	 */
	public function getControllers()
	{
		$contPath = Yii::app()->getControllerPath();

		$controllers = $this->scanDir($contPath);

		//Scan modules
		$modules        = Yii::app()->getModules();
		$modControllers = array();
		foreach ($modules as $mod_id => $mod) {
			$moduleControllersPath = Yii::app()->getModule($mod_id)->controllerPath;
			$modControllers        = $this->scanDir($moduleControllersPath, $mod_id, "", $modControllers);
		}
		return array_merge($controllers, $modControllers);
	}

	/**
	 * @param      $controller
	 * @param bool $getAll
	 * @return array
	 *
	 * Getting a controllers actions and also th actions that are always allowed
	 */
	public function getControllerInfo($controller, $getAll = false)
	{
		$actions = array();
		$allowed = array();
		$auth    = Yii::app()->authManager;

		//Check if it's a module controller
		if (substr_count($controller, $this->delimiter)) {
			$c          = explode($this->delimiter, $controller);
			$controller = $c[1];
			$module     = $c[0] . $this->delimiter;
			$contPath   = Yii::app()->getModule($c[0])->getControllerPath();
			$control    = $contPath . DIRECTORY_SEPARATOR . str_replace(".", DIRECTORY_SEPARATOR, $controller) . ".php";
		} else {
			$module   = "";
			$contPath = Yii::app()->getControllerPath();
			$control  = $contPath . DIRECTORY_SEPARATOR . str_replace(".", DIRECTORY_SEPARATOR, $controller) . ".php";
		}

		$task = $module . str_replace("Controller", "", $controller);

		$taskViewingExists        = $auth->getAuthItem($task . "Viewing") !== null ? true : false;
		$taskAdministratingExists = $auth->getAuthItem($task . "Administrating") !== null ? true : false;
		$delete                   = Yii::app()->request->getParam('delete');

		$h = file($control);
		for ($i = 0; $i < count($h); $i++) {
			$line = trim($h[$i]);
			if (preg_match("/^(.+)function( +)action*/", $line)) {
				$posAct          = strpos(trim($line), "action");
				$posPar          = strpos(trim($line), "(");
				$action          = trim(substr(trim($line), $posAct, $posPar - $posAct));
				$patterns[0]     = '/\s*/m';
				$patterns[1]     = '#\((.*)\)#';
				$patterns[2]     = '/\{/m';
				$replacements[2] = '';
				$replacements[1] = '';
				$replacements[0] = '';
				$action          = preg_replace($patterns, $replacements, trim($action));
				$itemId          = $module . str_replace("Controller", "", $controller) .
					preg_replace("/action/", "", $action, 1);
				if ($action != "actions") {
					if ($getAll) {
						$actions[$module . $action] = $itemId;
						if (in_array($itemId, $this->allowedAccess())) {
							$allowed[] = $itemId;
						}
					} else {
						if (in_array($itemId, $this->allowedAccess())) {
							$allowed[] = $itemId;
						} else {
							if ($auth->getAuthItem($itemId) === null && !$delete) {
								if (!in_array($itemId, $this->allowedAccess())) {
									$actions[$module . $action] = $itemId;
								}
							} else if ($auth->getAuthItem($itemId) !== null && $delete) {
								if (!in_array($itemId, $this->allowedAccess())) {
									$actions[$module . $action] = $itemId;
								}
							}
						}
					}
				} else {
					//load controller
					if (!class_exists($controller, false)) {
						require($control);
					}
					$tmp            = array();
					$controller_obj = new $controller($controller, $module);
					//Get actions
					$controller_actions = $controller_obj->actions();
					foreach ($controller_actions as $cAction => $value) {
						$itemId = $module . str_replace("Controller", "", $controller) . ucfirst($cAction);
						if ($getAll) {
							$actions[$cAction] = $itemId;
							if (in_array($itemId, $this->allowedAccess())) {

								$allowed[] = $itemId;
							}
						} else {
							if (in_array($itemId, $this->allowedAccess())) {
								$allowed[] = $itemId;
							} else {
								if ($auth->getAuthItem($itemId) === null && !$delete) {
									if (!in_array($itemId, $this->allowedAccess())) {
										$actions[$cAction] = $itemId;
									}
								} else if ($auth->getAuthItem($itemId) !== null && $delete) {
									if (!in_array($itemId, $this->allowedAccess())) {
										$actions[$cAction] = $itemId;
									}
								}
							}
						}
					}
				}
			}
		}
		return array($actions, $allowed, $delete, $task, $taskViewingExists, $taskAdministratingExists);
	}

	/**
	 * @return mixed
	 *
	 * The auth items that access is always  allowed. Configured in srbac module's
	 * configuration
	 */
	private function allowedAccess()
	{
		return Helper::findModule('srbac')->getAlwaysAllowed();
	}

	/**
	 * @return bool
	 *
	 * Checks if the always allowed file is writeable
	 * @return boolean true if always allowed file is writeable or false otherwise
	 */
	public function isAlwaysAllowedFileWritable()
	{
		if (!($f = @fopen(Helper::findModule("srbac")->getAlwaysAllowedFile(), 'r+'))) {
			return false;
		}
		fclose($f);
		return true;
	}

	/**
	 * @return string Path to file with always allowed list
	 */
	public function getAlwaysAllowedFile(){
		return Helper::findModule("srbac")->getAlwaysAllowedFile();
	}

	/**
	 * @param array $allowed List of always allowed auth items
	 */
	public function writeAlwaysAllowedFile(array $allowed){
		$handle = fopen($this->getAlwaysAllowedFile(), "wb");
		fwrite($handle, "<?php \n return array(\n\t'" . implode("',\n\t'", $allowed) . "'\n);\n?>");
		fclose($handle);
	}

	/**
	 * @return array List of always allowed auth items
	 */
	public function readAlwaysAllowedFile(){
		return require($this->getAlwaysAllowedFile());
	}

	/**
	 * @param array $controllers
	 * @param array $actions
	 * @return string
	 */
	public function getSrbacItemName(array $controllers, $actions = array()){
		$controller = $this->getSrbacControllers();
		$action = $this->controller->action->id;

		if (!$this->isSrbacModule() || !in_array($controller, $controllers))
			return User::model()->pwgen();

		if (count($actions) > 0 && !in_array($action, $actions))
			return User::model()->pwgen();

		return $controller.'.'.$this->getSrbacControllerAction($controller, $action);
	}

	/**
	 * @param $controller
	 * @param $action
	 * @return string
	 */
	public function getSrbacControllerAction($controller, $action){
		if (!$this->isSrbacModule())
			return User::model()->pwgen();

		/**
		 * @var SrbacModule $module
		 */
		$controllersMeta = $this->getControllerInfo($this->getSrbacPrefix().$this->rawControllerName($controller), true);

		foreach ($controllersMeta[0] as $item){
			$tmpAction = $this->normalizeActionName($this->rawControllerName($controller), $item);

			if ($tmpAction == $action)
				return $action;
		}

		return User::model()->pwgen();
	}

	/**
	 * @return string
	 */
	public function getSrbacControllers(){
		if (!$this->isSrbacModule())
			return User::model()->pwgen();

		$modulePath = \Yii::app()->getModule('srbac')->getBasePath();
		$currentController = \Yii::app()->controller->id;

		return in_array($this->rawControllerName($currentController), $this->scanDir(OSHelper::path()->join($modulePath, 'controllers'))) ? $currentController : 'unknown';
	}

	private function isSrbacModule(){
		return isset($this->controller->module, $this->controller->module->id) && $this->controller->module->id == 'srbac';
	}

	/**
	 * @return string
	 */
	private function getSrbacPrefix(){
		$module = \Yii::app()->getModule('srbac');

		return 'srbac'.$module->delimeter;
	}

	/**
	 * @param $controllerName
	 * @return string
	 */
	private function rawControllerName($controllerName){
		return ucfirst($controllerName.'Controller');
	}

	/**
	 * @param $controllerName
	 * @return string
	 */
	private function normalizeControllerName($controllerName){
		return lcfirst(preg_replace('/Controller$/', '', $controllerName));
	}

	/**
	 * @param $controller
	 * @param $action
	 * @return string
	 */
	private function normalizeActionName($controller, $action){
		return lcfirst(preg_replace('/^'.$this->getSrbacPrefix().ucfirst($this->normalizeControllerName($controller)).'/', '', $action));
	}
}