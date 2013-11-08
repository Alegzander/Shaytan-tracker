<?php
/**
 * User: alegz
 * Date: 11/7/13
 * Time: 3:15 AM
 */

class TorrentSearchForm extends CFormModel {
    const TYPE_ONE_PEACE = 0;
    const TYPE_ALL_WORDS = 1;
    const TYPE_ONE_WORD = 2;


    public $phrase;
    public $byTags = EnabledState::ENABLED;
    public $byName = EnabledState::ENABLED;

    public $accuracy;

    public function rules(){
        return array(
            array('phrase', 'required'),
            array('phrase', 'filter', 'filter' => 'trim'),
            array('phrase', 'filter', 'filter' => 'strip_tags'),
            array('byTags, byName', 'boolean', 'allowEmpty' => true),
            array('accuracy', 'default', 'value' => static::TYPE_ALL_WORDS),
            array('accuracy', 'in', 'range' => array(
                static::TYPE_ONE_PEACE,
                static::TYPE_ALL_WORDS,
                static::TYPE_ONE_WORD,
            ), 'allowEmpty' => true),
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
    
    public function getAccuracyLabels(){
        return array(
            static::TYPE_ONE_PEACE => \Yii::t('form-label', 'Search by phrase'),
            static::TYPE_ALL_WORDS => \Yii::t('form-label', 'Search by all words'),
            static::TYPE_ONE_WORD => \Yii::t('form-label', 'Search by word'),
        );
    }
} 