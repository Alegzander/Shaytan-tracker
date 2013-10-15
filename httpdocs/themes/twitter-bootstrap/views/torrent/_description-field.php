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
<?=$form->textAreaRow($formModel, $descriptionFieldName, array('class' => 'description'));?>
<?//=$form->ckEditorRow($formModel, $descriptionFieldName, array(
//    'class' => 'description',
//    'options' => array(
////        'plugins' => 'bbcode,image,dialog,toolbar,liststyle,image,imagepaste,clipboard,specialchar,stylesheetparser',
//        'removePlugins' => 'iframe,iframedialog,font,flash,save,about,adobeair,forms,preview,specialchar,div,smiley,fakeobject',
//        'resize_enabled' => 'js:false',
////        'toolbar' => array('Source', '-', 'Bold', 'Italic')
////        'startupMode' => 'source'
//    )
//));?>