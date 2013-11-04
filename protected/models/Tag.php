<?php

class Tag extends EMongoDocument
{
    public $tag;
    public $torrents = array();

    public function collectionName(){
        return 'tag';
    }

    /**
     * @return Tag
     */
    public static function model() {
		return parent::model(__CLASS__);
	}

    public function rules(){
        return array(
            array('tag', 'required'),
            array('tag', 'match', 'pattern' => '~^[\p{Xan}_]+$~u', 'allowEmpty' => false),
            array('tag', 'EMongoUniqueValidator', 'className' => 'Tag', 'attributeName' => 'tag')
        );
    }

    public function findAllByTorrentId($torrentId, $fields = array()){
        $criteria = new EMongoCriteria($this->getDbCriteria());
        $criteria->addCondition('torrents', $torrentId, '$in');

        if (is_array($torrentId)){
            foreach ($torrentId as $key => $value)
                $torrentId[$key] = new MongoId($value);
        } else if (is_string($torrentId)) {
            $torrentId = array(new MongoId($torrentId));
        }

        return $this->findAll(array('torrents' => array('$in' => $torrentId)), $fields);
    }
}