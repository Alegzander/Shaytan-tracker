<!-- наладить вид форм -->
<?=CHtml::beginForm("/torrent/new", "post", array("class" => "form-horizontal", "enctype" => "multipart/form-data")); ?>
  <div class="control-group <?=CHtml::$errorCss;?>">
  	<div class="controls">
  		<?=CHtml::errorSummary($model)."\n";?>
  	</div>
  </div>

  <div class="control-group<?=CHtml::error($model, "name") ? " ".CHtml::$errorCss:"";?>">
  	<?=CHtml::activeLabel($model, "name", array("class" => "control-label", "for" => "name"))."\n"; ?>  	
  	<div class="controls">
      <?=CHtml::activeTextField($model, "name", array("placeholder" => Yii::t("app", "Название торрента")))."\n"; ?>
    </div>
  </div>
  
  <?php if (Yii::app()->user->isGuest):?>
  <div class="control-group<?=CHtml::error($model, "email") ? " ".CHtml::$errorCss:"";?>">
    <?=CHtml::activeLabel($model, "email", array("class" => "control-label", "for" => "email"))."\n"; ?>
    <div class="controls">
      <?=CHtml::activeTextField($model, "email", array("placeholder" => Yii::t("app", "Электронная почта")))."\n"; ?>
    </div>
  </div>
  <?php endif; ?>

  <div class="control-group<?=CHtml::error($model, "torrent") ? " ".CHtml::$errorCss:"";?>">
    <?=CHtml::activeLabel($model, "torrent", array("class" => "control-label", "for" => "torrent"))."\n"; ?>
    <div class="controls">
     <?=CHtml::activeFileField($model, "torrent");?>
    </div>
  </div>

  <div class="control-group<?=CHtml::error($model, "category") ? " ".CHtml::$errorCss:"";?>">
    <?=CHtml::activeLabel($model, "category", array("class" => "control-label", "for" => "category"))."\n"; ?>
    <div class="controls">
      <select name="CreateTorrentForm[category]" id="CreateTorrentForm_category" class="span3">
        <option value="#" selected disabled><?=Yii::t("app", "Выберите категорию"); ?></option>
        <?php foreach ($categories as $groupName => $group) : ?>
        <optgroup label="<?=$groupName;?>">
        	<?php foreach ($group as $item): 
		        	$selected = ""; 
		        	if (isset($_POST["CreateTorrentForm"]["category"]) && $_POST["CreateTorrentForm"]["category"] == $groupName."-".$item)
		        		$selected = " selected";
        	?>
        	<option value="<?=$groupName;?>-<?=$item;?>"<?=$selected;?>><?=$item;?></option>
        	<?php endforeach;?>
        </optgroup>
        <?php endforeach;?>
      </select>
    </div>
  </div>

  <div class="control-group<?=CHtml::error($model, "infoUrl") ? " ".CHtml::$errorCss:"";?>">
    <?=CHtml::activeLabel($model, "infoUrl", array("class" => "control-label", "for" => "infoUrl"))."\n"; ?>
    <div class="controls">
      <?=CHtml::activeTextField($model, "infoUrl")."\n";?>
      <span><?=Yii::t("app", "Ссылка на источник торрента, ваш сайт, IRC-канал и так далее"); ?></span>
    </div>
  </div>

  <div class="control-group<?=CHtml::error($model, "description") ? " ".CHtml::$errorCss:"";?>">
    <?=CHtml::activeLabel($model, "description", array("class" => "control-label", "for" => "description"))."\n"; ?>
    <div class="controls">
      <?=CHtml::activeTextArea($model, "description", array("rows" => 3))."\n";?>
      <span>
		<br /><?=Yii::t("app", "Поддерживает ББКоды"); ?> [b], [i], [s], [u],
		<br />[left], [center], [right], [code], [email], [img], [url],
		<br />[color], [font], [size], [quote], and [spoiler].
	  </span>
    </div>
  </div>

  <div class="control-group<?=CHtml::error($model, "acceptRules") ? " ".CHtml::$errorCss:"";?>" id="accept-rules">
    <div class="controls">
    	<ul class="accept-rules">
    		<li><input type="checkbox" name="CreateTorrentForm[acceptRules]" value="1" /></li>
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