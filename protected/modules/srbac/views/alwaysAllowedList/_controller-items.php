<?php
/**
 * User: alegz
 * Date: 10/13/13
 * Time: 10:37 AM
 *
 * @var AlwaysAllowedListController $this
 * @var array $allowedOptions
 * @var AlwaysAllowedEditForm $formModel
 * @var TbActiveForm $form
 * @var array $items
 */
?>
<?php foreach ($items as $item): ?>
    <?php if (isset($allowedOptions[$item])) $formModel->item[$item] = $item; ?>
    <label class="checkbox">
        <?=$form->checkBox($formModel, 'item['.$item.']', array('value' => $item));?><?=$item;?>
    </label>
<?php endforeach;?>
