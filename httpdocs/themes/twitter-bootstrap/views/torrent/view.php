<?php
/**
 * User: alegz
 * Date: 10/20/13
 * Time: 3:39 PM
 *
 * @var TorrentController $this
 * @var Torrent $torrent
 */
$torrentMeta = $torrent->meta;
?>
<div class="container well">
<table class="table table-condensed" style="font-size: 12px;">
    <thead>

    <tr>
        <td colspan="3"></td>
        <td class="span1"><a href="#">??<?=\Yii::t('app', 'Abuse');?></a></td>
    </tr>

    </thead>
    <tbody>

    <tr>
        <td class="span1"><strong><?=$torrentMeta->getAttributeLabel('name');?>:</strong</td>
        <td class="span5"><?=$torrentMeta->name;?></td>
        <td class="span1"><strong><?=$torrentMeta->getAttributeLabel('dateCreated');?>:</strong</td>
        <td class="span5"><?=date('d M Y', $torrentMeta->dateCreated);?></td>
    </tr>
    <tr>
        <td class="span2"><strong><?=$torrentMeta->getAttributeLabel('informationUrl');?>:</strong</td>
        <td class="span4"><a href="<?=$torrentMeta->informationUrl?>"><?=$torrentMeta->informationUrl;?></a></td>
        <td class="span1"><strong><?=$torrentMeta->getAttributeLabel('numSeeds')?>:</strong</td>
        <td class="span5"><?=intval($torrentMeta->numSeeds);?></td>
    </tr>
    <tr>
        <td class="span1"><strong><?=$torrentMeta->getAttributeLabel('tags');?>:</strong</td>
        <td class="span5"><?php $this->renderPartial('view/_tags', array('tags' => $torrentMeta->tags)); ?></td>
        <td class="span2"><strong><?=$torrentMeta->getAttributeLabel('numLeechers')?>:</strong</td>
        <td class="span4"><?=intval($torrentMeta->numLeachers);?></td>
    </tr>

    <tr>
        <td class="span1"><strong></strong></td>
        <td class="span5"></td>
        <td class="span1"><strong><?=$torrentMeta->getAttributeLabel('size')?>:</strong</td>
        <td class="span5"><?=$torrentMeta->size;?></td>
    </tr>
    <tr>
        <td class="span1"><?=$torrentMeta->getAttributeLabel('rating')?>:</td>
        <td class="span5"><?php $this->renderPartial('view/_rating', array('rating' => $torrentMeta->rating)); ?></td>
        <td class="span1"><strong><?=\Yii::t('form-label', 'Download');?>:</strong</td>
        <td class="span5">
            <div class="btn-group">
                <a href="&nbsp">
                    <button class="btn">
                        <span style="font-size: 12px; line-height: 14px;">.torrent</span>
                    </button>
                </a>
                <a href="&nbsp/filetype/txt">
                    <button class="btn">
                        <span style="font-size: 12px; line-height: 14px;">.txt</span>
                    </button>
                </a>
            </div>
        </td>
    </tr>

    </tbody>
</table>
</div>