<?php
class CreateTorrentForm extends CFormModel
{
	public $name;
	public $email;
	public $torrent;
	public $category;
	public $infoUrl;
	public $description;
	public $acceptRules;
	
	
	public static function model($className = __CLASS__)
	{
		return new $className;
	}
	
	
}