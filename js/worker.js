self.onmessage = function(event) {
  var path = event.data[1] || ''; // Path URL of js
  var mode = event.data[2] || ''; // '' → std, 'mini', 'common', 'full'
  var syntax = event.data[3] || ''; // Syntax if specified in block

  // Load highlight.js script → loaded in hljs object
  self.importScripts(path + 'lib/js/highlight' + mode + '.pack.js');

  // Configure highlight.js script
  self.hljs.configure({
    tabReplace: '  '
  });

  // Run highlight.js
  if (syntax == '') {
    var result = self.hljs.highlightAuto(event.data[0]);
  } else {
    var result = self.hljs.highlightAuto(event.data[0], [syntax]);
  }

  // Return language detected (or set) and result
  self.postMessage({
    language: result.language, // Language detected
    value: result.value // HTML Result
  });
}
