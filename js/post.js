/*global jsToolBar, dotclear */
'use strict';

jsToolBar.prototype.elements.hljsSpace = {
  type: 'space',
  format: {
    wysiwyg: true,
    wiki: true,
    xhtml: true,
    markdown: true,
  },
};

jsToolBar.prototype.elements.hljs = {
  type: 'button',
  title: 'Highlighted Code',
  context: 'post',
  icon: 'index.php?pf=hljs/icon.svg',
  fn: {},
  fncall: {},
  open_url: 'plugin.php?p=hljs&popup=1',
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

jsToolBar.prototype.elements.hljs.title = dotclear.getData('hljs_editor', false).title;

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
  const stag = `<pre><code class="languague-${this.elements.hljs.data.syntax}">\n`;
  const etag = '\n</code></pre>\n';
  this.encloseSelection(stag, etag);
};
jsToolBar.prototype.elements.hljs.fncall.markdown = function () {
  const stag = `<pre><code class="language-${this.elements.hljs.data.syntax}">\n`;
  const etag = '\n</code></pre>\n';
  this.encloseSelection(stag, etag);
};
