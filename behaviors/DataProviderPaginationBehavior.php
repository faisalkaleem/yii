<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DateProviderPagination
 *
 * @author faisalkaleem
 */
class DataProviderPaginationBehavior extends CBehavior {
  
  protected $pagination = null;
  private $model_name = null;
  private $drop_down_options = array('' => 'Show All', 10=>'10', 20=>'20', 50 => '50', 100 => '100');


  public function getPagination() {
    $model_name = $this->getModelName();
    $this->pagination = $this->getDefaultOptions();
    if(isset($_GET[$model_name.'_page_size']) && !empty($_GET[$model_name.'_page_size']) && is_numeric($_GET[$model_name.'_page_size'])) {
      $this->pagination = array('pageSize' => $_GET[$model_name.'_page_size']);
    } else if(isset($_GET[$model_name.'_page_size']) && empty($_GET[$model_name.'_page_size'])) {
      $this->pagination = false;
    }
    return $this->pagination;
  }
  
  public function getModelName() {
    if($this->model_name===null) {
      $model = $this->getOwner();
      $this->model_name = get_class($model);
    }
    return $this->model_name; 
  }
  
  public function getDefaultOptions(){
//    return array('pageSize' => 10);
    return false;
  }
  
  public function getPageSizeDropdown($grid_id) {
    $model_name = $this->getModelName();
    $page_size = '';
    if(isset($_GET[$model_name.'_page_size'])) {
      $page_size = $_GET[$model_name.'_page_size'];
    } 
    
    $drop_down_id = $model_name.'_page_size';
    $this->registerJS($grid_id, $drop_down_id);
    return 'Number of results per page: '. CHtml::dropDownList($drop_down_id, $page_size, $this->getDropDownOptions());
  }
  
  protected function registerJS($grid_id, $drop_down_id){
    $model_name = $this->getModelName();
    Yii::app()->clientScript->registerScript($model_name.'_page-size-dropdown',
      '
      var form = jQuery("select#'.$drop_down_id.'").parents("form:first");
      var form_id = form.attr("id");
      jQuery("#"+form_id).on("change", "select#'.$drop_down_id.'", function(e){
        var data = form.serialize();
        jQuery("#'.$grid_id.'").yiiGridView("update", {data: data});
      })'
    );
  }
  
  public function getDropDownOptions() {
    return $this->drop_down_options;
  }
  
  public function setDropDownOptions(Array $options) {
    $this->drop_down_options = $options;
  }
}
