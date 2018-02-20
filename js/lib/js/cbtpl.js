/*exported hljsExtentCbtpl */
'use strict';
/*
Language: cbtpl
Requires: xml.js
Author: Franck Paul <carnet.franck.paul@gmail.com>
Description: Clearbricks templates (used by Dotclear)
Category: template
*/
var hljsExtentCbtpl = function(hljs) {
  var e = "[A-Za-z0-9\\._:-]+",
    t = {
      eW: !0,
      i: /</,
      r: 0,
      c: [{
        cN: "attr",
        b: e,
        r: 0
      }, {
        b: /=\s*/,
        r: 0,
        c: [{
          cN: "string",
          endsParent: !0,
          v: [{
            b: /"/,
            e: /"/
          }, {
            b: /'/,
            e: /'/
          }, {
            b: /[^\s"'=<>`]+/
          }]
        }]
      }]
    };
  return {
    aliases: ['dctpl'],
    cI: false,
    sL: 'xml',
    c: [
      hljs.C('<!-- #', '-->'), {
        cN: 'template-tag',
        b: /<tpl:?/,
        e: /\>/,
        r: 10,
        c: [{
            cN: "name",
            b: /[^}}{{\/><\s]+/,
            r: 0
        }, t]
      }, {
        cN: 'template-tag',
        b: /<\/tpl:?/,
        e: /\>/,
        r: 10,
        c: [{
            cN: "name",
            b: /[^}}{{\/><\s]+/,
            r: 0
        }]
      }, {
        cN: 'template-variable',
        b: /{{tpl:?/,
        e: /}}/,
        r: 10,
        c: [{
            cN: "name",
            b: /[^}}{{\/><\s]+/,
            r: 0
        }, t]
      }
    ]
  };
};
