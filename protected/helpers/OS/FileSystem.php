<?php
/**
 * User: alegz
 * Date: 10/21/13
 * Time: 1:30 AM
 */

namespace application\helpers\OS;


class FileSystem {
    private $byteSizeLabel = array(
        'B',
        'KB',
        'MB',
        'GB',
        'TB',
        'PB',
        'EB',
        'ZB',
        'YB'
    );

    public function getSizeLabel($rawInt, $accuracy = 2, $moreThanOne = true){
        $result = floatval($rawInt);
        $index = 0;
        for(
            $i = 1;
            $moreThanOne === true && ($result/1024) > 1 || $moreThanOne !== true && $result > 1;
            $i++){
            $result /= 1024;
            $index = $i;
        }

        return round($result, $accuracy).' '.$this->byteSizeLabel[$index];
    }
}