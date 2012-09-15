<?php
class TorrentController extends Controller
{
	public function actionNew()
	{
		$torrent = new Torrent;
		$modelClass = get_class($torrent);
		
		if (Yii::app()->request->getPost($modelClass) !== null)
		{
			$torrent->attributes = Yii::app()->request->getPost($modelClass);
			
			$tmpTorrent = Yii::app()->getParams()->tmpDir.DIRECTORY_SEPARATOR."tmp-".time().md5(rand(0, 999999999999)).".torrent";
			
			$torrentFile = CUploadedFile::getInstance($torrent, "torrent");
			
			if ($torrentFile !== NULL)
			{
				$torrentFile->saveAs($tmpTorrent);
				
				$torrent->parseTorrentFile($tmpTorrent);
				
				if (file_exists($tmpTorrent))
					unlink($tmpTorrent);
				
				if ($torrent->save())
					Yii::app()->request->redirect(Yii::app()->getParams()->baseUrl);
			}
			else
			{
				$torrent->addError("torrent", Yii::t("app", "Произошла ошибка при чтении файла, загрузите пожалуйста файл раз."));
			}
		}
		
		$this->render("create", array(
			"categories" => Yii::app()->getParams()->categories,
			"model" => $torrent,
			"modelClass" => $modelClass,	
		));
	}
}