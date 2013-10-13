<?php
/**
 * User: alegz
 * Date: 7/23/13
 * Time: 4:13 PM
 *
 * @var ClearAlwaysAllowedListController $this
 * @var array $unknownItems
 * @var AlwaysAllowedEditForm $formModel
 * @var TbActiveForm $form
 */
?>
<p class="lead textAlignCenter"><?=\Yii::t('app', 'The following items doesn\'t seem to belong to a controller.');?></p>
<div class="well well-small">
	<?php
	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'method' => 'POST',
		'action' => $this->createUrl('clear')
	));
	echo "\n"; //For html code beauty
	?>
		<div class="control-group checkbox-group">
			<label class="checkbox"><input class="check-all" type="checkbox"> <strong><?=\Yii::t('srbac', 'Check All');?></strong></label>
			<?php foreach ($unknownItems as $item): ?>
			<label class="checkbox"><?=$form->checkBox($formModel, 'item['.$item.']', array('value' => $item));?> <?=$item;?></label>
			<?php endforeach; ?>
		</div>
		<hr>
		<button class="btn btn-danger"><?=\Yii::t('srbac', 'Delete');?></button>
	<?php
	$this->endWidget();
	?>
</div>