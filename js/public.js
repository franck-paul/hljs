// Set defaults
var hljs_path = hljs_path || ''; // Path URL of js
var hljs_mode = hljs_mode || ''; // '' → std, 'mini', 'common', 'full'
var hljs_show_line = hljs_show_line && true; // Show/Hide line numbers
var hljs_use_ww = hljs_use_ww || false; // Use or not web workers
var hljs_yash = hljs_yash && true; // Yash compatibility

// Test browser support of web workers
var hljs_ww = !!window.Worker;

var hljsLoad = function() {
  if (!hljs_ww || !hljs_use_ww) {
    // Load highlight[-mode].js script → loaded in hljs object
    var hljs_sc = document.createElement('script');
    hljs_sc.src = hljs_path + 'lib/js/highlight' + (hljs_mode ? '-' + hljs_mode : '') + '.pack.js'; // URL
    hljs_sc.type = 'text/javascript';
    if (typeof hljs_sc['async'] !== 'undefined') {
      hljs_sc.async = true;
    }
    document.getElementsByTagName('head')[0].appendChild(hljs_sc);
  }
}

var hljsRun = function() {
  if (!hljs_ww || !hljs_use_ww) {
    // Configure highlight.js script
    hljs.configure({
      tabReplace: '  '
    });
  }

  var sel = 'pre code' + (hljs_yash ? ', pre[class^="brush:"]' : '');
  $(sel).each(function(i, block) {
    var $elt = $(this);

    // Get DOM element
    var elt = $elt[0];

    // Utility function to display line numbers
    var showLineNumber = function(e) {
      e.innerHTML =
        '<span class="hljs-line-number"></span>' +
        '\n' + e.innerHTML + '\n' +
        '<span class="hljs-cl"></span>';
      var num = e.innerHTML.split(/\n/).length;
      for (var j = 0; j < num; j++) {
        var line_num = e.getElementsByTagName('span')[0];
        line_num.innerHTML += '<span>' + (j == 0 || j == num - 1 ? '&nbsp;' : j) + '</span>';
      }
    };

    // Ensure that hljs class is set
    $elt.addClass('hljs');
    // Add wrapper class to parent
    if (elt.tagName == 'CODE') {
      $elt.parent().addClass('hljs-wrapper');
    } else {
      $elt.addClass('hljs-wrapper');
    }
    // Add no gutter class if necessary
    if (!hljs_show_line) {
      $elt.addClass('hljs-no-gutter');
    }

    // Trim content from newlines
    elt.textContent = elt.textContent.trim();

    // Run engine
    if (hljs_ww && hljs_use_ww) {
      // Get specified syntax if any
      var cls = block.className;
      var syntax = '';
      if (block.tagName == 'CODE') {
        // Standard mode (<pre><code [class=language-<syntax>]>…</code></pre>)
        var brush = cls.match(/\blanguage\-(\w*)\b/);
      } else {
        // Yash mode (<pre brush:<syntax>…</pre>)
        var brush = cls.match(/\bbrush\:(\w*)\b/);
      }
      if (brush && brush.length == 2) {
        if (brush[1] == 'plain' || brush[1] == 'txt' || brush[1] == 'text') {
          syntax = 'plain';
        } else {
          syntax = brush[1];
        }
      }
      // Create web worker
      var worker = new Worker(hljs_path + 'worker.js');
      // Cope with web worker returned message
      worker.onmessage = function(event) {
        // Web worker send result
        elt.innerHTML = event.data.result;
        $elt.addClass(event.data.language);
        if (hljs_show_line) {
          showLineNumber(elt);
        }
      }
      // Run web worker
      worker.postMessage([elt.textContent, hljs_path, hljs_mode, syntax]);
    } else {
      // If YASH, keep brush if not plain or txt:
      // - Get syntax in <pre class="brush:syntax">
      // - Test if not plain/txt and if it is supported by highlight.js and
      //     - if yes set class="language-syntax" to block
      //     - if no set class="hljs plain" to block
      if (block.tagName == 'PRE') {
        var cls = block.className;
        var brush = cls.match(/\bbrush\:(\w*)\b/);
        var syntax = 'plain';
        if (brush && brush.length == 2) {
          if (brush[1] != 'plain' && brush[1] != 'txt') {
            if (hljs.getLanguage(brush[1])) {
              syntax = 'language-' + brush[1];
            }
          }
        }
        // Set class : will be used by highlight.js
        $elt.addClass(syntax);
      }
      // Run highlight.js
      hljs.highlightBlock(block);
      if (hljs_show_line) {
        showLineNumber(elt);
      }
    }
  });
};

hljsLoad();
addEventListener('load', function() {
  hljsRun();
})
