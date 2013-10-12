<?php
/**
 * User: alegz
 * Date: 4/4/13
 * Time: 6:40 PM
 */
/**
 * @var AssignController $this
 * @var User $users
 * @var string $selectedItem
 * @var string $type
 * @var CAuthItem[] $assignedItems
 * @var CAuthItem[] $notAssignedItems
 * @var CArrayDataProvider $itemsProvider
 */

$this->renderPartial('/_assign-menu');
?>
<p class="lead"><?=\Yii::t('srbac', 'Assign Tasks to Roles');?></p>
<table class="table vAlignMiddle">
	<thead>
	<tr>
		<th><?=\Yii::t('srbac', 'Roles');?></th>
		<th><?=\Yii::t('srbac', 'Assigned Tasks');?></th>
		<th></th>
		<th><?=\Yii::t('srbac', 'Not Assigned Tasks');?></th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td>
			<?php
			$this->widget('bootstrap.widgets.TbGridView', array(
				'ajaxUpdate' => false,
				'type' => 'bordered',
				'template' => "{items}\n{pager}",
				'htmlOptions' => array('class' => 'table table-bordered table-hover'),
				'dataProvider' => $itemsProvider,
				'enableSorting' => false,
				//'filter' => false,
				'columns' => array(
					'name' => array(
						'class' => 'CLinkColumn',
						'header' => false,
						'urlExpression' => 'Yii::app()->controller->createUrl("items", array_merge($_GET, array("item" => $data->primaryKey, "type" => "'.$type.'")))',
						'labelExpression' => '$data->name',
					),
				),
			));
			?>
		</td>
		<td>
			<form method="post" action="<?=$this->createUrl('edititem', array_merge($_GET, array('type' => $type, 'item' => $selectedItem, 'action' => 'remove')));?>">
				<select size="15" multiple="multiple" name="items[]">
					<?php foreach ($assignedItems as $item): ?>
						<option value="<?=$item->name;?>"><?=$item->name;?></option>
					<?php endforeach; ?>
				</select>
		</td>
		<td>
			<button class="btn"><i class="icon-arrow-right"></i></button></form><hr>
			<form method="post" action="<?=$this->createUrl('edititem', array_merge($_GET, array('type' => $type, 'item' => $selectedItem, 'action' => 'add')));?>">
				<button class="btn"><i class="icon-arrow-left"></i></button>
		</td>
		<td>
			<select size="15" multiple="multiple" name="items[]">
				<?php foreach ($notAssignedItems as $item): ?>
					<option value="<?=$item->name;?>"><?=$item->name;?></option>
				<?php endforeach; ?>
			</select>
			</form>
		</td>
	</tr>
	</tbody>
</table>