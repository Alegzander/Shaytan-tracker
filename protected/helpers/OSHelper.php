<?php
/**
 * User: alegz
 * Date: 4/30/13
 * Time: 9:40 PM
 */
use application\helpers\OS\Path;
use application\helpers\OS\Web;
use \application\helpers\OS\FileSystem;

class OSHelper {
	public static function path(){
		return new Path();
	}

    public static function web(){
        return new Web();
    }

    public static function fileSystem(){
        return new FileSystem();
    }

    public static function network(){
        return new \application\helpers\OS\Network();
    }
}