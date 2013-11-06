<?php
/**
 * User: alegz
 * Date: 10/10/13
 * Time: 12:36 AM
 *
 * @var TorrentController $this
 * @var CreateTorrentForm $formModel
 * @var TbActiveForm $form
 * @var array $tagList
 */
\Yii::import('bootstrap.widgets.TbActiveForm');
\Yii::import('bootstrap.widgets.TbButton');
\Yii::import('bootstrap.widgets.TbAlert');
?>
<div class="page-header">
    <h1><?=$this->getPageTitle();?></h1>
</div>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'method' => 'POST',
    'type' => TbActiveForm::TYPE_HORIZONTAL,
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
));
?>
<!--Name field-->
<?=$form->textFieldRow($formModel, 'name', array(
        'hint' => \Yii::t('form-label', 'Here you put name of torrent that will be displayed in list. (Optional)')
));?>

<!--Torrent field-->
<?=$form->fileFieldRow($formModel, 'torrent');?>

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

<!--Tags field-->
<?=$form->select2Row($formModel, 'tags', array(
        'asDropDownList' => false,
    'options' => array(
        'tags' => $tagList,
        'placeholder' => \Yii::t('form-label', 'Tags'),
        'tokenSeparators' => array(',')
    )
));?>

<!--Information URL-->
<?=$form->textFieldRow($formModel, 'informationUrl', array(
    'hint' => 'Link to anything. IRC channel, Web site, etc.'
));?>

<?php if (isset($this->zone)): ?>
<div class="alert-danger">
    <?=$form->checkBoxRow($formModel, 'limitToZone', array(
    'hint' => \Yii::t('form-label', 'Your IP belongs to "{zone}" network zone. If you take off the flag torrent will be available for download for all users (from any country).', array(
            '{zone}' => $this->zone->name
        )),
)); ?>
</div>
<?php
\Yii::app()->user->setFlash(TbAlert::TYPE_DANGER, \Yii::t('form-label',
    '<b>Warning! Removing limit might cause big internet traffic expense. Taking off flag is at your own risk.</b>', array(
    )));
$this->widget('bootstrap.widgets.TbAlert', array(
    'closeText' => false
));
?>
<?php endif; ?>

<!--Is hidden-->
<?=$form->checkBoxRow($formModel, 'hidden');?>

<!--Description-->
<?php $this->renderPartial('create/_description-field', array(
    'form' => $form, 'formModel' => $formModel,
    'descriptionFieldName' => 'description',
    'descriptionFromFileFieldName' => 'descriptionFromFile'
));?>

<!--Accept rules-->
<?=$form->checkBoxRow($formModel, 'accept', array('value' => 'accepted'));?>

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