<?php
/**
 * User: alegz
 * Date: 7/22/13
 * Time: 2:23 PM
 *
 * @var AlwaysAllowedListController $this
 * @var string $file,
 * @var string $user
 */
$this->renderPartial('/_manage-menu');
?>
<h4><?=\Yii::t('error', 'File {file} is not writable. Please make sure that file is writable under system user {user}.',
		array(
			'{file}' => $file,
			'{user}' => $user
		));?></h4>
