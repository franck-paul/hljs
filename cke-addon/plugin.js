CKEDITOR.plugins.add('hljs', {
  init: function(editor) {

    editor.addCommand('hljsCommand', {
      exec: function(editor) {
        var popup_size = {
          'width': 480,
          'height': 240
        };
        var url = hljs_popup_url.replace(/&amp;/g, '&');
        $.toolbarPopup(url, popup_size);
      }
    });

    editor.ui.addButton("hljs", {
      label: hljs_title,
      command: 'hljsCommand',
      icon: this.path + 'icons/icon.png'
    });
  }
});
