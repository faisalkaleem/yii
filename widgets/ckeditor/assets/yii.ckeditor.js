var ckeditor_vars = {
  urlFieldId: null,
  buttonId:null,
  dialog:null
};

function selectFile(url) {
  if (ckeditor_vars.urlFieldId === null || ckeditor_vars.dialog._.name === null) {
    console.log('CKEditor: ckeditor_vars.urlFieldId and or not defined');
    return;
  }
  ckeditor_vars.dialog.setValueOf(ckeditor_vars.dialog._.currentTabId, ckeditor_vars.urlFieldId, url);
  jQuery('#dialog-doc-reference').dialog('close');
  return;
}

/**
 * Returns text field id to be updates with the URL.
 * 
 * @param {String} buttonId
 * @returns {String}
 */
function getUrlFieldId(buttonId) {
  var url_field_id = null;
  if (ckeditor_vars.dialog._.name == 'image') {
    url_field_id = 'txtUrl'
  } else if (ckeditor_vars.dialog._.name == 'flash') {
    url_field_id = 'src'
  } else if (ckeditor_vars.dialog._.name == 'files' || ckeditor_vars.dialog._.name == 'link') {
    url_field_id = 'url'
  } else if (ckeditor_vars.dialog._.name == 'video') {
    url_field_id = 'poster';
    if(buttonId == 'browse_src0') {
      url_field_id = 'src0';
    } else if(buttonId== 'browse_src1') {
      url_field_id = 'src1';
    }
  }
  ckeditor_vars.urlFieldId = url_field_id;
  return url_field_id;
}

