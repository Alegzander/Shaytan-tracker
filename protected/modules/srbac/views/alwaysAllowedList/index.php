<?php
/**
 * User: alegz
 * Date: 7/19/13
 * Time: 3:13 PM
 *
 * @var AlwaysAllowedListController $this
 * @var array $controllers
 * @var array $controllersMeta
 * @var string $delimiter
 * @var string $formAction
 * @var AlwaysAllowedEditForm $formModel
 */
?>
<?php $this->renderPartial('/_manage-menu');?>
<div>
	<?php
	/**
	 * @var TbActiveForm $form
	 */
	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'method' => 'POST',
		'action' => $formAction,
		'type'	 => 'horizontal'
	));
	?>
		<div class="tabbable tabs-left">
			<ul id="srbac_tab" class="nav nav-tabs">
				<?php foreach ($controllers as $controller): ?>
				<li><a href="#<?=$this->formatControllerName($controller);?>"><?=$controller;?></a></li>
				<?php endforeach; ?>
			</ul>
			<div class="tab-content">
				<?php foreach ($controllersMeta as $controllerName => $controllerActions): ?>
				<div id="<?=$this->formatControllerName($controllerName);?>" class="tab-pane">
					<?php foreach ($controllerActions as $action): ?>
					<label class="checkbox"><?=$form->checkBox($formModel, 'item['.$action.']', array('value' => $action));?><?=$action;?></label>
					<?php endforeach;?>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
		<hr>
		<div class="control-group">
			<div class="controls">
				<button class="btn btn-primary">Save</button>
			</div>
		</div>
	<?php
	$this->endWidget();
	?>
</div>