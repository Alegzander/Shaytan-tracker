<?php
/**
 * User: alegz
 * Date: 7/19/13
 * Time: 3:13 PM
 *
 * @var AlwaysAllowedListController $this
 * @var array $controllersMeta
 * @var array $allowedOptions
 * @var string $delimiter
 * @var string $formAction
 * @var AlwaysAllowedEditForm $formModel
 */
\Yii::import('bootstrap.widgets.TbTabs');
\Yii::import('bootstrap.widgets.TbButton');
$tabs = array();
?>
<?php
/**
 * @var TbActiveForm $form
 */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'method' => 'POST',
    'action' => $formAction,
    'type' => 'horizontal'
));

foreach ($controllersMeta as $controller => $authItems) {
    array_push($tabs, array(
        'label' => $controller,
        'active' => $controller === 'SiteController',
        'content' => $this->renderPartial('_controller-items', array(
            'items' => $authItems,
            'allowedOptions' => $allowedOptions,
            'formModel' => $formModel,
            'form' => $form
        ), true)
    ));
}
?>
<div class="container">
    <?php
    $this->widget('bootstrap.widgets.TbTabs', array(
        'placement' => TbTabs::PLACEMENT_LEFT,
        'tabs' => $tabs
    ));
    ?>
</div>
<div class="form-actions well">
    <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'type' => TbButton::TYPE_PRIMARY,
            'buttonType' => TbButton::BUTTON_SUBMIT,
            'label' => \Yii::t('form-label', 'Save')
        ));
    ?>
</div>
<?php
$this->endWidget();
?>
