<?php
class DefaultController extends Controller
{
    public $layout = "//layouts/backend-menu";

    public function actionIndex()
    {
        $this->render('index');
    }
}
