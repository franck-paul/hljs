/*global self, hljsExtentCbtpl */
'use strict';

globalThis.onmessage = (event) => {
  const path = event.data[1] || ''; // Path URL of js
  const mode = event.data[2] || ''; // '' → std, 'mini', 'common', 'full'
  let syntax = event.data[3] || ''; // Syntax if specified in block

  const suffix = mode ? `-${mode}` : '';
  let result;

  // Load highlight.js script → loaded in hljs object
  globalThis.importScripts(`${path}lib/js/highlight${suffix}.pack.js`);
  // Load highlight.js extensions
  globalThis.importScripts(`${path}lib/js/cbtpl.js`);

  // Register extensions
  globalThis.hljs.registerLanguage('cbtpl', hljsExtentCbtpl);

  // Configure highlight.js script
  globalThis.hljs.configure({
    tabReplace: '  ',
  });

  // Run highlight.js
  result = syntax === '' ? globalThis.hljs.highlightAuto(event.data[0]) : globalThis.hljs.highlightAuto(event.data[0], [syntax]);
  // Fix Markup as it is not done internally when using highlightAuto()
  result.value = globalThis.hljs.fixMarkup(result.value);
  if (syntax === '' && result.language !== undefined && result.language !== '') {
    syntax = result.language;
  }

  // Return language detected (or set) and result
  self.postMessage({
    language: syntax, // Language detected or specified
    result: result.value, // HTML Result
  });
};
