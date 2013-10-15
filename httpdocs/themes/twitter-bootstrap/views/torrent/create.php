<?php
/**
 * User: alegz
 * Date: 10/10/13
 * Time: 12:36 AM
 *
 * @var TorrentController $this
 * @var CreateTorrentForm $formModel
 * @var TbActiveForm $form
 */
\Yii::import('bootstrap.widgets.TbActiveForm');
\Yii::import('bootstrap.widgets.TbButton');
\Yii::import('bootstrap.widgets.TbAlert');
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'method' => 'POST',
    'type' => TbActiveForm::TYPE_HORIZONTAL
));
?>
<!--Name field-->
<?=$form->textFieldRow($formModel, 'name', array(
        'hint' => \Yii::t('form-label', 'Here you put name of torrent that will be displayed in list.')
));?>
<!--Warning section-->
<?php
    \Yii::app()->user->setFlash(TbAlert::TYPE_WARNING, \Yii::t('form-label',
        'Maximum torrent size {size} Megabytes.', array(
        '{size}' => OSHelper::web()->getMaxUploadSize()
    )));
    $this->widget('bootstrap.widgets.TbAlert', array(
        'closeText' => false
    ));
?>
<!--Torrent field-->
<?=$form->fileFieldRow($formModel, 'torrent');?>
<div class="form-actions well">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'type' => TbButton::TYPE_PRIMARY,
        'buttonType' => TbButton::BUTTON_SUBMIT,
        'label' => \Yii::t('form-label', 'Upload')
    ));
    ?>
</div>
<?php $this->endWidget(); ?>