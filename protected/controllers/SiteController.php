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

        $search = Yii::app()->request->getParam("search");
        $sort = Yii::app()->request->getParam("sort");
        $order = (int)Yii::app()->request->getParam("order");

        if (!isset($sort) || strlen($sort) <= 0)
            $sort = "_id";

        if (!isset($order) || !in_array($order, array(Torrent::SORT_DESC, Torrent::SORT_ASC)))
            $order = Torrent::SORT_DESC;

        $sortMenuItems = array();
        $orderMenu = array();

        if (
            isset(Yii::app()->getParams()->search) &&
            isset(Yii::app()->getParams()->search["allowedOrderList"]) &&
            is_array(Yii::app()->getParams()->search["allowedOrderList"]) &&
            count (Yii::app()->getParams()->search["allowedOrderList"]) > 0
        )
        {
            array_push($sortMenuItems,
                array(
                    'template' => Yii::t('app', 'Сортировать по:'),
                    'itemOptions' => array('class' => 'sort-label')
                )
            );

            foreach (Yii::app()->getParams()->search["allowedOrderList"] as $menuItem)
            {
                list($label, $field) = $menuItem;
                $tmp = array('label' => $label, 'url' => '/search/'.$search.'/'.$field.'/'.$order);

                if ($field == $sort)
                    $tmp["itemOptions"] = array('class' => 'active');

                array_push($sortMenuItems,
                    $tmp);
            }

            $descSort = array(
                'label' => Yii::t('app', 'по убыванию'),
                'url' => '/search/'.$search.'/'.$sort.'/'.Torrent::SORT_DESC,
            );

            $ascSort = array(
                'label' => Yii::t('app', 'по возрастанию'),
                'url' => '/search/'.$search.'/'.$sort.'/'.Torrent::SORT_ASC,
            );

            if ($order == Torrent::SORT_ASC)
                $ascSort["itemOptions"] = array('class' => 'active');
            else
                $descSort["itemOptions"] = array('class' => 'active');


            $orderMenu = array($descSort, $ascSort);
        }

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
            searchBy($search)->
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
                "tableRows" => $tableRows,
                "sortMenuItems" => $sortMenuItems,
                "orderMenu" => $orderMenu
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