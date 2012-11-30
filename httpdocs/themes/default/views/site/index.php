<?php
/* @var $this SiteController */
/* @var $pagination CPagination*/
?>
    <?php
    if (count($sortMenuItems) > 0)
    {
        $this->widget("zii.widgets.CMenu", array(
                'htmlOptions' => array('class' => 'nav nav-pills sort-bar'),
                'items' => $sortMenuItems
            ));

        $this->widget("zii.widgets.CMenu", array(
                'htmlOptions' => array('class' => 'nav nav-pills sort-type-bar'),
                'items' => $orderMenu
            ));
    }

    if ($pagination->getPageCount() > 1)
        $this->widget("Paginator", array("pagination" => $pagination));?>

    <table class="table">
      <thead>
        <tr>
          <th colspan="3"></th>
          <th><?= Yii::t("app", "Размер");?></th>
          <th><i class="icon-arrow-up"></i></th>
          <th><i class="icon-arrow-down"></i></th>
          <th><i class="icon-ok"></i></th>
          <th><i class="icon-comment"></i></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($tableRows as $row): ?>
        <tr>
          <td><span class="label label-success"><?=$row["category"]?></span></td>
          <td class="torrent-list"><a href="<?=$row["view"]?>"><?=$row["name"]?></a></td>
          <td class="torrent-list"><a href="<?=$row["download"]?>" alt="<?=Yii::t("app", "Скачать торрент-файл");?>" title="<?=Yii::t("app", "Скачать торрент-файл");?>"><i class="icon-download-alt"></i></a></td>
          <td class="torrent-list"><?=$row["size"]?></td>
          <td class="torrent-list"><?=$row["seeders"]?></td>
          <td class="torrent-list"><?=$row["leachers"]?></td>
          <td class="torrent-list"><?=$row["downloaded"]?></td>
          <td class="torrent-list"><?=$row["comments"]?></td>
        </tr>
        <?php endforeach ?>
      <tbody>
    </table>

    <?php
    if ($pagination->getPageCount() > 1)
        $this->widget("Paginator", array("pagination" => $pagination));
    ?>