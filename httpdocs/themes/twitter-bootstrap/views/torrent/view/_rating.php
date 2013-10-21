<?php
/**
 * User: alegz
 * Date: 10/21/13
 * Time: 1:03 AM
 *
 * @var TorrentController $this
 * @var int $rating
 */
$this->widget('bootstrap.widgets.TbButtonGroup', array(
    'buttons' => array(
        array(
            'icon' => 'plus',
            'url' => '#'
        ),
        array(
            'label' => $rating,
        ),
        array(
            'icon' => 'minus',
            'url' => '#'
        )
    )
));