<?php
/**
 * User: alegz
 * Date: 10/20/13
 * Time: 3:56 AM
 */

/**
 * Class EMongoIntegerValidators
 */
class EMongoIntegerValidator extends CValidator {
    const INT32 = 32;
    const INT64 = 64;

    public $allowEmpty = true;
    /**
     * @var int Integer type. Can be 32 or 64. You can use validators constants.
     * Default to 32 bit integer.
     */
    public $type = self::INT32;

    protected function validateAttribute($model, $attribute){
        $value = $model->{$attribute};

        if (intval($this->type) !== static::INT32 && intval($this->type) !== static::INT64){
            throw new CException(\Yii::t('error', 'Invalid type set. Expects to be integer with value 32 or 64, "{value}" is given', array(
                '{value}' => $this->type
            )));
        }

        if ($this->skipOnError && $model->hasErrors())
            return;

        if (count($this->on) > 0 && !in_array($model->getScenario(), $this->on))
            return;

        if (count($this->except) > 0 && in_array($model->getScenario(), $this->except))
            return;

        if ($this->allowEmpty && $this->isEmpty($value))
            return;

        if (is_object($value) && $this->type === static::INT32 && $value instanceof MongoInt32)
            return;

        if (is_object($value) && $this->type === static::INT64 && $value instanceof MongoInt64)
            return;

        if (is_string($value) && is_numeric($value) || is_int($value)){
            $model->{$attribute} = $this->type === static::INT32?new MongoInt32($value):new MongoInt64($value);
        } else if (is_object($value)) {
            if ($this->isEmpty($value))
                $this->message = \Yii::t('app', 'Attribute "{attribute}" should be string or integer or instance of MongoInt32 or MongoInt64 based on type parameter, instance of "{class}" is given.',
                array(
                    '{class}' => get_class($value),
                    '{attribute}' => $attribute
                ));
        } else {
            if ($this->isEmpty($this->message, true))
                $this->message = \Yii::app('app', 'Attribute "{attribute}" should be string or integer or instance of MongoInt32 or MongoInt64 based on type parameter, "{type}" is given', array(
                    '{type}' => gettype($model->{$attribute}),
                    '{attribute}' => $attribute
                ));
        }
    }
}