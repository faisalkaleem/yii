<?php

/**
 * Checks the uploaded file name size.
 *
 * @author Faisal Kaleem
 */
class fileNameLength extends CValidator {

  /**
   * Maximum file name length.
   * 
   * @var integer
   */
  public $max;

  /**
   * Validates the attribute of the object.
   * If there is any error, the error message is added to the object.
   * @param CModel $object the object being validated
   * @param string $attribute the attribute being validated
   * @throws CException if given attribute is not of type CUploadedFile.
   */
  protected function validateAttribute($object, $attribute) {
    $attribute_value = $object->{$attribute};
    if($attribute_value === null) {
      return true;
    }
    
    if(!($attribute_value instanceof CUploadedFile)) {
      throw new CHttpException(500, '"'.$attribute . '" must be of type CUploadedFile');
    }
    $file_name_length = $attribute_value->getName();
    if (strlen($file_name_length) > $this->max) {
      $message = $this->message !== null ? $this->message : Yii::t('yii', "{attribute} name must not exceed {$this->max} characters.");
      $this->addError($object, $attribute, $message);
    }
  }
  
}
