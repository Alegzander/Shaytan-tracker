<?php

class m131117_114742_more_torrent_meta_indexes extends CDbMigration
{
	public function safeUp()
	{
        TorrentMeta::model()->getCollection()->ensureIndex(array('hash' => 1), array('unique' => true));
	}

	public function down()
	{
		echo "m131117_114742_more_torrent_meta_indexes does not support migration down.\n";
		return false;
	}
}