<?php
/**
 * User: alegz
 * Date: 3/27/13
 * Time: 12:32 PM
 */

class AutocreateController extends SrbacWraperController
{
	/**
	 * @var SrbacUtils
	 */
	public $utils;

    public $layout = '/layouts/manage';

	public function init()
	{
		parent::init();

		$this->utils = new SrbacUtils();
	}

	public function actionIndex()
	{
		$authManager   = \Yii::app()->authManager;
		$controller    = \Yii::app()->request->getQuery('controller');
		$directive     = \Yii::app()->request->getQuery('directive');
		$page          = \Yii::app()->request->getQuery('page', 1);
		$displayTask   = true;
		$viewTask      = 'Viewing';
		$adminTask     = 'Administrating';
		$actions       = null;
		$cName         = null;
		$alwaysAllowed = null;

		if (!is_numeric($page))
			$page = 1;

		if (isset($controller)) {
			$controller = str_replace('default' . $this->utils->delimiter, '', $controller);

			$controllerInfo = $this->utils->getControllerInfo($controller, true);
			$actions        = $controllerInfo[0];
			$cName          = $controllerInfo[3];
			$viewTask       = $cName . $viewTask;
			$adminTask      = $cName . $adminTask;
			$alwaysAllowed  = $controllerInfo[1];

			foreach ($actions as $name => $itemName) {
				$item = $authManager->getAuthItem($itemName);

				if (in_array($itemName, $alwaysAllowed))
					unset($actions[$name]);
				else if ($directive == 'delete' && !isset($item))
					unset($actions[$name]);
				else if ($directive != 'delete' && isset($item))
					unset($actions[$name]);
			}

			$viewTaskItem  = $authManager->getAuthItem($viewTask);
			$adminTaskItem = $authManager->getAuthItem($adminTask);

			//Two if statements were made for better code readability
			if (isset($viewTaskItem, $adminTaskItem) && $directive != 'delete')
				$displayTask = false;
			else if ($directive == 'delete' && !isset($viewTaskItem, $adminTaskItem))
				$displayTask = false;

			unset($viewTaskItem);
			unset($adminTaskItem);
		}

		if (isset($directive))
			unset($_GET['directive']);

        $url = \Yii::app()->assetManager->publish(OSHelper::path()->join(dirname(__DIR__), 'js', 'autocreate', 'index.js'));
        \Yii::app()->clientScript->registerScriptFile($url);

		$this->render('index', array(
			'controllers'   => $this->getControllers(),
			'actions'       => $actions,
			'alwaysAllowed' => $alwaysAllowed,
			'cName'         => $cName,
			'displayTask'   => $displayTask,
			'viewTask'      => $viewTask,
			'adminTask'     => $adminTask,
			'directive'     => $directive,
			'page'          => $page
		));
	}

	public function actionCreate()
	{
		$controller  = \Yii::app()->request->getQuery('controller');
		$authManager = \Yii::app()->authManager;

		if (!isset($controller)) {
			\Yii::app()->user->setFlash('error', \Yii::t('error', 'Controller wasn\'t set.'));
			$this->redirect($this->createUrl('index'));
		} else
			$controller = str_replace('default' . $this->utils->delimiter, '', $controller);

		//POST data
		$actions     = \Yii::app()->request->getPost('actions');
		$actionsAll  = \Yii::app()->request->getPost('actionsAll');
		$createTasks = \Yii::app()->request->getPost('createTasks');
		$tasks       = \Yii::app()->request->getPost('tasks');

		if (isset($createTasks) && (int)($createTasks) === 1 && is_array($tasks))
			foreach ($tasks as $task)
				$authManager->createTask($task);

		if (isset($actionsAll) && (int)($actionsAll) === 1) {
			$controllerInfo = $this->utils->getControllerInfo($controller);
			$actions        = $controllerInfo[0];

			foreach ($actions as $action)
				$authManager->createOperation(trim(str_replace('action', '', $action)));
		} else if (isset($actions) && is_array($actions) && count($actions) > 0) {
			foreach ($actions as $action)
				$authManager->createOperation($action);
		}

		$this->redirect($this->createUrl('index'));
	}

	public function actionDelete()
	{
		$controller  = \Yii::app()->request->getQuery('controller');
		$authManager = \Yii::app()->authManager;

		if (!isset($controller)) {
			\Yii::app()->user->setFlash('error', \Yii::t('error', 'Controller wasn\'t set.'));
			$this->redirect($this->createUrl('index'));
		} else
			$controller = str_replace('default' . $this->utils->delimiter, '', $controller);

		//POST data
		$actions     = \Yii::app()->request->getPost('actions');
		$actionsAll  = \Yii::app()->request->getPost('actionsAll');
		$createTasks = \Yii::app()->request->getPost('createTasks');
		$tasks       = \Yii::app()->request->getPost('tasks');

		if (isset($createTasks) && (int)($createTasks) === 1 && is_array($tasks))
			foreach ($tasks as $task)
				$authManager->removeAuthItem($task);

		if (isset($actionsAll) && (int)($actionsAll) === 1) {
			$controllerInfo = $this->utils->getControllerInfo($controller);
			$actions        = $controllerInfo[0];

			foreach ($actions as $action)
				$authManager->removeAuthItem(trim(str_replace('action', '', $action)));
		} else if (isset($actions) && is_array($actions) && count($actions) > 0) {
			foreach ($actions as $action)
				$authManager->removeAuthItem($action);
		}

		$this->redirect($this->createUrl('index'));
	}

	private function getControllers()
	{
		$controllers = array();

		//Getting controllers list
		foreach ($this->utils->getControllers() as $item) {
			$module     = 'default';
			$controller = '';
			$delimiter  = Helper::findModule('srbac')->delimeter;

			//Little parse
			if (strstr($item, $delimiter))
				list($module, $controller) = explode($delimiter, $item);
			else
				$controller = $item;

			//Creating objet of controller valida for CArrayDataProvider
			$controllerModel             = new stdClass();
			$controllerModel->id         = $controller;
			$controllerModel->primaryKey = $controller;
			$controllerModel->name       = $controller;

			if (!isset($controllers[$module]))
				$controllers[$module] = array();

			array_push($controllers[$module], $controllerModel);
			unset($controllerModel);
		}

		//Reformatting our array
		foreach ($controllers as $module => $controllersList)
			$controllers[$module] = new CArrayDataProvider($controllersList);

		return $controllers;
	}
}