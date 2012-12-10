<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alegz
 * Date: 12/8/12
 * Time: 1:23 AM
 */
?>
<?=CHtml::beginForm('', 'post', array('class' => 'well'));?>
    <fieldset>
        <legend><?=Yii::t('app', 'Добавить нового пользователя')?></legend>
        <?=CHtml::errorSummary($model);?>
        <ul class="shaytan-form">
            <li>
                <?=CHtml::activeEmailField($model, 'email', array('placeholder' => $labels['email']));?>
                <?=CHtml::activeTextField($model, 'name', array('placeholder' => $labels['name']))?>
            </li>
            <li>
                <?=CHtml::activePasswordField($model, 'password', array('placeholder' => $labels['password']));?>
                <?=CHtml::activePasswordField($model, 'confirmPassword', array('placeholder' => $labels['confirmPassword']));?>
            </li>
            <li>
                </i><?=CHtml::activeNumberField($model, 'phone', array('placeholder' => $labels['phone']))?>
                <a href="#" class="helper" rel="tooltip" data-placement="top" title="<?=Yii::t('app', 'Номер должен быть в международном формате. Например: {phone}',
                array('{phone}' => $phone));?>"><i class="icon-question-sign"></i></a>
            </li>
            <li class='devider'></li>
            <li>
                <?=CHtml::activeDropDownList($model, 'roles', $roles, array('data-placeholder' => $labels['roles'], 'class' => 'chzn-select', 'multiple' => '1'));?>
            </li>
            <li class='devider'></li>
            <li class='devider'></li>
            <li><button type="submit" class="btn"><?=Yii::t('app', 'Создать пользователя');?></button></li>
        </ul>
    </fieldset>
<?=CHtml::endForm();?>