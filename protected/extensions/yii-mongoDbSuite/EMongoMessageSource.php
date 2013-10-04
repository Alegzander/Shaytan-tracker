<?php
/**
 * Created by JetBrains PhpStorm.
 * User:    alegz
 * E-mail:  alexander.aka.alegz@gmail.com
 * Date:    12/1/12
 * Time:    9:45 PM
 */

/**
 * Including required model class file
 */
require_once (__DIR__.DIRECTORY_SEPARATOR.'extra'.DIRECTORY_SEPARATOR.'EMongoI18nModel.php');

/**
 * EMongoMessageSource represents a message source that stores translate messages in mongo
 *
 * Databese should contain collection that will contain translations.
 * Collection name should be the same as set in $this->translateMessageCollection it is equal
 * to i18n by default.
 */
class EMongoMessageSource extends CMessageSource
{
    const CACHE_KEY_PREFIX = "ext.YiiMongoDbSuite.EMongoMessageSource";

    /**
     * @var string
     * @desc The id os database connection
     */
    public $connectionID = "mongodb";

    /**
     * @var string
     * @desc Name of collection with messages and their translations
     */
    public $translateMessageCollection = "i18n";

    /**
     * @var int
     * @desc The time in seconds that the message can remain a valid cache
     * default to 0, meaning that cache is disabled
     */
    public $cachingDuration = 0;

    /**
     * @var string
     * @desc cache id. Simple. Put yours if you don't like this
     */
    public $cacheID = 'cache';

    /**
     * @param string $category
     * @param string $language
     * @return array|null
     */
    public function loadMessages($category, $language)
    {
        if (
            $this->cachingDuration > 0 &&
            $this->cacheID !== false &&
            ($cache = Yii::app()->getComponent($this->cacheID)) !== null
        )
        {
            $key = self::CACHE_KEY_PREFIX.'.messages.'.$category.'.'.$language;
            $data = $cache->get($key);

            if ($data !== false)
                return unserialize($data);
        }

        $messages = $this->loadMessageFromDb($category, $language);

        if (isset($cache))
            $cache->set($key, serialize($messages), $this->cachingDuration);

        return $messages;
    }

    /**
     * @param $category
     * @param $language
     * @return array|null
     * @desc Method requests translation form mongo and returns request
     * result. Variable message contains array:
     * $message['someMessage'] = 'Some message in needed language'
     */
    protected function loadMessageFromDb($category, $language)
    {
        //Getting model
        $i18n = $this->getMessageModel();
        //Getting translations
        $translations = $i18n->getMessages($category, $language)->find();

        //If we get null instead of object we might face problem trying to get messages
        if ($translations instanceof EMongoI18nModel)
            return $translations->messages;

        return null;
    }

    /**
     * @return EMongoI18nModel
     * @throws CException
     * @desc Checks component and returns model
     */
    protected function getMessageModel()
    {
        $_db = Yii::app()->getComponent($this->connectionID);

        /**
         * Checking wether we have correct db component
         */
        if (!$_db instanceof EMongoDB)
        {
            throw new CException(Yii::t('app', 'EMongoMessageSource.connectionId is invalid. Please make sure "{id}" refers to a valid database application component.'),
                array("{id}" => $this->connectionID));
        }

        return EMongoI18nModel::model();
    }
}
