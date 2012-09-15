<?php

/**
 * This is the MongoDB Document model class based on table "torrent".
 */
class Torrent extends EMongoDocument
{
	public $name;
	public $email;
	public $torrent;
	public $category;
	public $infoUrl;
	public $description;
	public $acceptRules;
	
	public $announce;
	public $announceList = array();
	public $comment = array();
	public $created_by;
	public $creation_date;
	public $encoding;
	public $info = array();
	public $publisher;
	public $publisherUrl;
	
	public $checked = false;
	public $peers = array(
				"seeders" => array(),
				"leachers" => array()				
			);
	
	private $criteria;
	
	const SORT_DESC = EMongoCriteria::SORT_DESC;
	const SORT_ASC = EMongoCriteria::SORT_ASC;
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		$rawCategories = Yii::app()->getParams()->categories;
		$categories = array();
	
		foreach ($rawCategories as $groupName => $group)
		{
			foreach ($group as $item)
			{
				array_push($categories, $groupName."-".$item);
			}
		}
	
		sort($categories);
	
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array("category, acceptRules", "required"),
				array("name", "length", "allowEmpty" => false, "min" => 3, "max" => 32),
				array("email", "email", "allowEmpty" => false, "checkMX" => false, "checkPort" => false),
				array(
						"torrent",
						"file",
						"allowEmpty" => false,
						"maxSize" => 1048576,
						"tooLarge" => Yii::t("app", "Размер загружаемого торрент файла больше 1 МБ. Удостоверьтесь что вы загружаете торрент файл."),
						"tooMany" => Yii::t("app", "Торрент-файл может быть только один."),
						"types" => array("torrent", "txt"),
						"wrongType" => Yii::t("app", "Не верное расширение файла (не torrent и не txt). Загружаете ли вы торрент файл?")
				),
				array(
						"category",
						"in",
						"range" => $categories,
						"strict" => true
				),
				array(
						"acceptRules",
						"acceptedRules"
				)
		);
	}

	public function acceptedRules()
	{
		if ($this->acceptRules !== "1")
			$this->addError("acceptRules", "Вы не приняли условия работы с торрентом.");
	}

    public function getTotalSize($raw = false)
    {
        $totalCount = 0;
        $result = "";

        if (isset($this->info["files"]))
            foreach ($this->info["files"] as $file)
                $totalCount += $file["length"];

        $result = $totalCount;

        if (!$raw)
        {
            $index = 0;
            $mesure = array(
                Yii::t("app", "Б"),
                Yii::t("app", "КБ"),
                Yii::t("app", "МБ"),
                Yii::t("app", "ГБ"),
                Yii::t("app", "ТБ"),
                Yii::t("app", "ПБ"),
            );

            while (round($totalCount/1024, 2) > 1)
            {
                $totalCount /= 1024;
                $index++;
            }

            $result = round($totalCount, 2)." ".$mesure[$index];

            return $result;
        }
    }
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
				"name"			=> Yii::t("app", "Название торрента"),
				"email"			=> Yii::t("app", "Электронная почта"),
				"torrent"		=> Yii::t("app", "Торрент-файл"),
				"category"		=> Yii::t("app", "Категория"),
				"infoUrl"		=> Yii::t("app", "Дополнительная информация"),
				"description"	=> Yii::t("app", "Описание"),
				"acceptRules"	=> Yii::t("app", "о принятии правил")
		);
	}

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
	
	public function setCriteria()
	{
		$this->criteria = new EMongoCriteria();
		
		return $this->criteria;
	}
	
	public function parseTorrentFile($file, $meta = array(), $piece_length = 256)
	{
		if ($piece_length < 32 || $piece_length > 4096)
		{
			throw new Exception(__('Invalid piece lenth, must be between 32 and 4096'));
		}
		
		if (is_string($meta))
		{
			$meta =  array('announce' => $meta);
		}
		
		if ( $this->build($file, $piece_length * 1024))
		{
			$this->touch();
		}
		else
		{
			$meta = array_merge($meta, $this->decode($file));
		}

		/**
		 * @todo Make full data check. Replacing origin announce and announce-list
		 */	
		foreach( $meta as $key => $value )
		{
			$frontendKey = $key;
			
			if (strpos($frontendKey, "-") !== FALSE)
			{
				$parts = explode("-", $frontendKey);
				$frontendKey = $parts[0];
				$frontendKey .= ucfirst($parts[1]);
			}
			
			$frontendKey = str_replace(" ", "_", $frontendKey);
			
			/**
			 * @todo Определение находится ли на сайте гость или зарегистрированный пользователь
			 */
			
			if ($frontendKey == "info")
			{
				$value["pieces"] = base64_encode($value["pieces"]);
			}
			else if ($frontendKey == "announce")
			{
				$value = Yii::app()->getParams()->baseUrl."/announce";
			}
			else if ($frontendKey == "announceList")
			{
				$value = array();
				array_push($value, Yii::app()->getParams()->baseUrl."/announce", "http://re-tracker.uz/announce");
			}
			else if ($frontendKey == "publisher")
			{
				$rawPublisher = Yii::app()->getParams()->baseUrl;
				$rawPublisher = str_replace("http://", "", $rawPublisher);
				
				if ($rawPublisher[(count($rawPublisher) - 1)] == "/")
					$rawPublisher = substr($rawPublisher, 0, (count($rawPublisher) - 1));
				
				$value = $rawPublisher;
			}
			else if ($frontendKey == "publisherUrl")
			{
				$value = "";
			}
			
			$this->$frontendKey = $value;
		}
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
    		$this->errors[] = new Exception('Invalid string length, leading zero');
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
    		$this->errors[] = new Exception('Empty integer');
    
    	if ($this->char($data) == '-')
    		$start++;
    
    	if (substr($data, $start, 1) == '0' && ($start != 0 || $end > $start + 1))
    		$this->errors[] = new Exception('Leading zero in integer');
    
    	if (!ctype_digit(substr($data, $start, $end)))
    		$this->errors[] = new Exception('Non-digit characters in integer');
    
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
    
    public function __destruct()
    {
    	
    }
}