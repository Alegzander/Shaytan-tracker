<?php

class TorrentController extends Controller
{
	public function actionNew()
	{
		$formModel = CreateTorrentForm::model();
		
		$this->render("create", array(
			"categories" => Yii::app()->getParams()->categories,
			"model" => $formModel 
		));
	}
	
	public function actionUpload()
	{
		$form = CreateTorrentForm::model();
		
		if (isset($_POST["CreateTorrentForm"]))
		{
			$form->attributes = Yii::app()->request->getPost("CreateTorrentForm", null);
			
			if (!$form->validate())
			{
				$this->render("create", array(
						"categories" => Yii::app()->getParams()->categories,
						"model" => $form
				));
			}
			
			
		}
		else
		{
			
		}
	}
}