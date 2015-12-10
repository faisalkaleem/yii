<?php

/**
 * Description of requiredIf
 *
 * @author Faisal Kaleem
 */
class existInDB extends CValidator {
  /**
   *
   * @var string 
   */
  public $modelName;
  /**
   *
   * @var string
   */
  public $attributeName;
  /**
   *
   * @var boolean
   */
  public $allowEmpty = true;
  /**
   *
   * @var Array|CDbCriteria
   */
  public $criteria = array();

  /**
   * Validates the attribute of the object.
   * If there is any error, the error message is added to the object.
   * @param CModel $object the object being validated
   * @param string $attribute the attribute being validated
   * @throws CException if given {@link range} is not an array
   */
  protected function validateAttribute($object, $attribute) {
    $value = $object->$attribute;
    if ($this->allowEmpty && $this->isEmpty($value))
      return;

    $count = 1;
    if (is_array($value)) {
      $count = count($value);
    }

    $modelName = $this->modelName === null ? get_class($object) : Yii::import($this->modelName);
    
    $attributeName = $this->attributeName === null ? $attribute : $this->attributeName;
    $finder = CActiveRecord::model($modelName);
    $table = $finder->getTableSchema();
    if (($column = $table->getColumn($attributeName)) === null)
      throw new CException(Yii::t('yii', 'Table "{table}" does not have a column named "{column}".', array('{column}' => $attributeName, '{table}' => $table->name)));

    $columnName = $column->rawName;
    $criteria = new CDbCriteria();
    if ($this->criteria !== array())
      $criteria->mergeWith($this->criteria);
    $tableAlias = empty($criteria->alias) ? $finder->getTableAlias(true) : $criteria->alias;
    
    if(is_array($value)) {
        $criteria->addInCondition("{$tableAlias}.{$columnName}", $value);
    } else {
      $valueParamName = CDbCriteria::PARAM_PREFIX.CDbCriteria::$paramCount++;
      $criteria->addCondition("{$tableAlias}.{$columnName}={$valueParamName}");
      $criteria->params[$valueParamName] = $value;
    }
    
    if(!($finder->count($criteria)==$count)) {
      $message = $this->message !== null ? $this->message : Yii::t('yii', 'Some or all selected {attribute} do not exist.');
      $this->addError($object, $attribute, $message);
    }
  }
}
