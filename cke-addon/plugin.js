CKEDITOR.plugins.add('hljs', {
  requires:"dialog",

  init: function(editor) {
    editor.addCommand('hljsCommand', new CKEDITOR.dialogCommand('hljsDialog'));

    CKEDITOR.dialog.add('hljsDialog', this.path+'dialogs/popup.js');

    editor.ui.addButton("hljs", {
      label: hljs_title,
      command: 'hljsCommand',
      icon: this.path+'icons/icon.png'
    });
  }
});
