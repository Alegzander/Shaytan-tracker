<?php
/* @var $this SiteController */
?>
    <?php
    $this->widget("Paginator", $paginatorParams);?>

    <table class="table">
      <thead>

        <tr>
          <td colspan="3"></td>
          <td><?= Yii::t("app", "Размер");?></td>
          <td><i class="icon-arrow-up"></i></td>
          <td><i class="icon-arrow-down"></i></td>
          <td><i class="icon-ok"></i></td>
          <td><i class="icon-comment"></i></td>
        </tr>

      </thead>
      <tbody>

        <?php foreach($tableRows as $row): ?>
        <tr>
          <td><span class="label label-success"><?=$row["category"]?></span></td>
          <td><a href="<?=$row["view"]?>"><?=$row["name"]?></a></td>
          <td><a href="<?=$row["download"]?>" alt="<?=Yii::t("app", "Скачать торрент-файл");?>" title="<?=Yii::t("app", "Скачать торрент-файл");?>"><i class="icon-download-alt"></i></a></td>
          <td><?=$row["size"]?></td>
          <td><?=$row["seeders"]?></td>
          <td><?=$row["leachers"]?></td>
          <td><?=$row["downloaded"]?></td>
          <td><?=$row["comments"]?></td>
        </tr>
        <?php endforeach ?>

      <tbody>
    </table>

    <?php $this->widget("Paginator", $paginatorParams); ?>