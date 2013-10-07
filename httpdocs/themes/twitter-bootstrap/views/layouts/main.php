<?php
/**
 * @var BaseController $this
 */
\Yii::import('bootstrap.widgets.TbNavbar');
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="<?= \Yii::app()->getLanguage(); ?>"/>

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
<div class="container">
    <div id="mainmenu">
        <?php
        $menu = new MenuData();

        $this->widget('bootstrap.widgets.TbNavbar', array(
            'type' => TbNavbar::TYPE_INVERSE,
            'brandUrl' => '#',
            'fixed' => TbNavbar::FIXED_TOP,
            'collapse' => true,
            'items' => $menu->mainMenu()
        ));
        ?>
    </div>
    <!--  Pushin 80 pixels up  -->
    <div class="push"></div>
    <div class="content">
        <?php echo $content; ?>
    </div>
    <?php $this->renderPartial('//layouts/_footer'); ?>

</div>
<!-- page -->
</body>
</html>
