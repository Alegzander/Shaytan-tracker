<?php
/**
 * User: alegz
 * Date: 10/29/13
 * Time: 6:07 PM
 */

namespace application\helpers\OS;


class Network {
    function cidr_match($ip, $range)
    {
        list ($subnet, $bits) = explode('/', $range);
        $ip = ip2long($ip);
        $subnet = ip2long($subnet);
        $mask = -1 << (32 - $bits);
        $subnet &= $mask; # nb: in case the supplied subnet wasn't correctly aligned
        return ($ip & $mask) == $subnet;
    }
} 