<?php

/**
 * Description of requiredIf
 *
 * @author Faisal Kaleem
 */
class uniqueIf extends CValidator {

  /**
   *
   * @var mixed
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
      throw new CException(Yii::t('yii', 'The "field" property must be array because array of key values parameters.'));
    }
    $unique_validator = new CUniqueValidator();
    $is_unique = $unique_validator->validateAttribute($object, $attribute);
    $object->clearErrors($attribute);
    
    $true_count = 0;
    foreach($this->field as $f => $v) {
      if ($object->{$f} == $v) {
        $true_count++;
      }
    }
    
    if ($true_count==count($this->field) && !$is_unique) {
      $message = $this->message !== null ? $this->message : Yii::t('yii', '{attribute} is duplicate.');
      $this->addError($object, $attribute, $message);
    }
  }
  
}
