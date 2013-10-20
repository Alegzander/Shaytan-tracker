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
            'label' => '+',
            'url' => '#'
        ),
        array(
            'label' => $rating,
        ),
        array(
            'label' => '-',
            'url' => '#'
        )
    )
));