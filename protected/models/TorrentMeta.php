<?php

Yii::import('application.models._base.BaseTorrentMeta');

class TorrentMeta extends BaseTorrentMeta
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}