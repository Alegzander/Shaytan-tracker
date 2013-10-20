<?php
/**
 * User: alegz
 * Date: 10/16/13
 * Time: 12:07 AM
 */

/**
 * Class Torrent
 *
 * @property TorrentMeta $meta
 */
class Torrent extends EMongoDocument {
    /**
     * In property names camel case is used for fields with space
     * like "creation date" -> creationDate
     * and for "announce-list" -> announce_list
     *
     * This is used for normalizing name from raw array.
     * Information about fields you can find in link below
     * @link https://wiki.theory.org/BitTorrentSpecification
     */

    public $info;
    public $announce;
    public $announce_list;
    public $creationDate;
    public $comment;
    public $createdBy;
    public $publisher;
    public $publisher_url;
    public $encoding = 'UTF-8';

    public function collectionName(){
        return 'torrent';
    }

    public function rules(){
        return array(
            array('info, announce', 'required'),
            array('announce', 'match', 'pattern' => '%^(((https|http|udp|tcp)?://)|(www\.))([a-z0-9-].?)+(:[0-9]+)?(/.*)?$%i', 'allowEmpty' => false),
            array('publisher_url', 'url', 'allowEmpty' => true),
            array('comment, publisher, createdBy', 'filter', 'filter' => 'strip_tags'),
            array('creationDate', 'numerical', 'integerOnly' => true, 'allowEmpty' => true),
            array('info, announce, announceList, creationDate, comment, createdBy, publisher, publisher_url, encoding', 'safe')
        );
    }

    /**
     * @return Torrent
     */
    public static function model(){
        return parent::model(__CLASS__);
    }

    public function relations(){
        return array(
            'meta' => array(EMongoRelationType::ONE, 'TorrentMeta', 'torrentId')
        );
    }

    public static function normalizeKeyName($key){
        $findArray = array('/[\s]([a-z])/e', '/[-]([a-z])/');
        $replaceArray = array('strtoupper("\1")', '_\1');

        return preg_replace($findArray, $replaceArray, $key);
    }
}