<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alegz
 * Date: 12/8/12
 * Time: 12:12 AM
 */
class UserController extends BController
{
    public function actionAdd()
    {
        $extraResources = array(
            'styles' => array(
                250 => 'chosen.css'
            ),
            'scripts' => array(
                450 => 'chosen.jquery.min.js'
            )
        );

        $this->addResourceFiles($extraResources);

        $this->render('user-add');
    }
}
