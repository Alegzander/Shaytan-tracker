<?php
/**
 * User: alegz
 * Date: 10/15/13
 * Time: 10:21 AM
 */

class CreateTorrentForm extends CFormModel {
    public $name;
    public $torrent;
    public $tags;
    public $informationUrl;
    public $hidden;
    //Take description from comment value in torrent file
    public $descriptionFromFile = EnabledState::DISABLED;
    public $description;
    public $accept;

    public function rules(){
        return array(
            'required' => array('torrent, accept', 'required'),
            'name' => array('name', 'filter', 'filter' => 'strip_tags'),

            'torrent' => array('torrent', 'file',
                'maxSize' => (OSHelper::web()->getMaxUploadSize()*1024*1024),
                'types' => 'torrent',
                'mimeTypes' => 'application/x-bittorrent',
                'allowEmpty' => false),

            'tags' => array('tags', 'match', 'pattern' => '~^[\p{Xan}_]+(,([\s]+|)[\p{Xan}_]+){0,}$~u'),
            'informationUrl' => array('informationUrl', 'url', 'allowEmpty' => true),
            'hidden' => array('hidden', 'boolean', 'allowEmpty' => true),
            'descriptionFromFile' => array('descriptionFromFile', 'boolean'),
            'description' => array('description', 'filter', 'filter' => 'strip_tags'),
            'trimDescription' => array('description', 'filter', 'filter' => 'trim'),
            'accept' => array('accept', 'compare', 'compareValue' => 'accepted', 'allowEmpty' => false,
            'message' => \Yii::t('form-label', 'You can not post without accepting our rules.')),
        );
    }

    public function attributeLabels(){
        if (isset(\Yii::app()->params['rulesUrl'])){
            list($uri, $attributes) = \Yii::app()->params['rulesUrl'];
            $url = \Yii::app()->createUrl($uri, $attributes);
        } else {
            $url = '#';
        }

        return array(
            'name' => \Yii::t('form-label', 'Name'),
            'torrent' => \Yii::t('form-label', 'Torrent'),
            'tags' => \Yii::t('form-label', 'Tags'),
            'informationUrl' => \Yii::t('form-label', 'Information URL'),
            'hidden' => \Yii::t('form-label', 'Hidden'),
            'remake' => \Yii::t('form-label', 'Remake'),
            'descriptionFromFile' => \Yii::t('form-label', 'Take description from torrent file'),
            'description' => \Yii::t('form-label', 'Description'),
            'accept' => \Yii::t('form-label', 'I have read the <a href="{url}" >rules</a>, and I understand that failure to comply will result in the removal of my torrent.', array(
                '{url}' => $url
            ))
        );
    }
}