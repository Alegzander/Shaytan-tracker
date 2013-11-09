<?php
/**
 * User: alegz
 * Date: 11/7/13
 * Time: 3:10 AM
 *
 * @var BaseController $this
 * @var TbActiveForm $form
 * @var TorrentSearchForm $searchForm
 */

\Yii::import('bootstrap.widgets.TbActiveForm');
\Yii::import('bootstrap.widgets.TbButton');

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'type' => TbActiveForm::INPUT_INLINE,
    'method' => 'GET',
    'action' => '/site/index',
    'htmlOptions' => array(
        'class' => 'navbar-search pull-right'
    )
));

echo $form->textField($searchForm, 'phrase',
        array('class' => 'search-query span2 input-medium', 'placeholder' => $searchForm->getAttributeLabel('phrase')));
echo $form->hiddenField($searchForm, 'byTags',
    array('class' => 'search-by-tag', 'data-label' => \Yii::t('form-label', 'Tag')));
echo $form->hiddenField($searchForm, 'byName',
    array('class' => 'search-by-name', 'data-label' => \Yii::t('form-label', 'Name')));

$this->endWidget();
