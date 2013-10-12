<?php
/**
 * manage.php
 *
 * @author Spyros Soldatos <spyros@valor.gr>
 * @link http://code.google.com/p/srbac/
 */
/**
 * @var AuthitemController $this
 */
$this->renderPartial('/_manage-menu');
?>
<div class="row">
	<div class="span6">
		<p class="lead textAlignCenter"><?=\Yii::t('form-label', 'Auth items')?> <a hred="<?=$this->createUrl('index');?>"><button type="submit" class="btn btn-small btn-primary"><?=\Yii::t('form-label', 'Create');?></button></a></p>
		<?php
		/**
		 * TbActiveForm $form
		 */
		$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array('method' => 'GET', 'htmlOptions' => array('class' => 'form-search')));
		?>
		<div class="textAlignCenter">
			<?=$form->textField($model, 'name', array('placeholder' => \Yii::t('form-label', 'search')));?>
			<button type="submit" class="btn"><i class="icon-search"></i></button>
		</div>
		<?php
		$this->endWidget();

		$this->widget('bootstrap.widgets.TbGridView', array(
			'ajaxUpdate' => false,
			'dataProvider' => $model->search(),
			'type' => 'bordered',
			'template' => "{items}\n{pager}",
			'htmlOptions' => array('class' => 'table table-bordered table-hover'),
				'enableSorting' => false,
				'filter' => $model,
			'columns' => array(
				'name' => array(
					'class' => 'CLinkColumn',
					'header' => \Yii::t('form-label', 'Name'),
					'labelExpression' => '$data->name',
					'urlExpression' => '\Yii::app()->controller->createUrl("index", array("'.get_class($model).'[name]" => $data->name));'

				),
				'type' => array('name' => 'type', 'filter' => AuthItem::$TYPES, 'value' => 'AuthItem::$TYPES[$data->type]'),
				'actions' => array(
					'class' => 'application.components.TbGroupButtonColumn',
					'header' => 'Actions',
					'template' => '{update}{delete}',
					'buttons' => array(
						'update' => array(
							'icon' => 'icon-repeat',
							'options' => array('class' => 'btn btn-small'),
							'url' => '\Yii::app()->controller->createUrl("index", array("'.get_class($model).'[name]" => $data->name));'
						),
						'delete' => array(
							'icon' => 'icon-remove icon-white',
							'options' => array('class' => 'btn btn-small btn-danger'),
							'url' => '\Yii::app()->controller->createUrl("delete", array("name" => $data->name));'
						),
					),
				)
			)
		));
		?>
	</div>
	<div class="span6">
		<p class="lead textAlignCenter"><?=\Yii::t('form-label', 'Actions');?></p>
		<p class="textAlignCenter"><strong><?=\Yii::t('form-label', 'Create New Item');?></strong></p>
		<?php
		$form = $this->beginWidget('ext.YiiBooster.widgets.TbActiveForm',
			array(
				'method' => 'POST',
				'type' => 'horizontal',
				'action' => $this->createUrl('edit'),
				'htmlOptions' => array('class' => 'well well-small'),
			));

		echo $form->errorSummary($newItem);
		?>
			<div class="control-group">
				<?=$form->labelEx($newItem, 'name', array('class' => 'control-label', 'placeholder' => 'Name'));?>
				<div class="controls">
					<?=$form->textField($newItem, '[new]name', array('class' => 'span3'));?>
				</div>
			</div>
			<div class="control-group">
				<?=$form->labelEx($newItem, 'type', array('class' => 'control-label'));?>
				<div class="controls">
				<?=$form->dropDownList($newItem, '[new]type', AuthItem::$TYPES);?>
				</div>
			</div>
			<div class="control-group">
				<?=$form->labelEx($newItem, 'description', array('class' => 'control-label'));?>
				<div class="controls">
					<?=$form->textArea($newItem, '[new]description', array('placeholder' => 'Description'));?>
				</div>
			</div>
			<div class="control-group">
				<?=$form->labelEx($newItem, 'bizrule', array('class' => 'control-label'));?>
				<div class="controls">
					<?=$form->textArea($newItem, '[new]bizrule', array('placeholder' => 'Bizrule'));?>
				</div>
			</div>
			<div class="control-group">
				<?=$form->labelEx($newItem, 'data', array('class' => 'control-label'));?>
				<div class="controls">
					<?=$form->textField($newItem, '[new]data', array('class' => 'span3', 'placeholder' => 'Data'));?>
				</div>
			</div>
			<hr>
			<div class="control-group">
				<div class="controls">
					<button class="btn btn-primary">Save</button>
				</div>
			</div>
		<?php $this->endWidget(); ?>
	</div>
</div>