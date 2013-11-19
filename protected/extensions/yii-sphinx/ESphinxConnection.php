<?php
/**
 * This file contains filesource of class ESphinxConnection
 */

/**
 * Class ESphinxConnection
 *
 * @author mitallast <mitallast@gmail.com>
 * @version 0.1
 * @since 0.1
 * @package sphinx
 */
class ESphinxConnection extends EBaseSphinxConnection
{
	/**
	 * Instance of SphinxClient
	 * @var SphinxClient $sphinxClient
	 */
	private $sphinxClient;
	/**
	 * Flag check is client connected
	 * @var bool defaults false
	 */
	private $isConnected = false;
	/**
	 * Constructor initialise component variables. 
	 */
	public function __construct()
	{
		$this->sphinxClient = new SphinxClient();
		$this->sphinxClient->SetArrayResult(true);
	}

	public function setServer(array $parameters = array())
	{
		if(!isset ($parameters[0]))
			$parameters[0] = 'localhost';
		if(!isset ($parameters[1]))
			$parameters[1] = 3386;

		$this->sphinxClient->SetServer($parameters[0],$parameters[1]);
	}

	public function openConnection()
	{
		if($this->isConnected)
			throw new ESphinxException ("Sphinx client is already opened");
		else
		{
			$this->sphinxClient->Open();

			if($this->sphinxClient->IsConnectError())
				throw new ESphinxException("Sphinx exception: ".$this->sphinxClient->GetLastError());
			
			$this->isConnected = true;
		}
	}

	public function closeConnection()
	{
		if(!$this->isConnected)
			throw new ESphinxException ("Sphinx client is already closed");
		else
		{
			$this->sphinxClient->Open();
			$this->isConnected = false;
		}
	}

	public function getIsConnected()
	{
		return $this->isConnected;
	}

	public function setConnectionTimeout($timeout)
	{
		$this->sphinxClient->SetConnectTimeout((int)$timeout);
	}

	public function setQueryTimeout( $timeout )
	{
		$this->sphinxClient->SetMaxQueryTime((int)$timeout);
	}

	public function createExcerts(array $docs, $index, $words, array $opts = array())
	{
		return $this->sphinxClient->BuildExcerpts($docs, $index, $words, $opts);
	}

	public function createKeywords($query, $index, $hits = false)
	{
		return $this->sphinxClient->BuildKeywords($query, $index, $hits);
	}
	
	public function escape($string)
	{
		return $this->sphinxClient->EscapeString((string)$string);
	}
	
	public function update($index, array $attrs, array $values, $mfa=false)
	{
		return $this->sphinxClient->UpdateAttributes($index, $attrs, $values, $mfa);
	}

	public function executeQuery(ESphinxQuery $query)
	{
		$this->resetClient();
	    $this->applyQuery($query);
	    $results = $this->execute();
	    return $results[0];
	}

	public function executeQueries(array $queries)
	{
		$this->resetClient();
	    foreach ($queries as $query)
		    $this->applyQuery($query);

	    return $this->execute();
	}

	protected function applyQuery(ESphinxQuery $query)
	{
		$this->applyCriteria($query->getCriteria());
	    $this->sphinxClient->AddQuery($query->getText(), $query->getIndexes());
	}

	protected function applyCriteria(ESphinxCriteria $criteria)
	{
		$this->applyMatchMode($criteria->matchMode);
	    $this->applyRankMode($criteria->rankMode);
	    $this->applySortMode($criteria->sortMode);
	    // apply select
		if(strlen($criteria->select))
			$this->sphinxClient->SetSelect($criteria->select);
		// apply limit
		if($criteria->getIsLimited())
			$this->sphinxClient->SetLimits(
				$criteria->offset,
				$criteria->limit,
				$criteria->max_matches,
				$criteria->cutoff
			);
	    // apply group
		if($criteria->getIsGroupSetted())
			$this->sphinxClient->SetGroupBy($criteria->getGroupBy(), $criteria->getGroupFunc());

		// apply id range
		if($criteria->getIsIdRangeSetted())
			$this->sphinxClient->SetIDRange(
				$criteria->getIdMin(),
				$criteria->getIdMax()
			);
		// apply weights
	    $this->applyFieldWeights($criteria->getFieldWeights());
		$this->applyIndexWeights($criteria->getIndexWeights());
		// apply filters
	    $this->applyFilters($criteria->getInConditions());
	    $this->applyFilters($criteria->getNotInConditions(), true);
		// apply ranges
	    $this->applyRanges($criteria->getInRanges());
	    $this->applyRanges($criteria->getNotInRanges(),true);
	}

	protected function applyRanges(array $ranges, $exclude=false)
	{
		$exclude = (boolean)$exclude;
	    foreach($ranges as $field => $range)
	    {
		    $isFloat = is_float($range['max']) || is_float($range['min']);
	        if($isFloat)
		        $this->sphinxClient->SetFilterRange(
			        $field,
			        (float)$range['min'],
			        (float)$range['max'],
			        $exclude
		        );
	        else
		        $this->sphinxClient->SetFilterRange(
			        $field,
			        (int)$range['min'],
			        (int)$range['max'],
			        $exclude
		        );
	    }
	}

	protected function applyFilters(array $conditions, $exclude = false)
	{
		$exclude = (boolean)$exclude;
		foreach	($conditions as $field => $values)
		{
			$this->sphinxClient->SetFilter($field, $values, $exclude);
		}
	}

	protected function applyIndexWeights(array $weights)
	{
		foreach( $weights as $index => $weight )
			$weights[$index] = (int)$weight;

	    $this->sphinxClient->SetIndexWeights($weights);
	}

	protected function applyFieldWeights(array $weights)
	{
		foreach( $weights as $field => $weight )
			$weights[$field] = (int)$weight;
		
	    $this->sphinxClient->SetFieldWeights($weights);
	}

	protected function applySortMode($mode)
	{
		$mode = (int)$mode;
	    if(in_array($mode,ESphinxCriteria::$sortModes))
		    $this->sphinxClient->SetSortMode($mode);
	    else
		    throw new ESphinxException("Search mode {$mode} is undefined");
	}

	protected function applyMatchMode($mode)
	{
		$mode = (int)$mode;
		if(in_array($mode, ESphinxCriteria::$matchModes))
			$this->sphinxClient->SetMatchMode($mode);
	    else
		    throw new ESphinxException("Match mode {$mode} is not defined");
	}

	protected function applyRankMode($mode)
	{
		$mode = (int)$mode;
	    if(in_array($mode, ESphinxCriteria::$rankModes))
		    $this->sphinxClient->SetRankingMode($mode);
	}

	protected function resetClient()
	{
		$this->sphinxClient->ResetFilters();
	    $this->sphinxClient->ResetGroupBy();
	    $this->sphinxClient->ResetOverrides();
	    $this->sphinxClient->SetLimits(0, 20);
	    $this->sphinxClient->SetArrayResult(true);
	    $this->sphinxClient->SetFieldWeights(array());
	    $this->sphinxClient->SetIDRange(0,0);
	    $this->sphinxClient->SetIndexWeights(array());
	    $this->sphinxClient->SetMatchMode(SPH_MATCH_EXTENDED2);
	    $this->sphinxClient->SetRankingMode(SPH_RANK_NONE);
	    $this->sphinxClient->SetSortMode(SPH_SORT_RELEVANCE, "");
	    $this->sphinxClient->SetSelect("*");
	}

	protected function execute()
	{
		$sph = $this->sphinxClient->RunQueries();

		if( $error = $this->sphinxClient->GetLastError() )
		    throw new ESphinxException($error);
	    if( $error = $this->sphinxClient->GetLastWarning() )
		    throw new ESphinxException($error);
		if( !is_array($sph) )
			throw new ESphinxException("Sphinx client returns result not array");
		
	    $results = array();
	    foreach($sph as $result)
		{
			if(isset($result['error']) && strlen($result['error']))
				throw new ESphinxException($result['error']);
		    $results[] = new ESphinxResult($result);
		}
	    return $results;
	}
}
