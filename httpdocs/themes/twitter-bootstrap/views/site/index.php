<?php
/**
 * @var SiteController $this
 * @var TorrentMeta $model
 * @var Network $network
 */
\Yii::import('bootstrap.widgets.TbPager');
$this->widget('bootstrap.widgets.TbGridView', array(
    'ajaxUpdate' => false,
    'dataProvider' => $model->search(),
    'template' => "{pager}\n{summary}\n{items}\n{summary}\n{pager}",
    'pager' => array(
        'class' => 'bootstrap.widgets.TbPager',
        'alignment' => TbPager::ALIGNMENT_CENTER,
        'displayFirstAndLast' => true
    ),
    'columns' => array(
        'name' => array(
            'name' => 'name',
//            'header' => $model->getAttributeLabel('name'),
            'type' => 'html',
            'value' => '"<a href=\\"".\Yii::app()->createUrl(\'/torrent/view\', array(\'id\' => $data->torrentId))."\\">".$data->name."</a>"'
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
            'header' => '<i class="icon-inbox"></i>',
            'htmlOptions' => array('class' => 'span2')),

        'seeds' => array('name' => 'numSeeds', 'value' => 'intval($data->numSeeds)',
            'header' => '<i class="icon-arrow-up"></i>'),

        'leachers' => array('name' => 'numLeachers', 'value' => 'intval($data->numLeachers)',
            'header' => '<i class="icon-arrow-down"></i>'),

        'downloaded' => array('name' => 'numDownloaded', 'value' => 'intval($data->numDownloaded)',
            'header' => '<i class="icon-ok"></i>'),
    )
));
?>