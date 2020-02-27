/*global $, CKEDITOR, getData */
'use strict';

CKEDITOR.plugins.add('hljs', {
  init: function(editor) {

    let hljs_editor = getData('hljs_editor', false);

    editor.addCommand('hljsCommand', {
      exec: function() {
        $.toolbarPopup(
          hljs_editor.popup_url.replace(/&amp;/g, '&'), // URL
          { // Popup size
            'width': 480,
            'height': 240
          }
        );
      }
    });

    editor.ui.addButton("hljs", {
      label: hljs_editor.title,
      command: 'hljsCommand',
      icon: this.path + 'icons/icon.png'
    });
  }
});
