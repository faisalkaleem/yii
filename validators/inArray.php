<?php
/**
 * Description of inArray
 *
 * @author Faisal Kaleem
 */
class inArray extends CRangeValidator {

  public $lookAtIndex = false;

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
    if (!is_array($this->range))
      throw new CException(Yii::t('yii', 'The "range" property must be specified with a list of values.'));
    $result = false;
    if ($this->strict)
      $result = in_array($value, $this->range, true);
    else if($this->lookAtIndex) {
      $result = isset($this->range[$value]);
    } else {
      foreach ($this->range as $r) {
        $result = (strcmp($r, $value) === 0);
        if ($result)
          break;
      }
    }
    if (!$this->not && !$result) {
      $message = $this->message !== null ? $this->message : Yii::t('yii', '{attribute} is not in the list.');
      $this->addError($object, $attribute, $message);
    } elseif ($this->not && $result) {
      $message = $this->message !== null ? $this->message : Yii::t('yii', '{attribute} is in the list.');
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
	public function clientValidateAttribute($object,$attribute)
	{
		if(!is_array($this->range))
			throw new CException(Yii::t('yii','The "range" property must be specified with a list of values.'));

		if(($message=$this->message)===null)
			$message=$this->not ? Yii::t('yii','{attribute} is in the list.') : Yii::t('yii','{attribute} is not in the list.');
		$message=strtr($message,array(
			'{attribute}'=>$object->getAttributeLabel($attribute),
		));

		$range=array();
		foreach($this->range as $key => $value)
			$range[$key]=(string)$value;
		$range=CJSON::encode($range);

        $return = "";
        if($this->lookAtIndex){
          "
if(".($this->allowEmpty ? "jQuery.trim(value)!='' && " : '').($this->not ? 'typeof '.$range.'[value]!="undefined\"' : 'typeof '.$range.'[value]=="undefined\"').") {
	messages.push(".CJSON::encode($message).");
}
";
        } else {
          "
if(".($this->allowEmpty ? "jQuery.trim(value)!='' && " : '').($this->not ? "jQuery.inArray(value, $range)>=0" : "jQuery.inArray(value, $range)<0").") {
	messages.push(".CJSON::encode($message).");
}
";
        }
		return $return;
	}

  //put your code here
}
