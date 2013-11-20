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
	public $status = ETorrentStatus::_NEW_;

    public $zoneId;
    public $limitToZone = EnabledState::ENABLED;

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

    public $indexed = EnabledState::DISABLED;

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

    /**
     * @return TorrentMeta
     */
    public static function model() {
		return parent::model(__CLASS__);
	}

    public function rules(){
        return array(
            array('torrentId, name, hash, size, hidden, suspend, status, limitToZone, dateCreated, dateUpdated', 'required'),
            array('torrentId', 'EMongoExistValidator', 'className' => 'Torrent', 'attributeName' => '_id', 'allowEmpty' => false),
            array('torrentId', 'EMongoIdValidator', 'allowEmpty' => false),
            array('name', 'filter', 'filter' => 'strip_tags'),
            array('name', 'filter', 'filter' => 'trim'),

            array('size', 'EMongoIntegerValidator', 'type' => EMongoIntegerValidator::INT64, 'allowEmpty' => false),
            array('numSeeds, numLeachers, numDownloaded, numComments, rating', 'numerical', 'integerOnly' => true, 'min' => 0),
            array('hidden, suspend, limitToZone indexed', 'boolean', 'trueValue' => EnabledState::ENABLED, 'falseValue' => EnabledState::DISABLED, 'strict' => true),
            array('status', 'in', 'range' => ETorrentStatus::getEnums(), 'allowEmpty' => false),
            array('zoneId', 'EMongoExistValidator', 'className' => 'Zone', 'attributeName' => '_id', 'allowEmpty' => true),
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
        $searchForm = new TorrentSearchForm();
        $searchPost = \Yii::app()->request->getQuery(get_class($searchForm));

        $pagination = new CPagination();
        $pagination->setPageSize(100);

        $dataProvider = new EMongoBigDataProvider($this);
        $pagination->pageVar = $dataProvider->getId().'_page';

        $search = \Yii::app()->sphinx;

        $searchForm->setAttributes($searchPost);
        $foundTorrents = array();

        //TODO Check how it will work for multi page results and for bit data

        if ($searchForm->validate()){
            $search->getDbConnection()
            ->createCommand()
            ->select('torrent_id')
            ->from('torrent')
            ->where('MATCH(\'@(name,description,tags) "'.$searchForm->phrase.'"~4\')')
            ->execute();

            $metaData = $search->getDbConnection()->createCommand('SHOW META')->queryAll();

            foreach ($metaData as $data){
                if ($data['Variable_name'] === 'total_found'){
                    $pagination->setItemCount(intval($data['Value']));
                    break;
                }
            }

            $result = $search->getDbConnection()
                ->createCommand('   ')
                ->select('torrent_id')
                ->from('torrent')
                ->where('MATCH(\'@(name,description,tags) "'.$searchForm->phrase.'"~4\')')
                ->limit($pagination->getLimit())
                ->offset($pagination->getOffset())
                ->query();
        } else {
            $search->getDbConnection()
                ->createCommand()
                ->select('torrent_id')
                ->from('torrent')
                ->execute();

            $metaData = $search->getDbConnection()->createCommand('SHOW META')->queryAll();

            foreach ($metaData as $data){
                if ($data['Variable_name'] === 'total_found'){
                    $pagination->setItemCount(intval($data['Value']));
                    break;
                }
            }

            if ($pagination->getOffset() === 0)
                $limit = $pagination->getLimit();
            else
                $limit = $pagination->getOffset().', '.$pagination->getLimit();

            $result = $search->getDbConnection()
                ->createCommand('SELECT torrent_id FROM torrent LIMIT '.$limit)
                ->query();
        }

        foreach ($result as $row){
            array_push($foundTorrents, new MongoId($row['torrent_id']));
        }

        if (count($foundTorrents) > 0) {
            $tmpCriteria = new EMongoCriteria();
            $tmpCriteria->addCondition('torrentId', $foundTorrents, '$in');
            $this->mergeDbCriteria($tmpCriteria);

            unset($tmpCriteria);
        } else {
            return new CArrayDataProvider(array());
        }

        $dataProvider->setPagination($pagination);
        $dataProvider->setCriteria($this->getDbCriteria());

        return $dataProvider;
    }

    public function active(){
        $criteria = new EMongoCriteria();
        $criteria->addCondition('suspend', EnabledState::DISABLED);

        $this->mergeDbCriteria($criteria);

        return $this;
    }

    public function notHidden(){
        $criteria = new EMongoCriteria();
        $criteria->addCondition('hidden', EnabledState::DISABLED);

        $this->mergeDbCriteria($criteria);

        return $this;
    }

    public function revert(){
        $criteria = new EMongoCriteria();
        $criteria->setSort(array('dateUpdated' => -1));
        $this->mergeDbCriteria($criteria);

        return $this;
    }

    public function editExpired(){
        $expiryTime = \Yii::app()->getParams()->allowEditExpire;
        $criteria = new EMongoCriteria();

        $criteria->addCondition('dateUpdated', (time() - $expiryTime), '$lt');

        $this->mergeDbCriteria($criteria);

        return $this;
    }
}