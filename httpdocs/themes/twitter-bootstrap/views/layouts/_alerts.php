<?php
/**
 * User: alegz
 * Date: 10/13/13
 * Time: 3:37 PM
 */

$container = Yii::app()->user->getFlashes();

foreach ($container as $key => $message){
    $rawKey = explode('@', $key);

    if (count($rawKey) === 2)
        list($prefix, $flashKey) = $rawKey;
    else if (count($rawKey) === 1)
        list($flashKey) = $rawKey;

    if (isset($flashKey))
        \Yii::app()->user->setFlash($flashKey, $message);

    $this->widget('bootstrap.widgets.TbAlert', array(
        'block' => true,
        'fade' => true,
        'userComponentId' => 'user'
    ));
}