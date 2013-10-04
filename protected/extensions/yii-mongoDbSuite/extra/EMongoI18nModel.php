<?php
/**
 * Created by JetBrains PhpStorm.
 * User:    alegz
 * E-mail:  alexander.aka.alegz@gmail.com
 * Date:    12/1/12
 * Time:    10:42 PM
 */

class EMongoI18nModel extends EMongoDocument
{
    /**
     * @var string
     * @desc Category name
     */
    public $category;

    /**
     * @var string
     * @desc language code
     */
    public $language;

    /**
     * @var array
     * @desc Массив текстов
     */
    public $messages;

    /**
     * @param string $className
     * @return EMongoDocument
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string
     * @throws EMongoException
     */
    public function getCollectionName()
    {
        /**
         * @var CMongoMessageSource $messageComponent
         */
        $messageComponent = Yii::app()->getComponent("messages");

        if (!$messageComponent instanceof EMongoMessageSource)
            throw new EMongoException(Yii::t('app', 'Component message is not the instance of EMongoMessageSource.'));

        return $messageComponent->translateMessageCollection;
    }

    /**
     * @param $category
     * @param $language
     * @return EMongoI18nModel
     * @desc Method adds category and language criterias to the query.
     */
    public function getMessages($category, $language)
    {
        $this->getDbCriteria()->category = $category;
        $this->getDbCriteria()->language = $language;

        return $this;
    }
}
