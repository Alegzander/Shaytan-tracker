<?php
/**
 * User: alegz
 * Date: 10/21/13
 * Time: 1:03 AM
 *
 * @var TorrentController $this
 * @var array $tags
 */
\Yii::import('bootstrap.widgets.TbBadge');

foreach ($tags as $tag)
    $this->widget('bootstrap.widgets.TbBadge', array('label' => $tag, 'type' => TbBadge::TYPE_INFO));