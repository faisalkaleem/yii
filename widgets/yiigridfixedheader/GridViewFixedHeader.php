<?php
/**
 * Description of GridViewFixedHeader
 *
 * @author faisalkaleem
 */
class GridViewFixedHeader extends CWidget {
  public $grid_id = null;
  public $afterAjaxUpdate = array();
  
  protected $baseScriptUrl = null;


  public function init(){
    if($this->baseScriptUrl===null){
      $this->baseScriptUrl=Yii::app()->getAssetManager()->publish(dirname(__FILE__).'/assets');
    }
  }
  
  public function run() {
    $id = $this->getId();
    
    $cs = Yii::app()->clientScript;
    $cs->registerScriptFile($this->baseScriptUrl.'/jquery.yiigridviewfixedheader.js',CClientScript::POS_END);
    $options = array();
    if(!empty($this->afterAjaxUpdate)){
      $options['afterAjaxUpdate'] = $this->afterAjaxUpdate;
    }
    $options=CJavaScript::encode($options);
    $cs->registerScript(
      $id.'-js',
      'jQuery("#'.$this->grid_id.'").yiiGridViewFixedHeader('.$options.');'
    );
  }
}
