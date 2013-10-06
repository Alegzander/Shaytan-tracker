<?php
/**
 * The default srbac controller
 */
class DefaultController extends CController {
  /**
   * The default action if no route is specified
   */
	public function actionIndex() {
    	$this->redirect(array('manage/index'));
	}
 }