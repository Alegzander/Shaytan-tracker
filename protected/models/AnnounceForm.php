<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alegz
 * Date: 10/20/12
 * Time: 9:46 AM
 * To change this template use File | Settings | File Templates.
 */
class AnnounceForm extends CFormModel
{
    public $info_hash;
    public $peer_id;
    public $port;
    public $uploaded;
    public $downloaded;
    public $left;
    public $compact;
    public $no_peer_id;
    public $event;
    public $ip;
    public $numwant;
    public $key;
    public $trackerid;

    public function rules()
    {
        $eventsList = array("started", "stopped", "completed");
        $zeroto255 = "([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])";
        $oneto255 = "([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])";
        $ipv6block = "[a-fA-F0-9]{0,4}";
        
        /**
		 * При локальном тестировании трекера неожиданно было получено такое значение
		 * от торрент клиента "FE80::72F3:95FF:FEE7:AE40%wlan0", чтоб обработать такую 
		 * концовку этот кусок и сделан.
         */
        $ipv6end = "(|%[a-zA-Z0-9:]{2,10})";
        $ipv4pattern = $oneto255.".".$zeroto255.".".$zeroto255.".".$zeroto255;
        $ipv6pattern = $ipv6block.":".$ipv6block.":".$ipv6block.":".$ipv6block.":".$ipv6block.":".$ipv6block.$ipv6end; 

        return array(
            array("info_hash, peer_id, port, uploaded, downloaded, left", "required"),
            array("port", "numerical", "allowEmpty" => false, "min" => 1, "max" => 65535),
            array("uploaded", "numerical", "min" => 0, "allowEmpty" => false),
            array("downloaded", "numerical", "min" => 0, "allowEmpty" => false),
            array("left", "numerical", "min" => 0, "allowEmpty" => false),
            array("compact", "numerical", "min" => 0, "max" => 1),
            array("no_peer_id", "numerical", "min" => 0, "max" => 1),
            array("event", "in", "range" => $eventsList),
            array("numwant", "numerical", "min" => 0),
            array("ip", "match", "pattern" => "/^(".$ipv4pattern."|".$ipv6pattern.")$/"),
            array("key", "safe"),
        );
    }
}
