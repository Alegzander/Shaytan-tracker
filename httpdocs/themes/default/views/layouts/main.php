<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?=CHtml::encode($this->pageTitle); ?></title>
    <link rel="stylesheet" href="<?=$this->resources["resources"]."/css/bootstrap.min.css" ?>" />
    <link rel="stylesheet" href="<?=$this->resources["resources"]."/css/bootstrap-responsive.min.css" ?>" />
    <link rel="stylesheet" href="<?=$this->resources["resources"]."/css/style.css" ?>" />
    <script type="text/javascript" src="<?=$this->resources["resources"]."/js/bootstrap.min.js" ?>"></script>
    <script type="text/javascript" src="<?=$this->resources["resources"]."/js/bootstrap-typeahead.js" ?>"></script>
</head>
  <body style="padding: 5px;">
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
        	<?php $this->widget('zii.widgets.CMenu',array(
			'htmlOptions' => array('class' => 'nav'),
			'items'=>array(
				array('label'=>Yii::t('app', 'Торренты'), 'url'=>array('site/index')),
				array('label'=>Yii::t('app', 'Загрузить'), 'url'=>array('torrent/new')),
				array('label'=>Yii::t('app', 'Форум'), 'url'=>'http://anigari.anime.uz/'),
				array('label'=>Yii::t('app', 'Правила/FAQ'), 'url'=>array('section/faq')),

				/**
				 * Search form
				 */
				array('template' => '<form class="form-search">
                <input type="text" class="input-medium search-query">
                <button type="submit" class="btn">'.Yii::t("app", "Поиск").'</button>
              </form>')
			),
		));
        ?>
        </div>
    </div>
    <div id="content">
		<?=$content;?>
	</div>
  </body>
</html>