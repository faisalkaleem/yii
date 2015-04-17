<?php

/**
 * Description of requiredIf
 *
 * @author Faisal Kaleem
 */
class requiredIf extends CValidator {

  public $value = null;
  public $field;
  public $not = false;

  /**
   * Validates the attribute of the object.
   * If there is any error, the error message is added to the object.
   * @param CModel $object the object being validated
   * @param string $attribute the attribute being validated
   * @throws CException if given {@link range} is not an array
   */
  protected function validateAttribute($object, $attribute) {
    $field = $this->field;
    $result = false;

    if (empty($object->$attribute) && $object->$field == $this->value) {
      $message = $this->message !== null ? $this->message : Yii::t('yii', '{attribute} is required.');
    } else {
      $result = true;
    }
    if (!$result) {
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
