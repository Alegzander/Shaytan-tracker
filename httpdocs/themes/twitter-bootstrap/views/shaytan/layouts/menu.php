<?php
/**
 * User: alegz
 * Date: 10/27/13
 * Time: 9:26 PM
 *
 * @var CController $this
 */
$this->beginContent('/layouts/main');
?>
<table>
    <tr>
        <td>
            <div id="mainmenu" class="well">
                <?php
                $menu = new MenuData();

                $this->widget('bootstrap.widgets.TbMenu', array(
                    'type' => TbMenu::TYPE_LIST,
                    'items' => $menu->adminMenu()
                ));
                ?>
            </div>
        </td>
        <td>
            <?php echo $content; ?>
        </td>
    </tr>
</table>
<?php
$this->endContent();