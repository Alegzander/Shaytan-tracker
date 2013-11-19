<?php
/**
 * This file contains filesource of class ESphinxCriteria
 */

/**
 * Class ESphinxCriteria
 *
 * @author mitallast <mitallast@gmail.com>
 * @version 0.1
 * @since 0.1
 * @package 
 */
class ESphinxCriteria extends CComponent
{
	# Sphinx match mode types

	/**
	 * Matches all query words (default mode).
	 * 
	 * @const int 0 
	 */
	const MATCH_ALL = 0;
	/**
	 * Matches any of the query words.
	 *
	 * @const int 1
	 */
	const MATCH_ANY = 1;
	/**
	 * matches query as a phrase, requiring perfect match.
	 * 
	 * @const int 2
	 */
	const MATCH_PHRASE = 2;
	/**
	 * Matches query as a boolean expression.
	 *
	 * @see http://sphinxsearch.com/docs/manual-0.9.9.html#boolean-syntax
	 * @const int 3
	 */
	const MATCH_BOOLEAN = 3;
	/**
	 * Matches query as an expression in Sphinx internal query language.
	 * As of 0.9.9, this has been superceded by SPH_MATCH_EXTENDED2, providing additional functionality
	 * and better performance. The ident is retained for legacy application code that will continue to be
	 * compatible once Sphinx and its components, including the API, are upgraded.
	 *
	 * @see http://sphinxsearch.com/docs/manual-0.9.9.html#extended-syntax "Section 4.3, Extended query syntax"
	 * @const int 4
	 */
	const MATCH_EXTENDED = 4;
	/**
	 * Matches query, forcibly using the "full scan" mode as below. NB, any query terms will be ignored, such that
	 * filters, filter-ranges and grouping will still be applied, but no text-matching.
	 * 
	 * @const int 5
	 */
	const MATCH_FULLSCAN = 5;
	/**
	 * Matches query using the second version of the Extended matching mode.
	 *
	 * @const int 6
	 */
	const MATCH_EXTENDED2 = 6;
	/**
	 * All match mode variants.
	 * 
	 * @see http://sphinxsearch.com/docs/manual-0.9.9.html#matching-modes
	 * @var array $matchModes
	 */
	static $matchModes = array(0,1,2,3,4,5,6);

	# Sphinx rank mode types

	/**
	 * Default ranking mode which uses and combines both phrase proximity and BM25 ranking.
	 *
	 * @const int 0
	 */
	const RANK_PROXIMITY_BM25 = 0;
	/**
	 * Statistical ranking mode which uses BM25 ranking only (similar to most other full-text engines).
	 * This mode is faster but may result in worse quality on queries which contain more than 1 keyword.
	 *
	 * @const int 1
	 */
	const RANK_BM25 = 1;
	/**
	 * Disabled ranking mode. This mode is the fastest.
	 * It is essentially equivalent to boolean searching.
	 * A weight of 1 is assigned to all matches.
	 *
	 * @const int 2
	 */
	const RANK_NONE = 2;
	/**
	 * Ranking by keyword occurrences count. This ranker computes the amount of per-field keyword occurrences,
	 * then multiplies the amounts by field weights, then sums the resulting values for the final result.
	 *
	 * @const int 3
	 */
	const RANK_WORDCOUNT = 3;
	/**
	 * Added in version 0.9.9-rc1, returns raw phrase proximity value as a result.
	 * This mode is internally used to emulate SPH_MATCH_ALL queries.
	 *
	 * @const int 4
	 */
	const RANK_PROXIMITY = 4;
	/**
	 * Added in version 0.9.9-rc1, returns rank as it was computed in SPH_MATCH_ANY mode earlier, 
	 * and is internally used to emulate SPH_MATCH_ANY queries
	 *
	 * @const int 5
	 */
	const RANK_MATCHANY  = 5;
	/**
	 * Added in version 0.9.9-rc2, returns a 32-bit mask with N-th bit corresponding to N-th fulltext field,
	 * numbering from 0. The bit will only be set when the respective field has any keyword
	 * occurences satisfiying the query
	 *
	 * @const int 6
	 */
	const RANK_FIELDMASK = 6;
	/**
	 * List all rank modes.
	 *
	 * @var array $rankModes
	 */
	static $rankModes = array(0,1,2,3,4,5);

	# Sphinx sort mode types

	/**
	 * Sorts by relevance in descending order (best matches first).
	 *
	 * @const int 0
	 */
	const SORT_RELEVANCE = 0;
	/**
	 * Sorts by an attribute in descending order (bigger attribute values first).
	 *
	 * @const int 1
	 */
	const SORT_ATTR_DESC = 1;
	/**
	 * Sorts by an attribute in ascending order (smaller attribute values first).
	 *
	 * @const int 2
	 */
	const SORT_ATTR_ASC = 2;
	/**
	 * Sorts by time segments (last hour/day/week/month) in descending order,
	 * and then by relevance in descending order.
	 *
	 * @const int 3
	 */
	const SORT_TIME_SEGMENTS = 3;
	/**
	 * Sorts by SQL-like combination of columns in ASC/DESC order.
	 *
	 * @const int 4
	 */
	const SORT_EXTENDED = 4;
	/**
	 * Sorts by an arithmetic expression.
	 *
	 * @const int 5
	 */
	const SORT_EXPR = 5;
	/**
	 * List all sort modes.
	 *
	 * @see http://sphinxsearch.com/docs/manual-0.9.9.html#sorting-modes
	 * @var array
	 */
	static $sortModes = array(0,1,2,3,4,5);
	# Sphinx group mode types
	/**
	 * Extracts year, month and day in YYYYMMDD format from timestamp
	 *
	 * @const int 0
	 */
	const GROUP_BY_DAY = 0;
	/**
	 * Extracts year and first day of the week number (counting from year start) in YYYYNNN format from timestamp.
	 *
	 * @const int 1
	 */
	const GROUP_BY_WEEK = 1;
	/**
	 * Extracts month in YYYYMM format from timestamp.
	 *
	 * @const int 2
	 */
	const GROUP_BY_MONTH = 2;
	/**
	 * Extracts year in YYYY format from timestamp.
	 *
	 * @const int 3
	 */
	const GROUP_BY_YEAR = 3;
	/**
	 * Uses attribute value itself for grouping
	 *
	 * @const int 4
	 */
	const GROUP_BY_ATTR = 4;
	/**
	 * List all group modes.
	 * 
	 * @see http://sphinxsearch.com/docs/manual-0.9.9.html#clustering
	 * @var array
	 */
	static $groupModes = array(0,1,2,3,4);
	/**
	 * Sphinx match mode.
	 *
	 * @see http://sphinxsearch.com/docs/manual-0.9.9.html#matching-modes
	 * @var int
	 */
	public $matchMode;
	/**
	 * Ranking mode.
	 * Only available in SPH_MATCH_EXTENDED2 matching mode at the time of this writing. Parameter must be a constant
	 * specifying one of the known modes.
	 *
	 * By default, Sphinx computes two factors which contribute to the final match weight. The major part is query
	 * phrase proximity to document text. The minor part is so-called BM25 statistical function, which varies
	 * from 0 to 1 depending on the keyword frequency within document (more occurrences yield higher weight) and within
	 * the whole index (more rare keywords yield higher weight).
	 *
	 * However, in some cases you'd want to compute weight differently - or maybe avoid computing it at all for
	 * performance reasons because you're sorting the result set by something else anyway. This can be accomplished
	 * by setting the appropriate ranking mode. 
	 *
	 * @var int
	 */
	public $rankMode;
	/**
	 * @var string $sort sort expression sql-like
	 */
	public $order;
	/**
	 * Sphinx sort mode.
	 * 
	 * @var int
	 */
	public $sortMode;
	/**
	 * Select column SQL-like list.
	 * @var string $select
	 */
	public $select;

	/**
	 * @var int defaults to null, no limit
	 */
	public $limit;
	/**
	 * @var int defaults to null, no offset
	 */
	public $offset;
	/**
	 * Property $max_matches setting controls how much matches searchd will keep in RAM while searching. All matching documents 
	 * will be normally processed, ranked, filtered, and sorted even if max_matches is set to 1. But only best N
	 * documents are stored in memory at any given moment for performance and RAM usage reasons, and this setting controls
	 * that N. Note that there are two places where max_matches limit is enforced. Per-query limit is controlled by
	 * this API call, but there also is per-server limit controlled by max_matches setting in the config file.
	 * To prevent RAM usage abuse, server will not allow to set per-query limit higher than the per-server limit.
	 *
	 * @var int defaults to 0, default matches in sphinx server configuration
	 */
	public $max_matches=0;
	/**
	 * Property is intended for advanced performance control. It tells searchd to forcibly stop search query once $cutoff
	 * matches had been found and processed.
	 *
	 * @var int defaults to 0, defaults cutoff
	 */
	public $cutoff=0;
	/**
	 * @var int $idMax filters maximum id
	 */
	private $idMax;
	/**
	 * @var int $idMin filters minimum id
	 */
	private $idMin;

	/**
	 * @var array hash of field weights: array(field=>weight)
	 */
	private $fieldWeights = array();
	/**
	 * @var array hash of index weights: array(index=>weight)
	 */
	private $indexWeights = array();
	/**
	 * @var array $include include attribute values list filter
	 */
	private $include = array();
	/**
	 * @var array $exclude exclude attribute values list filter
	 */
	private $exclude = array();
	/**
	 * @var array $inRange include attribute value range array(10, 20)
	 */
	private $inRange = array();
	/**
	 * @var array $outRange exclude attribute value range array(10, 20)
	 */
	private $outRange = array();
	/**
	 * @var string $groupBy
	 */
	private $groupBy;
	/**
	 * @var int $groupFunc
	 */
	private $groupFunc;
	/**
	 * @var string $groupSort defaults to "@group desc"
	 */
	private $groupSort = "@group desc";

	/**
	 * Set field weight. If weight isset, it will be rewrited.
	 * @param string $field
	 * @param int $weight
	 */
	public function setFieldWeight($field, $weight)
	{
		$this->fieldWeights[(string)$field] = (int)$weight;
	}
	/**
	 * Set field weights. All setted earlier weights will be cleared.
	 * @param array $weights hash of field weight array(field=>weight)
	 */
	public function setFieldWeights(array $weights)
	{
		$this->fieldWeights = array();
		foreach ( $weights as $field => $weight )
			$this->setFieldWeight ($field, $weight);
	}
	/**
	 * Get setted field weights
	 * @return array hash of $field=>$weight
	 * @see setFieldWeight
	 * @see setFieldWeights
	 */
	public function getFieldWeights()
	{
		return $this->fieldWeights;
	}
	/**
	 * Set index weight. If weight isset, it will be rewrited.
	 * @param string $index
	 * @param int $weight
	 */
	public function setIndexWeight($index, $weight)
	{
		$this->indexWeights[(string)$index] = (int)$weight;
	}
	/**
	 * Set index weights. All setted earlier weights will be cleared.
	 * @param array $weights hash of field weight array(field=>weight)
	 */
	public function setIndexWeights(array $weights)
	{
		$this->indexWeights = array();
		foreach ( $weights as $index => $weights )
			$this->setIndexWeight ($index, $weights);
	}
	/**
	 * Get setted index weights
	 * @return array hash of $index=>$weight
	 * @see setIndexWeight
	 * @see setIndexWeights
	 */
	public function getIndexWeights()
	{
		return $this->indexWeights;
	}
	/**
	 * Add condition filter by field value in list values.
	 * @param string $field
	 * @param array $values
	 */
	public function setInCondition($field, array $values)
	{
		$field = strtolower(trim($field));
		$this->include[$field] = $values;
	}
	/**
	 * Get setted include filter of attribute values list
	 * <code>
	 * array(
	 *   "attributeName" => array( 1, 2, 3, 4)
	 * )
	 * </code>
	 * @return array
	 * @see setInCondition
	 */
	public function getInConditions()
	{
		return $this->include;
	}/**
	 * Add condition filter by field value not in list values.
	 * @param string $field
	 * @param array $values
	 */
	public function setNotInCondition($field, array $values)
	{
		$field = strtolower(trim($field));
		$this->exclude[$field] = $values;
	}
	/**
	 * Get setted exclude filter of attribute values list
	 * <code>
	 * array(
	 *   "attributeName" => array( 1, 2, 3, 4)
	 * )
	 * </code>
	 * @return array
	 * @see setNotInCondition
	 */
	public function getNotInConditions()
	{
		return $this->exclude;
	}
	/**
	 * Add filter by field value in range (between $min and $max).
	 * @param string $field
	 * @param int $min
	 * @param int $max
	 */
	public function setInRange($field, $min, $max)
	{
		$field = strtolower(trim($field));
		$this->inRange[$field] = array(
			"min" => (int)$min,
			"max" => (int)$max,
		);
	}
	/**
	 * Get setted in ranges
	 * <code>
	 * array(
	 *   "field" => array(
	 *     "min" => 0
	 *     "max" => 100
	 *	  )
	 * )
	 * </code>
	 * @return array
	 */
	public function getInRanges()
	{
		return $this->inRange;
	}
	/**
	 * Add filter by field value not in range (between $min and $max).
	 * @param string $field
	 * @param int $min
	 * @param int $max
	 */
	public function setNotInRange($field, $min, $max)
	{
		$field = strtolower(trim($field));
		$this->outRange[$field] = array(
			"min" => (int)$min,
			"max" => (int)$max,
		);
	}
	/**
	 * Get setted out ranges
	 * <code>
	 * array(
	 *   "field" => array(
	 *     "min" => 0
	 *     "max" => 100
	 *	  )
	 * )
	 * </code>
	 * @return array
	 */
	public function getNotInRanges()
	{
		return $this->outRange;
	}
	/**
	 * Set filter by model id range
	 * @param int $min
	 * @param int $max
	 * @see getIdMax
	 * @see getIdMin
	 */
	public function setIdRange($min, $max)
	{
		$this->idMin = (int)$min;
		$this->idMax = (int)$max;
	}
	/**
	 * Get maximum id in range
	 * @return int
	 * @see getIdMax
	 * @see setIdRange
	 */
	public function getIdMax()
	{
		return $this->idMax;
	}
	/**
	 * Get minimum id in range
	 * @return int
	 * @see getIdMin
	 * @see setIdRange
	 */
	public function getIdMin()
	{
		return $this->idMin;
	}
	/**
	 * Check is id range setted
	 * @return bool
	 */
	public function getIsIdRangeSetted()
	{
		return is_int($this->idMax) && is_int($this->idMin);
	}
	/**
	 * Set group field and function. 
	 * $attribute is a string that contains group-by attribute name.
	 * $type is a constant that chooses a function applied to the attribute value in order to compute group-by key.
	 * $sort is a clause that controls how the groups will be sorted.
	 *
	 * @param string $attribute
	 * @param int $type defaults to GROUP_ATTR
	 * @param string $sort defaults to "@group desc"
	 * @see getGroupBy
	 * @see getGroupFunc
	 * @see getGroupSort
	 * @see getIsGroupSetted
	 */
	public function setGroup($attribute, $type = 4, $sort = "@group desc")
	{
		$this->groupBy = (string)$attribute;
		$this->groupFunc = (int)$type;
		$this->groupSort = (string)$sort;
	}
	/**
	 * Get group attribute
	 * @return string|null string if setted, else null
	 * @see setGroup
	 */
	public function getGroupBy()
	{
		return $this->groupBy;
	}
	/**
	 * Get group function type.
	 * 
	 * @return int|null int if setted, else null
	 * @see setGroup
	 */
	public function getGroupFunc()
	{
		return $this->groupFunc;
	}
	/**
	 * Get group sort string expression.
	 * 
	 * @return string|null string if setted, else null
	 * @see setGroup
	 */
	public function getGroupSort()
	{
		return $this->groupSort;
	}
	/**
	 * Check is grouping setted
	 * @return bool
	 * @see setGroup
	 */
	public function getIsGroupSetted()
	{
		return strlen($this->groupBy) > 0;
	}
	/**
	 * Check is limit setted.
	 * 
	 * @return bool true if is limited
	 */
	public function getIsLimited()
	{
		return (int)$this->limit > 0;
	}
	/**
	 * Sets offset into server-side result set ($offset) and amount of matches to return to client starting from
	 * that offset ($limit). Can additionally control maximum server-side result set size for current query
	 * ($max_matches) and the threshold amount of matches to stop searching at ($cutoff). All parameters must be
	 * non-negative integers.
	 * 
	 * It's identical in behavior to MySQL LIMIT clause. They instruct searchd
	 * to return at most $limit matches starting from match number $offset. The default offset and limit settings
	 * are 0 and 20, that is, to return first 20 matches.
	 *
	 *
	 * @param int $limit
	 * @param int $offset
	 * @return void
	 */
	public function setLimit($limit, $offset = 0)
	{
		$this->limit = (int)$limit;
		$this->offset = (int)$offset;
	}
	/**
	 * Set sort by attribute name. It's equivalents to set sortMode at extended mode
	 * and set sort string like as "id desc" 
	 *
	 * @param string $attributeName
	 * @param string $mode "asc" or "desc"
	 * @return void
	 */
	public function setOrder($attributeName, $mode = 'asc')
	{
		$this->sortMode = self::SORT_EXTENDED;
		$this->order = $attributeName ." ".$mode;
	}
}