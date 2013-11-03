<?php
/**
 * User: alegz
 * Date: 10/27/13
 * Time: 6:53 PM
 */

class ShaytanController extends SBaseController {
    public $layout = '/layouts/menu';

    public function init(){
        Yii::import('bootstrap.widgets.TbAlert');
    }
} 