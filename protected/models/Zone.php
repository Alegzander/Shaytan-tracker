<?php
/**
 * User: alegz
 * Date: 11/1/13
 * Time: 7:16 PM
 */

class Zone extends EMongoDocument {
    public $name;
    public $defaultLanguage;
    public $subnetList;

    /**
     * @return Zone
     */
    public static function model(){
        return parent::model(__CLASS__);
    }

    public function collectionName(){
        return 'zone';
    }

    public function rules(){
        if (isset(\Yii::app()->getParams()->supportedLanguages))
            $languagesList = array_flip(\Yii::app()->getParams()->supportedLanguages);
        else {
            $languagesList = array();
            $this->addError('defaultLanguage',
                \Yii::t('error', 'Supported languages are not set. Please configure application first.'));
        }

        return array(
            array('name, subnetList, defaultLanguage', 'required'),
            array('name', 'filter', 'filter' => 'strip_tags'),
            array('name', 'filter', 'filter' => 'trim'),
            array('name', 'length', 'min' => 1),
            array('defaultLanguage', 'in', 'range' => $languagesList),
            array('subnetList', 'validateZones', 'allowEmpty' => false)
        );
    }

    public function validateZones($attribute, $params){
        $zeroTo255 = "([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])";
        $oneTo255 = "([1-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])";
        $subnetPattern = '/^'.implode('\\.', array($oneTo255, $zeroTo255, $zeroTo255, $zeroTo255)).'\/([8-9]|[1-2][0-9]|3[0-2])$/';
        $hasErrors = false;

        $rawList = is_array($this->{$attribute}) ? implode(', ', $this->{$attribute}) : $this->{$attribute};
        $rawList = preg_replace('/([0-9])([\s]+|[,][\s]+|[\s]+[,][\s]+)([0-9])/', '\1,\3', trim($rawList));

        $subnetList = explode(',', $rawList);

        foreach ($subnetList as $subnet){
            if (preg_match($subnetPattern, trim($subnet)) !== 1){
                $hasErrors = true;
                $this->addError($attribute, \Yii::t('Invalid subnet "{subnet}".', array('{subnet}' => $subnet)));
            }
        }

        if (!$hasErrors)
            $this->{$attribute} = $subnetList;

        return !$hasErrors;
    }
} 