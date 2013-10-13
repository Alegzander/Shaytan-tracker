<?php
/**
 * User: alegz
 * Date: 7/22/13
 * Time: 5:07 PM
 */

class AlwaysAllowedEditForm extends CFormModel{
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
            array('item', 'required'),
			array('item', 'checkItems', 'on' => 'list'),
            array('item', 'authItemCheck', 'on' => 'item'),
//            array('item', 'boolean', 'on' => '')
		);
	}

    public function authItemCheck($item, array $params){
        if (!isset(\Yii::app()->authManager)){
            $this->addError($item, \Yii::t('error', 'Auth manager is not set in application.'));
            return false;
        }

        $moduleId = \Yii::app()->controller->module->id;
        $controller = $this->getControllerFromItem($this->{$item});

        if (is_null($controller)){
            return true;
        } else if ($controller === false) {
            $this->addError($item, \Yii::t('error', 'Item "{item}" is invalid.', array(
                '{item}' => $this->{$item}
            )));
        }

        if (\Yii::app()->authManager->getAuthItem($this->{$item}) === null){
            $this->addError($item, \Yii::t('error',
                'Auth item "{item}" does not exist. Please <a href="{link}" target="_blank">create</a> item first.',
                array(
                    '{link}' => \Yii::app()->createUrl('/'.$moduleId.'/autocreate/index', array(
                        'controller' => $controller
                    )),
                    '{item}' => $this->{$item}
            )));

            return false;
        }

        return true;
    }

    private function getControllerFromItem($item){
        $matches = array();

        preg_match_all('/(?<block>[A-Z][a-z]+|[A-z]+@[A-Z][a-z]+)/', $item, $matches);

        if (count($matches['block']) === 0)
            return null;

        $delimeter = Helper::findModule('srbac')->delimeter;
        $rawData = explode($delimeter, $matches['block'][0]);

        if (count($rawData) === 2)
            list($module, $controller) = $rawData;
        else if (count($rawData === 1))
            list($controller) = $rawData;
        else
            return null;

        if (isset($module)){
            $controllerPath = Helper::findModule($module)->getControllerPath();
        } else {
            $controllerPath = \Yii::getPathOfAlias('application.controllers');
        }

        $found = false;

        for ($i = 1; $i < count($matches['block']); $i++){
            if (!file_exists(OSHelper::path()->join($controllerPath, $controller.'Controller.php'))){
                $controller .= $matches['block'][$i];
            } else {
                $controller .= 'Controller';
                $found = true;
                break;
            }

        }

        if (isset($module))
            $controller = $module.$delimeter.$controller;

        return $found?$controller:false;
    }

	public function checkItems($item, array $params){
		if (!is_array($this->{$item}))
			return false;

        $result = true;

        foreach ($this->{$item} as $index => $authItem){
            $form = new AlwaysAllowedEditForm('item');
            $form->item = $authItem;

            if (!$form->validate()){
                $result = false;
                $this->addError($item.'['.$index.']', $form->getError($item));
            }
		}

		return $result;
	}
}