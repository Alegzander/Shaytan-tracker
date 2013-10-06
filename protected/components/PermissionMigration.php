<?php
/**
 * User: alegz
 * Date: 9/18/13
 * Time: 4:49 PM
 */

abstract class PermissionMigration extends CDbMigration {
	/**
	 * @var array
	 * @desc List of new operations to add
	 */
	protected $operations = array();

	/**
	 * @var array
	 * @desc List of new tasks to add
	 */
	protected $tasks = array();

	/**
	 * @var array
	 * @desc List of new roles to add
	 */
	protected $roles = array();

	/**
	 * @var array
	 * @desc Here you set permission hierarchy that you want to settle
	 * For instance you want for role administrator set tast "GarmentAdministrator"
	 * In migration you put
	 *
	 * class m000000_add_administrator_permissions extends PermissionMigration{
	 * 		protected $permissionHierarchy = array(
	 *			'Administrator' => array(
	 *				'GarmentAdministrator'
	 * 			)
	 * 		);
	 * }
	 *
	 * Migration ready.
	 */
	protected $permissionHierarchy = array();

	public function up(){
		$authManager = \Yii::app()->authManager;

		if (count($this->operations) > 0)
			foreach ($this->operations as $operation)
				if ($authManager->getAuthItem($operation) === null)
					$authManager->createOperation($operation);

		if (count($this->tasks) > 0)
			foreach ($this->tasks as $task)
				if ($authManager->getAuthItem($task) === null)
					$authManager->createTask($task);

		if (count($this->roles) > 0)
			foreach ($this->roles as $role)
				if ($authManager->getAuthItem($role) === null)
					$authManager->createRole($role);

		if (count($this->permissionHierarchy) > 0){
			foreach ($this->permissionHierarchy as $parent => $children){
				if ($authManager->getAuthItem($parent) === null)
					throw new CException(\Yii::t('error', 'Auth item "{item}" in permission list does not exist. Create this item first.',
					array('{item}' => $parent)));

				foreach ($children as $child){
					if ($authManager->getAuthItem($child) === null)
						throw new CException(\Yii::t('error', 'Auth item "{item}" in permission list does not exist. Create this item first.',
							array('{item}' => $child)));

					if ($authManager->hasItemChild($parent, $child) === false)
						$authManager->addItemChild($parent, $child);
				}
			}
		}
	}

	public function down(){
		$authManager = \Yii::app()->authManager;

		if (count($this->permissionHierarchy) > 0){
			foreach ($this->permissionHierarchy as $parent => $children){
				if ($authManager->getAuthItem($parent) === null)
					throw new CException(\Yii::t('error', 'Auth item "{item}" in permission list does not exist. Create this item first.',
						array('{item}' => $parent)));

				foreach ($children as $child){
					if ($authManager->getAuthItem($child) === null)
						throw new CException(\Yii::t('error', 'Auth item "{item}" in permission list does not exist. Create this item first.',
							array('{item}' => $child)));

					if ($authManager->hasItemChild($parent, $child) === true)
						$authManager->removeItemChild($parent, $child);
				}
			}
		}

		if (count($this->roles) > 0)
			foreach ($this->roles as $role)
				if ($authManager->getAuthItem($role) !== null)
					$authManager->removeAuthItem($role);

		if (count($this->tasks) > 0)
			foreach ($this->tasks as $task)
				if ($authManager->getAuthItem($task) !== null)
					$authManager->removeAuthItem($task);

		if (count($this->operations) > 0)
			foreach ($this->operations as $operation)
				if ($authManager->getAuthItem($operation) !== null)
					$authManager->removeAuthItem($operation);
	}
}