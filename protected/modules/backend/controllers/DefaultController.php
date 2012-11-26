<?php
class DefaultController extends Controller
{
    public $layout = "//layouts/backend";

    public function actionIndex()
    {
        $this->render('index');
    }
}
