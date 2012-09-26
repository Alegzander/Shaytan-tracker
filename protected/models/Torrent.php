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
	public $encoding;
	public $info = array();

    public $comments = array();
	public $peers = array(
				"seeders" => array(),
				"leachers" => array()				
			);
        public $downloaded = 0;
    public $approved = true;
    public $type;
    public $raiting;
    public $uploaded;
	
	const SORT_DESC = EMongoCriteria::SORT_DESC;
	const SORT_ASC = EMongoCriteria::SORT_ASC;

    const GOOD = 0;
    const TRUSTED = 1;
    const REFACTOR = 2;

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
				array("name, email, category, acceptRules", "required"),
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

    private function torrentFileAttributes()
    {
        return array(
            "announce",
            "announce-list",
            "encoding",
            "info",
        );
    }

    public function getTotalSize($raw = false)
    {
        $totalCount = 0;

        if (isset($this->info["files"]))
            foreach ($this->info["files"] as $file)
                $totalCount += $file["length"];
        else
            $totalCount = $this->info["length"];

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

    public function getById($id)
    {
        $criteria = $this->setCriteria();
        $criteria->_id = new MongoId($id);

        if ($this->count($criteria) > 0)
            return $this->find($criteria);
        else
            return null;
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

    private function normalizeName($rawName)
    {
        $normalName = $rawName;

        if (strpos($normalName, "-") !== FALSE)
        {
            $parts = explode("-", $normalName);
            $normalName = $parts[0];
            $normalName .= ucfirst($parts[1]);
        }

        $normalName = str_replace(" ", "_", $normalName);

        return $normalName;
    }
	
	public function parseTorrentFile($file, $meta = array(), $piece_length = 256)
	{
		if ($piece_length < 32 || $piece_length > 4096)
		{
			throw new CException(Yii::t("app", "Неверная длина куска, должен быть не менее 32 и не более 4096"));
		}
		
		if (is_string($meta))
            $meta =  array('announce' => $meta);
		
		if ( !$this->build($file, $piece_length * 1024))
            $meta = array_merge($meta, $this->decode($file));

		foreach( $meta as $key => $value)
		{
            $frontendKey = $this->normalizeName($key);

            /**
			 * @todo Определение находится ли на сайте гость или зарегистрированный пользователь
			 */
            if ($frontendKey == "announce" ||
                $frontendKey == "announceList"  ||
                $frontendKey == "publisher")
			{
				continue;
			}

            if ($frontendKey == "info")
            {
                if (isset($value["pieces"]))
                    $value["pieces"] = base64_encode($value["pieces"]);
            }

            if (isset($this->$frontendKey))
               $this->$frontendKey = $value;
		}

        $this->announce = Yii::app()->getParams()->baseUrl."/announce";

        array_push($this->announceList,
            Yii::app()->getParams()->baseUrl."/announce",
            "http://re-tracker.uz/announce"
        );

        if (strlen($this->encoding) == 0)
            $this->encoding = "UTF-8";

        $this->uploaded = time();
	}

    public function getFile()
    {
        $fileParameters = $this->torrentFileAttributes();
        $fileData = array();

        foreach ($fileParameters as $fileParameter)
        {
            $fileData[$fileParameter] = $this->{$this->normalizeName($fileParameter)};
        }

        $fileData["info"]["pieces"] = base64_decode($fileData["info"]["pieces"]);
        $fileData["publisher"] = Yii::app()->getParams()->domain;
        $fileData["publisher-url"] = Yii::app()->getParams()->baseUrl."/torrent/download/id/".$this->_id;

        return $this->encode( $fileData );
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
    		$this->errors[] = new CException(Yii::t("app", "Число отсутствует."));
    
    	if ($this->char($data) == '-')
    		$start++;
    
    	if (substr($data, $start, 1) == '0' && ($start != 0 || $end > $start + 1))
    		$this->errors[] = new CException(Yii::t("app", "Число начинается с нуля."));
    
    	if (!ctype_digit(substr($data, $start, $end)))
    		$this->errors[] = new CException(Yii::t("app", "В числе присутствуют не численные значения"));
    
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

    /**** Encode BitTorrent ****/

    /** Encode torrent data
     * @param mixed data to encode
     * @return string torrent encoded data
     */
    private function encode ( $mixed )
    {
        switch ( gettype( $mixed ) )
        {
            case 'integer':
            case 'double':
                return $this->encode_integer( $mixed );
            case 'object':
                $mixed = (array) $mixed; //Bugfix by W-Shadow. Objects can't be ksort'ed anyway (see encode_array()).
            case 'array':
                return $this->encode_array( $mixed );
            default:
                return $this->encode_string( (string) $mixed );
        }
    }

    /** Encode torrent string
     * @param string string to encode
     * @return string encoded string
     */
    private function encode_string ( $string )
    {
        return strlen( $string ) . ':' . $string;
    }

    /** Encode torrent integer
     * @param integer integer to encode
     * @return string encoded integer
     */
    private function encode_integer ( $integer )
    {
        return 'i' . $integer . 'e';
    }

    /** Encode torrent dictionary or list
     * @param array array to encode
     * @return string encoded dictionary or list
     */
    private function encode_array ( $array )
    {
        if ( self::is_list( (array) $array ) )
        {
            $return = 'l';
            foreach ( $array as $value ) {
                $return .= self::encode( $value );
            }
        }
        else
        {
            ksort( $array, SORT_STRING );
            $return = 'd';
            foreach ( $array as $key => $value )
            {
                $return .= self::encode( strval( $key ) ) . self::encode( $value );
            }
        }
        return $return . 'e';
    }

    /** Helper to test if an array is a list
     * @param array array to test
     * @return boolean is the array a list
     */
    private function is_list ( $array )
    {
        foreach ( array_keys( $array ) as $key )
        {
            if ( ! is_int( $key ) )
            {
                return false;
            }
        }

        return true;
    }
}