/*global dotclear */
'use strict';

dotclear.ready(() => {
  // Cancel button fired
  document.getElementById('hljs-cancel')?.addEventListener('click', (event) => {
    event.preventDefault();
    window.close();
  });

  // Ok button fired
  document.getElementById('hljs-ok')?.addEventListener('click', (event) => {
    event.preventDefault();
    sendClose();
    window.close();
  });

  function sendClose() {
    // Get option and format selection if any, or insert a sample one
    const insert_form = document.getElementById('hljs-form');
    if (!insert_form) {
      return;
    }
    const editor_name = window.opener.$.getEditorName();
    const editor = window.opener.CKEDITOR.instances[editor_name];
    const selected_text = editor.getSelection().getSelectedText() || '';
    const syntax = insert_form.syntax.value;
    const elt_code = new window.opener.CKEDITOR.dom.element('code');
    if (syntax !== '') {
      elt_code.addClass(`language-${syntax}`);
    }
    elt_code.appendText(selected_text === '' ? 'code' : selected_text);
    const elt_pre = new window.opener.CKEDITOR.dom.element('pre');
    elt_pre.append(elt_code);
    editor.insertElement(elt_pre);
    if (selected_text === '') {
      editor.getSelection().selectElement(elt_code);
    }
  }
});
