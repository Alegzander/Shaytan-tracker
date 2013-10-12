<?php
/**
 * User: alegz
 * Date: 4/10/13
 * Time: 6:29 AM
 */
/**
 * @var UserController $this
 * @var array $items
 * @var array $users
 */
?>
<div class="push"></div>
<form method="GET">
<?php
$this->widget('bootstrap.widgets.TbSelect2', array(
	'asDropDownList' => true,
	'events' => array(),
	'data' => $users,
	'name' => 'name',
	'options' => array(
		//'tags' => $users,
		'placeholder' => 'Select user',
		'width' => '40%',
		'tokenSeparators' => array(',', ' ')
	)));
?>
&nbsp;&nbsp;
<button type="submit" class="btn"><?=\Yii::t('srbac', 'Get assignments');?></button>
</form>
<!--User assignment table -->
<table class="table table-bordered">
	<thead>
	<tr>
		<th><?=\Yii::t('srbac', 'Roles');?></th>
		<th width="35%"><?=\Yii::t('srbac', 'Tasks');?></th>
		<th width="35%"><?=\Yii::t('srbac', 'Operations');?></th>
	</tr>
	</thead>
	<tbody>
	<!--Starting Roles list -->
	<?php foreach ($items as $role => $roleItems): ?>
	<tr>
		<td><?=$role;?></td>
		<td colspan="2">
			<!--Here goes tasks -->
			<?php foreach ($roleItems as $task => $taskItems):?>
			<table class="table table-bordered">
				<tbody>
				<tr>
					<td width="50%"><?=$task;?></td>
					<td width="50%">
						<!--Operations list -->
						<?php foreach($taskItems as $operation): ?>
						<?=$operation;?><br>
						<?php endforeach; ?>
					</td>
				</tr>

				</tbody>
			</table>
			<?php endforeach; ?>
		</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
</table>