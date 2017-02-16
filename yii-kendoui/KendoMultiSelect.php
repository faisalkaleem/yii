<?php

/**
 * Renders Kendo MultiSelect.
 *
 * @author faisalkaleem
 */
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'KendoInput.php';

class KendoMultiSelect extends KendoInput {
  
  public $settings = array();
  
  /**
   * Select options array.
   * @var array
   */
  public $data = array();
  
  /**
   * Is called before {@link run()} call.
   */
  public function init() {
    $this->dependencies = array(
      'list',
      'data',
      'popup',
      'fx',
      'userevents',
      'draganddrop',
      'mobile.scroller',
      'virtuallist',
    );
    parent::init();
//    $this->registerDependencies($this->dependencies);
    Yii::app()->clientScript->registerScriptFile(
        Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/kendoui/src/js/kendo.multiselect.js', true),
        CClientScript::POS_HEAD);
  }
  
  /**
   * Renders Kendo Multiselect.
   * It uses CHtml::activeDropDownList().
   * @return string
   */
  public function run() {
    $this->htmlOptions['multiple'] = true;
    if($this->model) {
      $name_id = $this->resolveNameID();
      $input_id = $name_id[1];
      $select_html = CHtml::activeDropDownList($this->model, $this->attribute, $this->data, $this->htmlOptions);
    } else {
      $input_id = $this->attribute;
      $select_html = CHtml::dropDownList($this->attribute, $this->value, $this->data, $this->htmlOptions);
    }
    
    $options=CJavaScript::encode($this->options);
    Yii::app()->clientScript->registerScript('kendoui-multiselect-'.$input_id,
        'var '.$input_id.' = jQuery("#'.$input_id.'").kendoMultiSelect('.$options.').data("kendoMultiSelect");'
    );
    
    echo $select_html;
  }
}
