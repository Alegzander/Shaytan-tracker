<?php
/**
 * User: alegz
 * Date: 10/12/13
 * Time: 11:57 PM
 *
 * @var BaseController $this
 */
\Yii::import('bootstrap.widgets.TbMenu');
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => TbMenu::TYPE_PILLS,
    'items' => array(
        array(
            'label' => 'Manage auth items',
            'url' => $this->createUrl('manage/index'),
            'active' =>  in_array(\Yii::app()->controller->id, array(
                'manage', 'autocreate', 'alwaysAllowedList', 'clearAlwaysAllowedList'
            ))
        ),
        array(
            'label' => 'Assign to user',
            'url' => $this->createUrl('assign/users'),
            'active' => \Yii::app()->controller->id === 'assign'
        ),
        array(
            'label' => 'User\'s assignments',
            'url' => $this->createUrl('user/index'),
            'active' => \Yii::app()->controller->id === 'user'
        )
    )
));
?>