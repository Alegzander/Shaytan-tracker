<?php
/**
 * User: alegz
 * Date: 10/29/13
 * Time: 6:07 PM
 */

namespace application\helpers\OS;


class Network {
    const INT32SIZE = 4294967296;

    public function cidr_match($ip, $range)
    {
        list ($subnet, $bits) = explode('/', $range);
        $ip = ip2long($ip);
        $subnet = ip2long($subnet);
        $mask = -1 << (32 - $bits);
        $subnet &= $mask; # nb: in case the supplied subnet wasn't correctly aligned
        return ($ip & $mask) == $subnet;
    }

    public function getNetworkFromSubnet($subnetString, $asLong = false){
        list($ip, $mask) = explode('/', $subnetString);

        $mask = $this->prepareNetMask($mask);

        if ($mask === -1)
            $network = ip2long($ip);
        else
            $network = (ip2long($ip) & $mask);

        return $asLong === true ? $network : long2ip($network);
    }

    public function prepareNetMask($mask){
        if (count(explode('.', $mask)) === 4)
            $mask = ip2long($mask) - static::INT32SIZE;
        else if (is_numeric($mask) && intval($mask) !== 0 && intval($mask) == floatval($mask))
            $mask = -1 << (32 - $mask);
        else
            throw new \CException(\Yii::t('error', 'Invalid network mask {mask}. Must be bit or netmask (127.0.0.0/8 or 127.0.0.0/255.0.0.0).', array(
                '{mask}' => $mask
            )));

        return $mask;
    }

    public function getMaskFromSubnet($subnetString){
        list($ip, $mask) = explode('/', $subnetString);
        unset($ip);

        return $this->prepareNetMask($mask);
    }

    public function getMaximumIpInSubnet($subnetString, $asLong = false){
        $mask = $this->getMaskFromSubnet($subnetString);
        $network = $this->getNetworkFromSubnet($subnetString, true);
        $maxIp = $network + ($mask * -1) - 2;

        if ($maxIp <= $network)
            $maxIp = $this->getMinimumIpInSubnet($subnetString, true);

        return $asLong === true ? $maxIp : long2ip($maxIp);
    }

    public function getMinimumIpInSubnet($subnetString, $asLong = false){
        $mask = $this->getMaskFromSubnet($subnetString);

        if ($mask < -1)
            $network = $this->getNetworkFromSubnet($subnetString, true) + 1;
        else
            $network = $this->getNetworkFromSubnet($subnetString, true);

        return $asLong === true ? $network : long2ip($network);
    }
}