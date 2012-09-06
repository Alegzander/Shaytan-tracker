<!-- наладить вид форм -->
<?php $error = false;?>
<?=CHtml::beginForm("/torrent/upload", "post", array("class" => "form-horizontal")); ?>
  <div class="control-group">
  	<div class="controls">
  		<?=CHtml::errorSummary($model)."\n";?>
  	</div>
  </div>

  <?php if (CHtml::error($model, "name"))
	  		$error = true;
		else
			$error = false;
  ?>
  <div class="control-group<?=$error ? " ".CHtml::$errorCss:"";?>">
  	<?=CHtml::activeLabel($model, "name", array("class" => "control-label", "for" => "name"))."\n"; ?>  	
  	<div class="controls">
      <?=CHtml::activeTextField($model, "name", array("placeholder" => Yii::t("app", "Название торрента")))."\n"; ?>
      <?php if ($error): ?>
      <span class="help-inline"><?=CHtml::error($model, "name");?></span>
      <?php endif;?>
    </div>
  </div>
  
  <?php if (Yii::app()->user->isGuest):?>
  <?php if (CHtml::error($model, "email"))
	  		$error = true;
		else
			$error = false;
  ?>
  <div class="control-group<?=$error ? " ".CHtml::$errorCss:"";?>">
    <?=CHtml::activeLabel($model, "email", array("class" => "control-label", "for" => "email"))."\n"; ?>
    <div class="controls">
      <?=CHtml::activeTextField($model, "email", array("placeholder" => Yii::t("app", "Электронная почта")))."\n"; ?>
      <?php if ($error): ?>
      <span class="help-inline"><?=CHtml::error($model, "email");?></span>
      <?php endif;?>
    </div>
  </div>
  <?php endif; ?>

  <?php if (CHtml::error($model, "torrent"))
	  		$error = true;
		else
			$error = false;
  ?>
  <div class="control-group<?=$error ? " ".CHtml::$errorCss:"";?>">
    <?=CHtml::activeLabel($model, "torrent", array("class" => "control-label", "for" => "torrent"))."\n"; ?>
    <div class="controls">
     <?=CHtml::activeFileField($model, "torrent", array("class" => "input-file"))."\n";?>
     <?php if ($error): ?>
	 <span class="help-inline"><?=CHtml::error($model, "torrent");?></span>
	 <?php endif;?>
    </div>
  </div>

  <?php if (CHtml::error($model, "category"))
	  		$error = true;
		else
			$error = false;
  ?>
  <div class="control-group<?=$error ? " ".CHtml::$errorCss:"";?>">
    <?=CHtml::activeLabel($model, "category", array("class" => "control-label", "for" => "category"))."\n"; ?>
    <div class="controls">
      <select name="CreateTorrentForm[category]" id="CreateTorrentForm_category" class="span3">
        <option value="#" selected disabled><?=Yii::t("app", "Выберите категорию"); ?></option>
        <?php foreach ($categories as $groupName => $group) : ?>
        <optgroup label="<?=$groupName;?>">
        	<?php foreach ($group as $item): ?>
        	<option value="<?=$groupName;?> - <?=$item;?>"><?=$item;?></option>
        	<?php endforeach;?>
        </optgroup>
        <?php endforeach;?>
      </select>
      <?php if ($error): ?>
      <span class="help-inline"><?=CHtml::error($model, "category");?></span>
      <?php endif;?>
    </div>
  </div>

  <?php if (CHtml::error($model, "infoUrl"))
	  		$error = true;
		else
			$error = false;
  ?>
  <div class="control-group<?=$error ? " ".CHtml::$errorCss:"";?>">
    <?=CHtml::activeLabel($model, "infoUrl", array("class" => "control-label", "for" => "infoUrl"))."\n"; ?>
    <div class="controls">
      <?=CHtml::activeTextField($model, "infoUrl")."\n";?>
      <?php if ($error): ?>
      <span class="help-inline"><?=CHtml::error($model, "infoUrl");?></span>
      <?php else: ?>
      <span><?=Yii::t("app", "Ссылка на источник торрента, ваш сайт, IRC-канал и так далее"); ?></span>
      <?php endif;?>
    </div>
  </div>

  <?php if (strlen(CHtml::error($model, "description")) > 0)
	  		$error = true;
		else
			$error = false;
  ?>
  <div class="control-group<?=$error ? " ".CHtml::$errorCss:"";?>">
    <?=CHtml::activeLabel($model, "description", array("class" => "control-label", "for" => "description"))."\n"; ?>
    <div class="controls">
      <?=CHtml::activeTextArea($model, "description", array("rows" => 3))."\n";?>
      <span>
		<br /><?=Yii::t("app", "Поддерживает ББКоды"); ?> [b], [i], [s], [u],
		<br />[left], [center], [right], [code], [email], [img], [url],
		<br />[color], [font], [size], [quote], and [spoiler].
	  </span>
	  <?php if ($error): ?>
      <span class="help-inline"><?=CHtml::error($model, "description");?></span>
      <?php endif;?>
    </div>
  </div>

  <?php if (CHtml::error($model, "acceptRules"))
	  		$error = true;
		else
			$error = false;
  ?>
  <div class="control-group<?=$error ? " ".CHtml::$errorCss:"";?>" id="accept-rules">
    <div class="controls">
    	<ul class="accept-rules">
    		<li><input type="checkbox" name="CreateTorrentForm[acceptRules]" /></li>
    		<li>
    			<p><?=Yii::t("app", "Я полностью прочитал раздел"); ?> <a href="/site/page/view/faq"><?=Yii::t("app", "Правила/FAQ"); ?></a><?=Yii::t("app", ", и полностью, и безоговорочно принимаю правила указанные там."); ?></p>
        		<p><?=Yii::t("app", "В случае их не соблюдения мной, даю согласие на удаление загружаемого мною торрент фйала с данного трекера."); ?></p>
    		</li>
    	</ul>
    </div>
  </div>
  
  <div class="control-group">
    <div class="controls">
      <?=CHtml::submitButton(Yii::t("app", "Загрузить"), array("class" => "btn"))."\n";?>
    </div>
  </div>
<?=CHtml::endForm()."\n"; ?>