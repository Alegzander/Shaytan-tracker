<?php
/**
 * User: alegz
 * Date: 4/4/13
 * Time: 4:15 PM
 */

class AssignController extends SrbacWraperController {
	public $defaultAction = 'users';

	/**
	 * @desc Displaying users
	 */
	public function actionUsers()
	{
		$authManager = \Yii::app()->authManager;
		$selectedUser = \Yii::app()->request->getQuery('user');
		$assignedRoles = array();
		$notAssignedRoles = array();

		$users = User::model();

		if(isset($selectedUser))
		{
			$assignedRoles = $authManager->getRoles($selectedUser);
			$allRoles = $authManager->getRoles();

			foreach ($allRoles as $role)
			{
				if (!$authManager->isAssigned($role->name, $selectedUser))
					array_push($notAssignedRoles, $role);
			}
		}

		$this->render('users', array(
			'users' => $users,
			'selectedUser' => $selectedUser,
			'authManager' => $authManager,
			'assignedRoles' => $assignedRoles,
			'notAssignedRoles' => $notAssignedRoles
		));
	}

	/**
	 * @throws CHttpException
	 * @desc Displaying roles and tasks
	 */
	public function actionItems()
	{
		$authManager = \Yii::app()->authManager;
		$selectedItem = \Yii::app()->request->getQuery('item');
		$type = \Yii::app()->request->getQuery('type');
		$childrenItems = array();
		$notChildrenItems = array();
		$data = array();
		$typeMap = array(
			'roles' => EAuthItem::ROLE,
			'tasks'  => EAuthItem::TASK
		);
		$typeChildren = array(
			EAuthItem::ROLE => EAuthItem::TASK,
			EAuthItem::TASK => EAuthItem::OPERATION
		);

		if (!isset($typeMap[$type]))
			throw new CHttpException(404);
		else
			$selectedType = $typeMap[$type];

		foreach ($authManager->getAuthItems($selectedType) as $authItem){
			$tmpObject = new stdClass();
			$tmpObject->primaryKey = $authItem->getName();
			$tmpObject->id = $authItem->getName();
			$tmpObject->name = $authItem->getName();

			array_push($data, $tmpObject);
			unset($tmpObject);
		}

		if(isset($selectedItem))
		{
			$selectedItemObject = $authManager->getAuthItem($selectedItem);

			$childrenItems = $selectedItemObject->getChildren();
			$allItems = $authManager->getAuthItems($typeChildren[$selectedType]);

			foreach ($allItems as $item)
			{
				if (!$authManager->hasItemChild($selectedItemObject->getName(), $item->getName()))
					array_push($notChildrenItems, $item);
			}
		}

		$this->render('items', array(
			'itemsProvider' => new CArrayDataProvider($data),
			'type' => $type,
			'selectedItem' => $selectedItem,
			'authManager' => $authManager,
			'assignedItems' => $childrenItems,
			'notAssignedItems' => $notChildrenItems
		));
	}

	//TODO To do something with repeating code

	/**
	 * @desc Users edition
	 */
	public function actionEdit()
	{
		$authManager = \Yii::app()->authManager;
		$user = \Yii::app()->request->getQuery('user');
		$type = \Yii::app()->request->getQuery('type');
		$action = \Yii::app()->request->getQuery('action');
		$items = \Yii::app()->request->getPost('items');
		$responseActionText = array(
			'add' 		=> 'assigned',
			'remove' 	=> 'revoked'
		);

		if (isset($user, $type) && in_array($action, array('add', 'remove')) && count($items) > 0)
		{
			foreach($items as $item)
			{
				if ($authManager->getAuthItem($item))
					if ($action == 'remove' && $authManager->isAssigned($item, $user))
						$authManager->revoke($item, $user);
					else if ($action == 'add' && !$authManager->isAssigned($item, $user))
						$authManager->assign($item, $user);
				unset($item);
			}

			\Yii::app()->user->setFlash('success', \Yii::t('srbac', 'Roles successfully {action}',
				array('{action}' => $responseActionText[$action])));
		}
		else
			\Yii::app()->user->setFlash('error', \Yii::t('srbac', 'Bad request.'));

		unset($_GET['action']);

		$this->redirect($this->createUrl($type, array_merge($_GET, array('user' => $user))));
	}

	/**
	 * @throws CHttpException
	 * @desc Roles and Tasks edition
	 */
	public function actionEditItem()
	{
		$authManager = \Yii::app()->authManager;
		$selectedItem = \Yii::app()->request->getQuery('item');
		$type = \Yii::app()->request->getQuery('type');
		$action = \Yii::app()->request->getQuery('action');
		$items = \Yii::app()->request->getPost('items');
		$responseActionText = array(
			'add' 		=> 'assigned',
			'remove' 	=> 'revoked'
		);
		$typeMap = array(
			'roles' => EAuthItem::TASK,
			'tasks'  => EAuthItem::OPERATION
		);

		if (!isset($typeMap[$type]))
			throw new CHttpException(404);

		if (isset($selectedItem) && in_array($action, array('add', 'remove')) && count($items) > 0)
		{
			foreach($items as $item)
			{
				if ($authManager->getAuthItem($item))
					if ($action == 'remove' && $authManager->hasItemChild($selectedItem, $item))
						$authManager->removeItemChild($selectedItem, $item);
					else if ($action == 'add' && !$authManager->hasItemChild($selectedItem, $item))
						$authManager->addItemChild($selectedItem, $item);
				unset($item);
			}

			\Yii::app()->user->setFlash('success', \Yii::t('srbac', 'Roles successfully {action}',
				array('{action}' => $responseActionText[$action])));
		}
		else
			\Yii::app()->user->setFlash('error', \Yii::t('srbac', 'Bad request.'));

		unset($_GET['action']);

		$this->redirect($this->createUrl('items', array_merge($_GET, array('type' => $type, 'item' => $selectedItem))));
	}
}