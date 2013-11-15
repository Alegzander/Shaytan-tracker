<?php

class SiteController extends BaseController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),

			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	public function actionIndex()
	{
        $torrentMeta = TorrentMeta::model()->notHidden()->active();

        $this->render('index', array('model' => $torrentMeta));
	}

	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
            if (\Yii::app()->session->get('isBot', false) === true){
                \Yii::app()->session->remove('isBot');
                $response = array('result' => 'error', 'message' => $error['message']);
                $this->renderPartial('//json', array('data' => $response));
            } else if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
}