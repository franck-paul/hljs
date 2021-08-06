/*global $ */
'use strict';

$(function () {
  // Cancel button fired
  $('#hljs-cancel').on('click', function () {
    window.close();
    return false;
  });

  // Ok button fired
  $('#hljs-ok').on('click', function () {
    sendClose();
    window.close();
    return false;
  });

  function sendClose() {
    const insert_form = $('#hljs-form').get(0);
    if (insert_form == undefined) {
      return;
    }
    const tb = window.opener.the_toolbar;
    const data = tb.elements.hljs.data;
    data.syntax = insert_form.syntax.value;
    tb.elements.hljs.fncall[tb.mode].call(tb);
  }
});
