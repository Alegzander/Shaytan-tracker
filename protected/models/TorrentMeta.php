<?php

class TorrentMeta extends EMongoDocument
{
    public $torrentId;

	public $name;
	public $hash;
	public $size = 0;
    public $informationUrl;
	public $description;
	public $hidden = EnabledState::DISABLED;
	public $suspend = EnabledState::DISABLED;
    public $remake = EnabledState::DISABLED;
	public $status = ETorrentStatus::_NEW_;

	public $numSeeds;
	public $numLeachers;
	public $numDownloaded;

    public $tags = array();
	public $rating = 0;

    public $numComments = 0;
	public $lastCommentDate = null;
	public $lastCommentResponder = 0;

    public $dateCreated;
    public $dateUpdated;

    public function behaviors(){
        return array(
            'EMongoTimestampBehaviour' => array(
                'class' => 'EMongoTimestampBehaviour',
                'createAttribute' => 'dateCreated',
                'updateAttribute' => 'dateUpdated',
                'notOnScenario' => array('search'),
                'setUpdateOnCreate' => true
            )
        );
    }
    
    public function getCollection(){
        return 'torrent_meta';
    }
    
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

    public function rules(){
        return array(
            array('torrentId, name, hash, size, hidden, remake, suspend, status, dateCreated, dateUpdated', 'required'),
            array('torrentId', 'EMongoIdValidator', 'allowEmpty' => false),
            array('torrentId', 'EMongoExistValidator', 'className' => 'Torrent', 'attributeName' => '_id'),
            array('name', 'filter', 'filter' => 'strip_tags'),
            array('name', 'length', 'min' => 1, 'max' => 100, 'allowEmpty' => true),

            array('hash', 'match',
                'pattern' => '/^(?:[A-Za-z0-9+/]{4})*(?:[A-Za-z0-9+/]{2}==|[A-Za-z0-9+/]{3}=)?$/',
                'allowEmpty' => false),

            array('size', 'EMongoIntegerValidator', 'type' => EMongoIntegerValidator::INT64, 'allowEmpty' => false),
            array('numSeeds, numLeachers, numDownloaded, numComments, rating', 'numerical', 'integerOnly' => true, 'min' => 0),
            array('hidden, suspend, remake', 'boolean', 'trueValue' => EnabledState::ENABLED, 'falseValue' => EnabledState::DISABLED, 'strict' => true),
            array('status', 'in', 'range' => ETorrentStatus::getEnums(), 'allowEmpty' => false),
            array('informationUrl', 'url', 'allowEmpty' => true),
            array('description', 'filter' => 'strip_tags'),
            array('tags, dateCreated, dateUpdated', 'safe')
        );
    }
}