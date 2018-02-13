$(function() {
  $('#hljs-cancel').click(function() {
    // Do nothing
    window.close();
  });

  $('#hljs-ok').click(function(e) {
    // Get option and format selection if any, or insert a sample one
    var insert_form = $('#hljs-form').get(0);
    if (insert_form == undefined) {
      return;
    }
    e.preventDefault();
    var editor_name = window.opener.$.getEditorName();
    var editor = window.opener.CKEDITOR.instances[editor_name];
    var selected_text = editor.getSelection().getSelectedText() || '';
    var syntax = insert_form.syntax.value;
    var elt_code = new window.opener.CKEDITOR.dom.element('code');
    if (syntax != '') {
      elt_code.addClass('language-' + syntax);
    }
    if (selected_text == '') {
      elt_code.appendText('code');
    } else {
      elt_code.appendText(selected_text);
    }
    var elt_pre = new window.opener.CKEDITOR.dom.element('pre');
    elt_pre.append(elt_code);
    editor.insertElement(elt_pre);
    if (selected_text == '') {
      editor.getSelection().selectElement(elt_code);
    }
    window.close();
  });

  // Populate language list combo
  var sc = document.createElement('script');
  sc.src = hljs_path + 'lib/js/highlight' + (hljs_mode ? '-' + hljs_mode : '') + '.pack.js'; // URL
  sc.type = 'text/javascript';
  sc.onload = function() {
    // Load extension
    var sce = document.createElement('script');
    sce.src = hljs_path + 'lib/js/cbtpl.js'; // URL
    sce.type = 'text/javascript';
    sce.onload = function() {
      // Register extensions
      hljs.registerLanguage('cbtpl', hljsExtentCbtpl);
      // Get languages list
      var input = document.getElementById('syntax');
      var ll = hljs.listLanguages().sort();
      var l = t = null;
      ll.forEach(function(e) {
        l = hljs.getLanguage(e);
        t = e;
        if (typeof l.aliases !== 'undefined') {
          t = t + ', ' + l.aliases.join(", ");
        }
        // Add new option to input combolist (value = e, label = t)
        var option = document.createElement('option');
        option.text = t;
        option.value = e;
        input.add(option, null);
      });
    };
    document.getElementsByTagName('head')[0].appendChild(sce);
  };
  document.getElementsByTagName('head')[0].appendChild(sc);
});
