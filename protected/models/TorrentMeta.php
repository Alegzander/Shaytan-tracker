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
                'timestampExpression' => 'time()',
                'setUpdateOnCreate' => true
            )
        );
    }
    
    public function collectionName(){
        return 'torrent_meta';
    }
    
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

    public function rules(){
        return array(
            array('torrentId, name, hash, size, hidden, remake, suspend, status, dateCreated, dateUpdated', 'required'),
            array('torrentId', 'EMongoExistValidator', 'className' => 'Torrent', 'attributeName' => '_id', 'allowEmpty' => false),
            array('torrentId', 'EMongoIdValidator', 'allowEmpty' => false),
            array('name', 'filter', 'filter' => 'strip_tags'),
            array('name', 'filter', 'filter' => 'trim'),

            array('size', 'EMongoIntegerValidator', 'type' => EMongoIntegerValidator::INT64, 'allowEmpty' => false),
            array('numSeeds, numLeachers, numDownloaded, numComments, rating', 'numerical', 'integerOnly' => true, 'min' => 0),
            array('hidden, suspend, remake', 'boolean', 'trueValue' => EnabledState::ENABLED, 'falseValue' => EnabledState::DISABLED, 'strict' => true),
            array('status', 'in', 'range' => ETorrentStatus::getEnums(), 'allowEmpty' => false),
            array('informationUrl', 'url', 'allowEmpty' => true),
            array('description', 'filter', 'filter' => 'strip_tags'),
            array('hash, tags, dateCreated, dateUpdated', 'safe')
        );
    }
    
    public function attributeLabels(){
        return array(
            'name' => \Yii::t('form-label', 'Name'),
            'hash' => \Yii::t('form-label', 'Hash'),
            'size' => \Yii::t('form-label', 'Size'),
            'informationUrl' => \Yii::t('form-label', 'Information URL'),
            'description' => \Yii::t('form-label', 'Description'),
            'hidden' => \Yii::t('form-label', 'Hidden'),
            'suspend' => \Yii::t('form-label', 'Suspend'),
            'remake' => \Yii::t('form-label', 'Remake'),
            'status' => \Yii::t('form-label', 'Status'),
            'numSeeds' => \Yii::t('form-label', 'Seeds'),
            'numLeachers' => \Yii::t('form-label', 'Leachers'),
            'numDownloaded' => \Yii::t('form-label', 'Downloaded'),
            'tags' => \Yii::t('form-label', 'Tags'),
            'rating' => \Yii::t('form-label', 'Rating'),
            'numComments' => \Yii::t('form-label', 'Number of comments'),
            'lastCommentDate' => \Yii::t('form-label', 'Last comment date'),
            'lastCommentResponder' => \Yii::t('form-label', 'Last comment responder'),
            'dateCreated' => \Yii::t('form-label', 'Date created'),
            'dateUpdated' => \Yii::t('form-label', 'Date updated'),
        );
    }

    public function search(){
        return new EMongoDataProvider($this, array(
            'criteria' => $this->getDbCriteria()
        ));
    }
}