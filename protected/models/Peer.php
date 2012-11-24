<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alegz
 * Date: 10/26/12
 * Time: 10:34 PM
 * To change this template use File | Settings | File Templates.
 */
class Peer extends CModel
{
    const STATE_STARTED = "started";
    const STATE_COMPLETED = "completed";
    const STATE_STOPPED = "stopped";

    const STATUS_LEACHER = "leachers";
    const STATUS_SEEDER = "seeders";

    public $downloaded;
    public $uploaded;
    public $left;
    public $ip;
    public $port;
    public $key;
    public $trackerid;
    public $state;
    public $status;
    public $id;
    public $updated;

    /**
     * @return array
     */
    public function attributeNames()
    {
        return array(
            "downloaded",
            "uploaded",
            "left",
            "ip",
            "port",
            "key",
            "trackerid",
            "state",
            "status",
            "id",
            "updated"
        );
    }

    /**
     * @param string $class
     * @return mixed
     */
    public static function model($class = __CLASS__)
    {
        return new $class;
    }

    /**
     * @param Torrent $torrent
     * @param $peerId
     * @return Peer
     * @throws CException
     */
    public function findById(Torrent $torrent, $peerId, $key = null)
    {
    	$id = $peerId;
    	
    	if (isset($key))
    		$id = sha1($peerId.$key);
    	
        if (array_key_exists($id, $torrent->peers[self::STATUS_LEACHER]) === true)
            $this->changeStatus(self::STATUS_LEACHER);
        else if (array_key_exists($id, $torrent->peers[self::STATUS_SEEDER]))
            $this->changeStatus(self::STATUS_SEEDER);
        else
            return null;
        
        if (isset($key))
        {
            if (
                array_key_exists("key", $torrent->peers[$this->status][$id]) !== true ||
                $torrent->peers[$this->status][$id]["key"] != $key
            )
            {
                $torrent->peers[$this->status][$id] = null;
                $torrent->save();

                return null;
            }
        }

        $peer = $torrent->peers[$this->status][$id];
        $this->attributes = $peer;

        return $this;
    }

    /**
     * @method create
     * @param AnnounceForm $announceData
     * @desc Метод для создания сущности класса Peer
     */
    public function create(AnnounceForm $announceData)
    {
        $peerParams = array();
        
        if($announceData->validate() !== true)
        {
        	$errorString = "";
        	
        	foreach ($announceData->getErrors() as $error)
        		$errorString .= implode(" ", $error)." ";
        		
        	$errorString = trim($errorString);
        	
        	throw new CException(Yii::t("app", $errorString));
        }

        foreach ($this->attributeNames() as $attribute)
        {
        	if($attribute != "peer_id" && isset($announceData->{$attribute}))
        	{
            	$this->{$attribute} = $announceData->{$attribute};
        	}
        	else 
        	{
        		if (isset($announceData->key))
        			$this->id = sha1($announceData->peer_id.$announceData->key);
        		else
        			$this->id = $announceData->peer_id;
        	}
        }
        
        $this->changeState($announceData->event);

        return $this;
    }

    /**
     * @param $state
     * @throws CException
     */
    public function changeState($state)
    {
        if (
            $state != self::STATE_STARTED &&
            $state != self::STATE_STOPPED &&
            $state != self::STATE_COMPLETED
        )
            throw new CException(Yii::t("app", "Попытка задать не известное состояние."));

        //Если человек завершил раздачу нужно это отметить
        if ($state === self::STATE_COMPLETED)
            $this->changeStatus(self::STATUS_SEEDER);

        if ($state === self::STATE_STARTED)
        {
            if ((int)$this->left > 0)
                $this->changeStatus(self::STATUS_LEACHER);
            else
                $this->changeStatus(self::STATUS_SEEDER);
        }

        $this->updated = time();
        $this->state = $state;
    }

    /**
     * @param $status
     * @throws CException
     */
    public function changeStatus($status)
    {
        if (
            $status != self::STATUS_LEACHER &&
            $status != self::STATUS_SEEDER
        )
            throw new CException(Yii::t("app", "Не верно задан статус."));

        if (
            $this->state === self::STATUS_SEEDER &&
            $status === self::STATUS_LEACHER
        )
            throw new CException(Yii::t("app", "Ошибка смены статуса."));

        $this->updated = time();
        $this->status = $status;
    }
}
