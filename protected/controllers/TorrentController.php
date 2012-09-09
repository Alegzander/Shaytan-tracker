<?php

class TorrentController extends Controller
{
	public function actionNew()
	{
		$formModel = CreateTorrentForm::model();
		
		if (Yii::app()->request->getPost("CreateTorrentForm") !== null)
		{
			$formModel->attributes = Yii::app()->request->getPost("CreateTorrentForm");
			
			if ($formModel->validate())
			{
				$tmpTorrent = Yii::app()->getParams()->tmpDir.DIRECTORY_SEPARATOR."tmp-".time().md5(rand(0, 999999999999)).".torrent";
				move_uploaded_file($_FILES["CreateTorrentForm"]["tmp_name"]["torrent"], $tmpTorrent);
				$torrent = Torrent::model()->fromTorrentFile($tmpTorrent);
				
				unlink($tmpTorrent);
			}
		}
		
		$this->render("create", array(
			"categories" => Yii::app()->getParams()->categories,
			"model" => $formModel 
		));
	}
}