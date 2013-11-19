<?php
/**
 * This file contains filesource of class ESphinxQuery
 */

/**
 * Class implements sphinx query model.
 * Query contains search text, indexes list, and sphinx criteria.
 */
class ESphinxQuery extends CComponent
{
	/**
	 * @var string $_text
	 */
	private $_text;
	/**
	 * @var string $_indexes
	 */
	private $_indexes;
	/**
	 * @var ESphinxCriteria $_criteria
	 */
	private $_criteria;

	/**
	 * Query constructor.
	 * 
	 * @param string $text search phrase
	 * @param string $indexes list of indexes
	 * @param ESphinxCriteria $criteria search criterias
	 */
	public function __construct($text, $indexes="*", $criteria = null )
	{
		$this->_text = (string)$text;
		$this->_indexes = (string)$indexes;

		if($criteria instanceof ESphinxCriteria )
	        $this->_criteria = $criteria;
		else
			$this->_criteria = new ESphinxCriteria;

	    if(is_array($indexes))
		    $indexes = join(" ", $indexes);
	}
	/**
	 * Get search query
	 * @return string
	 */
	public function getText()
	{
		return $this->_text;
	}
	/**
	 * Get list indexes as string
	 * @return string
	 */
	public function getIndexes()
	{
		return $this->_indexes;
	}
	/**
	 * Get search criteria
	 * @return ESphinxCriteria
	 */
	public function getCriteria()
	{
		return $this->_criteria;
	}
}