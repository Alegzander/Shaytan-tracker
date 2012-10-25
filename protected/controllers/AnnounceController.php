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
        * ip
        * numwant
        * key
        * optional: trackerid
        *
        * @todo Запилить обработку параметра compact пока игнорится
        */
        /**
         * @var $input CHttpRequest
         */
        $input = Yii::app()->request;

        $iInfoHash = $input->getParam("info_hash");
        $iPeerId = $input->getParam("peer_id");
        $iPort = $input->getParam("port");
        $iUploaded = $input->getParam("uploaded");
        $iDownloaded = $input->getParam("downloaded");
        $iLeft = $input->getParam("left");
        $iCompact = $input->getParam("compact");
        $iNoPeerId = $input->getParam("no_peer_id");
        $iEvent = $input->getParam("info_hash");
        $iIp = $input->getParam("ip");
        $iNumwant = $input->getParam("numwant");
        $iKey = $input->getParam("key");
        $iTrackerid = $input->getParam("trackerid");

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

    }
}
