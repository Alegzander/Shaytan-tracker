<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alegz
 * Date: 12/4/12
 * Time: 2:25 PM
 * To change this template use File | Settings | File Templates.
 */
class BController extends Controller
{
    public $layout = '//layouts/backend-menu';

    public function init()
    {
        //TODO Сделать формирование массива меню

        /**
         * @var WebUser $user
         */
        Yii::app()->user->loginUrl = '/backend/login/authenticate';

        parent::init();
    }

    public function filters()
    {
        return array('accessControl');
    }
}
