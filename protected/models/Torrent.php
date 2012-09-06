<?php

/**
 * This is the MongoDB Document model class based on table "torrent".
 */
class Torrent extends EMongoDocument
{
	private $announce = array();
	private $name;
	private $piece_length = array();
	private $pieces = array();
	private $length;
	private $files = array();

	/**
	 * Returns the static model of the specified AR class.
	 * @return Torrent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * returns the primary key field for this model
	 */
	public function primaryKey()
	{
		return NULL;
	}

	/**
	 * @return string the associated collection name
	 */
	public function getCollectionName()
	{
		return 'torrents';
	}
	
	public function setTorrentFile($file)
	{
		if ($piece_length < 32 || $piece_length > 4096)
		{
			throw new Exception(__('Invalid piece lenth, must be between 32 and 4096'));
		}
		
		if (is_string($meta))
		{
			$meta =  array('announce' => $meta);
		}
		
		if ( $this->build($data, $piece_length * 1024))
		{
			$this->touch();
		}
		else
		{
			$meta = array_merge($meta, $this->decode($data));
		}
		
		die ($meta);
		
		/*foreach( $meta as $key => $value )
		 {
		$this->{$key} = $value;
		}*/
		
		return $this;
	}
	
	private function build ($data, $piece_length) 
    {
    	if (is_null( $data ))
    		return false;
    	elseif (is_array($data) && $this->is_list($data))
    		return $this->info = $this->files( $data, $piece_length );
    	elseif (is_dir($data))
    		return $this->info = $this->folder($data, $piece_length);
    	elseif (is_file($data) && pathinfo($data, PATHINFO_EXTENSION) != 'torrent')
    		return $this->info = $this->file($data, $piece_length);
    	else
			return false;
    }
    
    private function touch () {
    	$this->{'created by'}       = 'Anime Tracker tracker.anime.uz';
    	$this->{'creation date'}    = time();
    }
    
    /*** Decode BitTorrent ***/
    
    /** Decode torrent data or file
     * @param string data or file path to decode
     * @return array decoded torrent data
     */
    private function decode ($string)
    {
    	$data = is_file($string) ?
    	file_get_contents($string) :
    	$string;
    
    	return $this->decode_data($data);
    }
    
    /** Decode torrent data
     * @param string data to decode
     * @return array decoded torrent data
     */
    private function decode_data (&$data)
    {
    	switch($this->char($data))
    	{
    		case 'i':
    			$data = substr($data, 1);
    			return $this->decode_integer($data);
    		case 'l':
    			$data = substr($data, 1);
    			return $this->decode_list($data);
    		case 'd':
    			$data = substr($data, 1);
    			return $this->decode_dictionary($data);
    		default:
    			return $this->decode_string($data);
    	}
    }
    
    /** Decode torrent dictionary
     * @param string data to decode
     * @return array decoded dictionary
     */
    private function decode_dictionary (&$data)
    {
    	$dictionary = array();
    	$previous = null;
    	while (($char = $this->char($data)) != 'e' )
    	{
    		if ($char === false)
    		{
    			throw new Exception(__('Unterminated dictionary'));
    		}
    
    		if (!ctype_digit($char))
    		{
    			throw new Exception(__('Invalid dictionary key'));
    		}
    
    		$key = $this->decode_string($data);
    
    		if (isset($dictionary[$key]))
    		{
    			throw new Exception(__('Duplicate dictionary key'));
    		}
    
    		if ($key < $previous)
    		{
    			throw new Exception(__('Missorted dictionary key'));
    		}
    
    		$dictionary[$key] = $this->decode_data($data);
    		$previous = $key;
    	}
    
    	$data = substr($data, 1);
    
    	return $dictionary;
    }
    
    /** Decode torrent list
     * @param string data to decode
     * @return array decoded list
     */
    private function decode_list (&$data)
    {
    	$list = array();
    	while (($char = $this->char($data)) != 'e' )
    	{
    		if ($char === false)
    		{
    			throw new Exception('Unterminated list');
    		}
    
    		$list[] = $this->decode_data($data);
    	}
    
    	$data = substr($data, 1);
    
    	return $list;
    }
    
    /** Decode torrent string
     * @param string data to decode
     * @return string decoded string
     */
    private function decode_string (&$data)
    {
    	if ($this->char($data) === '0' && substr($data, 1, 1) != ':')
    	{
    		$this->$errors[] = new Exception('Invalid string length, leading zero');
    	}
    
    	if (!$colon = @strpos( $data, ':' ))
    	{
    		throw new Exception('Invalid string length, colon not found');
    	}
    
    	$length = intval(substr($data, 0, $colon));
    
    	if ($length + $colon + 1 > strlen($data))
    	{
    		throw new Exception('Invalid string, input too short for string length');
    	}
    
    	$string = substr($data, $colon + 1, $length);
    	$data = substr($data, $colon + $length + 1);
    
    	return $string;
    }
    
    /** Decode torrent integer
     * @param string data to decode
     * @return integer decoded integer
     */
    private function decode_integer (&$data)
    {
    	$start  = 0;
    	$end    = strpos($data, 'e');
    
    	if ($end === 0)
    		$this->$errors[] = new Exception('Empty integer');
    
    	if ($this->char($data) == '-')
    		$start++;
    
    	if (substr($data, $start, 1) == '0' && ($start != 0 || $end > $start + 1))
    		$this->$errors[] = new Exception('Leading zero in integer');
    
    	if (!ctype_digit(substr($data, $start, $end)))
    		$this->$errors[] = new Exception('Non-digit characters in integer');
    
    	$integer = substr($data, 0, $end);
    	$data = substr($data, $end + 1);
    
    	return $integer + 0;
    }
    
    /** Helper to return the first char of encoded data
     * @param string encoded data
     * @return string|boolean first char of encoded data or false if empty data
     */
    private function char ($data)
    {
    	return empty($data) ?
    	false :
    	substr($data, 0, 1);
    }
}