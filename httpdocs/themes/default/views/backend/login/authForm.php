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
        <?=CHtml::activeTextField($model, 'login', array('placeholder' => $mod));?>
        <input type="text" placeholder="E-mail">
        <input type="password" placeholder="Пароль">
        <!-- img src="img/135411084855s.jpg" -->
        <?php
        CHtml::activeLabelEx($model, 'captcha');
        $this->widget('CCaptcha');
        ?>
        <input type="text" placeholder="Капча">
        <label class="checkbox">
            <input type="checkbox"> Запомнить меня
        </label>
        <button type="submit" class="btn">Войти</button>
    </fieldset>
</form>