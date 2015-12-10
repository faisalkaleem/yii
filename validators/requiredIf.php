<?php

/**
 * Description of requiredIf
 *
 * @author Faisal Kaleem
 */
class requiredIf extends CValidator {

  /**
   *
   * @var mixed
   */
  public $field;
  
  /**
   *
   * @var mixed
   */
  public $value = null;
  
  /**
   * Currently not used.
   * @var boolean
   */
  public $not = false;
  
  /**
   *
   * @var string
   */
  public $required_value = null;

  /**
   * Validates the attribute of the object.
   * If there is any error, the error message is added to the object.
   * @param CModel $object the object being validated
   * @param string $attribute the attribute being validated
   * @throws CException if given {@link range} is not an array
   */
  protected function validateAttribute($object, $attribute) {
    $field = $this->field;

    $validated = true;
    if(!is_array($this->field)) {
      throw new CException(Yii::t('yii', 'The "value" property must be array because array give in "field" parameter.'));
    }
    $true_count = 0;
    
    if($this->required_value) {
      $is_attribute_value_invalid = $object->$attribute != $this->required_value;
    } else {
      $is_attribute_value_invalid = empty($object->$attribute);
    }
    foreach($this->field as $f => $v) {
      if ($is_attribute_value_invalid && $object->$f == $v) {
        $true_count++;
      }
    }
    
    if ($true_count==count($this->field)) {
      $message = $this->message !== null ? $this->message : Yii::t('yii', '{attribute} is required.');
      $this->addError($object, $attribute, $message);
    }
  }

  /**
   * Returns the JavaScript needed for performing client-side validation.
   * @param CModel $object the data object being validated
   * @param string $attribute the name of the attribute to be validated.
   * @throws CException if given {@link range} is not an array
   * @return string the client-side validation script.
   * @see CActiveForm::enableClientValidation
   * @since 1.1.7
   */
//  public function clientValidateAttribute($object, $attribute) {
//
//    if (($message = $this->message) === null) $message = Yii::t('yii', '{attribute} is required.');
//    
//    $message = strtr($message, array(
//      '{attribute}' => $object->getAttributeLabel($attribute),
//    ));
//
//    $field = $this->field;
//    
//    return "
//if(value == '' && jQuery(".$object->$field ." == '".$this->value."') {
//	messages.push(" . CJSON::encode($message) . ");
//}
//";
//    return $return;
//  }

  //put your code here
}
