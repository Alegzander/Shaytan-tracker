<div<?php
    /**
     * @var Paginator $this
     * @var CPagination $pagination
     */

    $pagination = $this->pagination;

    if (count($this->paginatorOptions) > 0)
		foreach ($this->paginatorOptions as $attribute => $value)
			echo " ".$attribute."=\"".$value."\"";
      ?>>
      <ul<?php 
	      if (count($this->groupOptions) > 0)
	      	foreach ($this->groupOptions as $attribute => $value)
	      		echo " ".$attribute."=\"".$value."\"";
      ?>>
       	<li<?php
	        if ($pagination->getCurrentPage() < $this->displayItemsLimit)
            {
	        	echo ' class="disabled"';
                $url = "#";
            }
            else
            {
                $url =  Yii::app()->createUrl(
                    $this->url,
                    array(
                        $this->varName =>
                        ($pagination->getCurrentPage() - $this->displayItemsLimit) + 1
                    )
                );
            }
       	?>><a href="<?=$url;?>"><?=Yii::t('app', 'Назад');?></a></li>
        <?php
        	$tmp = "";
        
        	for ($i = $this->startPage; $i <= $this->endPage; $i++)
        	{
        		if (array_key_exists("class", $this->itemOptions))
        		{
        			$tmp = $this->itemOptions["class"];
        		}
        		
        		if ($i != $this->startPage)
        			echo "        ";
        		
        		echo "<li";
        		
        		if ($pagination->getCurrentPage() == ($i-1))
        		{
        			$this->itemOptions["class"] = "active";
        		}
        		
        		if (count($this->itemOptions) > 0)
        			foreach ($this->itemOptions as $attribute => $value)
        				echo " ".$attribute."=\"".$value."\"";
        		
        		echo '><a href="'.Yii::app()->createUrl($this->url, array($this->varName => $i)).'">'.$i.'</a></li>';
        		
        		$this->itemOptions["class"] = $tmp;
        	}
        ?>
        <li<?php
        	if (
                $pagination->getCurrentPage() >= ($pagination->getPageCount() - $this->displayItemsLimit)
            )
            {
        		echo " class=\"disabled\"";
                $url = "#";
            }
            else
            {
                $url = Yii::app()->createUrl(
                    $this->url,
                    array(
                        $this->varName =>
                        ($pagination->getCurrentPage() + $this->displayItemsLimit) + 1
                    )
                );
            }
        ?>><a href="<?=$url?>"><?=Yii::t('app', 'Вперёд');?></a></li>
      </ul>
</div>