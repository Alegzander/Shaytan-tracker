<?php $this->beginContent('//layouts/backend'); ?>
<!--Главное меню слева-->
<div class="span3">
    <div id="sidebar">
        <?php
        //Главная менюшка справа
        $this->widget("zii.widgets.CMenu", $this->menu);
        ?>
    </div>
</div>
<!--Правая часть кода сюда попадает content-->
<div class="span9">
    <?=$content?>
</div>
<?php $this->endContent('//layouts/backend'); ?>