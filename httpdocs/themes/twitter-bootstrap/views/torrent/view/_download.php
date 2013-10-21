<?php
/**
 * User: alegz
 * Date: 10/21/13
 * Time: 1:51 AM
 *
 * @var TorrentController $this
 * @var string $id
 */
\Yii::import('bootstrap.widgets.TbButton');
$this->widget('bootstrap.widgets.TbButtonGroup', array(
    'type' => TbButton::TYPE_PRIMARY,
    'buttons' => array(
        array('label' => '.txt', 'url' => $this->createUrl('download', array('id' => $id, 'type' => 'txt'))),
        array('label' => '.torrent', 'url' => $this->createUrl('download', array('id' => $id))),
    )
));