/*global dotclear, $, hljs, hljsExtentCbtpl */
'use strict';

$(() => {
  // Cope with enter key in popup
  dotclear.enterKeyInForm('#hljs-form', '#hljs-ok', '#hljs-cancel');

  let hljs_config = dotclear.getData('hljs_config');

  // Populate language list combo
  const sc = document.createElement('script');
  sc.src = `${hljs_config.path}lib/js/highlight${hljs_config.mode ? `-${hljs_config.mode}` : ''}.pack.js`; // URL
  sc.type = 'text/javascript';
  sc.onload = () => {
    // Load extension
    const sce = document.createElement('script');
    sce.src = `${hljs_config.path}lib/js/cbtpl.js`; // URL
    sce.type = 'text/javascript';
    sce.onload = () => {
      // Register extensions
      hljs.registerLanguage('cbtpl', hljsExtentCbtpl);
      // Get languages list
      const input = document.getElementById('syntax');
      const ll = hljs.listLanguages().sort();
      let l = null;
      let t = null;
      ll.forEach((e) => {
        l = hljs.getLanguage(e);
        t = e;
        if (typeof l.aliases !== 'undefined') {
          t = `${t}, ${l.aliases.join(', ')}`;
        }
        // Add new option to input combolist (value = e, label = t)
        const option = document.createElement('option');
        option.text = t;
        option.value = e;
        input.add(option, null);
      });
    };
    document.getElementsByTagName('head')[0].appendChild(sce);
  };
  document.getElementsByTagName('head')[0].appendChild(sc);
});
