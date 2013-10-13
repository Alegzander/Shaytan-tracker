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

    public $layout = '/layouts/manage';

	public function init()
	{
		parent::init();

		$this->utils = new SrbacUtils();
	}

	public function actionIndex()
	{
		try {
			$form = new AlwaysAllowedEditForm();

			if ($this->utils->isAlwaysAllowedFileWritable() === false)
				throw new CException();

            $allowedOptions = array_flip($this->utils->readAlwaysAllowedFile());
			$controllers     = $this->utils->getControllers();
			$controllersMeta = array();

            foreach ($controllers as $index => $controller) {
				$tmp                          = $this->utils->getControllerInfo($controller, true);
				$controllersMeta[$controller] = $tmp[0];
                unset($tmp);
			}

            unset($controllers);

			AssetsHelper::register(array('js/srbac/alwaysAllowedList.js'));

			$this->render('index', array(
				'controllersMeta'     => $controllersMeta,
                'allowedOptions' => $allowedOptions,
				'formAction'	  => $this->createUrl('edit'),
				'formModel'		  => $form
			));
		} catch (CException $error) {
            $this->render('/_not-writable',
            array(
                'file' => $this->utils->getAlwaysAllowedFile(),
                'user' => isset($_ENV['USER'])?$_ENV['USER']:'anonymous'
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