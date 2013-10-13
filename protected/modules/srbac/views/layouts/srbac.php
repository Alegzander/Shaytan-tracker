<?php
/**
 * User: alegz
 * Date: 10/13/13
 * Time: 12:17 AM
 *
 * @var BaseController $this
 */
$this->beginContent('//layouts/main');
$this->renderPartial('/_main-menu');
?>
<div class="push"></div>
<?=$content;?>
<?php
$this->endContent();
?>
