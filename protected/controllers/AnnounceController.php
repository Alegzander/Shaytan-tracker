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
        * optional: ip
        * optional: numwant
        * optional: key
        * optional: trackerid
        *
        * @todo Запилить обработку параметра compact пока игнорится
        */

        $responce = array();
        $announceForm = new AnnounceForm();

        $announceForm->attributes = $_REQUEST;

        if ($announceForm->validate() === false)
        {
            $errorString = "";

            foreach ($announceForm->getErrors() as $error)
                $errorString .= implode(" ", $error)." ";

            $errorString = trim($errorString);
            $responce = array("failure reason" => $errorString);

            echo Torrent::model()->announceResponce($responce);

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
            $responce = array("failure reason" => Yii::t("app", "Торрент-файла с указанным хэшем не существует."));

            //Возвращаем ответ
            echo Torrent::model()->announceResponce($responce);
        }

        $leachers = $torrentModel->peers["leachers"];
        $seeders = $torrentModel->peers["seeders"];

        /**
         * @desc Данные которые пишутся в пиры
         * ip
         * port
         * peer id
         * key
         * trackerid
         */
        //Если есть ещё что качать, он личер
        if ((int)$announceForm->left > 0)
        {

        }

        //Первым делом сохраним и запомним нашего пира, или проверим не был ли он у нас в записях до этого


        $responce["interval"] = Yii::app()->getParams()->interval;
        $responce["min interval"] = Yii::app()->getParams()->interval;
    }
}
