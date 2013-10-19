<?php

class TorrentMeta extends EMongoDocument
{
    public $torrent_id;

	public $name;
	public $hash;
	public $size = 0;
	public $description;
	public $hidden;
	public $suspend = EnabledState::DISABLED;
    public $remake = EnabledState::DISABLED;
	public $status =ETorrentStatus::_NEW_;

	public $numSeeds;
	public $numLeachers;
	public $numDownloaded;

    public $tags = array();
	public $raiting = 0;

    public $num_comments = 0;
	public $last_comment_date = null;
	public $last_comment_responder = 0;

    public $date_created;
    public $date_updated;

    public function behaviors(){
        return array(
            'EMongoTimestampBehaviour' => array(
                'class' => 'EMongoTimestampBehaviour',
                'createAttribute' => 'date_created',
                'updateAttribute' => 'date_updated',
                'notOnScenario' => array('search'),
                'setUpdateOnCreate'
            )
        );
    }
    
    public function getCollection(){
        return 'torrent_meta';
    }
    
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}