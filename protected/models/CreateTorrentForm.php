<?php
class CreateTorrentForm extends CFormModel
{
	public $name;
	public $email;
	public $torrent;
	public $category;
	public $infoUrl;
	public $description;
	public $acceptRules;
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
			array("name", "length", "min" => 3, "max" => 32),
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
	
	public static function model($className = __CLASS__)
	{
		return new $className;
	}
	
	public function acceptedRules()
	{
		if ($this->acceptRules !== "1")
			$this->addError("acceptRules", "Вы не приняли условия работы с торрентом.");
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
}