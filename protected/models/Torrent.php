<?php

Yii::import('application.models._base.BaseTorrent');

class Torrent extends BaseTorrent
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}