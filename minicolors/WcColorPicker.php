<?php

/**
 * WcColorPicker class file.
 * This widget is based on jquery-minicolors by Cory LaViska
 *
 * @author Faisal Kaleem <faisalkaleem at msn dot com>
 * @link https://github.com/claviska/jquery-minicolors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 *
 * minicolors JQuery Plugin:
 * http://labs.abeautifulsite.net/jquery-minicolors/
 *
 * A typical CActiveForm usage (with a model) of JColorPicker is as follows:
 * <pre>
 * $this->widget('ext.minicolors.WcColorPicker', array(
 *     'model' => $model,
 *     'attribute' => 'attribute_name',
 *     'hidden'=>false, // defaults to false - can be set to hide the textarea with the hex
 *     'options' => array(), // jQuery plugin options
 *     'htmlOptions' => array(), // html attributes
 * ));
 * 
 * defaults: {
 *    animationSpeed: 50,
 *    animationEasing: 'swing',
 *    change: null,
 *    changeDelay: 0,
 *    control: 'hue',
 *    dataUris: true,
 *    defaultValue: '',
 *    format: 'hex',
 *    hide: null,
 *    hideSpeed: 100,
 *    inline: false,
 *    keywords: '',
 *    letterCase: 'lowercase',
 *    opacity: false,
 *    position: 'bottom left',
 *    show: null,
 *    showSpeed: 100,
 *    theme: 'default',
 *    swatches: []
 * }
 * </pre>
 *
 */
class WcColorPicker extends CWidget {

  /**
   * @var CActiveRecord model
   */
  public $model;
  
  /**
   * @var string
   */
  public $name;
  
  /**
   * @var string
   */
  public $value;

  /**
   * @var name of the CActiveRecord model attribute
   */
  public $attribute;

  /**
   * @var whether the textfield with the hex value is shown or not (next to the color picker)
   */
  public $hidden = false;

  /**
   * @var array miniColors jQuery plugin options
   */
  public $options = array();

  /**
   * @var array input element attributes
   */
  public $htmlOptions = array();

  /**
   * Initializes the widget.
   * This method will publish jQuery and miniColors plugin assets if necessary.
   * @return void
   */
  public function init() {
    $this->getGenerateId();
    $input_id = $this->getId();
    $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'jquery-minicolors';
    $baseUrl = Yii::app()->getAssetManager()->publish($dir);
    $cs = Yii::app()->getClientScript();
    
    $cs->registerCoreScript('jquery');
    $cs->registerScriptFile($baseUrl . '/jquery.minicolors.min.js');
    $cs->registerCssFile($baseUrl . '/jquery.minicolors.css');

    $options = CJavaScript::encode($this->options);
    $cs->registerScript('wccolorpicker-' . $input_id, '$("#' . $input_id . '").minicolors(' . $options . ');');
  }

  /**
   * Renders the widget.
   * @return void
   */
  public function run() {
    if($this->model) {
      if ($this->hidden)
        echo CHtml::activeHiddenField($this->model, $this->attribute, $this->htmlOptions);
      else
        echo CHtml::activeTextField($this->model, $this->attribute, $this->htmlOptions);
    } else {
      if ($this->hidden)
        echo CHtml::hiddenField($this->name, $this->value, $this->htmlOptions);
      else
        echo CHtml::textField($this->name, $this->value, $this->htmlOptions);
    }
  }
  
  /**
   * Generated Input Id
   * Can be used in the javascript.
   * 
   * @throws CException
   */
  private function getGenerateId() {
    if($this->model) {
      $this->setId(CHtml::activeId($this->model, $this->attribute));
    } else if(isset($this->htmlOptions['id']) && !empty($this->htmlOptions['id'])) {
      $this->setId($this->htmlOptions['id']);
    } else if($this->name){
      $this->setId(CHtml::getIdByName($this->name));
    } else {
      throw new CException("Neither model nor name mentioned.", 500);
    }
  }

}
