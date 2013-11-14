<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alegz
 * Date: 10/2/12
 * Time: 11:14 PM
 * To change this template use File | Settings | File Templates.
 */
class sphinxDataCommand extends CConsoleCommand
{
    public function run($args)
    {
        $document = new DOMDocument("1.0", "UTF-8");
        $rootElement = $document->createElement("sphinx:docset");

        $schemaElement = $document->createElement("sphinx:schema");

        /**
         * @var DOMElement $metaElement
         */
        $metaElement = $document->createElement("sphinx:field");
        $metaElement->setAttribute("name", "name");
        $schemaElement->appendChild($metaElement);

        $metaElement = $document->createElement("sphinx:field");
        $metaElement->setAttribute("name", "description");
        $schemaElement->appendChild($metaElement);

        $metaElement = $document->createElement("sphinx:attr");
        $metaElement->setAttribute("name", "torrent_id");
        $metaElement->setAttribute("type", "string");
        $schemaElement->appendChild($metaElement);

        $metaElement = $document->createElement("sphinx:attr");
        $metaElement->setAttribute("name", "suspend");
        $metaElement->setAttribute("type", "int");
        $metaElement->setAttribute("bits", "1");
        $metaElement->setAttribute('default', EnabledState::DISABLED);
        $schemaElement->appendChild($metaElement);

        $rootElement->appendChild($schemaElement);
        $document->appendChild($rootElement);

        /**
         * @var EMongoCursor $torrentsList
         * @var TorrentMeta $torrent
         */
        $torrentsList = TorrentMeta::model()->editExpired()->findAll();

        if ($torrentsList->count() === 0)
            \Yii::app()->end();

        $i = 1;

        foreach ($torrentsList as $torrent)
        {
            $dataElement = $document->createElement("sphinx:document");
            $dataElement->setAttribute("id", (int)$i);

            $name = $dataElement->appendChild(new DOMElement("name"));
            $name->appendChild(new DOMCdataSection($torrent->name));

            $description = $dataElement->appendChild(new DOMElement("description"));
            $description->appendChild(new DOMCdataSection($torrent->description));

            /**
             * @desc torrnet_id
             */
            $dataElement->appendChild(new DOMElement("torrent_id", $torrent->torrentId));

            /**
             * @desc suspend
             */
            $dataElement->appendChild(new DOMElement("suspend", $torrent->suspend));

            $rootElement->appendChild($dataElement);

            $i++;
        }

        echo $document->saveXML();
    }
}
