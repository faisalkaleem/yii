<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Moreless
 *
 * @author faisalkaleem
 */
class Moreless extends CWidget {
  public $input;
  public $visible_length;
  public $replace_empty = 'N/A';
  private $id = null;
  
  public $show_more_link_text = 'Show More';
  public $show_less_link_text = 'Show Less';

  public function run() {
    if(!$this->input){
      echo $this->replace_empty;
    } else {
      $this->id = $this->getId();
      if(strlen($this->input) > $this->visible_length) {
        echo '<p id="less-'.$this->id.'">'.nl2br($this->getReducedInput()).'</p>';
        echo '<p class="more-less" id="more-'.$this->id.'" style="display:none;">'.nl2br($this->input).'</p>';
        echo '<a class="show-moreless-'.$this->id.'" data-show="more" href="javascript:;">Show More</a>';
        Yii::app()->clientScript->registerScript('less-more'.$this->id, 
          'jQuery(".show-moreless-'.$this->id.'").on("click", function(e){
            var target = e.currentTarget;
            if(jQuery(target).data("show")=="more"){
              jQuery(target).data("show", "less");
              jQuery(target).text("'.$this->show_less_link_text.'");
              jQuery("#less-'.$this->id.'").hide();
              jQuery("#more-'.$this->id.'").show();
            } else {
              jQuery(target).data("show", "more");
              jQuery("#less-'.$this->id.'").show();
              jQuery("#more-'.$this->id.'").hide();
              jQuery(target).text("'.$this->show_more_link_text.'");
            }
          });'
        );
      } else {
        echo '<p class="more-less" id="more-'.$this->id.'">'.nl2br($this->input).'</p>';
      }
    }
  }
  
  public function getReducedInput(){
    $length = strlen($this->input);
    if($length>$this->visible_length) {
      $string = substr($this->input, 0, $this->visible_length) . '...';
    } else {
      $string = $this->input;
    }
    
    return $string;
  }

}
