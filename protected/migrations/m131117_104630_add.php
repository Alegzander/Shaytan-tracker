<?php

class m131117_104630_add extends CDbMigration
{
	public function safeUp()
	{
        TorrentMeta::model()->getCollection()->ensureIndex(array('torrentId' => 1), array('unique' => true));
        TorrentMeta::model()->getCollection()->ensureIndex('suspend');
        TorrentMeta::model()->getCollection()->ensureIndex('hidden');
        TorrentMeta::model()->getCollection()->ensureIndex('size');
        TorrentMeta::model()->getCollection()->ensureIndex('numSeeds');
        TorrentMeta::model()->getCollection()->ensureIndex('numLeachers');
        TorrentMeta::model()->getCollection()->ensureIndex('numDownloaded');
        TorrentMeta::model()->getCollection()->ensureIndex(array('dateUpdated' => -1));
	}

	public function down()
	{
		echo "m131117_104630_add does not support migration down.\n";
		return false;
	}
}