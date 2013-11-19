<?php
/**
 * File contains class EDbSphinxConnection
 */

/**
 * Class EDbSphinxConnection
 */
class EDbSphinxConnection extends EBaseSphinxConnection
{
	public $host = 'localhost';
	public $port = 3306;

	/**
	 * @var CDbConnection
	 */
	private $db;
	private $connectionTimeout;
	private $queryTimeout;

    public function init(){
        parent::init();
        $this->setServer();
        $this->openConnection();
    }

	function setConnectionTimeout($timeout)
	{
		$this->connectionTimeout = (int)$timeout;
	}

	public function setQueryTimeout($timeout)
	{
		$this->queryTimeout = (int)$timeout;
	}

	public function getDbConnection()
	{
		if(!$this->getIsConnected())
		{
			throw new ESphinxException('Connection is not opened');
		}
		return $this->db;
	}

	public function openConnection()
	{
		$dsn = "mysql:host={$this->host};port={$this->port};";
		$this->db = new CDbConnection($dsn);
		$this->db->setAttribute(PDO::ATTR_TIMEOUT, $this->connectionTimeout);
		$this->db->setActive(true);
	}

	public function closeConnection()
	{
		$this->getDbConnection()->setActive(false);
	}
	
	public function setServer(array $parameters = array())
	{
		if(!isset ($parameters[0]))
			$parameters[0] = $this->host;
		if(!isset ($parameters[1]))
			$parameters[1] = $this->port;

		$this->host = $parameters[0];
		$this->port = $parameters[1];
	}

	public function getIsConnected()
	{
		return $this->db && $this->db->getActive();
	}

	public function createExcerts(array $docs, $index, $words, array $opts =array())
	{
		$excerts = array();
		$options = "";
		$optionParams = array();
		foreach($opts as $name => $value)
		{
			$options .= ", :{$name} as {$name}";
			$optionParams[":{$name}"] = $value;
		}

		foreach($docs as $data)
		{
			$query = $this->db->createCommand("CALL SNIPPETS(:data, :index, :words {$options} )");
			$query->params = $optionParams + array(
				":data", $data,
				":index", $index,
				":words", $words,
			);
			$excerts[] = $query->queryAll();
		}

		return $excerts;
	}

	public function createKeywords($query, $index, $hits = false)
	{
		$command = $this->db->createCommand('CALL KEYWORDS(:query, :index)');
		// @todo check for hits
		$command->params = array(
			':query'=>$query,
			':index'=>$index,
		);
		return $command->queryAll();
	}

	public function escape($string)
	{
		$this->db->quoteValue($string);
	}

	public function executeQueries(array $queries)
	{
		$results = array();
		foreach($queries as $query)
			$results[] = $this->executeQuery($query);
		return $results;
	}

	public function executeQuery(ESphinxQuery $query)
	{
		$cb = $this->db->getCommandBuilder();
		$command = $cb->createFindCommand(
			$query->getIndexes(),
			$this->createDbCriteria($query)
		);
		$meta = $this->createMeta($command);
		return new ESphinxResult($meta);
	}

	private function createMeta(CDbCommand $command)
	{
		$matches = $command->queryAll();
		$metaInfo = $this->db->createCommand("SHOW META")->queryAll();
		$meta = array();
		foreach($metaInfo as $item)
		{
			list($name, $value) = array_values($item);
			$meta[$name] = $value;
		}
		$meta['matches'] = $matches;
		return $meta;
	}

	/**
	 * @param  $query
	 * @return CDbCriteria
	 */
	private function createDbCriteria(ESphinxQuery $query)
	{
		$criteria = new CDbCriteria();
		$this->applySelect($criteria, $query);
		$this->applyCondition($criteria, $query);
		$this->applyGroup($criteria, $query);
		$this->applyOrder($criteria, $query);
		$this->applyLimit($criteria, $query);

		return $criteria;
	}

	private function applyLimit(CDbCriteria $criteria, ESphinxQuery $query)
	{
		$queryCriteria = $query->getCriteria();
		$criteria->limit = $queryCriteria->limit;
		$criteria->offset = $queryCriteria->offset;
	}

	private function applyOrder(CDbCriteria $criteria, ESphinxQuery $query)
	{
		$queryCriteria = $query->getCriteria();
		if($queryCriteria->order)
			$criteria->order = $queryCriteria->order;
	}

	private function applyGroup(CDbCriteria $criteria, ESphinxQuery $query)
	{
		$queryCriteria = $query->getCriteria();
		if($queryCriteria->getGroupBy())
			$criteria->group = $queryCriteria->getGroupBy();
	}

	private function applyCondition(CDbCriteria $criteria, ESphinxQuery $query)
	{
		$queryCriteria = $query->getCriteria();

		if(strlen($query->getText()))
		{
			$criteria->addCondition('MATCH(:match)');
			$criteria->params[':match'] = $query->getText();
		}

		foreach($queryCriteria->getInConditions() as $name => $values)
			$criteria->addInCondition($name, $values);
		foreach($queryCriteria->getNotInConditions() as $name => $values)
			$criteria->addNotInCondition($name, $values);
		foreach($queryCriteria->getInRanges() as $name => $range)
			$criteria->addBetweenCondition($name, $range['min'], $range['max']);
		foreach($queryCriteria->getNotInRanges() as $name => $range)
		{
			$criteria->addCondition("{$name} NOT BETWEEN ? AND ?");
			$criteria->params[] = $range['min'];
			$criteria->params[] = $range['max'];
		}
		if($queryCriteria->getIdMax())
		{
			$criteria->addCondition('id <= :maxid');
			$criteria->params[':maxid'] = $queryCriteria->getIdMax();
		}
		if($queryCriteria->getIdMin())
		{
			$criteria->addCondition('id <= :minid');
			$criteria->params[':minid'] = $queryCriteria->getIdMin();
		}
	}

	private function applySelect(CDbCriteria $criteria, ESphinxQuery $query)
	{
		$criteria->select = $query->getCriteria()->select;
	}
}