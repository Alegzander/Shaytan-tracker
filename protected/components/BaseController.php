<?php
/**
 * User: alegz
 * Date: 10/5/13
 * Time: 9:21 PM
 */

class BaseController extends SBaseController {
    /**
     * @var Zone|null
     */
    protected $zone;

    public function init(){
        parent::init();

        AssetsHelper::register(array('/css/project.css'));

        $defaultLanguage = \Yii::app()->getParams()->defaultLanguage;
        $languagesList = \Yii::app()->getParams()->supportedLanguages;
        $cookieExpire = \Yii::app()->getParams()->cookieExpire;
        $cookies = \Yii::app()->request->getCookies();
        $requestedLanguage = \Yii::app()->request->getQuery('language');
        $languageVar = 'language';
        $languageParams = array('httpOnly' => true, 'expire' => time() + $cookieExpire);

        $ip = $_SERVER['REMOTE_ADDR'];
        $network = Network::model()->findByIp($ip);

        if (isset($network)){
            $this->zone = Zone::model()->findByPk($network->zoneId);
            $network->zone = $this->zone;
            $defaultLanguage = $network->zone->defaultLanguage;
        }

        if (isset($requestedLanguage)){
            $language = new CHttpCookie($languageVar, $requestedLanguage, $languageParams);
        } else if (!isset($cookies[$languageVar])){
            if (isset($network)){
                $zone = $network->zone;
                $defaultLanguage = $zone->defaultLanguage;
            }

            $language = new CHttpCookie($languageVar, $defaultLanguage, $languageParams);
        } else {
            $language = $cookies[$languageVar];
        }

        if (!isset($languagesList[$language->value])){
            $language = new CHttpCookie($languageVar, $defaultLanguage, array(
                'httpOnly' => true,
                'expire' => $cookieExpire
            ));
        }

        \Yii::app()->request->cookies->add($languageVar, $language);
        \Yii::app()->setLanguage($language->value);
    }
}