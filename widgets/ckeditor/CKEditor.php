<?php

/**
 * CKEditor class file.
 *
 * @author Faisal Kaleem
 * @version 0.1
 */

/**
 * CKEditor generates ckeditor text area.
 *
 * This widget is implemented based on CKEditor:
 * (see {@link http://ckeditor.com/demo}).
 *
 * @author Faisal Kaleem
 * @package application.extensions.widgets.ckeditor
 * @since 1.0
 */
class CKEditor extends CInputWidget {

  /**
   * Wheather to File browser or not.
   * 
   * @var boolean
   */
  public $use_file_borwser = true;
  
  /**
   * JQueryUI dialog ID
   * 
   * @var string
   */
  public $file_browser_dialog_id = 'dialog-file-browser';
  
  /**
   * Extra plugins.
   * Can include: video
   * 
   * @var array
   */
  public $extra_plugins = array();
  
  /**
   * Clip ID the is used to place the CJuiDialog html.
   * e.g: echo $this->clips['ckeditor-dialog']
   * 
   * @var string
   */
  public $dialog_clip_id = 'ckeditor-dialog';
  
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
  protected $contentsCss = array();
  
  /**
   * Current theme path.
   * 
   * @var string
   */
  protected $theme_path;
  
  /**
   * ID of the input field.
   * 
   * @var string
   */
  protected $input_id;

  public function init() {
    if ($this->baseScriptUrl === null) {
      $this->baseScriptUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets', false/*, 1*/);
    }
    
    $this->theme_path = Yii::app()->theme->getBaseUrl(true);
    if(file_exists($this->theme_path . "/css/styles.css")) {
      $this->contentsCss[] = Yii::app()->theme->getBaseUrl(true) . "/css/styles.css";
    }
    if(file_exists($this->theme_path . "/css/ckeditor.css")) {
      $this->contentsCss[] = Yii::app()->theme->getBaseUrl(true) . "/css/styles.css";
    }
    
  }
  
  protected function enableFileBrowser() {
    $this->file_browser_dialog_id = 'dialog-doc-reference';
    $this->beginWidget('system.web.widgets.CClipWidget', array('id'=>$this->dialog_clip_id));
    $this->beginWidget('CJuiDialog', array(
      'id'=>$this->file_browser_dialog_id,
      'options'=>array(
        'title'=>'Reference Documents',
        'autoOpen'=>false,
        'modal'=>true,
        'width'=>'670',
      ),
    ));
    echo $this->controller->renderPartial('application.modules.admin.views.document._select', array(
            'project'=>Yii::app()->project->model,
            'multiple' => 'false',
            'add_button_text' => 'Select',
            'mode' => 'ckeditor',
          ), true);
    $this->endWidget('CJuiDialog');
    $this->endWidget();
    
    Yii::app()->clientScript->registerScriptFile($this->baseScriptUrl.'/yii.ckeditor.js',CClientScript::POS_HEAD);
    
    Yii::app()->clientScript->registerScript('2hfilebrowser',
    "
    CKEDITOR.on('dialogDefinition', function (event) { // connection manager
      var dialogDefinition = event.data.definition;
      var tabCount = dialogDefinition.contents.length;
      for (var i = 0; i < tabCount; i++) { // cycle to replace the click of button 'View on the server'
        var browseButton = dialogDefinition.contents[i].get('browse');
        if (browseButton !== null) {
          browseButton.hidden = false;
          browseButton.onClick = function (dialog, i) {
            var dialog = CKEDITOR.dialog.getCurrent();
            var z_index = parseInt(dialog.parts.dialog.$.style.zIndex)+10;
            ckeditor_vars.dialog = dialog;
            ckeditor_vars.urlFieldId = getUrlFieldId('browse');
            jQuery('#".$this->file_browser_dialog_id."').parent('.ui-dialog').css('z-index', z_index);
            jQuery('#".$this->file_browser_dialog_id."').dialog('open');
          }
        }
        //if(dialog._.name == 'video') {
          var browseVideoButton0 = dialogDefinition.contents[i].get('browse_src0');
          if (browseVideoButton0 !== null) {
            browseVideoButton0.hidden = false;
            browseVideoButton0.onClick = function (dialog, i) {
              var dialog = CKEDITOR.dialog.getCurrent();
              var z_index = parseInt(dialog.parts.dialog.$.style.zIndex)+10;
              ckeditor_vars.dialog = dialog;
              ckeditor_vars.urlFieldId = getUrlFieldId('browse_src0');
              jQuery('#".$this->file_browser_dialog_id."').parent('.ui-dialog').css('z-index', z_index);
              jQuery('#".$this->file_browser_dialog_id."').dialog('open');
            }
          }
          
          var browseVideoButton1 = dialogDefinition.contents[i].get('browse_src1');
          if (browseVideoButton1 !== null) {
            browseVideoButton1.hidden = false;
            browseVideoButton1.onClick = function (dialog, i) {
              var dialog = CKEDITOR.dialog.getCurrent();
              var z_index = parseInt(dialog.parts.dialog.$.style.zIndex)+10;
              ckeditor_vars.dialog = dialog;
              ckeditor_vars.urlFieldId = getUrlFieldId('browse_src1');
              jQuery('#".$this->file_browser_dialog_id."').parent('.ui-dialog').css('z-index', z_index);
              jQuery('#".$this->file_browser_dialog_id."').dialog('open');
            }
          }
        //}
      }
    });
    "
    );
  }
  
  protected function configEditor() {
    Yii::app()->clientScript->registerScript(
        'ckeditor-config',
        "//CKEDITOR.config.contentsCss = ['". implode("', '", $this->contentsCss)."'];
        CKEDITOR.config.toolbar = [
              { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
              { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
              { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
              { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
              //{ name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
              //'/',
              { name: 'document', items: [ 'Source' ] },
              '/',
              { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
              { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
              { name: 'insert', items: [ 'Image', 'Video', 'Table', 'HorizontalRule', 'SpecialChar', 'FileBrowser'] },
              { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
              { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
              { name: 'others', items: [ '-' ] },
              //{ name: 'about', items: [ 'About' ] }
            ];
        CKEDITOR.config.allowedContent=true;
  ");
  }

  protected function registerJS() {
    $cs = Yii::app()->clientScript;
    $cs->registerCss('ckeditor', '#cke_'.$this->input_id.'{width:74%; float:left;}');
//    $cs->registerScriptFile(Yii::app()->getBaseUrl(true) . "/js/ckeditor/ckeditor.js");
    $cs->registerScriptFile($this->baseScriptUrl.'/ckeditor/ckeditor.js',CClientScript::POS_END);
    if($this->use_file_borwser) {
      $this->enableFileBrowser();
    }
    $this->configEditor();
    $cs->registerScript(
        'ckeditor',
        "
          var editor = CKEDITOR.replace(
          'Topic_text', {
            extraPlugins: '".  implode(',', $this->extra_plugins)."',
            filebrowserBrowseUrl: 'javascript:;',
//            filebrowserUploadUrl: '/uploader/upload.php',
//            filebrowserWindowWidth: '640',
//            filebrowserWindowHeight: '480'
          }
        );
//        editor.on( 'instanceReady', function() {
//            console.log( editor.filter.allowedContent );
//        } );
  ");
  }
  public function run() {
    $name_id = $this->resolveNameID();
    $this->input_id = CHtml::getIdByName($name_id[0]);
    echo CHtml::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
    $this->registerJS();
  }

}
