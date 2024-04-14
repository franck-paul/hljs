/*global dotclear, hljs, hljsExtentCbtpl */
'use strict';

dotclear.hljs_config = dotclear.getData('hljs_config');

// Set defaults
dotclear.hljs_config.path = dotclear.hljs_config.path || ''; // Path URL of js
dotclear.hljs_config.mode = dotclear.hljs_config.mode || ''; // '' → std, 'mini', 'common', 'full'
dotclear.hljs_config.show_line = dotclear.hljs_config.show_line && true; // Show/Hide line numbers
dotclear.hljs_config.badge = dotclear.hljs_config.badge || false; // Use or not web workers
dotclear.hljs_config.use_ww = dotclear.hljs_config.use_ww || false; // Use or not web workers
dotclear.hljs_config.yash = dotclear.hljs_config.yash && true; // Yash compatibility

// Test browser support of web workers
dotclear.hljs_config.ww = !!window.Worker;

dotclear.hljs = {
  // Utility function: hljsAddClass()
  hljsAddClass: (element, classname) => {
    const currentClassList = (element.className || '').split(/\s+/);
    currentClassList.push(currentClassList.includes(classname) ? '' : classname);
    element.className = currentClassList.join(' ').trim();
  },

  // Utility function: hljsDataLanguage()
  hljsDataLanguage: (element, syntax) => {
    if (
      dotclear.hljs_config.badge &&
      syntax !== undefined &&
      syntax !== 'undefined' &&
      syntax !== 'plain' &&
      syntax !== 'plaintext' &&
      syntax !== 'txt' &&
      syntax !== 'text'
    ) {
      element.dataset.language = syntax;
    }
    return element.dataset.language;
  },

  // highlight.js script loader
  hljsLoad: () => {
    if (!(!dotclear.hljs_config.ww || !dotclear.hljs_config.use_ww)) {
      return;
    }
    // Load highlight[-mode].js script → loaded in hljs object
    const hljs_sc = document.createElement('script');
    hljs_sc.src = `${dotclear.hljs_config.path}lib/js/highlight${
      dotclear.hljs_config.mode ? `-${dotclear.hljs_config.mode}` : ''
    }.pack.js`; // URL
    hljs_sc.type = 'text/javascript';
    if (typeof hljs_sc.async !== 'undefined') {
      hljs_sc.async = true;
    }
    document.getElementsByTagName('head')[0].appendChild(hljs_sc);
  },

  // highlight.js extensions script loader
  hljsLoadExtensions: () => {
    if (!(!dotclear.hljs_config.ww || !dotclear.hljs_config.use_ww)) {
      return;
    }
    // Load highlight[-mode].js script → loaded in hljs object
    const hljs_sc = document.createElement('script');
    hljs_sc.src = `${dotclear.hljs_config.path}lib/js/cbtpl.js`; // URL
    hljs_sc.type = 'text/javascript';
    if (typeof hljs_sc.async !== 'undefined') {
      hljs_sc.async = true;
    }
    document.getElementsByTagName('head')[0].appendChild(hljs_sc);
  },

  // highlight.js script runner
  hljsRun: () => {
    if (dotclear.hljs_config.yash) {
      // Encapsulate <pre class="brush:…" ></pre> content in <code></code> tag
      const yb = document.querySelectorAll('pre[class^="brush:"]');
      yb.forEach((block) => {
        block.innerHTML = `<code class="${block.className}">${block.innerHTML.trim()}</code>`;
      });
    }

    const sel = 'pre code:not(.nohighlight)';
    const blocks = document.querySelectorAll(sel);

    // Utility function to display line numbers
    const showLineNumber = (e) => {
      e.innerHTML = `<span class="hljs-line-number"></span>\n${e.innerHTML}\n<span class="hljs-cl"></span>`;
      const num = e.innerHTML.split(/\n/).length;
      for (let j = 0; j < num; j++) {
        const line_num = e.getElementsByTagName('span')[0];
        line_num.innerHTML += `<span>${j == 0 || j == num - 1 ? '&nbsp;' : j}</span>`;
      }
    };

    // Utility function to cope with copy button
    const copyButtonTemplate = new DOMParser().parseFromString(
      `<button class="hljs-copy-button">${dotclear.hljs_config.copy}</button>`,
      'text/html',
    ).body.firstChild;
    async function writeClipboardText(text) {
      try {
        await navigator.clipboard.writeText(text);
      } catch (error) {
        console.error(error.message);
      }
    }
    const createCopyButton = (block) => {
      const button = copyButtonTemplate.cloneNode(true);
      block.appendChild(button);
      // Cope click event on button
      button.addEventListener('click', () => {
        const text = [];
        block.childNodes.forEach(function check(child) {
          if (child.nodeType === Node.ELEMENT_NODE && child.tagName.toLowerCase() === 'button') {
            // Will ignore copy button text child
          } else if (
            child.nodeType !== Node.ELEMENT_NODE ||
            child.tagName.toLowerCase() !== 'span' ||
            !child.classList.contains('hljs-line-number')
          ) {
            if (child.nodeType === Node.TEXT_NODE) {
              text.push(child.nodeValue);
            }
            child.childNodes.forEach(check);
          }
        });
        writeClipboardText(text.join('').trim());
        button.textContent = dotclear.hljs_config.copied;
      });
      button.addEventListener('focusout', () => {
        if (button.textContent !== dotclear.hljs_config.copy) button.textContent = dotclear.hljs_config.copy;
      });
    };

    // Main loop
    blocks.forEach((block) => {
      // Ensure that hljs class is set
      dotclear.hljs.hljsAddClass(block, 'hljs');
      // Add wrapper class to parent
      dotclear.hljs.hljsAddClass(block.parentNode, 'hljs-wrapper');
      // Add no gutter class if necessary
      if (!dotclear.hljs_config.show_line) {
        dotclear.hljs.hljsAddClass(block, 'hljs-no-gutter');
      }

      // Trim content from newlines
      block.textContent = block.textContent.trim();

      // Run engine
      let cls;
      let syntax = '';
      let brush;
      if (dotclear.hljs_config.ww && dotclear.hljs_config.use_ww) {
        // Web workers mode
        // Get specified syntax if any
        cls = block.className;
        // Standard mode (<pre><code [class=language-<syntax>]>…</code></pre>)
        brush = cls.match(/\blanguage-(\w*)\b/);
        if (dotclear.hljs_config.yash && (!brush || brush.length !== 2)) {
          // Yash mode (<pre brush:<syntax>…</pre>)
          brush = cls.match(/\bbrush:(\w*)\b/);
        }
        if (brush && brush.length == 2) {
          syntax =
            brush[1] == 'plain' || brush[1] == 'txt' || brush[1] == 'text' || brush[1] == 'plaintext' ? 'plaintext' : brush[1];
        }

        // Create web worker
        const worker = new Worker(`${dotclear.hljs_config.path}worker.js`);
        // Cope with web worker returned message
        worker.onmessage = (event) => {
          // Web worker send result
          block.innerHTML = event.data.result;
          const syntax = event.data.language;
          dotclear.hljs.hljsAddClass(block, syntax);
          dotclear.hljs.hljsDataLanguage(block, syntax);
          if (dotclear.hljs_config.show_line) {
            showLineNumber(block);
          }
          // Creation bouton
          if (dotclear.hljs_config.show_copy) createCopyButton(block);
        };
        // Run web worker
        worker.postMessage([block.textContent, dotclear.hljs_config.path, dotclear.hljs_config.mode, syntax]);
        return;
      }
      // Standard mode
      // Register extensions
      hljs.registerLanguage('cbtpl', hljsExtentCbtpl);

      // If YASH, keep brush if not plain or txt:
      // - Get syntax in <code class="brush:syntax">
      // - Test if not plain/txt and if it is supported by highlight.js and
      //     - if yes set class="language-syntax" to block
      //     - if no set class="hljs plain" to block
      cls = block.className;
      syntax = 'plain';
      brush = cls.match(/\blanguage-(\w*)\b/);
      let yash = false;
      if (dotclear.hljs_config.yash && (!brush || brush.length !== 2)) {
        // Yash mode (<pre brush:<syntax>…</pre>)
        brush = cls.match(/\bbrush:(\w*)\b/);
        if (brush && brush.length == 2) {
          yash = true;
        }
      }
      if (
        brush &&
        brush.length == 2 &&
        brush[1] != 'plain' &&
        brush[1] != 'plaintext' &&
        brush[1] != 'txt' &&
        brush[1] != 'text' &&
        hljs.getLanguage(brush[1])
      ) {
        syntax = brush[1];
      }
      // Set class : will be used by highlight.js
      if (yash) {
        dotclear.hljs.hljsAddClass(block, syntax);
      }
      dotclear.hljs.hljsDataLanguage(block, syntax);
      // Configure highlight.js script
      hljs.configure({
        tabReplace: '  ',
      });
      // Run highlight.js
      hljs.highlightBlock(block);
      if (dotclear.hljs_config.show_line) {
        showLineNumber(block);
      }
      if (dotclear.hljs.hljsDataLanguage(block) === undefined) {
        cls = block.className.split(' ');
        cls.forEach((syntax) => {
          if (hljs.getLanguage(syntax)) {
            dotclear.hljs.hljsDataLanguage(block, syntax);
          }
        });
      }
      // Creation bouton
      if (dotclear.hljs_config.show_copy) createCopyButton(block);
    });
  },
};

dotclear.hljs.hljsLoad();
dotclear.hljs.hljsLoadExtensions();

dotclear.ready(() => {
  dotclear.hljs.hljsRun();
});
