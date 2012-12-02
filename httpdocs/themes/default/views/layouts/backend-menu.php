<?php $this->beginContent('//layouts/backend'); ?>
<!--Главное меню слева-->
<div class="span3">
    <div id="sidebar">
        <?php
        //Главная менюшка справа
        $this->widget("zii.widgets.CMenu", array(
                'htmlOptions' => array('class' => 'nav nav-list'),
                'encodeLabel' => false,
                'items' => array(
                    array('itemOptions' => array('class' => 'nav-header'), 'label' => '', 'template' => ''),
                    //Заголовок с надписью главная
                    array(
                        'itemOptions' => array('class' => 'active'),
                        'label' => '<i class="icon-home icon-white"></i>&nbsp;'.Yii::t('app', 'Главная'),
                        'url' => '#'
                    ),
                    array('itemOptions' => array('class' => 'divider'), 'template' => '', 'label' => '', ),

                    //Раздел торрентов
                    array(
                        'itemOptions' => array(
                            'data-target' => '#torrent-types',
                            'data-toggle' => 'collapse'
                        ),
                        'label' => '<i class="icon-wrench"></i>'.Yii::t('app', 'Торренты').'<span class="badge badge-important pull-right">14</span>',
                        'url'   => '#',
                    ),

                    array(
                        'itemOptions' => array('class' => 'collapse in', 'id' => 'torrent-types'),
                        'template' => '', 'label' => '',
                        'submenuOptions' => array('class' => 'nav nav-list'),
                        'items' => array(
                            array(
                                'label' => '<i class="icon-inbox"></i>'.Yii::t('app', 'Новые').'<span class="badge badge-info pull-right">12</span>',
                                'url' => '#',
                            ),
                            array(
                                'label' => '<i class="icon-ok-sign"></i>'.Yii::t('app', 'Активные'),
                                'url' => '#',
                            ),
                            array(
                                'label' => '<i class="icon-remove-sign"></i>'.Yii::t('app', 'Неактивные'),
                                'url' => '#',
                            ),
                            array(
                                'label' => '<i class="icon-question-sign"></i>'.Yii::t('app', 'Подозрительные'),
                                'url' => '#',
                            ),
                            array(
                                'label' => '<i class="icon-fire"></i>'.Yii::t('app', 'Жалобы').'<span class="badge badge-important pull-right">2</span>',
                                'url' => '#',
                            ),
                            array(
                                'label' => '<i class="icon-plus-sign"></i>'.Yii::t('app', 'Добавить'),
                                'url' => '#',
                            ),
                        )
                    ),

                    array('itemOptions' => array('class' => 'divider'), 'template' => '', 'label' => ''),

                    //Раздел пользователей
                    array(
                        'itemOptions' => array(
                            'data-target' => '#user-operations',
                            'data-toggle' => 'collapse'
                        ),
                        'label' => '<i class="icon-user"></i>'.Yii::t('app', 'Пользователи'),
                        'url'   => '#',
                    ),

                    array(
                        'itemOptions' => array('class' => 'collapse in', 'id' => 'user-operations'),
                        'template' => '', 'label' => '',
                        'submenuOptions' => array('class' => 'nav nav-list'),
                        'items' => array(
                            array(
                                'label' => '<i class="icon-plus-sign"></i>'.Yii::t('app', 'Добавить'),
                                'url' => '#',
                            ),
                            array(
                                'label' => '<i class="icon-edit"></i>'.Yii::t('app', 'Редактировать'),
                                'url' => '#',
                            ),
                        ),
                    ),

                    array('itemOptions' => array('class' => 'divider'), 'template' => '', 'label' => ''),
                )
            ));
        ?>
    </div>
</div>
<!--Правая часть кода сюда попадает content-->
<div class="span9">
    <?=$content?>
</div>
<?php $this->endContent('//layouts/backend'); ?>