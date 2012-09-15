<!-- tracker.anime.uz -->
<html>
  <head>
    <title><?=CHtml::encode($this->pageTitle); ?></title>
    <meta charset="UTF-8">
  </head>
  <body style="padding: 5px;">
    <?php
    $this->widget("bootstrap.widgets.TbNavbar", array(
        "type" => "inverse",
        "collapse" => true,
        "items" => array(
            array(
                "class"=>"bootstrap.widgets.TbMenu",
                "items" => array(
                    array('label'=>Yii::t('app', 'Торренты'), 'url'=>array('/site/index')),
                    array('label'=>Yii::t('app', 'Загрузить'), 'url'=>array('/torrent/new')),
                    array('label'=>Yii::t('app', 'Форум'), 'url'=>'http://anigari.anime.uz/'),
                    array('label'=>Yii::t('app', 'Правила/FAQ'), 'url'=>array('/site/page/view/faq')),
                )
            )
        ),
    ))
    ?>
    <!--div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
        	<?php $this->widget('zii.widgets.CMenu',array(
			'htmlOptions' => array('class' => 'nav'),
			'items'=>array(
				array('label'=>Yii::t('app', 'Торренты'), 'url'=>array('/site/index')),
				array('label'=>Yii::t('app', 'Загрузить'), 'url'=>array('/torrent/new')),
				array('label'=>Yii::t('app', 'Форум'), 'url'=>'http://anigari.anime.uz/'),
				array('label'=>Yii::t('app', 'Правила/FAQ'), 'url'=>array('/site/page/view/faq')),

				/**
				 * Search form
				 */
				array('template' => '<form class="form-search">
                <input type="text" class="input-medium search-query">
                <button type="submit" class="btn">Search</button>
              </form>')
			),
		)); ?>
        </div>
    </div -->
    <div id="content">
		<?=$content;?>
	</div>
  </body>
</html>