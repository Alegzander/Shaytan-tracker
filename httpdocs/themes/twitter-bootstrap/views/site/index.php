<?php
/**
 * @var SiteController $this
 * @var TorrentMeta $model
 */

$this->widget('bootstrap.widgets.TbGridView', array(
    'ajaxUpdate' => false,
    'dataProvider' => $model->search(),
    'columns' => array(
        'name' => array(
            'class' => 'CLinkColumn',
            'labelExpression' => '$data->name',
            'header' => $model->getAttributeLabel('name'),
            'urlExpression' => '\Yii::app()->createUrl(\'/torrent/view\', array(\'id\' => $data->torrentId))'
        ),

        'download' => array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'htmlOptions' => array('class' => 'nowrap'),
            'template' => '{download}',
            'buttons' => array(
                'download' => array(
                    'icon' => 'download-alt',
                    'url' => '\Yii::app()->createUrl(\'/torrent/download\', array(\'id\' => $data->torrentId))'
                )
            )

        ),
        'size' => array('name' => 'size',
            'value' => 'OSHelper::fileSystem()->getSizeLabel($data->size)',
            'header' => '<i class="icon-inbox"></i>'),

        'seeds' => array('name' => 'numSeeds', 'value' => 'intval($data->numSeeds)',
            'header' => '<i class="icon-arrow-up"></i>'),

        'leachers' => array('name' => 'numLeachers', 'value' => 'intval($data->numLeachers)',
            'header' => '<i class="icon-arrow-down"></i>'),

        'downloaded' => array('name' => 'numDownloaded', 'value' => 'intval($data->numDownloaded)',
            'header' => '<i class="icon-ok"></i>'),
    )
));
?>