<?php
/**
 * User: alegz
 * Date: 10/20/13
 * Time: 3:39 PM
 *
 * @var TorrentController $this
 * @var Torrent $torrent
 * @var array $tagList
 * @var bool $editAllowed
 */
$torrentMeta = $torrent->meta;
$editUrl = $this->createUrl('update');
?>
<div class="container well">
    <table class="table table-condensed" style="font-size: 12px;">
        <tbody>

        <tr>
            <td class="span1"><strong><?=$torrentMeta->getAttributeLabel('name');?>:</strong</td>
            <td class="span5" colspan="3"><?php $this->widget('bootstrap.widgets.TbEditableField',
                    array('type' => 'text', 'model' => $torrentMeta, 'attribute' => 'name',
                        'url' => $editUrl, 'apply' => $editAllowed))?></td>
        </tr>
        <tr>
            <td class="span2"><strong><?=$torrentMeta->getAttributeLabel('informationUrl');?>:</strong</td>
            <td class="span4"><?php $this->widget('bootstrap.widgets.TbEditableField',
                    array('type' => 'text', 'model' => $torrentMeta, 'attribute' => 'informationUrl',
                        'url' => $editUrl, 'apply' => $editAllowed))?></td>
            <td class="span1"><strong><?=$torrentMeta->getAttributeLabel('numSeeds')?>:</strong</td>
            <td class="span5"><?=intval($torrentMeta->numSeeds);?></td>
        </tr>
        <tr>
            <td class="span1"><strong><?=$torrentMeta->getAttributeLabel('tags');?>:</strong</td>
            <td class="span5">
                <?php if($editAllowed): ?>
                    <?php $torrentMeta->tags = implode(', ', $torrentMeta->tags); ?>
                    <?php $this->widget('bootstrap.widgets.TbEditableField', array('type' => 'select2',
                        'model' => $torrentMeta, 'attribute' => 'tags', 'url' => $editUrl, 'apply' => true,
                        'select2' => array('tokenSeparators' => array(','), 'tags' => $tagList),
                        'options' => array('asDropDownList' => false)));?>
                <?php else: ?>
                    <?php $this->renderPartial('view/_tags', array('tags' => $torrentMeta->tags)); ?>
                <?php endif; ?>
            </td>
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
    <?php if (!empty($torrentMeta->description) || $editAllowed): ?>
        <?php if ($editAllowed): ?>
            <div class="description">
                <div><b><?= $torrentMeta->getAttributeLabel('description') ?></b></div>
                <div><?php $this->widget('bootstrap.widgets.TbEditableField',
                        array('type' => 'textarea', 'model' => $torrentMeta, 'attribute' => 'description',
                            'url' => $editUrl, 'apply' => true))?></div>
            </div>
        <?php else: ?>
            <div class="description">
                <div><b><?= $torrentMeta->getAttributeLabel('description') ?></b></div>
                <div><?= $torrentMeta->description; ?></div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($editAllowed): ?>
        <div class="form-actions">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'type' => TbButton::TYPE_DANGER,
                'buttonType' => TbButton::BUTTON_LINK,
                'label' => \Yii::t('form-label', 'Delete this torrent'),
                'url' => $this->createUrl('delete', array('id' => $torrentMeta->torrentId))
            ));?>
        </div>
    <?php endif; ?>
</div>