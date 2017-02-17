<?php
/**
 * Description of Helper
 *
 * @author faisal.kaleem
 */
class Helper {
  
  public static function dump($var, $return = false, $depth=10, $highlight=false){
    if($return) {
      return CVarDumper::dumpAsString ($var, $depth, $highlight);
    } else {
      echo '<pre>';
      CVarDumper::dump($var, $depth, $highlight);
      echo '</pre>';
    }
  }

  public static function createDirectory($path, $mode = 0777, $recursive = true){
    return @mkdir($path, $mode, $recursive);
  }

  public static function validatePath($path){
    if(!is_dir($path)){
      self::createDirectory($path);
    }
    if(!is_dir($path)){
      throw new CHttpException('Directory permission error.');
    }
    return true;
  }
  
  public static function getColorCode($color) {
    if(strstr($color, '#')) {
      return $color;
    } else {
      return '#' . $color;
    }
  }

  public static function formatDate($date, $parseFormat = 'yyyy-MM-dd', $dateWidth = 'medium', $timeWidth = 'short') {
    if(!empty($date))
      return date('m/d/Y', CDateTimeParser::parse($date, $parseFormat));
    return null;
  }

  public static function formatDateTime($date, $parseFormat = 'yyyy-MM-dd hh:mm:ss', $dateWidth = 'medium', $timeWidth = 'short') {
    return self::formatDate($date, $parseFormat, $dateWidth, $timeWidth);
  }

  public static function formatDateForDB($date, $parse_format = 'MM/dd/yyyy') {
    if(!empty($date))
      return date('Y-m-d', CDateTimeParser::parse($date, $parse_format));
    return null;
  }
  
  public static function formatDateTimeForDB($date, $parse_format = 'MM/dd/yyyy') {
    if(!empty($date))
      return date('Y-m-d 00:00:00', CDateTimeParser::parse($date, $parse_format));
    return null;
  }

  public function getMonthStartDate(){
    return date('Y-m') . '-01';
  }

  public function getMonthEndDate() {
    $month = (int)date('m');
    $last_date = 30;
    if($month==2) {
      if((date('Y')%4)==0) // check for leap year
        $last_date = 29;
      else
        $last_date = 28;
    } else if(in_array($month, array(1,3,5,7,8,10,12))) {
      $last_date = 31;
    }

    return date('Y-m') . '-'.$last_date;
  }

  public static function getYearFirstMonthDate() {
    return date('Y').'-01-01';
  }

  public static function getYearLastMonthDate() {
    return date('Y').'-12-01';
  }
  
  public static function isFirstDayOfMonth($date){
//    $first_of_month_dates = self::getFirtstOfMonths();
//    return isset($first_of_month_dates[$date]);
    return (strrpos($date, '-01')==7);
  }
  
  public static function getFirtstOfMonths($rolling=false) {
    $year = date('Y');
    $dates = array();
    if($rolling){
      for($i=11; $i>=0; $i--) {
        $first_of_month = date("Y-m", strtotime( date( 'Y-m-01' )." -$i months")).'-01';
        $dates[$first_of_month] = $first_of_month;
      }
    } else {
      for($i=1; $i<=12; $i++) {
        $first_of_month = $year.'-'.sprintf("%02s", $i).'-01';
        $dates[$first_of_month] = $first_of_month;
      }
    }
      
      return $dates;
  }
  
  public static function getPlural($string) {
      
    $last_letter = strtolower($string[strlen($string)-1]);
    switch($last_letter) {
        case 'y':
            return substr($string,0,-1).'ies';
        case 's':
            return $string.'es';
        default:
            return $string.'s';
    }
      
    return $string;
  }

  public static function generateRandomString($length = 8) {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $random_string = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < $length; $i++) {
      $n = rand(0, $alphaLength);
      $random_string[] = $alphabet[$n];
    }
    return implode($random_string); //turn the array into a string
  }
  
  public static function truncateString($input, $length) {
    $input_length = strlen($input);
    $output_string = $input;
    if($input_length>$length) {
      $output_string = substr($input, 0, $length) . '...';
    }
    return $output_string;
  }
  
  public static function implodeObject($glue, Array $array_pieces, $attribute, $empty_value='N/A') {
    if(empty($array_pieces)) return $empty_value;
    $array = array();
    foreach($array_pieces as $obj){
      $array[] = $obj->$attribute;
    }
    return implode(', ', $array);
  }
  
  /**
   * Return array having the only the properties passed to to the function.
   * 
   * @param array $array
   * @param array $attribute_properties
   * @param string $index_property
   * @return array
   */
  public static function getDropDownListOptions($array, $attribute_properties, $index_property=null) {
    $result = array();
    foreach($array as $ar) {
      $options_attributes = array();
      foreach($attribute_properties as $attribute => $attribute_property) {
        $options_attributes[$attribute] = $ar->{$attribute_property};
      }
      if($index_property) {
        $result[$ar->$index_property] = $options_attributes;
      } else {
        $result[] = $options_attributes;
      }
      
//      $result[$ar->$index_property] = array($option_property=>$ar->$attribute_properties);
    }
    return $result;
  }
  
  /**
   * Returns Acronym of string in uppercase.
   * 
   * @param String $string
   */
  public static function getAcronym($string) {
    if(preg_match_all('/\b(\w)/',strtoupper($string),$m)) {
      return implode('',$m[1]); // $v is now SOQTU
    }
    return null;
  }
  
  public static function arraySumObject($objects, $property) {
    $sum = 0;
    foreach($objects as $object) {
      $sum += $object->$property;
    }
    return $sum;
  }
  
  /**
   * Tells if the URL is accessed internaly.
   * 
   * @return boolean
   */
  public static function isInternalRequest() {
    return ($_SERVER['REMOTE_ADDR']==$_SERVER['SERVER_ADDR']);
  }
  
  /**
   * Retursn item at the first index.
   * 
   * @param array $items
   */
  public static function getFirstIndex($items) {
    $exception = null;
    if(!is_array($items)) {
      $exception_message = array('message' => 'getFirstIndex accpets only array.', 'code' => 500);
    }
    if(empty($items)) {
      $exception_message = array('message' => 'Empty array is passed in getFirstIndex method.', 'code' => 500);
    }
    if($exception) {
      throw new Exception($exception['message'], $exception['code']);
    }
    
    $first = array_keys($items);
    return $first[0];
  }
  
  /**
   * Returns options string to be used for HTML select input.
   * 
   * @param CActiveRecord[] $records
   * @param boolean $include_select_empty
   * @param string $empty_label
   * @return string
   */
  public static function getArrayToSelectOptions($records, $value_attribute, $text_attribute, $include_select_empty=true, $empty_label = '--') {
    $options = '';
    if($include_select_empty) {
      $options = '<option value="">--Select '.$empty_label.'--</option>';
    }
    foreach($records as $record) {
      $options .= '<option value="'.$record->{$value_attribute}.'">'.$record->{$text_attribute}.'</option>';
    }
    return $options;
  }
  
  /**
   * Returns array of models having only selected attributes.
   * 
   * @param CActiveRecord $models
   * @param array $attributes
   */
  public static function getSelectedAttributes($models, $attributes) {
    $output = array();
    foreach($models as $model) {
      $record = array();
      foreach($attributes as $attribute) {
        $record[$attribute] = $model->{$attribute};
      }
      $output[] = $record;
    }
    return $output;
  }
}
