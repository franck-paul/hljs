/*global $, CKEDITOR, dotclear */
'use strict';

CKEDITOR.plugins.add('hljs', {
  init(editor) {
    const hljs_editor = dotclear.getData('hljs_editor', false);

    editor.addCommand('hljsCommand', {
      exec() {
        $.toolbarPopup(
          hljs_editor.popup_url.replace(/&amp;/g, '&'), // URL
          {
            // Popup size
            width: 480,
            height: 240,
          },
        );
      },
    });

    editor.ui.addButton('hljs', {
      label: hljs_editor.title,
      command: 'hljsCommand',
      icon: `${this.path}icons/icon.svg`,
    });
  },
});
