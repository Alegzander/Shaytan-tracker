<?php
/**
 * User: alegz
 * Date: 10/27/13
 * Time: 10:40 PM
 */

class CaptchaAction extends CCaptchaAction
{
    public $paranoid = false;

    /**
     * Runs the action.
     */
    public function run()
    {
        if (isset($_GET[self::REFRESH_GET_VAR])) // AJAX request for regenerating code
        {
            $code = $this->getVerifyCode(true);
            echo CJSON::encode(array(
                'hash1' => $this->generateValidationHash($code),
                'hash2' => $this->generateValidationHash(strtolower($code)),
                // we add a random 'v' parameter so that FireFox can refresh the image
                // when src attribute of image tag is changed
                'url' => $this->getController()->createUrl($this->getId(), array('v' => uniqid())),
            ));
        } else {
            $this->renderImage($this->getVerifyCode($this->paranoid));
        }

        Yii::app()->end();
    }
} 