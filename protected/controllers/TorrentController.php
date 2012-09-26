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

    public function actionView()
    {
        $id = Yii::app()->request->getQuery("id");

        if ($id === null)
            Yii::app()->request->redirect(Yii::app()->getParams()->baseUrl);

        $torrent = Torrent::model()->getById($id);

        if ($torrent === null)
            Yii::app()->request->redirect(Yii::app()->getParams()->baseUrl);

        $category = explode("-", $torrent->category);

        $filesList = array();

        if (isset($torrent->info["files"]))
            foreach ($torrent->info["files"] as $file)
                array_push($filesList, implode("/", $file["path"]));
        else
            array_push($filesList, $torrent->info["name"]);

        $uploadedTime = new CDateFormatter("ru_RU");

        $downloadLink = Yii::app()->getParams()->baseUrl."/torrent/download/id/".$torrent->_id;

        $this->render("view", array(
            "torrent" => $torrent,
            "category" => $category,
            "filesList" => $filesList,
            "uploadedTime" => $uploadedTime,
            "downloadLink" => $downloadLink
        ));
    }

    public function actionDownload()
    {
        $id = Yii::app()->request->getQuery("id");
        $fileType = Yii::app()->request->getQuery("filetype");
        $contentType = "application/x-bittorrent";

        if ($id === null)
            Yii::app()->request->redirect(Yii::app()->getParams()->baseUrl);

        if ($fileType != "torrent" && $fileType != "txt")
            $fileType = "torrent";

        if ($fileType == "txt")
            $contentType = "plain/text";

        $torrent = Torrent::model()->getById($id);

        if ($torrent === null)
            throw new CHttpException(404, Yii::t("app", "Торрент не найден. Проверьте правильность ссылки."));

        header("Content-type: ".$contentType);
        header("Content-Disposition: attachment; filename=\"".$torrent->name.".".$fileType."\"");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

        echo $torrent->getFile();
    }
}