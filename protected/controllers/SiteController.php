<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	
	public $listNum = 1;
	
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
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
		if (Yii::app()->request->getParam("list") &&
			is_numeric(Yii::app()->request->getParam("list")))
			$this->listNum = Yii::app()->request->getParam("list");
		
		$displayTorrents = Yii::app()->getParams()->displayTorrents;
		
		$criteria = Torrent::model()->setCriteria();
        $criteria->approved = true;

        $numTorrents = Torrent::model()->count($criteria);

        $criteria->limit($displayTorrents)->sort("_id", Torrent::SORT_DESC)->offset((($this->listNum-1) * $displayTorrents));
		
		$torrentsList = Torrent::model()->findAll($criteria);

        $tableRows = array();

        /**
         * @var $torrent Torrent
         */
        foreach ($torrentsList as $num => $torrent)
        {
            $rawCategory = explode("-", $torrent->category);

            $categoryIndex = count($rawCategory)-1;

            $displayCategory = $rawCategory[$categoryIndex];
            $downloadUrl = Yii::app()->getParams()->baseUrl."/torrent/download/id/".$torrent->_id;
            $viewUrl = Yii::app()->getParams()->baseUrl."/torrent/view/id/".$torrent->_id;

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

        $paginatorParams = array(
				"pagingAction" => Yii::app()->getParams()->baseUrl."/site/index/list",
				"dataSize" => $numTorrents,
				"pageNum" => $this->listNum,
				"displayLimit" => $displayTorrents
        );
		
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'		
		$this->render('index', array(
				"torrentsList" => $torrentsList, 
				"displayTorrents" => $displayTorrents,
				"paginatorParams" => $paginatorParams,
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