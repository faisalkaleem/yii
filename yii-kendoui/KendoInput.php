<?php

/**
 * Renders Kendo UI widgets
 *
 * @author faisalkaleem
 */
abstract class KendoInput extends CInputWidget {
  
  /**
   * Kendo Widget options.
   * @var array
   */
  public $options = array();
  
  /**
   * Asset manager object.
   * @var CAssetManager
   */
  protected $asset_manager;
  
  /**
   * Directory path of the extension.
   * @var string
   */
  protected $extension_directory;

  /**
   * Dependencies of the widget.
   * Used to include individual Javascript files required to render the widget.
   * @var array
   */
  protected $dependencies = array();
  
  /**
   * Initialization function which prepares things required to render the widget.
   */
  public function init() {
    $this->extension_directory = dirname(__FILE__);
    $this->asset_manager = Yii::app()->getAssetManager();
    
    $this->registerCss();
    $this->registerJs();
  }
  
  /**
   * Registers Javascript dependencies.
   */
  protected function registerDependencies() {
    foreach($this->dependencies as $dependency_name) {
      Yii::app()->clientScript->registerScriptFile(
        Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/kendoui/src/js/kendo.'.$dependency_name.'.js', true),
        CClientScript::POS_HEAD);
    }
  }
  
  /**
   * Registers CSS files for the widget.
   */
  protected function registerCss() {
    $css_common_file = $this->asset_manager->publish($this->extension_directory . '/kendoui/styles/kendo.common-material.min.css', true);
    $hash_dir_name = basename(dirname($css_common_file));
    
    Yii::app()->clientScript->registerCssFile($css_common_file);
    
    Yii::app()->clientScript->registerCssFile(
        $this->asset_manager->publish($this->extension_directory . '/kendoui/styles/kendo.material.min.css', true));
    
    Yii::app()->clientScript->registerCssFile(
        $this->asset_manager->publish($this->extension_directory . '/kendoui/styles/kendo.material.mobile.min.css', true));
    
    $this->asset_manager->publish(
        $this->extension_directory . '/kendoui/styles/Material', true, -1, null, $hash_dir_name . DIRECTORY_SEPARATOR . 'Material');
    $this->asset_manager->publish(
        $this->extension_directory . '/kendoui/styles/images', true, -1, null, $hash_dir_name . DIRECTORY_SEPARATOR . 'images');
  }
  
  /**
   * Register core Javascript script and dependencies.
   */
  protected function registerJS() {
    Yii::app()->clientScript->registerScriptFile(
        $this->asset_manager->publish($this->extension_directory . '/kendoui/src/js/kendo.core.js', true),
        CClientScript::POS_HEAD);
    $this->registerDependencies();
  }
  
}
