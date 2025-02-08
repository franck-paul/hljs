/*global dotclear, hljs, hljsExtentCbtpl */
'use strict';

dotclear.ready(() => {
  // Cope with enter key in popup
  dotclear.enterKeyInForm('#hljs-form', '#hljs-ok', '#hljs-cancel');

  const hljs_config = dotclear.getData('hljs_config');

  // Populate language list combo
  const script = document.createElement('script');
  const suffix = hljs_config.mode ? `-${hljs_config.mode}` : '';
  script.src = `${hljs_config.path}lib/js/highlight${suffix}.pack.js`; // URL
  script.type = 'text/javascript';
  script.onload = () => {
    // Load extension
    const scriptExtension = document.createElement('script');
    scriptExtension.src = `${hljs_config.path}lib/js/cbtpl.js`; // URL
    scriptExtension.type = 'text/javascript';
    scriptExtension.onload = () => {
      // Register extensions
      hljs.registerLanguage('cbtpl', hljsExtentCbtpl);
      // Get languages list
      const input = document.getElementById('syntax');
      const languages = hljs.listLanguages().sort();
      for (const language of languages) {
        const languageDefinition = hljs.getLanguage(language);
        let languageLabel = language;
        if (typeof languageDefinition.aliases !== 'undefined') {
          languageLabel = `${languageLabel}, ${languageDefinition.aliases.join(', ')}`;
        }
        // Add new option to input combolist
        const option = document.createElement('option');
        option.text = languageLabel;
        option.value = language;
        input.add(option, null);
      }
    };
    document.getElementsByTagName('head')[0].appendChild(scriptExtension);
  };
  document.getElementsByTagName('head')[0].appendChild(script);
});
