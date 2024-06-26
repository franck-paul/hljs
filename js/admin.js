/*global $, hljs, hljsExtentCbtpl, dotclear */
'use strict';

// Show list of languages
dotclear.hljs_config.listLanguages = (init) => {
  const sc = document.createElement('script');
  sc.src = `${dotclear.hljs_config.path}lib/js/highlight${
    dotclear.hljs_config.mode ? `-${dotclear.hljs_config.mode}` : ''
  }.pack.js`; // URL
  sc.type = 'text/javascript';
  sc.onload = () => {
    // Load extension
    const sce = document.createElement('script');
    sce.src = `${dotclear.hljs_config.path}lib/js/cbtpl.js`; // URL
    sce.type = 'text/javascript';
    sce.onload = () => {
      // Register extensions
      hljs.registerLanguage('cbtpl', hljsExtentCbtpl);
      // Get languages list
      const ll = hljs.listLanguages().sort();
      let list = '';
      if (!init) {
        // Show diff between current choosen list and the selected one
        let full = ll.concat(dotclear.hljs_config.list.filter((item) => !ll.includes(item)));
        full = full.sort();
        full.forEach((e) => {
          if (list !== '') {
            list = `${list}, `;
          }
          if (!dotclear.hljs_config.list.includes(e)) {
            // Language added
            list = `${list}<ins>${e}</ins>`;
          } else if (!ll.includes(e)) {
            // Language removed
            list = `${list}<del>${e}</del>`;
          } else {
            list += e;
          }
        });
      } else {
        list = ll.join(', ');
      }
      document.getElementById('syntaxes').innerHTML = list ? `<br>${list}` : '';
      if (init) {
        // Store current list choosen
        dotclear.hljs_config.list = ll;
      }
    };
    document.getElementsByTagName('head')[0].appendChild(sce);
  };
  document.getElementsByTagName('head')[0].appendChild(sc);
};
// Update list of languages
dotclear.hljs_config.selectMode = () => {
  const input = document.getElementById('mode');
  dotclear.hljs_config.mode = input.options[input.selectedIndex].value;
  dotclear.hljs_config.listLanguages(false);
  dotclear.hljs_config.current_mode = dotclear.hljs_config.mode;
};
// Change theme CSS of code sample
dotclear.hljs_config.selectTheme = () => {
  const input = document.getElementById('theme');
  let theme = input.options[input.selectedIndex].value;
  if (theme == '') {
    theme = 'default';
  }
  const $css = $(`link[href^="${dotclear.hljs_config.path}lib/css/${dotclear.hljs_config.previous_theme}.css"]`);
  $css.attr('href', `${dotclear.hljs_config.path}lib/css/${theme}.css`);
  dotclear.hljs_config.previous_theme = theme;
};
// Change theme CSS of code sample on arrow key
dotclear.hljs_config.nextTheme = (forward = true) => {
  const e = document.getElementById('theme');
  let next = e.selectedIndex;
  next = (forward ? ++next : --next + e.options.length) % e.options.length;
  e.value = e.options[next].value;
  dotclear.hljs_config.selectTheme();
};

dotclear.ready(() => {
  dotclear.hljs_config.listLanguages(true);
  $('#theme').on('change', dotclear.hljs_config.selectTheme);
  $('#mode').on('change', dotclear.hljs_config.selectMode);
  $('#theme').on('keydown', (e) => {
    if (e.which === 39) {
      // Right arrow
      dotclear.hljs_config.nextTheme(true);
      return false;
    } else if (e.which === 37) {
      // Left arrow
      dotclear.hljs_config.nextTheme(false);
      return false;
    }
  });
});
