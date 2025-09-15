/*global jsToolBar, dotclear */
'use strict';

dotclear.ready(() => {
  const data = dotclear.getData('hljs_editor', false);

  jsToolBar.prototype.elements.hljs = {
    group: 'block',
    type: 'button',
    title: 'Highlighted Code',
    context: 'post',
    icon: data.icon,
    icon_dark: data.icon_dark,
    shortkey: 'KeyJ',
    shortkey_name: 'J',
    fn: {},
    fncall: {},
    open_url: data.open_url,
    data: {},
    popup() {
      window.the_toolbar = this;
      this.elements.hljs.data = {};

      window.open(
        this.elements.hljs.open_url,
        'dc_popup',
        'alwaysRaised=yes,dependent=yes,toolbar=yes,height=240,width=480,menubar=no,resizable=yes,scrollbars=yes,status=no',
      );
    },
  };

  jsToolBar.prototype.elements.hljs.title = data.title;

  jsToolBar.prototype.elements.hljs.fn.wiki = function () {
    this.elements.hljs.popup.call(this);
  };
  jsToolBar.prototype.elements.hljs.fn.xhtml = function () {
    this.elements.hljs.popup.call(this);
  };
  jsToolBar.prototype.elements.hljs.fn.markdown = function () {
    this.elements.hljs.popup.call(this);
  };

  jsToolBar.prototype.elements.hljs.fncall.wiki = function () {
    const stag = `\n///hljs ${this.elements.hljs.data.syntax}\n`;
    const etag = '\n///\n';
    this.encloseSelection(stag, etag);
  };
  jsToolBar.prototype.elements.hljs.fncall.xhtml = function () {
    const stag = `<pre><code class="language-${this.elements.hljs.data.syntax}">\n`;
    const etag = '\n</code></pre>\n';
    this.encloseSelection(stag, etag);
  };
  jsToolBar.prototype.elements.hljs.fncall.markdown = function () {
    const stag = `\`\`\`language-${this.elements.hljs.data.syntax}\n`;
    const etag = '\n```\n';
    this.encloseSelection(stag, etag);
  };
});
