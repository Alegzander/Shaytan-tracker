<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alegz
 * Date: 12/9/12
 * Time: 11:38 AM
 */
?>
<!-- фильтры -->
<div class="pull-right">
    <ul class="nav nav-pills filter">
        <li class="filter-title">Сортировать по:</li>
        <li class="active"><a href="#">по дате регистрации</a></li>
        <li><a href="#">по статусу</a></li>
    </ul>
    <ul class="nav nav-pills filter filter-second">
        <li class="active"><a href="#">по возрастанию</a></li>
        <li><a href="#">по убыванию</a></li>
        <!-- uncomment these when sorting by status -->
        <!--
        <li class="active"><a href="#">активные</a></li>
        <li><a href="#">неактивные</a></li>
        -->
    </ul>
</div>
<!-- форма поиска пользователей -->
<form>
    <div class="input-prepend input-append">
        <span class="add-on"><i class="icon-envelope"></i></span>
        <input class="span3" id="appendedPrependedInput" type="text" placeholder="Искать по почтовому адресу">
        <a href="#" class="add-on btn"><i class="icon-remove" title="очистить поле"></i></a>
    </div>
    <div class="input-prepend input-append">
        <span class="add-on"><i class="icon-user"></i></span>
        <input class="span3" id="appendedPrependedInput" type="text" placeholder="Искать по имени пользователя">
        <a href="#" class="add-on btn"><i class="icon-remove" title="очистить поле"></i></a>
    </div>
    <div class="input-prepend input-append">
        <span class="add-on"><i class="icon-th-list"></i></span>
        <input class="span3" id="appendedPrependedInput" type="text" placeholder="Искать по номеру телефона">
        <a href="#" class="add-on btn"><i class="icon-remove" title="очистить поле"></i></a>
    </div>
    <button class='btn' href='#'><i class='icon-search'></i></button>
</form>
<!-- верхний пагинатор -->
<div class='pagination pagination-centered'>
    <ul>
        <li class='disabled'><a href='#'>«</a></li>
        <li class='active'><a href='#'>1</a></li>
        <li><a href='#'>2</a></li>
        <li><a href='#'>3</a></li>
        <li><a href='#'>4</a></li>
        <li><a href='#'>»</a></li>
    </ul>
</div>
<!-- список пользователей -->
<table class='table table-hover'>
    <thead>
    <tr>
        <th>Email</th>
        <th>Имя пользователя</th>
        <th>Номер телефона</th>
        <th>Активность</th>
        <th>Опции</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="5">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne" title="создать нового пользователя">
                    <center><i class="icon-plus"></i></center>
                </a>
            </div>
            <div id="collapseOne" class="accordion-body collapse in">
                <div class="accordion-inner">
                    //поместить здесь форму создания пользователя
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td><a href="#" title="перейти к профилю пользователя">umcdwarf@gmail.com</a></td>
        <td><a href="#" title="перейти к профилю пользователя">Urist McDwarf</a></td>
        <td>(+123 45) 123-45-67</td>
        <td><img class="img-circle user-status-active"></td>
        <td>
            <button class="btn" title="редактировать пользователя"><i class="icon-pencil"></i></button>&nbsp;&nbsp;&nbsp;
            <button class="btn btn-danger" title="удалить пользователя"><i class="icon-remove icon-white"></i></button>
        </td>
    </tr>
    <tr>
        <td><a href="#" title="перейти к профилю пользователя">jdoe@test.com</a></td>
        <td><a href="#" title="перейти к профилю пользователя">John Doe</a></td>
        <td>(+98 876) 765-54-43</td>
        <td><img class="img-circle user-status-inactive"></td>
        <td>
            <button class="btn" title="редактировать пользователя"><i class="icon-pencil"></i></button>&nbsp;&nbsp;&nbsp;
            <button class="btn btn-danger" title="удалить пользователя"><i class="icon-remove icon-white"></i></button>
        </td>
    </tr>
    <tr>
        <td><a href="#" title="перейти к профилю пользователя">jdoe@test.com</a></td>
        <td><a href="#" title="перейти к профилю пользователя">John Doe</a></td>
        <td>(+98 876) 765-54-43</td>
        <td><img class="img-circle user-status-inactive"></td>
        <td>
            <button class="btn" title="редактировать пользователя"><i class="icon-pencil"></i></button>&nbsp;&nbsp;&nbsp;
            <button class="btn btn-danger" title="удалить пользователя"><i class="icon-remove icon-white"></i></button>
        </td>
    </tr>
    <tr>
        <td><a href="#" title="перейти к профилю пользователя">jdoe@test.com</a></td>
        <td><a href="#" title="перейти к профилю пользователя">John Doe</a></td>
        <td>(+98 876) 765-54-43</td>
        <td><img class="img-circle user-status-inactive"></td>
        <td>
            <button class="btn" title="редактировать пользователя"><i class="icon-pencil"></i></button>&nbsp;&nbsp;&nbsp;
            <button class="btn btn-danger" title="удалить пользователя"><i class="icon-remove icon-white"></i></button>
        </td>
    </tr>
    <tr>
        <td><a href="#" title="перейти к профилю пользователя">jdoe@test.com</a></td>
        <td><a href="#" title="перейти к профилю пользователя">John Doe</a></td>
        <td>(+98 876) 765-54-43</td>
        <td><img class="img-circle user-status-inactive"></td>
        <td>
            <button class="btn" title="редактировать пользователя"><i class="icon-pencil"></i></button>&nbsp;&nbsp;&nbsp;
            <button class="btn btn-danger" title="удалить пользователя"><i class="icon-remove icon-white"></i></button>
        </td>
    </tr>
    <tr>
        <td><a href="#" title="перейти к профилю пользователя">jdoe@test.com</a></td>
        <td><a href="#" title="перейти к профилю пользователя">John Doe</a></td>
        <td>(+98 876) 765-54-43</td>
        <td><img class="img-circle user-status-inactive"></td>
        <td>
            <button class="btn" title="редактировать пользователя"><i class="icon-pencil"></i></button>&nbsp;&nbsp;&nbsp;
            <button class="btn btn-danger" title="удалить пользователя"><i class="icon-remove icon-white"></i></button>
        </td>
    </tr>
    <tr>
        <td><a href="#" title="перейти к профилю пользователя">jdoe@test.com</a></td>
        <td><a href="#" title="перейти к профилю пользователя">John Doe</a></td>
        <td>(+98 876) 765-54-43</td>
        <td><img class="img-circle user-status-inactive"></td>
        <td>
            <button class="btn" title="редактировать пользователя"><i class="icon-pencil"></i></button>&nbsp;&nbsp;&nbsp;
            <button class="btn btn-danger" title="удалить пользователя"><i class="icon-remove icon-white"></i></button>
        </td>
    </tr>
    <tr>
        <td><a href="#" title="перейти к профилю пользователя">jdoe@test.com</a></td>
        <td><a href="#" title="перейти к профилю пользователя">John Doe</a></td>
        <td>(+98 876) 765-54-43</td>
        <td><img class="img-circle user-status-inactive"></td>
        <td>
            <button class="btn" title="редактировать пользователя"><i class="icon-pencil"></i></button>&nbsp;&nbsp;&nbsp;
            <button class="btn btn-danger" title="удалить пользователя"><i class="icon-remove icon-white"></i></button>
        </td>
    </tr>
    <tr>
        <td><a href="#" title="перейти к профилю пользователя">jdoe@test.com</a></td>
        <td><a href="#" title="перейти к профилю пользователя">John Doe</a></td>
        <td>(+98 876) 765-54-43</td>
        <td><img class="img-circle user-status-inactive"></td>
        <td>
            <button class="btn" title="редактировать пользователя"><i class="icon-pencil"></i></button>&nbsp;&nbsp;&nbsp;
            <button class="btn btn-danger" title="удалить пользователя"><i class="icon-remove icon-white"></i></button>
        </td>
    </tr>
    </tbody>
</table>
<!-- нижний пагинатор -->
<div class='pagination pagination-centered'>
    <ul>
        <li class='disabled'><a href='#'>«</a></li>
        <li class='active'><a href='#'>1</a></li>
        <li><a href='#'>2</a></li>
        <li><a href='#'>3</a></li>
        <li><a href='#'>4</a></li>
        <li><a href='#'>»</a></li>
    </ul>
</div>
</div>