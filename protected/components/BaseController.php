<?php
/**
 * User: alegz
 * Date: 10/5/13
 * Time: 9:21 PM
 */

class BaseController extends SBaseController {
    public function init(){
        parent::init();

        AssetsHelper::register(array('/css/project.css'));
    }
}