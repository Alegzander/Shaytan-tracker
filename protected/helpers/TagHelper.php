<?php
/**
 * User: alegz
 * Date: 11/5/13
 * Time: 12:00 PM
 */

class TagHelper {
    public static function removeTorrent($torrentId){
        $tags = Tag::model()->findAllByTorrentId($torrentId);

        static::cleanTorrentsList($tags, $torrentId);
    }

    public static function removeTorrentFromTag($tag, $torrentId){
        $tag = Tag::model()->findByTag($tag);

        if ($torrentId instanceof MongoId)
            $torrentId = strval($torrentId);

        $key = array_keys($tag->torrents, $torrentId);

        if (isset($key)) {
            foreach ($key as $index)
                unset($tag->torrents[$index]);

            if (count($tag->torrents) === 0)
                $tag->delete();
            else
                $tag->save();
        }
    }

    private static function cleanTorrentsList(EMongoCursor $tags, $torrentId)
    {
        if (!$tags->valid())
            return;

        while (($tag = $tags->getNext())) {
            $matchedKeys = array_keys($tag->torrents, $torrentId);

            foreach ($matchedKeys as $key)
                unset($tag->torrents[$key]);

            if (count($tag->torrents) === 0)
                $tag->delete();
            else
                $tag->save();
        }
    }
} 