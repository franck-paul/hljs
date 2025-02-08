/*global dotclear */
'use strict';

dotclear.ready(() => {
  // Cancel button fired
  document.getElementById('hljs-cancel')?.addEventListener('click', () => {
    window.close();
    return false;
  });

  // Ok button fired
  document.getElementById('hljs-ok')?.addEventListener('click', () => {
    sendClose();
    window.close();
    return false;
  });

  function sendClose() {
    const insert_form = document.getElementById('hljs-form');
    if (!insert_form) {
      return;
    }
    const tb = window.opener.the_toolbar;
    const { data } = tb.elements.hljs;
    data.syntax = insert_form.syntax.value;
    tb.elements.hljs.fncall[tb.mode].call(tb);
  }
});
