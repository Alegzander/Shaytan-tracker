<?php
/**
 * User: alegz
 * Date: 4/30/13
 * Time: 9:40 PM
 */
use application\helpers\OS\Path;
use application\helpers\OS\Web;

class OSHelper {
	public static function path(){
		return new Path();
	}

    public static function web(){
        return new Web();
    }
}