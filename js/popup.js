$(function() {
  // Cancel button fired
	$('#hljs-cancel').click(function() {
		window.close();
		return false;
	});

  // Ok button fired
	$('#hljs-ok').click(function() {
		sendClose();
		window.close();
		return false;
	});

	function sendClose() {
		var insert_form = $('#hljs-form').get(0);
		if (insert_form == undefined) { return; }
		var tb = window.opener.the_toolbar;
		var data = tb.elements.hljs.data;
		data.syntax = insert_form.syntax.value;
		tb.elements.hljs.fncall[tb.mode].call(tb);
	};

  // Populate language list combo
  var sc = document.createElement('script');
  sc.src = hljs_path + 'lib/js/highlight' + (hljs_mode ? '-' + hljs_mode : '') + '.pack.js'; // URL
  sc.type = 'text/javascript';
  sc.onload = function() {
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
  document.getElementsByTagName('head')[0].appendChild(sc);
});
