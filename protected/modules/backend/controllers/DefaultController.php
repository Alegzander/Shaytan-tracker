<?php
class DefaultController extends BController
{
    public function accessRules()
    {
        return array(
            array(
                'deny',
                'actions' => array('index'),
                'users' => array('?')
            )
        );
    }

    public function actionIndex()
    {
        $this->render('index');
    }
}
