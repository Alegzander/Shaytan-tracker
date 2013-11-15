<?php
/**
 * User: alegz
 * Date: 11/16/13
 * Time: 2:49 AM
 */

defined('NL') || define('NL', "\n");
defined('TAB') || define('TAB', "\t");

class UploadTorrentsCommand extends CConsoleCommand {
    public function init(){

    }

    public function run($args){
        if (!isset($args[0]))
            throw new CException('Url is not set');

        if (!isset($args[1]))
            throw new CException('Path is not set');

        $url = $args[0];
        $path = $args[1];

        if (is_dir($path)){
            $this->processDirectory($path);
        } else if (is_file($path)){
            $this->uploadTorrent($path);
        }

    }

    private function processDirectory($dir){
        foreach (scandir($dir) as $item){
            $path = $dir.DIRECTORY_SEPARATOR.$item;
            if (is_dir($path)){
                $this->processDirectory($path);
            } else if (is_file($path) && mime_content_type($path) === 'application/x-bittorrent'){
                $this->uploadTorrent($path);
            }
        }
    }

    private function uploadTorrent($file){
        echo $file.NL;
    }

    /**
     * @param $name
     * @return array
     */
    private function parseTorrentName($name){
        $matches = array();
    }
} 