<?php
/**
 * User: alegz
 * Date: 7/19/13
 * Time: 2:51 PM
 */

class AlwaysAllowedListController extends SrbacWraperController
{
	/**
	 * @var SrbacUtils
	 */
	private $utils;

	public function init()
	{
		parent::init();

		$this->utils = new SrbacUtils();
	}

	public function actionIndex()
	{
		try {
			$form = new AlwaysAllowedEditForm();
			$allowedOptions = array_flip($this->utils->readAlwaysAllowedFile());

			if ($this->utils->isAlwaysAllowedFileWritable() === false)
				throw new CException();

			$controllers     = $this->utils->getControllers();
			$controllersMeta = array();

			foreach ($controllers as $index => $controller) {
				$tmp                          = $this->utils->getControllerInfo($controller, true);
				$controllersMeta[$controller] = array();

				foreach ($tmp[0] as $authItem){
					if (\Yii::app()->authManager->getAuthItem($authItem) !== null)
						array_push($controllersMeta[$controller], $authItem);

					if (isset($allowedOptions[$authItem]))
						$form->item[$authItem] = $authItem;
				}

				//This should remove empty controllers and auth items that doesn't exist
				if (count($controllersMeta[$controller]) == 0){
					unset($controllersMeta[$controller]);
					unset($controllers[$index]);
				}


				unset($tmp);
			}

			AssetsHelper::register(array('js/srbac/alwaysAllowedList.js'));

			$this->render('index', array(
				'controllers'     => $controllers,
				'controllersMeta' => $controllersMeta,
				'formAction'	  => $this->createUrl('edit'),
				'formModel'		  => $form
			));
		} catch (CException $error) {
			$this->render('/_not-writable',
				array(
					'file' => $this->utils->getAlwaysAllowedFile(),
					'user' => $_ENV['USER']
				)
			);
		}
	}

	public function actionEdit(){
		$form = new AlwaysAllowedEditForm();
		$post = \Yii::app()->request->getPost(get_class($form));

		$form->attributes = $post;

		if ($form->validate() && $this->utils->isAlwaysAllowedFileWritable()){
			$this->utils->writeAlwaysAllowedFile($form->item);
		} else {
			foreach ($form->getErrors() as $attributes => $errors){
				foreach ($errors as $error){
					\Yii::app()->user->setFlash('error', $error);
				}
			}
		}

		if ($this->utils->isAlwaysAllowedFileWritable() === false){
			\Yii::app()->user->setFlash('error', 'File "{file}" is not writable.', array(
				'{file}' => $this->utils->getAlwaysAllowedFile()
			));
		}

		$this->redirect($this->createUrl('index'));
	}

	public function formatControllerName($controller){
		return str_replace($this->utils->delimiter, '-', $controller);
	}
}