<?php
/**
 * User: alegz
 * Date: 4/30/13
 * Time: 9:44 PM
 */

namespace application\helpers\OS;


class Path {
	public function join(){
		$params = func_get_args();
		return preg_replace('/[\\'.DIRECTORY_SEPARATOR.']{2,}/', DIRECTORY_SEPARATOR, implode($params, DIRECTORY_SEPARATOR));
	}
}