<?php
/**
 * User: alegz
 * Date: 10/6/13
 * Time: 1:33 PM
 */
\Yii::import('bootstrap.widgets.TbMenu');

class MenuData {
    public function mainMenu(){
        return array(
            array(
                'class' => 'bootstrap.widgets.TbMenu',
                'type' => TbMenu::TYPE_PILLS,
                'items' => array(
                    array(
                        'label' => \Yii::t('app', 'Home'),
                        'url' => \Yii::app()->createUrl('/site/index'),
                        'active' => \Yii::app()->getController()->id == 'site' && \Yii::app()->getController()->action->id == 'index'
                    ),
                    array(
                        'label' => \Yii::t('app', 'Add torrent'),
                        'url' => \Yii::app()->createUrl('/site/addTorrent'),
                        'active' => \Yii::app()->getController()->id == 'site' && \Yii::app()->getController()->action->id == 'addTorrent'
                    )
                )
            )
        );
    }
}