<?php
/**
 * User: alegz
 * Date: 4/10/13
 * Time: 6:11 AM
 */

class UserController extends SrbacWraperController {
	public function actionIndex()
	{
		$authManager = \Yii::app()->authManager;
		$name = \Yii::app()->request->getQuery('name');
		$userModels = User::model()->findAll();
		$items = array();
		$userList = array(''=>'');
		$user = null;

		if (isset($name) && ($user = User::model()->findByAttributes(array('username' => $name))) != null)
			foreach ($authManager->getAuthAssignments($user->id) as $role)
				foreach ($authManager->getItemChildren($role->getItemName()) as $task)
					foreach ($authManager->getItemChildren($task->getName()) as $operation)
						$items[$role->getItemName()][$task->getName()][] = $operation->getName();

		foreach ($userModels as $userModel)
			$userList[$userModel->username] = $userModel->username;

		$this->render('index', array('items' => $items, 'users' => $userList));
	}
}