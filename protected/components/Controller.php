<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	
	public $resources = array();
	
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	public function init()
	{
		parent::init();
		
		//Putting resoures to assets and getiing links
		//Making shure this is not Ajax, who doesn't need css or js
		if (!Yii::app()->request->isAjaxRequest)
		{
			//Getting resources list from config
			$resourceList = Yii::app()->getParams("resources")->resources;
			
			//Checking if we've got array
			if (is_array($resourceList))
			{
				foreach ($resourceList as $groupName => $resourceGroup)
				{
					foreach ($resourceGroup as $resource)
					{
						$this->resources[$resource] = Yii::app()->assetManager->publish(
													Yii::getPathOfAlias('webroot.themes.'.Yii::app()->theme->getName().'.views.'.$groupName).'/'.$resource
												);
					}
				}
			}
			else
			{
				new CException ("resourceList variable should be array ".gettype($resourceList)." is given.");
			}
		}
	}
}