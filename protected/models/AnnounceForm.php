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
        $zeroto254 = "([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-4])";

        return array(
            array("info_hash, peer_id, port, uploaded, downloaded, left, compact, no_peer_id", "required"),
            array("port", "numerical", "allowEmpty" => false, "min" => 1, "max" => 65535),
            array("uploaded", "numerical", "min" => 0, "allowEmpty" => false),
            array("downloaded", "numerical", "min" => 0, "allowEmpty" => false),
            array("left", "numerical", "min" => 0, "allowEmpty" => false),
            array("compact", "numerical", "min" => 0, "max" => 1, "allowEmpty" => false),
            array("no_peer_id", "numerical", "min" => 0, "max" => 1, "allowEmpty" => false),
            array("event", "in", "range" => $eventsList),
            array("numwant", "numerical", "min" => 0),
            array("ip", "match", "pattern" => "/^".$oneto255.".".$zeroto255.".".$zeroto255.".".$zeroto254."$/")
        );
    }
}
