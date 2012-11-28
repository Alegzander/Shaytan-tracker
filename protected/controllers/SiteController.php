<?php

class SiteController extends Controller
{
	public function actions()
	{
		return array(
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'section'=>array(
				'class'=>'CViewAction',
			),
		);
	}
	
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		if (Yii::app()->request->getParam("page") &&
			is_numeric(Yii::app()->request->getParam("page")))
			$pageNum = Yii::app()->request->getParam("page");
        else
            $pageNum = 1;

        $sort = null;
        $order = null;

        if ($sort != Yii::app()->request->getParam("sort"))
            $sort = Yii::app()->request->getParam("sort");

        if ($order != Yii::app()->request->getParam("order"))
            $order = Yii::app()->request->getParam("order");

        /*
         * Задаю сколько отображать результатов в списке.
         */
        if (isset(Yii::app()->getParams()->displayTorrents))
		    $displayTorrents = Yii::app()->getParams()->displayTorrents;
        else
		    $displayTorrents = 100;

		$numTorrents = Torrent::model()->count();

        $paginator = new CPagination($numTorrents);
        $paginator->setPageSize($displayTorrents);
        $paginator->setCurrentPage(($pageNum - 1));

        $torrentsList = Torrent::model()->
            setPagination($paginator)->
            sort($sort, $order)->
            findAll();

        $tableRows = array();

        /**
         * @var $torrent Torrent
         */
        foreach ($torrentsList as $num => $torrent)
        {
            //TODO Сделать нормальную работу с категориями
            $rawCategory = explode("-", $torrent->category);

            $categoryIndex = count($rawCategory)-1;

            $displayCategory = $rawCategory[$categoryIndex];
            $downloadUrl = Yii::app()->getParams()->baseUrl."/torrent/download/".$torrent->_id;
            $viewUrl = Yii::app()->getParams()->baseUrl."/torrent/view/".$torrent->_id;

            array_push($tableRows, array(
                "id" => $num,
                "category" => $displayCategory,
                "name" => $torrent->name,
                "view" => $viewUrl,
                "download" => $downloadUrl,
                "size" => $torrent->getTotalSize(),
                "seeders" => count($torrent->peers["seeders"]),
                "leachers" => count($torrent->peers["leachers"]),
                "downloaded" => $torrent->downloaded,
                "comments" => count($torrent->comments)
            ));
        }
		
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'		
		$this->render('index', array(
				"torrentsList" => $torrentsList, 
				"displayTorrents" => $displayTorrents,
				"pagination" => $paginator,
                "tableRows" => $tableRows
				));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}