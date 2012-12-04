<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alegz
 * Date: 12/4/12
 * Time: 10:06 PM
 */

/**
 * @var LoginController $this
 */
?>
<form class="well backend-auth" method='post' action=''>
    <fieldset>
        <legend>Авторизация</legend>
        <?=CHtml::errorSummary($model,null, null, array('class' => 'unstyled'));?>
        <?=CHtml::activeTextField($model, 'login', array('placeholder' => $label['login']));?>
        <?=CHtml::activePasswordField($model, 'password', array('placeholder' => $label['password']));?>
        <?php
        $this->widget('CCaptcha', array('buttonOptions' => array('class' => 'new-line')));
        ?>
        <?=CHtml::activeTextField($model, 'captcha', array('placeholder' => $label['captcha']));?>
        <label class="checkbox">
            <?=CHtml::activeCheckBox($model, 'rememberMe')?> <?=$label['rememberMe'];?>
        </label>
        <button type="submit" class="btn">Войти</button>
    </fieldset>
</form>