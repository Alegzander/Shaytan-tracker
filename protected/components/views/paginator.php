<div<?php
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
	        if (($this->pageNum - $this->pagesLimit) < 1)
	        	echo " class=\"disabled\"";	        
       	?>><a href="<?=$this->pagingAction."/".($this->pageNum-$this->pagesLimit);?>">Назад</a></li>
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
        		
        		if ($this->pageNum == $i)
        		{
        			$this->itemOptions["class"] = "active";
        		}
        		
        		if (count($this->itemOptions) > 0)
        			foreach ($this->itemOptions as $attribute => $value)
        				echo " ".$attribute."=\"".$value."\"";
        		
        		echo "><a href=\"".$this->pagingAction."/".$i."\">".$i."</a></li>";
        		
        		$this->itemOptions["class"] = $tmp;
        	}
        ?>
        <li<?php
        	if (($this->pageNum + $this->pagesLimit) > $this->countPages)
        		echo " class=\"disabled\"";
        ?>><a href="<?=$this->pagingAction."/".($this->pageNum+$this->pagesLimit);?>">Далее</a></li>
      </ul>
</div>