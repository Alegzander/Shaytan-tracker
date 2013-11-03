<?php
/**
 * @var BaseController $this
 */
\Yii::import('bootstrap.widgets.TbMenu');
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="<?= \Yii::app()->getLanguage(); ?>"/>

    <title><?= CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
<div class="container">
    <!--  Pushin 80 pixels up  -->
    <div class="push"></div>
    <div class="flash">
        <?php $this->renderPartial('//layouts/_alerts');?>
    </div>
    <div class="content">
        <?=$content;?>
    </div>
    <?php $this->renderPartial('//layouts/_footer'); ?>

</div>
<!-- page -->
</body>
</html>
