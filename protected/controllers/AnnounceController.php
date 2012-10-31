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
    	$get = $_GET;
    	
    	$get["info_hash"] = base64_encode($get["info_hash"]);
    	
    	Yii::log("GET parameters: ".serialize($get), "info");
    	
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

        if ($announceForm->validate() === false)
        {
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

        //Есть ли он
        if ($torrentModel === null)
        {
            //Формируем ответ
            $response = array("failure reason" => Yii::t("app", "Торрент-файла с указанным хэшем не существует."));

            //Возвращаем ответ
            echo Torrent::model()->announceResponce($response);
            Yii::app()->end();
        }

        $response["interval"] = Yii::app()->getParams()->interval;
        $response["min interval"] = Yii::app()->getParams()->min_interval;
        
        /**
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
             * @var $peer Peer
             */
            $peer = Peer::model()->findById($torrentModel, $announceForm->peer_id);

            if (isset($announceForm->event))
                $peer->changeState($announceForm->event);
        }
        catch (CException $error)
        {
        	if (!isset($announceForm->event))
            {
                $response = array("failure reason" => Yii::t("app", "Свойство event не задано."));

                echo $torrentModel->announceResponce($response);
                Yii::app()->end();
            }

            /**
             * @var $peer Peer
             */
            $peer = Peer::model()->create($announceForm);
            $peer->changeState($announceForm->event);
        }
    }
}
