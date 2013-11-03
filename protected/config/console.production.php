<?php
/**
 * User: alegz
 * Date: 10/5/13
 * Time: 11:55 AM
 */
defined('DS') || define('DS', DIRECTORY_SEPARATOR); //Alias for simplicity

return CMap::mergeArray(
    require('console.application.php'),
    array(
        'components' => array(
            'mongodb' => array(
                'class' => 'EMongoClient',
                'server' => 'mongodb://localhost:27017',
                'db' => 'shaytan'
            ),
        )
    )
);