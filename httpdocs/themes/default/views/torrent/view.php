<?php
/**
 * @var $this TorrentController
 * @var $torrent Torrent
 */

setlocale(LC_ALL, "ru_RU");
?>
    <ul class="breadcrumb" style="margin-top: 60px; font-size: 12px;">
      <li><a href="#"><?=$category[0];?></a> <span class="divider">/</span></li>
      <li class="active"><?=$category[1];?></li>
    </ul>

    <table class="table table-condensed" style="font-size: 12px;">
      <thead>

        <tr>
          <td colspan="3"></td>
          <td><a href="#">Пожаловаться</a></td>
        </tr>

      </thead>
      <tbody>

        <tr>
          <td><strong>Название:</strong</td>
          <td><?=$torrent->name;?></td>
          <td><strong>Выложен:</strong</td>
          <td><?=$uploadedTime->format("dd MMMM y, HH:mm", $torrent->uploaded); ?></td>
        </tr>
        <tr>
          <td><strong>Опубликовал:</strong</td>
          <td><a href="#" style="color: #00AA00; font-weight: bold;"><?=$torrent->email;?></a></td>
          <td><strong>Раздающих:</strong</td>
          <td><?=count($torrent->peers["seeders"]);?></td>
        </tr>
        <tr>
          <td><strong>Трекер:</strong</td>
          <td><?=Yii::app()->getParams()->baseUrl;?></td>
          <td><strong>Качающих:</strong</td>
          <td><?=count($torrent->peers["leachers"]);?></td>
        </tr>
        <tr>
          <td><strong>Информация:</strong</td>
          <td><a href="#"><?=$torrent->infoUrl;?></a></td>
          <td><strong>Скачавших:</strong</td>
          <td><?=$torrent->downloaded;?></td>
        </tr>

        <tr>
          <td><strong>Рейтинг:</strong></td>
          <td>
            <div class="btn-group">
              <button class="btn"><i class="icon-minus"></i></button>
              <button class="btn"><span style="font-size: 12px; line-height: 14px;"><strong>2</strong></span></button>
              <button class="btn disabled"><i class="icon-plus"></i></button>
            </div>
          </td>
          <td><strong>Размер:</strong</td>
          <td><?=$torrent->getTotalSize();?></td>
        </tr>
        <tr>
          <td colspan="2"></td>
          <td><strong>Скачать:</strong</td>
          <td>
            <div class="btn-group">
              <a href="<?=$downloadLink;?>">
                  <button class="btn">
                      <span style="font-size: 12px; line-height: 14px;">.torrent</span>
                  </button>
              </a>
              <a href="<?=$downloadLink;?>/filetype/txt">
                <button class="btn">
                    <span style="font-size: 12px; line-height: 14px;">.txt</span>
                </button>
              </a>
            </div>
          </td>
        </tr>

      </tbody>
    </table>

    <table class="table" style="font-size: 12px;">

      <?php if (strlen($torrent->description) > 0): ?>
      <tr><td><strong>Описание торрента:</strong></td></tr>
      <tr><td class="well"><?=$torrent->description?></div></td></tr>
      <?php endif; ?>

      <tr><td><strong>Список файлов:</strong></td></tr>
      <?php foreach ($filesList as $file):?>
      <tr><td class="well"><?=$file;?></div></td></tr>
      <?php endforeach;?>

      <?php if(count($torrent->comments) > 0): ?>        $
      <tr><td><strong>Комментарии:</strong></td></tr>
      <?php foreach($torrent->comments as $comment): ?>
      <tr><td class="well"><em><?=$comment["author"];?>:&nbsp;&nbsp;</em><?=$comment["comment"];?></div></td></tr>
      <?php endforeach; ?>
      <?php endif; ?>

    </table>
