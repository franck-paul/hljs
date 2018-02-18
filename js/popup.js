/*global $, hljs, hljs_path, hljs_mode:true, hljsExtentCbtpl */
'use strict';

$(function() {
  $('#hljs-form').keyup(function(e) {
    // Cope with Escape key anyway in form
    if (e.key == 'Escape') {
      e.preventDefault();
      $('#hljs-cancel').trigger('click');
    }
  });

  $('#hljs-ok, #syntax').keyup(function(e) {
    // Cope with return key on syntax select or Ok button
    if (e.key == 'Enter') {
      e.preventDefault();
      $('#hljs-ok').trigger('click');
    }
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
      var l = null;
      var t = null;
      ll.forEach(function(e) {
        l = hljs.getLanguage(e);
        t = e;
        if (typeof l.aliases !== 'undefined') {
          t = t + ', ' + l.aliases.join(', ');
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
