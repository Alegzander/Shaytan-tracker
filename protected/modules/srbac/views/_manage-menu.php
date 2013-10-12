<?php
/**
 * User: alegz
 * Date: 3/22/13
 * Time: 5:36 PM
 */
/**
 * @var $this SBaseController
 */
?>
<div class='push-top'></div>
<div class='wraper'>
	<div class='container'>
		<?php
		$items = array(
			'manage.index' => array('label' => \Yii::t('srbac', 'Manage Auth Item'), 'url' => $this->createUrl('manage/index')),
			'autocreate.index' => array('label' => \Yii::t('srbac', 'Autocreate Auth Items'), 'url' => $this->createUrl('autocreate/index')),
			'alwaysAllowedList.index' => array('label' => \Yii::t('srbac', 'Edit always allowed list'), 'url' => $this->createUrl('alwaysAllowedList/index')),
			'clearAlwaysAllowedList.index' => array('label' => \Yii::t('srbac', 'Clear obsolete authItems'), 'url' => $this->createUrl('clearAlwaysAllowedList/index')),
		);

		if (isset($items[$this->id.'.'.$this->action->id]))
			$items[$this->id.'.'.$this->action->id]['active'] = true;
		else if (isset($items[$this->action->id]))
			$items[$this->action->id]['active'] = true;

		$this->widget('zii.widgets.CMenu', array(
			'htmlOptions' => array('class' => 'nav nav-tabs'),
			'items' => $items,
		));
		?>
	</div>
</div>