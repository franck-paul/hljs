/*global hljs, hljsExtentCbtpl, dotclear */
'use strict';

dotclear.ready(() => {
  // Show list of languages
  const listLanguages = (init) => {
    const script = document.createElement('script');
    const suffix = dotclear.hljs_config.mode ? `-${dotclear.hljs_config.mode}` : '';
    script.src = `${dotclear.hljs_config.path}lib/js/highlight${suffix}.pack.js`; // URL
    script.type = 'text/javascript';
    script.onload = () => {
      // Load extension if necessary
      const scriptExtension = document.createElement('script');
      scriptExtension.src = `${dotclear.hljs_config.path}lib/js/cbtpl.js`; // URL
      scriptExtension.type = 'text/javascript';
      scriptExtension.onload = () => {
        // Register extensions
        hljs.registerLanguage('cbtpl', hljsExtentCbtpl);
      };
      document.getElementsByTagName('head')[0].appendChild(scriptExtension);

      // Get languages list
      const languages = hljs.listLanguages().sort();
      let list = '';
      if (!init) {
        // Show diff between current choosen list and the selected one
        let allLanguages = languages.concat(dotclear.hljs_config.list.filter((item) => !languages.includes(item)));
        allLanguages = allLanguages.sort();
        for (const language of allLanguages) {
          if (list !== '') {
            list = `${list}, `;
          }
          if (!dotclear.hljs_config.list.includes(language)) {
            // Language added
            list = `${list}<ins>${language}</ins>`;
          } else if (!languages.includes(language)) {
            // Language removed
            list = `${list}<del>${language}</del>`;
          } else {
            list += language;
          }
        }
      } else {
        list = languages.join(', ');
      }
      document.getElementById('syntaxes').innerHTML = list ? `<br>${list}` : '';
      if (init) {
        // Store current list choosen
        dotclear.hljs_config.list = languages;
      }
    };
    document.getElementsByTagName('head')[0].appendChild(script);
  };

  // Update list of languages
  const selectMode = () => {
    const input = document.getElementById('mode');
    dotclear.hljs_config.mode = input.options[input.selectedIndex].value;
    listLanguages(false);
    dotclear.hljs_config.current_mode = dotclear.hljs_config.mode;
  };

  // Change theme CSS of code sample
  const selectTheme = () => {
    const input = document.getElementById('theme');
    const theme = input.options[input.selectedIndex].value || 'default';
    const css = document.querySelector(
      `link[href^="${dotclear.hljs_config.path}lib/css/${dotclear.hljs_config.previous_theme}.css"]`,
    );
    if (css) css.href = `${dotclear.hljs_config.path}lib/css/${theme}.css`;
    dotclear.hljs_config.previous_theme = theme;
  };

  // Change theme CSS of code sample on arrow key
  const nextTheme = (forward = true) => {
    const theme = document.getElementById('theme');
    let next = theme.selectedIndex;
    next = (forward ? ++next : --next + theme.options.length) % theme.options.length;
    theme.value = theme.options[next].value;
    selectTheme();
  };

  listLanguages(true);

  document.getElementById('theme')?.addEventListener('change', selectTheme);
  document.getElementById('mode')?.addEventListener('change', selectMode);

  document.getElementById('theme')?.addEventListener('keydown', (event) => {
    if (event.key === 'ArrowRight') {
      // Right arrow
      nextTheme(true);
      return false;
    }
    if (event.key === 'ArrowLeft') {
      // Left arrow
      nextTheme(false);
      return false;
    }
  });
});
