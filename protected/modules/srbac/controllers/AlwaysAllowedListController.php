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
        \Yii::import('bootstrap.widgets.TbAlert');
        $form = new AlwaysAllowedEditForm('list');

        try {
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

            //Processing post request
            if (\Yii::app()->request->isPostRequest){
                $post = \Yii::app()->request->getPost(get_class($form));

                $form->attributes = $post;

                if ($form->validate()){
                    $form->item = array_unique($form->item);
                    if (($badIndex = array_search('0', $form->item)) !== false) unset($form->item[$badIndex]);
                    $this->utils->writeAlwaysAllowedFile($form->item);
                    \Yii::app()->user->setFlash(TbAlert::TYPE_SUCCESS, \Yii::t('flash', 'Always allowed list successfully updated.'));
                } else {
                    foreach ($form->getErrors() as $attributes => $errors){
                        foreach ($errors as $error){
                            \Yii::app()->user->setFlash(uniqid().'@'.TbAlert::TYPE_ERROR, $error);
                        }
                    }
                }

                if ($this->utils->isAlwaysAllowedFileWritable() === false){
                    \Yii::app()->user->setFlash('error', 'File "{file}" is not writable.', array(
                        '{file}' => $this->utils->getAlwaysAllowedFile()
                    ));
                }
            }
            //END processing post request

            $url = \Yii::app()->assetManager->publish(OSHelper::path()->join(dirname(__DIR__), 'js', 'alwaysAllowedList.js'));
            \Yii::app()->clientScript->registerScriptFile($url);

			$this->render('index', array(
				'controllersMeta'     => $controllersMeta,
                'allowedOptions' => $allowedOptions,
				'formAction'	  => $this->createUrl('index'),
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

	public function formatControllerName($controller){
		return str_replace($this->utils->delimiter, '-', $controller);
	}
}