<?php
/**
 * User: alegz
 * Date: 3/22/13
 * Time: 5:55 PM
 */

class ManageController extends SrbacWraperController {
    public $layout = '/layouts/manage';

	public function actionIndex()
	{
		$model = new AuthItem('search');
		$newItem = new AuthItem();
		$model->unsetAttributes();
		$get = \Yii::app()->request->getQuery(get_class($model));
		$post = \Yii::app()->request->getPost(get_class($model));

		if (isset($get))
		{
			$model->attributes = $get;

			$search = $model->search();

			if ($search->totalItemCount == 1)
			{
				$tmpModel = $search->getData();
				$newItem = $tmpModel[0];
				unset($tmpModel);
			}
		}

		AssetsHelper::register(array('/js/srbac/manage.js'));

		$this->render('manage', array('model' => $model, 'newItem' => $newItem));
	}

	public function actionDelete()
	{
		$id = \Yii::app()->request->getQuery('name');

		$model = AuthItem::model();

		if (!$model->deleteByPk($id))
			\Yii::app()->user->setFlash('error', $model->getError('name'));

		if (!\Yii::app()->request->isAjaxRequest)
			$this->redirect($this->createUrl('index'));
	}

	public function actionEdit()
	{
		$post = \Yii::app()->request->getPost(get_class(AuthItem::model()));

		if (isset($post['new']))
		{
			if (!$model = AuthItem::model()->findByPk($post['new']['name']))
				$model = new AuthItem();

			$model->attributes = $post['new'];

			if (!$model->save())
				\Yii::app()->user->setFlash('error', $model->getError('name'));
		}

		if (!\Yii::app()->request->isAjaxRequest)
			$this->redirect('index');
	}
}