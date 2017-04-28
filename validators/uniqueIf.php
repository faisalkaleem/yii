<?php

/**
 * Conditionally required attribute.
 * @uses CUniqueValidator
 *
 * @author Faisal Kaleem
 */
class uniqueIf extends CValidator {

  /**
   * Key value pair array
   * Keys will be treated as attribute names.
   * 
   * @var array
   */
  public $field;

  /**
   * Validates the attribute of the object.
   * If there is any error, the error message is added to the object.
   * @param CModel $object the object being validated
   * @param string $attribute the attribute being validated
   * @uses CUniqueValidator
   * @throws CException if given {@link range} is not an array
   */
  protected function validateAttribute($object, $attribute) {
    if(!is_array($this->field)) {
      throw new CException(Yii::t('yii', 'The "field" property must be an array of key values parameters.'));
    }
    
    $true_count = 0;
    foreach($this->field as $f => $v) {
      if ($object->{$f} == $v) {
        $true_count++;
      }
    }
    if ($true_count==count($this->field)) {
      $previous_error_count = count($object->getErrors($attribute));
      $unique_validator = new CUniqueValidator();
      $unique_validator->validateAttribute($object, $attribute);
      if($previous_error_count < count($object->getErrors($attribute))) {
        $message = $this->message !== null ? $this->message : Yii::t('yii', '{attribute} is duplicate.');
        $this->addError($object, $attribute, $message);
      }
    }
  }
  
}
