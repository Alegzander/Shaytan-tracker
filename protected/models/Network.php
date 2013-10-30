<?php
/**
 * User: alegz
 * Date: 10/29/13
 * Time: 8:35 AM
 */

class Network extends EMongoDocument {

    public $name;
    public $zones;

    /**
     * @return Network
     */
    public static function model(){
        return parent::model(__CLASS__);
    }

    public function collectionName(){
        return 'network';
    }

    public function rules(){
        return array(
            array('name, zones', 'required'),
            array('name', 'filter', 'filter' => 'strip_tags'),
            array('name', 'filter', 'filter' => 'trim'),
            array('name', 'length', 'min' => 1),
            array('zones', 'validateZones', 'allowEmpty' => false)
        );
    }

    public function validateZones($attribute, $params){
        $zeroTo255 = "([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])";
        $oneTo255 = "([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])";
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