<?php
/**
 * User: alegz
 * Date: 4/4/13
 * Time: 6:40 PM
 */
/**
 * @var AssignController $this
 * @var User $users
 * @var string $selectedUser
 * @var CAuthItem[] $assignedRoles
 * @var CAuthItem[] $notAssignedRoles
 */

$this->renderPartial('/_assign-menu');
?>
<p class="lead"><?=\Yii::t('srbac', 'Assign Roles to Users');?></p>
<table class="table vAlignMiddle">
	<thead>
	<tr>
		<th><?=\Yii::t('srbac', 'User');?></th>
		<th><?=\Yii::t('srbac', 'Assigned Roles');?></th>
		<th></th>
		<th><?=\Yii::t('srbac', 'Not Assigned Roles');?></th>
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
				'dataProvider' => $users->search(),
				'enableSorting' => false,
				//'filter' => false,
				'columns' => array(
					'name' => array(
						'class' => 'CLinkColumn',
						'header' => false,
						'urlExpression' => 'Yii::app()->controller->createUrl("users", array_merge($_GET, array("user" => $data->primaryKey)))',
						'labelExpression' => '$data->username',
					),
				),
			));
			?>
		</td>
		<td>
			<form method="post" action="<?=$this->createUrl('edit', array('type' => 'users', 'user' => $selectedUser, 'action' => 'remove'));?>">
			<select size="15" multiple="multiple" name="items[]">
				<?php foreach ($assignedRoles as $role): ?>
				<option value="<?=$role->name;?>"><?=$role->name;?></option>
				<?php endforeach; ?>
			</select>
		</td>
		<td>
			<button class="btn"><i class="icon-arrow-right"></i></button></form><hr>
			<form method="post" action="<?=$this->createUrl('edit', array('type' => 'users', 'user' => $selectedUser, 'action' => 'add'));?>">
			<button class="btn"><i class="icon-arrow-left"></i></button>
		</td>
		<td>
			<select size="15" multiple="multiple" name="items[]">
				<?php foreach ($notAssignedRoles as $role): ?>
				<option value="<?=$role->name;?>"><?=$role->name;?></option>
				<?php endforeach; ?>
			</select>
			</form>
		</td>
	</tr>
	</tbody>
</table>