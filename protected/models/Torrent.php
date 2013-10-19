<?php
/**
 * User: alegz
 * Date: 10/16/13
 * Time: 12:07 AM
 */

class Torrent extends EMongoDocument {
    public $info;
    public $announce;
    public $announceList;
    public $creationDate;
    public $comment;
    public $createdBy;
    public $encoding = 'UTF-8';

    public function collectionName(){
        return 'torrent';
    }

    /**
     * @return TorrentFile
     */
    public static function model(){
        return parent::model(__CLASS__);
    }


}