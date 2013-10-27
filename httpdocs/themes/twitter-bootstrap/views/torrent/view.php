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
        <tbody>

        <tr>
            <td class="span1"><strong><?=$torrentMeta->getAttributeLabel('name');?>:</strong</td>
            <td class="span5" colspan="3"><?=$torrentMeta->name;?></td>
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
            <td class="span2"><strong><?=$torrentMeta->getAttributeLabel('numLeachers')?>:</strong</td>
            <td class="span4"><?=intval($torrentMeta->numLeachers);?></td>
        </tr>

        <tr>
            <td class="span1"><strong></strong></td>
            <td class="span5"></td>
            <td class="span1"><strong><?=$torrentMeta->getAttributeLabel('size')?>:</strong</td>
            <td class="span5"><?=OSHelper::fileSystem()->getSizeLabel($torrentMeta->size);?></td>
        </tr>
        <tr>
            <td class="span1"><strong><?=$torrentMeta->getAttributeLabel('dateCreated');?>:</strong</td>
            <td class="span5"><?=date('d M Y', $torrentMeta->dateCreated);?></td>
            <td class="span1"><strong><?=\Yii::t('form-label', 'Download');?>:</strong</td>
            <td class="span5"><?php $this->renderPartial('view/_download', array('id' => $torrentMeta->torrentId)); ?></td>
        </tr>

        </tbody>
    </table>
    <?php if (!empty($torrentMeta->description)): ?>
    <div class="description">
        <div><b><?=$torrentMeta->getAttributeLabel('description')?></b></div>
        <div><?=$torrentMeta->description; ?></div>
    </div>
    <?php endif; ?>
</div>