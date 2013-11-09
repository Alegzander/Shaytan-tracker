<?php
/**
 * User: alegz
 * Date: 11/7/13
 * Time: 3:15 AM
 */

class TorrentSearchForm extends CFormModel {
    public $phrase;
    public $byTags = EnabledState::ENABLED;
    public $byName = EnabledState::ENABLED;

    public $accuracy = ESearchAccuracy::MATCH_ALL_WORDS;

    public function rules(){
        return array(
            array('phrase', 'required'),
            array('phrase', 'filter', 'filter' => 'trim'),
            array('phrase', 'filter', 'filter' => 'strip_tags'),
            array('accuracy', 'in', 'range' => array(ESearchAccuracy::getEnums())),
            array('byTags, byName', 'boolean', 'allowEmpty' => true),
        );
    }

    public function attributeLabels(){
        return array(
            'phrase' => \Yii::t('form-label', 'Search'),
            'byTags' => \Yii::t('form-label', 'By tag'),
            'byName' => \Yii::t('form-label', 'By name'),
            'accuracy' => \Yii::t('form-label', 'Accuracy'),
        );
    }
} 