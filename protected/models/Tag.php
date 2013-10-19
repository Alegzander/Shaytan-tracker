<?php

class Tag extends EMongoDocument
{
    public $tag;

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
}