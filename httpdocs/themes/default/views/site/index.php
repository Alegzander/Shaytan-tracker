<?php
/* @var $this SiteController */
?>
    <!-- ul class="nav nav-pills">
      <li style="padding: 8px 12px 8px 5px;">Сортировать по:</li>
      <li class="active"><a href="#">дате</a></li>
      <li><a href="#">раздающим</a></li>
      <li><a href="#">скачивающим</a></li>
      <li><a href="#">количествам скачиваний</a></li>
      <li><a href="#">размерам</a></li>
      <li><a href="#">названию</a></li>
      <li class="active"><a href="#">по убыванию</a></li>
      <li><a href="#">по возрастанию</a></li>
    </ul -->

    <?php
    $this->widget("Paginator", $paginatorParams);?>

    <table class="table">
      <thead>

        <tr>
          <td colspan="3"></td>
          <td>Размер</td>
          <td><i class="icon-arrow-up"></i></td>
          <td><i class="icon-arrow-down"></i></td>
          <td><i class="icon-ok"></i></td>
          <td><i class="icon-comment"></i></td>
        </tr>

      </thead>
      <tbody>

        <tr>
          <td><span class="label label-success">Равки</span></td>
          <td><a href="#">[Zero-Raws] Jinrui wa Suitai Shimashita - 09</a></td>
          <td><a href="#" alt="Скачать торрент-файл" title="Скачать торрент-файл"><i class="icon-download-alt"></i></a></td>
          <td>277.2 МБ</td>
          <td>6</td>
          <td>1</td>
          <td>23</td>
          <td>0</td>
        </tr>

        <tr>
          <td><span class="label label-important">Ансаб</span></td>
          <td><a href="#">[HorribleSubs] Tari Tari - 09 [480p]</a></td>
          <td><a href="#" alt="Скачать торрент-файл" title="Скачать торрент-файл"><i class="icon-download-alt"></i></a></td>
          <td>145.7 МБ</td>
          <td>2</td>
          <td>5</td>
          <td>3</td>
          <td>1</td>
        </tr>

        <tr>
          <td><span class="label label-important">Ансаб</span></td>
          <td><a href="#">[HorribleSubs] Utakoi - 09 [480p]</a></td>
          <td><a href="#" alt="Скачать торрент-файл" title="Скачать торрент-файл"><i class="icon-download-alt"></i></a></td>
          <td>142.1 МБ</td>
          <td>5</td>
          <td>1</td>
          <td>2</td>
          <td>0</td>
        </tr>

        <tr>
          <td><span class="label label-info">Русаб</span></td>
          <td><a href="#">[Advantage] YuruYuri S2 - 09 [480p]</a></td>
          <td><a href="#" alt="Скачать торрент-файл" title="Скачать торрент-файл"><i class="icon-download-alt"></i></a></td>
          <td>149.9 МБ</td>
          <td>56</td>
          <td>7</td>
          <td>12</td>
          <td>43</td>
        </tr>

        <tr>
          <td><span class="label label-success">Равки</span></td>
          <td><a href="#">[Zero-Raws] Jinrui wa Suitai Shimashita - 09</a></td>
          <td><a href="#" alt="Скачать торрент-файл" title="Скачать торрент-файл"><i class="icon-download-alt"></i></a></td>
          <td>277.2 МБ</td>
          <td>6</td>
          <td>1</td>
          <td>23</td>
          <td>0</td>
        </tr>

        <tr>
          <td><span class="label label-important">Ансаб</span></td>
          <td><a href="#">[HorribleSubs] Tari Tari - 09 [480p]</a></td>
          <td><a href="#" alt="Скачать торрент-файл" title="Скачать торрент-файл"><i class="icon-download-alt"></i></a></td>
          <td>145.7 МБ</td>
          <td>2</td>
          <td>5</td>
          <td>3</td>
          <td>1</td>
        </tr>

        <tr>
          <td><span class="label label-important">Ансаб</span></td>
          <td><a href="#">[HorribleSubs] Utakoi - 09 [480p]</a></td>
          <td><a href="#" alt="Скачать торрент-файл" title="Скачать торрент-файл"><i class="icon-download-alt"></i></a></td>
          <td>142.1 МБ</td>
          <td>5</td>
          <td>1</td>
          <td>2</td>
          <td>0</td>
        </tr>

        <tr>
          <td><span class="label label-info">Русаб</span></td>
          <td><a href="#">[Advantage] YuruYuri S2 - 09 [480p]</a></td>
          <td><a href="#" alt="Скачать торрент-файл" title="Скачать торрент-файл"><i class="icon-download-alt"></i></a></td>
          <td>149.9 МБ</td>
          <td>56</td>
          <td>7</td>
          <td>12</td>
          <td>43</td>
        </tr>

        <tr>
          <td><span class="label label-warning">Рудаб</span></td>
          <td><a href="#">[Kuba77] Oneme S15 - 186 [480p]</a></td>
          <td><a href="#" alt="Скачать торрент-файл" title="Скачать торрент-файл"><i class="icon-download-alt"></i></a></td>
          <td>277.2 МБ</td>
          <td>6</td>
          <td>1</td>
          <td>23</td>
          <td>0</td>
        </tr>

      <tbody>
    </table>

    <?php $this->widget("Paginator", $paginatorParams); ?>