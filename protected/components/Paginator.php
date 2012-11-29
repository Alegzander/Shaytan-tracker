<?php
/**
 * In process
 * */
class Paginator extends CWidget
{
    /**
     * @var CPagination
     */
    public $pagination;
    public $url = "";
    public $varName = "page";

    public $displayItemsLimit = 10;

    public $paginatorOptions = array("class" => "pagination pagination-centered");
    public $groupOptions = array();
    public $itemOptions = array();

    public $startPage;
    public $endPage;
	
	public function run()
	{
        if ($this->pagination->getPageCount() <= $this->displayItemsLimit)
        {
            $this->startPage = 1;
            $this->endPage = $this->pagination->getPageCount();
        }
        else
        {
            $this->startPage = $this->pagination->getCurrentPage() - floor($this->displayItemsLimit / 2) + 1;

            if ($this->startPage < 1)
                $this->startPage = 1;

            $this->endPage = $this->startPage + $this->displayItemsLimit - 1;

            if ($this->endPage > $this->pagination->getPageCount())
            {
                $this->endPage = $this->pagination->getPageCount();
                $this->startPage = $this->endPage - $this->displayItemsLimit + 1;
            }
        }

		$this ->render("paginator");
	}
}