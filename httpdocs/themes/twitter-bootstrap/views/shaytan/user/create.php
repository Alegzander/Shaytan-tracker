<?php
/**
 * User: alegz
 * Date: 10/28/13
 * Time: 3:21 AM
 *
 * @var UserController $this
 * @var User $model
 * @var TbActiveForm $form
 */
\Yii::import('bootstrap.widgets.TbActiveForm');
\Yii::import('bootstrap.widgets.TbButton');
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'type' => TbActiveForm::TYPE_HORIZONTAL,
    'htmlOptions' => array('class' => 'well span8')
));
?>
<?=$form->textFieldRow($model, 'username');?>
<?=$form->textFieldRow($model, 'email');?>
<?=$form->passfieldFieldRow($model, 'password');?>
<?=$form->checkBoxRow($model, 'suspend');?>
<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'type' => TbButton::TYPE_PRIMARY,
        'buttonType' => TbButton::BUTTON_SUBMIT,
        'label' => \Yii::t('form-label', 'Create')
    ));?>
    &nbsp;
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => TbButton::BUTTON_RESET,
        'label' => \Yii::t('form-label', 'Reset')
    ));
    ?>
</div>
<?php
$this->endWidget();