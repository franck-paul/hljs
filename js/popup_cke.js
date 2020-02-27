/*global $ */
'use strict';

$(function() {
  // Cancel button fired
  $('#hljs-cancel').on('click', function() {
    window.close();
  });

  // Ok button fired
  $('#hljs-ok').on('click', function(e) {
    e.preventDefault();
    sendClose();
    window.close();
  });

  function sendClose() {
    // Get option and format selection if any, or insert a sample one
    const insert_form = $('#hljs-form').get(0);
    if (insert_form == undefined) {
      return;
    }
    const editor_name = window.opener.$.getEditorName();
    const editor = window.opener.CKEDITOR.instances[editor_name];
    const selected_text = editor.getSelection().getSelectedText() || '';
    const syntax = insert_form.syntax.value;
    const elt_code = new window.opener.CKEDITOR.dom.element('code');
    if (syntax != '') {
      elt_code.addClass(`language-${syntax}`);
    }
    if (selected_text == '') {
      elt_code.appendText('code');
    } else {
      elt_code.appendText(selected_text);
    }
    const elt_pre = new window.opener.CKEDITOR.dom.element('pre');
    elt_pre.append(elt_code);
    editor.insertElement(elt_pre);
    if (selected_text == '') {
      editor.getSelection().selectElement(elt_code);
    }
  }
});
