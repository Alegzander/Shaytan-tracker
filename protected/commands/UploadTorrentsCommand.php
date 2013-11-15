<?php
/**
 * User: alegz
 * Date: 11/16/13
 * Time: 2:49 AM
 */

defined('NL') || define('NL', "\n");
defined('TAB') || define('TAB', "\t");

class UploadTorrentsCommand extends CConsoleCommand {
    private $connection;

    public function init(){
        $this->connection = curl_init();
        curl_setopt($this->connection, CURLOPT_POST, 1);
    }

    public function run($args){
        if (!isset($args[0]))
            throw new CException('Url is not set');

        if (!isset($args[1]))
            throw new CException('Path is not set');

        $url = $args[0];
        $path = $args[1];

        curl_setopt($this->connection, CURLOPT_URL, $url);

        if (is_dir($path)){
            $this->processDirectory($path);
        } else if (is_file($path)){
            $this->uploadTorrent($path);
        }

        curl_close($this->connection);
    }

    private function processDirectory($dir){
        $dp = opendir($dir);
        $forbidenDirs = array('.', '..');

        while (($item = readdir($dp)) !== false){
            $path = preg_replace('/[\\'.DIRECTORY_SEPARATOR.']{2,}/', DIRECTORY_SEPARATOR, $dir.DIRECTORY_SEPARATOR.$item);

            if (is_dir($path) && !in_array($item, $forbidenDirs)){
                $this->processDirectory($path);
            } else if (is_file($path) && substr($path, -7) === 'torrent'){
                $this->uploadTorrent($path);
            }
        }

        closedir($dp);
    }

    private function uploadTorrent($file){
        $fields = array(
            'CreateTorrentForm[torrent]' => '@'.$file,
            'CreateTorrentForm[descriptionFromFile]' => 1,
            'CreateTorrentForm[accept]' => 'accepted'
        );

        curl_setopt($this->connection, CURLOPT_POSTFIELDS, $fields);

        $result = curl_exec($this->connection);

        $result = CJSON::decode($result);

        echo $file.TAB.ucfirst($result['result']);

        if ($result['result'] == 'error')
            echo ': "'.$result['message'].'"';

        echo NL;
    }

    /**
     * @param $name
     * @return array
     */
    private function parseTorrentName($name){
        $matches = array();
    }
} 