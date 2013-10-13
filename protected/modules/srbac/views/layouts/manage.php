<?php
/**
 * User: alegz
 * Date: 10/13/13
 * Time: 10:04 AM
 *
 * @var SBaseController $this
 */
$this->beginContent('/layouts/srbac');
?>
<div class='push-top'></div>
<?php $this->renderPartial('/_manage-menu');?>
<?=$content;?>
<?php $this->endContent(); ?>