<?php
/**
 * User: alegz
 * Date: 3/27/13
 * Time: 1:36 PM
 */
/**
 * @var AutocreateController $this
 * @var CArrayDataProvider[] $controllers
 */
$this->renderPartial('/_manage-menu');

?>
<div class="row">
	<div class="span6">
		<?php
		foreach ($controllers as $module => $controller)
		{
			$this->widget('bootstrap.widgets.TbGridView', array(
				'ajaxUpdate' => true,
				'type' => 'bordered',
				'template' => "{items}\n{pager}",
				'htmlOptions' => array('class' => 'table table-bordered table-hover'),
				'dataProvider' => $controller,
				'enableSorting' => false,
				//'filter' => false,
				'columns' => array(
					'controller' => array(
						'name' => 'name',
						'header' => $module == 'default'?\Yii::t('app', 'Controller'):$module
					),
					'actions' => array(
						'class' => 'application.components.TbGroupButtonColumn',
						'header' => $module == 'default'?\Yii::t('app', 'Actions'):false,
						'template' => '{edit} {remove}',
						'buttons' => array(
							'edit' => array(
								'icon' => 'search',
								'options' => array('class' => 'btn btn-small'),
								'url' => '\Yii::app()->controller->createUrl("index", array("controller" => "'.$module.$this->utils->delimiter.'$data->primaryKey", "page" => '.$page.'))'
							),
							'remove' => array(
								'icon' => 'remove white',
								'options' => array('class' => 'btn btn-small btn-danger'),
								'url' => '\Yii::app()->controller->createUrl("index", array("controller" => "'.$module.$this->utils->delimiter.'$data->primaryKey", "directive" => "delete"))',
								'tip' => 'Delete all items connected with this controller'
							)
						)
					)
				),
			));
		}
		?>
	</div>
	<div class="span6">
		<?php if (isset($actions, $cName)): ?>
		<p class="lead textAlignCenter">Auth items</p>
		<div class="well well-small">
			<p class="textAlignCenter"><strong><?=$cName.'Controller';?></strong></p>
			<?php
			if (!isset($directive))
				$directive = 'create';

			$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
				'action' => $this->createUrl($directive, $_GET)
			));
			?>
				<?php if (count($actions) > 0): ?>
				<div class="control-group checkbox-group">
					<label class="checkbox"><input type="checkbox" name="actionsAll" class="check-all" value="1" />
						<strong><?=\Yii::t('form-label', 'Check All');?></strong></label>
					<?php foreach ($actions as $name => $label): ?>
					<label class="checkbox"><input type="checkbox" name="actions[<?=$name;?>]" value="<?=$label?>"><?=$label?></label>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
				<hr>
				<p><?=\Yii::t('form-label', 'Pages that access is always allowed:')?></p>
				<?php if (count($alwaysAllowed) > 0): ?>
				<ul>
					<?php foreach ($alwaysAllowed as $item): ?>
					<li><?=$item;?></li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
				<hr>
				<?php if ($displayTask): ?>
				<div class="control-group">
					<label class="checkbox"><input checked="checked" type="checkbox" name="createTasks" value="1" /><?=\Yii::t('form-label' , 'Create tasks');?></label>
				</div>
				<div class="control-group">
					<label class="control-label"><?=$viewTask;?></label>
					<div class="controls">
						<input type="text" readonly="readonly" class="span3" placeholder="<?=$viewTask;?>" name="tasks[viewing]" value="<?=$viewTask;?>" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?=$cName;?><?=\Yii::t('form-label', 'Administrating');?></label>
					<div class="controls">
						<input type="text" readonly="readonly" class="span3" placeholder="<?=$adminTask;?>" name="tasks[administrating]" value="<?=$adminTask;?>" />
					</div>
				</div>
				<?php endif; ?>
				<div class="control-group">
					<div class="controls">
						<button class="btn btn-primary"><?=\Yii::t('form-label', 'Create');?></button>
					</div>
				</div>
			<?php
			$this->endWidget();
			?>
		</div>
		<?php endif; ?>
	</div>
</div>