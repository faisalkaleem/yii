<?php

/**
 * Renders Kendo MultiSelect.
 *
 * @author faisalkaleem <faisalkaleem@msn.com>
 */
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'KendoInput.php';

class KendoTooltip extends KendoInput {
  
  /**
   * Tag to initiat
   * @var string
   */
  public $tag = 'span';
  
  /**
   * content to show in the Tooltip.
   * 
   * @var string
   */
  public $text;
  
  /**
   * HTML options e.g: class, id etc.
   * 
   * @var array
   */
  public $htmlOptions;
  
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
      'popup',
      'tooltip'
    );
    parent::init();
  }
  
  /**
   * Renders Kendo Multiselect.
   * It uses CHtml::activeDropDownList().
   * @return string
   */
  public function run() {
    $html = CHtml::tag($this->tag, $this->htmlOptions, $this->text, true);
    $id = $this->getId();
    $options=CJavaScript::encode($this->options);
    Yii::app()->clientScript->registerScript('kendoui-tooltip-'.$id,
        'var '.  str_replace('-', '_', $id).' = jQuery("#'.$id.'").kendoTooltip('.$options.').data("kendoTooltip");'
    );
    echo $html;
  }
}
