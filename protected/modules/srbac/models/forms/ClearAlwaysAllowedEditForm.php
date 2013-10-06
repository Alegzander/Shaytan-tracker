<?php
/**
 * User: alegz
 * Date: 7/22/13
 * Time: 5:07 PM
 */

class ClearAlwaysAllowedEditForm extends CFormModel{
	/**
	 * @var SrbacUtils
	 */
	private $utils;

	/**
	 * @var array
	 */
	public $item;

	public function init(){
		parent::init();

		$this->utils = new SrbacUtils();
	}

	public function rules(){
		return array(
			array('item', 'checkItems')
		);
	}

	public function checkItems($item, array $params){
		if (!is_array($this->{$item}))
			return false;

		$alwaysAllowedList = $this->utils->readAlwaysAllowedFile();
		$numberOfErrorsBeforeCheck = count($this->getErrors($item));

		foreach ($this->{$item} as $index => $authItem){
			if ($authItem == "0"){
				unset($this->{$item}[$index]);
				continue;
			}

			if (\Yii::app()->authManager->getAuthItem($authItem) === null && !in_array($authItem, $alwaysAllowedList))
				$this->addError($item, \Yii::app('error', 'Invalid {item} value. Expects to be one of auth items. "{fact}" is given.', array(
					'{item}' => $item,
					'{fact}' => strip_tags($authItem)
				)));
		}

		$numberOfErrorsAfterCheck = count($this->getErrors($item));

		if ($numberOfErrorsAfterCheck != $numberOfErrorsBeforeCheck)
			return false;

		return true;
	}
}