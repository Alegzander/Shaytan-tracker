<?php
/**
 * User: alegz
 * Date: 10/15/13
 * Time: 3:13 PM
 */

namespace application\helpers\OS;

class Web {
    public function getMaxUploadSize(){
        $max_upload = intval(ini_get('upload_max_filesize'));
        $max_post = intval(ini_get('post_max_size'));
        $memory_limit = intval(ini_get('memory_limit'));

        return min($max_upload, $max_post, $memory_limit);
    }
}