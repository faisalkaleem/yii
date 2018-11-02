<?php

/**
 * TinyMCE class file.
 *
 * @author Faisal Kaleem
 * @version 0.1
 */

/**
 * TinyMCE generates TinyMCE text area.
 *
 * This widget is implemented based on CKEditor:
 * (see {@link http://ckeditor.com/demo}).
 *
 * @author Faisal Kaleem <faisalkaleem AT msn DOT com>
 * @package application.extensions.widgets.tinymce
 * @since 1.0
 */
class TinyMce extends CInputWidget {

  /**
   * Wheather to File browser or not.
   * 
   * @var boolean
   */
  public $use_file_borwser = true;
  
  /**
   * ID of the input field.
   * 
   * @var string
   */
  protected $input_id;
  
  /**
   * Theme to to use of TinyMCE
   * 
   * @var string
   */
  public $skin = 'modern';
  
  /**
   * Theme for the editor.
   * 
   * @var string
   */
  public $theme = 'advanced';
  
  /**
   * Width of the container.
   * 
   * @var string
   */
  public $width = '100%';
  
  /**
   * CSS styels for container.
   * 
   * @var string
   */
  public $styles = '';
  
  /**
   * Height of editor.
   * 
   * @var integer
   */
  public $height = 300;
  
  /**
   * Whether to show inline formatting controls.
   * 
   * @var boolean
   */
  public $inline = false;
  
  /**
   * Wheather to show menu bar or not.
   * 
   * @var boolean
   */
  public $menubar = true;
  
  /**
   * List of toolbar items.
   * 
   * @var string
   */
  public $toolbar = array(
      "undo redo | bold italic underline strikethrough subscript superscript removeformat | "
      . "forecolor backcolor | styleselect formatselect fontselect fontsizeselect",
      "copy cut paste pastetext pasteword | alignleft aligncenter alignright alignjustify | bullist numlist | "
    . "outdent indent blockquote | table merge_cells image media link unlink anchor hr separator | "
    . "charmap | search replace | fullscreen code"
  );
//  public $toolbar = 'undo redo styleselect bold italic alignleft aligncenter alignright bullist numlist outdent indent code';
  
  /**
   * TinyMCE plugins to enable.
   * Currently following plugins are disabled:
   * <ul>
   * <li>a11ychecker</li>
   * <li>advcode</li>
   * <li>linkchecker</li>
   * <li>tinymcespellchecker</li>
   * <li>mediaembed</li>
   * <li>powerpaste</li>
   * <li>codesample</li>
   * <li>insertdatetime</li>
   * <li>print</li>
   * <ul>
   * 
   * @var array
   */
  public $plugins = array(
    "paste searchreplace advlist anchor charmap autolink code colorpicker contextmenu fullscreen help image imagetools",
    " lists link media noneditable preview",
    "table template textcolor visualblocks wordcount"
  ); //removed:  
  
  /**
   * Base of URL of CKEditor js files.
   * 
   * @var string
   */
  protected $baseScriptUrl = null;
  
  /**
   * Extra CSS for content styling within the CKEditor.
   * 
   * @var array
   */
  protected $contents_css = array();
  
  /**
   * Current theme path.
   * 
   * @var string
   */
//  protected $theme_path;
  
  /**
   * If there are multiple ckeditors it will be set to true and the assets will not be registered again.
   * 
   * @var boolean
   */
//  public static $assets_already_rendered = false;

  public function init() {
    if ($this->baseScriptUrl === null) {
      $this->baseScriptUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/js/tinymce', false/*, 1*/);
    }
    $this->contents_css = array(
      Yii::app()->theme->getBaseUrl(true)."/css/styles.css",
      Yii::app()->theme->getBaseUrl(true)."/css/ckeditor.css"
    );
    if($this->use_file_borwser) {
      Yii::app()->clientScript->registerScript(
          'elfinder-callback', 
          "function elFinderBrowser (callback, value, meta) {
            tinymce.activeEditor.windowManager.open({
              file: '".Yii::app()->baseUrl."/elfinder/browser.php',// use an absolute path!
              title: 'iCUE - File Browser',
              width: 900,
              height: 450,
              resizable: 'yes'
            }, {
              oninsert: function (file, fm) {
                var url, reg, info;

                // URL normalization
                url = '".Yii::app()->baseUrl."/'+fm.convAbsUrl(file.url);

                // Make file info
                info = file.name + ' (' + fm.formatSize(file.size) + ')';

                // Provide file and text for the link dialog
                if (meta.filetype == 'file') {
                  callback(url, {text: info, title: info});
                }

                // Provide image and alt text for the image dialog
                if (meta.filetype == 'image') {
                  callback(url, {alt: info});
                }

                // Provide alternative source and posted for the media dialog
                if (meta.filetype == 'media') {
                  callback(url);
                }
              }
            });
            return false;
          }"
      );
    }
//    
//    $this->theme_path = Yii::app()->theme->getBaseUrl(true);
//    if(file_exists($this->theme_path . "/css/styles.css")) {
//      $this->contentsCss[] = Yii::app()->theme->getBaseUrl(true) . "/css/styles.css";
//    }
    
//    if(file_exists($this->theme_path . "/css/ckeditor.css")) {
//      $this->contentsCss[] = Yii::app()->theme->getBaseUrl(true) . "/css/styles.css";
//    }
    
  }

  protected function registerJS() {
    $cs = Yii::app()->clientScript;
//    $cs->registerCss('ckeditor', '#cke_'.$this->input_id.'{width:74%; float:left;}');
    $cs->registerScriptFile($this->baseScriptUrl.'/tinymce.min.js',CClientScript::POS_END);

    $additional_options = '';
    if($this->use_file_borwser) {
      $additional_options = ",file_picker_callback: elFinderBrowser, \r\nfile_browser_callback: true";
    }
    if(!empty($this->contents_css)) {
      $additional_options .= ",content_css: ".CJavaScript::encode($this->contents_css);
    }
    
    $cs->registerScript(
        'tinymce-'.$this->input_id,
        'tinymce.init({
          selector: "#'.$this->input_id.'",
          inline: '.($this->inline?"true":"false").',
          menubar: '.($this->menubar?"true":"false").',
          height: '.$this->height.',
          width: "'.$this->width.'",
          styles: "'.$this->styles.'",
          toolbar: '.CJavaScript::encode($this->toolbar).',
          plugins: '.  CJavaScript::encode($this->plugins).'
          '.$additional_options.'
        });
    ');
//    $cs->registerScript(
//        'ckeditor'.$this->input_id,
//        "
//          var editor_".$this->input_id." = CKEDITOR.replace(
//          '".$this->input_id."', {
//            extraPlugins: '".  implode(',', $this->extra_plugins)."',
//            filebrowserBrowseUrl: '".Yii::app()->baseUrl."/ckfinder/ckfinder.html',
//            filebrowserUploadUrl: '".Yii::app()->baseUrl."/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
//            //filebrowserWindowWidth: '640',
//            //filebrowserWindowHeight: '480'
//          }
//        );
////        editor.on( 'instanceReady', function() {
////            console.log( editor.filter.allowedContent );
////        } );
//  ");

  }
  public function run() {
    $name_id = $this->resolveNameID();
    $this->input_id = CHtml::getIdByName($name_id[0]);
    if($this->model && $this->attribute) {
      echo CHtml::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
    } else if($this->name) {
      echo CHtml::textArea($this->name, $this->value, $this->htmlOptions);
    }
    
    $this->registerJS();
  }

}
