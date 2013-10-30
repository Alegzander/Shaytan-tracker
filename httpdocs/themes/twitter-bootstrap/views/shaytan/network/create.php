<?php
/**
 * User: alegz
 * Date: 10/29/13
 * Time: 8:27 AM
 *
 * @var NetworkController $this
 * @var Network $model
 * @var TbActiveForm $form
 */
\Yii::import('bootstrap.widgets.TbActiveForm');
\Yii::import('bootstrap.widgets.TbButton');
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'type' => TbActiveForm::TYPE_HORIZONTAL,
    'method' => 'post',
    'htmlOptions' => array('class' => 'well')
));
?>
<?=$form->textFieldRow($model, 'name', array('hint' => \Yii::t('form-label', 'Any zone name')));?>
<?=$form->textAreaRow($model, 'zones',
    array('hint' => \Yii::t('form-label', 'Add comma separated network subnets to this field like: 127.0.0.0/8, 192.168.10.0/24')));?>
<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'type' => TbButton::TYPE_PRIMARY,
        'buttonType' => TbButton::BUTTON_SUBMIT,
        'label' => \Yii::t('form-label', 'Save'),
    ));
    ?>
    &nbsp;
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'type' => TbButton::TYPE_PRIMARY,
        'buttonType' => TbButton::BUTTON_RESET,
        'label' => \Yii::t('form-label', 'Save'),
    ));
    ?>
</div>
<?
$this->endWidget();