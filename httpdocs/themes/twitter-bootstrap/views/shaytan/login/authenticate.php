<?php
/**
 * User: alegz
 * Date: 10/27/13
 * Time: 9:19 PM
 *
 * @var DefaultController $this
 * @var LoginForm $model
 * @var TbActiveForm $form
 */
Yii::import('bootstrap.widgets.TbActiveForm');
Yii::import('bootstrap.widgets.TbButton');
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'type' => TbActiveForm::TYPE_HORIZONTAL,
//    'ajaxUpdate' => false
));
?>
<div class="form well">
    <?=$form->textFieldRow($model, 'username');?>
    <?=$form->passwordFieldRow($model, 'password');?>
    <?=$form->checkBoxRow($model, 'rememberMe');?>
    <?=$form->captchaRow($model, 'captcha', array('class' => 'captcha-refresh-button'));?>
    <div class="form-actions">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'type' => TbButton::TYPE_PRIMARY,
            'buttonType' => TbButton::BUTTON_SUBMIT,
            'label' => \Yii::t('form-label', 'Login')
        ));
        ?>
    </div>
</div>
<?php
$this->endWidget();