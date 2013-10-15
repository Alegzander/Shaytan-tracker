<?php
/**
 * User: alegz
 * Date: 10/15/13
 * Time: 5:57 PM
 *
 * @var TorrentController $this
 * @var TbActiveForm $form
 * @var CreateTorrentForm $formModel
 * @var string $descriptionFieldName
 * @var string $descriptionFromFileFieldName
 */
?>
<?=$form->toggleButtonRow($formModel, $descriptionFromFileFieldName, array(
    'class' => 'description-from-file',
));?>
<?=$form->textAreaRow($formModel, $descriptionFieldName, array(
    'class' => 'description'
));?>