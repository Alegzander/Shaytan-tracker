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
        $criteria = new EMongoCriteria();

        if (is_array($torrentId)){
            foreach ($torrentId as $key => $value)
                $torrentId[$key] = new MongoId($value);
        } else if (is_string($torrentId)) {
            $torrentId = array(new MongoId($torrentId));
        }

        $criteria->addCondition('torrents', $torrentId, '$in');
        $this->mergeDbCriteria($criteria);

        return $this->findAll($this->getDbCriteria(), $fields);
    }

    /**
     * @param array|string $tag
     * @param array $fields
     * @return Tag|null
     */
    public function findByTag($tag, $fields = array()){
        $criteria = new EMongoCriteria();
        if (is_string($tag)){
            $criteria->addCondition('tag', $tag);
        } else if (is_array($tag)){
            $criteria->addCondition('tag', $tag, '$in');
        }

        $this->mergeDbCriteria($criteria);

        return $this->findOne(array(), $fields);
    }
}