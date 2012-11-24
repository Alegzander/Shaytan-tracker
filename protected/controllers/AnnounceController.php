<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alegz
 * Date: 10/5/12
 * Time: 11:10 PM
 * To change this template use File | Settings | File Templates.
 */
class AnnounceController extends Controller
{
    /**
     * @desc данны метод производит публикацию файла для торрент клиентов
     *
     */
    public function actionIndex()
    {
        /**
         * Для отладки
         * @todo не забыть убрать эту хрень для отладки
         */
        if (isset($_REQUEST["info_hash"]))
            $_REQUEST["info_hash"] = base64_decode($_REQUEST["info_hash"]);

       /**
        * @desc входящие параметры
        * info_hash
        * peer_id
        * port
        * uploaded
        * downloaded
        * left
        * compact
        * no_peer_id
        * event
        *   started
        *   stopped
        *   completed
        * optional: ip
        * optional: numwant
        * optional: key
        * optional: trackerid
        *
        * @todo Запилить обработку параметра compact пока игнорится
        */

        $response = array();
        $announceForm = new AnnounceForm();

        $announceForm->attributes = $_REQUEST;

        /**
         * Если ип не здавался задаём его по remote addr
         */
        if (!isset($announceForm->ip))
        	$announceForm->ip = $_SERVER["REMOTE_ADDR"];

        /**
         * Валидация пришедших данных
         */
        if ($announceForm->validate() === false)
        {
            /**
             * Чёт херня какая-то выводим ошибку.
             */
            $errorString = "";

            foreach ($announceForm->getErrors() as $error)
                $errorString .= implode(" ", $error)." ";

            $errorString = trim($errorString);
            $response = array("failure reason" => $errorString);

            echo Torrent::model()->announceResponce($response);

            Yii::app()->end();
        }

        /**
         * @desc исходящие параметры
         * failure reason
         * warning message
         * interval
         * min interval
         * tracker id
         * complete
         * incomplete
         * peers
         *  peer id
         *  ip
         *  port
         *
         */

        /**
         * @var Torrent $torrentModel
         * @desc Ищем нужный нас торрент файл
         */
        $torrentModel = Torrent::model()->findByInfoHash($announceForm->info_hash);

        //Если ничего нет
        if ($torrentModel === null)
        {
            //Возвращаем ошибку.

            //Формируем ответ
            $response = array("failure reason" => Yii::t("app", "Торрент-файла с указанным хэшем не существует."));

            //Возвращаем ответ
            echo Torrent::model()->announceResponce($response);
            Yii::app()->end();
        }

        /**
         * Берём интерфайлы из конфига
         */
        $response["interval"] = Yii::app()->getParams()->interval;
        $response["min interval"] = Yii::app()->getParams()->min_interval;
        
        /**
         * @todo Запилить поддержку trackerid
         * @desc Данные которые пишутся в пиры
         * ip
         * port
         * peer id
         * key
         * trackerid
         */

        try
        {
        	/**
             * @var Peer $peer
             */
            $peer = Peer::model()->findById($torrentModel, $announceForm->peer_id, $announceForm->key);
            
            if (!isset($peer))
            	$peer = Peer::model()->create($announceForm);
            
            if (!isset($announceForm->event))
            	$announceForm->event = Peer::STATE_STARTED;
            
            $peer->changeState($announceForm->event);

            if ($announceForm->event == Peer::STATE_COMPLETED)
            {
            	$torrentModel->downloaded++;
            		
				if (isset($torrentModel->peers[Peer::STATUS_LEACHER][$peer->id]))
            		unset($torrentModel->peers[Peer::STATUS_LEACHER][$peer->id]);
            }
            
            foreach ($peer->attributeNames() as $attribute)
            {
            	if ($attribute != "id")
            		$torrentModel->peers[$peer->status][$peer->id][$attribute] = $peer->{$attribute};
            }
            
            if ($torrentModel->save(false))
            {
            	if (!isset($announceForm->numwant))
            		$numwant = Yii::app()->getParams()->interval;
            	else
            		$numwant = $announceForm->numwant;
            	
            	$response["complete"] = count($torrentModel->peers[Peer::STATUS_SEEDER]);
            	$response["incomplete"] = count($torrentModel->peers[Peer::STATUS_LEACHER]);
            	
            	$rawPeersList = array();
            	$peersList = array();
            	
            	if ($peer->status == Peer::STATUS_SEEDER)
            	{
            		 $rawPeersList = array_slice($torrentModel->peers[Peer::STATUS_LEACHER], 0, $numwant);
            	}
            	else if ($peer->status == Peer::STATUS_LEACHER)
            	{
            		$numSeeders = ceil($numwant/2);
            		$numLeachers = floor($numwant/2);
            		
            		$seeders = array_slice($torrentModel->peers[Peer::STATUS_SEEDER], 0, $numSeeders);
            		$leachers = array_slice($torrentModel->peers[Peer::STATUS_LEACHER], 0, $numLeachers);
            		
            		$rawPeersList = array_merge($seeders, $leachers);
            	}
            	
            	foreach ($rawPeersList as $peerId => $peerData)
            	{
            		$packet = array(
            				"ip" => $peerData["ip"],
            				"port" => $peerData["port"]
            		);
            		 
            		if (
            			(int)$announceForm->no_peer_id !== 1 &&
            			(int)$announceForm->compact !== 1
            		)
            			$packet["peer id"] = $peerId;
            	
            		array_push($peersList, $packet);
            		 
            		unset($packet);
            	}
            	
            	if ((int)$announceForm->compact === 1)
            	{
            		foreach ($peersList as $peerParams)
            			$packet = pack("Nn", ip2long($peerParams["ip"]), $peerParams["port"]);
            		
            		$response["peers"] = $packet;
            	}
            	else
            	{
            		$response["peers"] = $peersList;
            	}
            	
            	echo $torrentModel->announceResponce($response);
            	Yii::app()->end();            		
            }
            else
            {
                throw new CException(Yii::t("app", "Не удалось сохранить данные о пире."));
            }
            
        }
        catch (CException $error)
        {
        	$response = array("failure reason" => $error->getMessage());

            echo $torrentModel->announceResponce($response);
            Yii::app()->end();
        }
    }
}
