<?php
/**
 * In process
 * */
class Paginator extends CWidget
{
	public $displayLimit = 25;
	public $pagesLimit = 10;
	public $pagingAction = "#";
	public $dataSize = 0;
	public $pageNum = 0;
	public $paginatorOptions = array("class" => "pagination pagination-centered");
	public $groupOptions = array();
	public $itemOptions = array();
	
	public $countPages;
	public $startPage;
	public $endPage;
	
	private function makeCalculations()
	{
		$this->countPages = ceil($this->dataSize/$this->displayLimit);
		
		$range = floor($this->pagesLimit/2);
		
		if ($this->pageNum > $this->countPages)
			$this->pageNum = $this->countPages;
		
		if ($this->pageNum < 1)
			$this->pageNum = 1;
		
		$this->startPage = $this->pageNum - $range;
		
		if ($this->startPage < 1)
			$this->startPage = 1;
		else if (($this->startPage - 1) + $this->pagesLimit > $this->countPages)
			$this->startPage = ($this->countPages - $this->pagesLimit + 1);
		
		$this->endPage = ($this->startPage - 1) + $this->pagesLimit;
		
		if ($this->endPage > $this->countPages)
			$this->endPage = $this->countPages;
	}
	
	public function run()
	{
		if (is_numeric($this->dataSize) && $this->dataSize > 0)
		{
			$this->makeCalculations();
			
			if ($this->pageNum === 0)
				$this->pageNum = 1;
			
			if ($this->pageNum > $this->countPages)
				$this->pageNum = $this->countPages;
			
			$this ->render("paginator");
		}
	}
}