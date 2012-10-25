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
        return array(
            array("info_hash, peer_id, port, uploaded, downloaded, left, compact, no_peer_id", "required"),

        );
    }
}
