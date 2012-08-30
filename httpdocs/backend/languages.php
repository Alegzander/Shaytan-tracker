<?php
//
//  TorrentTrader v2.x
//  Languages
//  Author: TorrentialStorm
//
//    $LastChangedDate: 2011-11-17 00:13:07 +0000 (Thu, 17 Nov 2011) $
//
//    http://www.torrenttrader.org
//
//


// Plural forms: http://www.gnu.org/software/hello/manual/gettext/Plural-forms.html
// $LANG["PLURAL_FORMS"] is in the plural= format

function T_ ($s) {
	GLOBAL $LANG;
    
    $s = str_replace(" ", "_", strtoupper($s));
	
	if ($ret = $LANG[$s]) {
		return $ret;
	}

	if ($ret = $LANG["{$s}[0]"]) {
		return $ret;
	}

	return $s;
}

function P_ ($s, $num) {
	GLOBAL $LANG;

	$num = (int) $num;

	$plural = str_replace("n", $num, $LANG["PLURAL_FORMS"]);
	$i = eval("return intval($plural);");

	if ($ret = $LANG["{$s}[$i]"])
		return $ret;

	return $s;
}

?>