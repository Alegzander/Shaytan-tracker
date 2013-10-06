<?php
/**
 * User: alegz
 * Date: 7/23/13
 * Time: 4:09 PM
 */

class ClearAlwaysAllowedListController extends SrbacWraperController {
	/**
	 * @var SrbacUtils
	 */
	private $utils;

	public function init(){
		parent::init();

		$this->utils = new SrbacUtils();
	}

	public function actionIndex(){
		if ($this->utils->isAlwaysAllowedFileWritable() === false){
			$this->render('/_not-writable',
				array(
					'file' => $this->utils->getAlwaysAllowedFile(),
					'user' => $_ENV['USER']
				)
			);
			return false;
		}

		$controllers = $this->utils->getControllers();
		$existingAuthItems = array();
		$unknownItems = array();

		foreach($controllers as $controller){
			$tmp = $this->utils->getControllerInfo($controller, true);

			foreach ($tmp[0] as $item){
				array_push($existingAuthItems, $item);

				unset($item);
			}

			unset($controller);
		}

		/**
		 * @var CAuthItem[] $items
		 */
		$items = \Yii::app()->authManager->getAuthItems(CAuthItem::TYPE_OPERATION);

		foreach ($items as $item){
			if (!in_array($item->name, $existingAuthItems)){
				array_push($unknownItems, $item->name);
			}

			unset($item);
		}

		foreach ($this->utils->readAlwaysAllowedFile() as $operation){
			if (\Yii::app()->authManager->getAuthItem($operation) === null)
				array_push($unknownItems, $operation);

			unset($operation);
		}

		AssetsHelper::register(array('/js/srbac/autocreate/index.js'));

		$this->render('index', array(
			'unknownItems' => $unknownItems,
			'formModel' => new ClearAlwaysAllowedEditForm()
		));
	}

	public function actionClear(){
		if ($this->utils->isAlwaysAllowedFileWritable() === false){
			$this->render('/_not-writable',
				array(
					'file' => $this->utils->getAlwaysAllowedFile(),
					'user' => $_ENV['USER']
				)
			);
			return false;
		}

		$form = new ClearAlwaysAllowedEditForm();
		$post = \Yii::app()->request->getPost(get_class($form));

		$form->attributes = $post;

		if ($form->validate()){
			$existingItems = $this->utils->readAlwaysAllowedFile();
			$existingItemsIndex = array_flip($existingItems);

			foreach ($form->item as $item){
				if (!isset($existingItemsIndex[$item])){
					if (\Yii::app()->authManager->getAuthItem($item) !== null)
						\Yii::app()->authManager->removeAuthItem($item);

					continue;
				}

				$index = $existingItemsIndex[$item];

				unset($existingItems[$index]);

				$this->utils->writeAlwaysAllowedFile($existingItems);

				unset($item);
			}

		} else {
			foreach ($form->getErrors('item') as $error){
				\Yii::app()->user->setFlash('error', $error);
			}
		}

		$this->redirect($this->createUrl('index'));
	}
}