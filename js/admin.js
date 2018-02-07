function selectTheme() {
  var input = document.getElementById('theme');
  var theme = input.options[input.selectedIndex].value;
  if (theme == '') { theme = 'default' };
  var $css = $('link[href^="' + hljs_path + 'lib%2Fcss%2F' + hljs_previous_theme + '.css"]');
  $css.attr('href', hljs_path + 'lib%2Fcss%2F' + theme + '.css');
  hljs_previous_theme = theme;
}
function listLanguages(init) {
  var sc = document.createElement('script');
  sc.src = hljs_path + 'lib/js/highlight' + (hljs_mode ? '-' + hljs_mode : '') + '.pack.js'; // URL
  sc.type = 'text/javascript';
  sc.onload = function() {
    var ll = hljs.listLanguages().sort();
    if (!init) {
      var full = ll.concat(hljs_list.filter(function (item) {
          return ll.indexOf(item) < 0;
      }));
      var list = '';
      full = full.sort();
      full.forEach(function(e) {
        if (list !== '') {
          list = list + ', ';
        }
        if (!hljs_list.includes(e)) {
          list = list + '<ins>' + e + '</ins>';
        } else if (!ll.includes(e)) {
          list = list + '<del>' + e + '</del>';
        } else {
          list = list + e;
        }
      })
    } else {
      var list = ll.join(", ");
    }
    document.getElementById("syntaxes").innerHTML = (list ? '<br />' + list : '');
    if (init) {
      hljs_list = ll;
    }
  };
  document.getElementsByTagName('head')[0].appendChild(sc);
}
function selectMode() {
  var input = document.getElementById('mode');
  hljs_mode = input.options[input.selectedIndex].value;
  listLanguages(false);
  hljs_current_mode = hljs_mode;
}
$(document).ready(function() {
  listLanguages(true);
});
