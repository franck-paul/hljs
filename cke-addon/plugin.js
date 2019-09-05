/*global $, CKEDITOR, hljs_popup_url, hljs_title */
'use strict';

CKEDITOR.plugins.add('hljs', {
  init: function(editor) {

    editor.addCommand('hljsCommand', {
      exec: function() {
        $.toolbarPopup(
          hljs_popup_url.replace(/&amp;/g, '&'), // URL
          { // Popup size
            'width': 480,
            'height': 240
          }
        );
      }
    });

    editor.ui.addButton("hljs", {
      label: hljs_title,
      command: 'hljsCommand',
      icon: this.path + 'icons/icon.png'
    });
  }
});
