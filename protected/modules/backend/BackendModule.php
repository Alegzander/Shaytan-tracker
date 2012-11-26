<?php

class BackendModule extends CWebModule
{
    public function init()
    {
        $this->setImport(array(
            'backend.models.*',
            'backend.components.*',
        ));
    }

    public function beforeControllerAction($controller, $action)
    {
        if(parent::beforeControllerAction($controller, $action))
            return true;
        else
            return false;
    }
}
