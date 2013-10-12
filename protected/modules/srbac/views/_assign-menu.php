<?php
/**
 * User: alegz
 * Date: 4/4/13
 * Time: 4:38 PM
 */
/**
 * @var SBaseController $this
 */
?>
<div class='push-top'></div>
<div class='wraper'>
	<div class='container'>
		<?php
		$items = array(
			'users' => array('label' => \Yii::t('srbac', 'Users'), 'url' => $this->createUrl('users', array('type' => 'users'))),
			'roles' => array('label' => \Yii::t('srbac', 'Roles'), 'url' => $this->createUrl('items', array('type' => 'roles'))),
			'tasks' => array('label' => \Yii::t('srbac', 'Tasks'),  'url' => $this->createUrl('items', array('type' => 'tasks'))),
		);

		if (isset($items[$this->id.'.'.$this->action->id]))
			$items[$this->id.'.'.$this->action->id]['active'] = true;
		else if (isset($items[$this->action->id]))
			$items[$this->action->id]['active'] = true;
		else if (($type = \Yii::app()->request->getQuery('type')) !== null && isset($items[$type]))
			$items[$type]['active'] = true;

		$this->widget('zii.widgets.CMenu', array(
			'htmlOptions' => array('class' => 'nav nav-tabs'),
			'items' => $items,
		));
		?>
	</div>
</div>