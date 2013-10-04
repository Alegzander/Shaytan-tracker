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
    public function init()
    {
        parent::init();
    }

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

        $metaElement = $document->createElement("sphinx:attr");
        $metaElement->setAttribute("name", "_id");
        $metaElement->setAttribute("type", "string");
        $schemaElement->appendChild($metaElement);

        $metaElement = $document->createElement("sphinx:attr");
        $metaElement->setAttribute("name", "type");
        $metaElement->setAttribute("type", "int");
        $schemaElement->appendChild($metaElement);

        $rootElement->appendChild($schemaElement);
        $document->appendChild($rootElement);

        $searchCriteria = Torrent::model()->getDbCriteria();
        $searchCriteria->select(array("_id", "name", "type"))->sort("_id", Torrent::SORT_ASC);

        $torrentsList = Torrent::model()->findAll($searchCriteria);

        $i = 1;

        foreach ($torrentsList as $torrent)
        {
            $dataElement = $document->createElement("sphinx:document");
            $dataElement->setAttribute("id", (int)$i);

            $contentTag = $dataElement->appendChild(new DOMElement("name"));
            $contentTag->appendChild(new DOMCdataSection($torrent->name));

            /**
             * @desc _id tag
             */
            $dataElement->appendChild(new DOMElement("_id", $torrent->_id));

            /**
             * @desc type tag
             */
            $dataElement->appendChild(new DOMElement("type", $torrent->type));

            $rootElement->appendChild($dataElement);
            $i++;
        }

        echo $document->saveXML();
    }
}
