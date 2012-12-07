<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alegz
 * Date: 12/8/12
 * Time: 1:23 AM
 * To change this template use File | Settings | File Templates.
 */
?>
<form class="well">
    <fieldset>
        <legend>Добавить нового пользователя</legend>

        <ul class="shaytan-form">
            <li><input type="text" placeholder="E-mail"></li>
            <li>
                <input type="password" placeholder="Пароль">
                <input type="password" placeholder="Повтор пароля">
            </li>
            <li>
                <input type="text" placeholder="Имя пользователя">
                <input type="text" placeholder="Телефон">
            </li>
            <li class='devider'></li>
            <li>
                <select data-placeholder="Роли пользователя" class="chzn-select" multiple>
                    <option>Наблюдатель</option>
                    <option>Оператор</option>
                    <option>Модератор</option>
                    <option>Администратор</option>
                </select>
            </li>
            <li class='devider'></li>
            <li class='devider'></li>
            <li><button type="submit" class="btn">Создать пользователя</button></li>
        </ul>
    </fieldset>
</form>