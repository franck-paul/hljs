CKEDITOR.dialog.add('hljsDialog', function(editor) {
  return {
    title: hljs_title,
    minWidth: 400,
    minHeight: 150,
    contents: [
      {
        id: 'tab-syntax',
        label: hljs_tab_syntax,
        elements: [{
          type: 'radio',
          id: 'alignment',
          label: hljs_syntax,
          items: [
            [ noembedmedia_align_none, 'none' ],
            [ noembedmedia_align_left, 'left' ],
            [ noembedmedia_align_right, 'right'],
            [ noembedmedia_align_center, 'center'] ],
          'default': 'none'
        }]
      }
    ],
    onOk: function() {
      var dialog = this;

      var syntax = dialog.getValueOf('tab-syntax', 'syntax');
      if (syntax !== '') {
        syntax = ' class="language-' + syntax + '"';
      }

      var

      var block = editor.document.createElement('pre');
      var code = '<code' + syntax + '>' + editor.getSelection().getSelectedText() + '</code>';

      $.getJSON('https://noembed.com/embed?url='+url+'&callback=?',
          function(data) {
            var div = editor.document.createElement('div');
            var style = '';
            div.setAttribute('class', 'external-media');
            if (alignment == 'left') {
              style = 'float: left; margin: 0 1em 1em 0;';
            } else if (alignment == 'right') {
              style = 'float: right; margin: 0 0 1em 1em;';
            } else if (alignment == 'center') {
              style = 'margin: 1em auto; text-align: center;';
            }
            if (style!='') {
              div.setAttribute('style', style);
            }

            div.appendHtml(data.html);
            editor.insertElement(div);
          });
    }
  };
});
