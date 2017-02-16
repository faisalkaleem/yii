<?php

/**
 * Checks for duplicates values in a table.
 * Provides conditional messages and conditional validation.
 *
 * @author Faisal Kaleem <faisalkaleem AT msn DOT com>
 */
class CompositeUnique extends CValidator {
  
  /**
   * Error message in case the value is invalid.
   * It can have dynamic parameters surrounded by curly braces e.g: {param}. 
   * {param} will be replaced with the value of existing model record.
   * 
   * @var string
   */
  public $message;
  
  /**
   * Parameters list that needs to be repleced used in {@link $message}.
   * 
   * @var array
   */
  public $params;
  
  /**
   * Conditional message in the following format:
   *  array(
   *    array(
   *      'param' => 'attribute',
   *      'value' => 'null',
   *      'operator' => '==',
   *      'message' => 'The record having already exists. The id is: {id}',
   *      'message_params' => array('{id}')
   *    ),
   *    array(
   *      'param' => 'campaign_id',
   *      'value' => 'null',
   *      'operator' => '!=',
   *      'message' => 'Record already exist <a href="http://yoursite.com/index.php?r=user/update&id={id}">click here</a> 
   *                    to see to update the record record',
   *      'message_params' => array('{campaign_id}')
   *    )
   * @var array
   */
  public $conditional_message;
  
  /**
   * Criteria whether to validate or not based on some condition. The format is follows:
   * array('attribute_name' => attribute_value),
   * 
   * @var array
   */
  public $validate_if;
  
  /**
   * Validates the attribute of the object.
   * If there is any error, the error message is added to the object.
   * @param CModel $object the object being validated
   * @param string $attribute the attribute being validated
   */
  protected function validateAttribute($object, $attribute) {
    $attributes = explode('+', $attribute);
    $criteria = new CDbCriteria();
    $validate = true;
    if($this->validate_if) {
      if(is_array($this->validate_if)) {
        foreach($this->validate_if as $attr => $val) {
          $validate = $validate && ($object->{$attr} == $val);
          if(!$validate) break;
        }
      }
    }
    if($object->id) {
      $criteria->compare('id', $object->id);
    }
    $fields_labels = array();
    $attributes_has_values = true;
    foreach ($attributes as $attr) {
      $attributes_has_values = $attributes_has_values && $object->$attr;
      if(!$attributes_has_values) break;
      $criteria->compare($attr, $object->$attr);
      $fields_labels[] = $object->getAttributeLabel($attr);
    }
    $record = $object->find($criteria);
    $params = array();
    if ($record !== null && $validate && $attributes_has_values) {
      if($this->conditional_message) {
        $this->message = $this->getConditionalMessage($record);
      }
      if($this->message) {
        if($this->params) {
          $params = array();
          foreach($this->params as $param) {
            $params[urlencode($param)] = $record->{str_replace(array('{', '}'), '', $param)};
          }
        }
        
      } else {
        $this->message = 'Duplicate entry for fields: ' . implode(', ', $fields_labels);
      }
      $this->message = strtr($this->message, $params);
      $this->addError($object, $attributes[0], $this->message);
    }
  }
  
  /**
   * Prepares message based on {@link $conditional_message} param.
   * 
   * @param CActiveRecord $record
   * @return bool
   */
  protected function getConditionalMessage($record) {
    foreach ($this->conditional_message as $condition_message) {
      if (!empty($condition_message)) {
        $attribute_value = $record->{$condition_message['param']} ? $record->{$condition_message['param']} : 'null';
        $expression = 'return (' . $attribute_value . $condition_message['operator'] . $condition_message['value'] . ');';
        if (eval($expression)) {
          $this->params = $condition_message['message_params'];
          return $condition_message['message'];
        }
      }
    }
    return null;
  }
}
