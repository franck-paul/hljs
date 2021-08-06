/*global self, hljsExtentCbtpl */
'use strict';

self.onmessage = function (event) {
  const path = event.data[1] || ''; // Path URL of js
  const mode = event.data[2] || ''; // '' → std, 'mini', 'common', 'full'
  let syntax = event.data[3] || ''; // Syntax if specified in block
  let result;

  // Load highlight.js script → loaded in hljs object
  self.importScripts(`${path}lib/js/highlight${mode ? '-' + mode : ''}.pack.js`);
  // Load highlight.js extensions
  self.importScripts(`${path}lib/js/cbtpl.js`);

  // Register extensions
  self.hljs.registerLanguage('cbtpl', hljsExtentCbtpl);

  // Configure highlight.js script
  self.hljs.configure({
    tabReplace: '  ',
  });

  // Run highlight.js
  if (syntax == '') {
    result = self.hljs.highlightAuto(event.data[0]);
  } else {
    result = self.hljs.highlightAuto(event.data[0], [syntax]);
  }
  // Fix Markup as it is not done internally when using highlightAuto()
  result.value = self.hljs.fixMarkup(result.value);
  if (syntax == '' && result.language !== undefined && result.language !== '') {
    syntax = result.language;
  }

  // Return language detected (or set) and result
  self.postMessage({
    language: syntax, // Language detected or specified
    result: result.value, // HTML Result
  });
};
