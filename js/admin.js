/*global $, hljs, hljsExtentCbtpl, hljs_config */
/*exported selectMode, selectTheme */
'use strict';

// Show list of languages
function listLanguages(init) {
  const sc = document.createElement('script');
  sc.src = `${hljs_config.path}lib/js/highlight${hljs_config.mode ? '-' + hljs_config.mode : ''}.pack.js`; // URL
  sc.type = 'text/javascript';
  sc.onload = function() {
    // Load extension
    const sce = document.createElement('script');
    sce.src = `${hljs_config.path}lib/js/cbtpl.js`; // URL
    sce.type = 'text/javascript';
    sce.onload = function() {
      // Register extensions
      hljs.registerLanguage('cbtpl', hljsExtentCbtpl);
      // Get languages list
      const ll = hljs.listLanguages().sort();
      let list = '';
      if (!init) {
        // Show diff between current choosen list and the selected one
        let full = ll.concat(hljs_config.list.filter(function(item) {
          return ll.indexOf(item) < 0;
        }));
        full = full.sort();
        full.forEach(function(e) {
          if (list !== '') {
            list = `${list}, `;
          }
          if (!hljs_config.list.includes(e)) {
            // Language added
            list = `${list}<ins>${e}</ins>`;
          } else if (!ll.includes(e)) {
            // Language removed
            list = `${list}<del>${e}</del>`;
          } else {
            list = list + e;
          }
        });
      } else {
        list = ll.join(', ');
      }
      document.getElementById('syntaxes').innerHTML = (list ? `<br />${list}` : '');
      if (init) {
        // Store current list choosen
        hljs_config.list = ll;
      }
    };
    document.getElementsByTagName('head')[0].appendChild(sce);
  };
  document.getElementsByTagName('head')[0].appendChild(sc);
}
// Update list of languages
function selectMode() {
  const input = document.getElementById('mode');
  hljs_config.mode = input.options[input.selectedIndex].value;
  listLanguages(false);
  hljs_config.current_mode = hljs_config.mode;
}
// Change theme CSS of code sample
function selectTheme() {
  const input = document.getElementById('theme');
  let theme = input.options[input.selectedIndex].value;
  if (theme == '') {
    theme = 'default';
  }
  const $css = $(`link[href^="${hljs_config.path}lib%2Fcss%2F${hljs_config.previous_theme}.css"]`);
  $css.attr('href', `${hljs_config.path}lib%2Fcss%2F${theme}.css`);
  hljs_config.previous_theme = theme;
}

$(document).ready(function() {
  listLanguages(true);
});
