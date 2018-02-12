/*
Language: cbtpl
Requires: xml.js
Author: Franck Paul <carnet.franck.paul@gmail.com>
Description: Clearbricks templates (used by Dotclear)
Category: template
*/
var hljsExtentCbtpl = function(hljs) {
  return {
    aliases: ['dctpl'],
    cI: false,
    sL: 'xml',
    c: [
      hljs.C('<!-- #', '-->'),
      {
        cN: 'template-tag',
        b: /\<tpl:/, e: /\>/,
        r: 10
      },
      {
        cN: 'template-tag',
        b: /\<\/tpl:?/, e: /\>/,
        r: 10
      },
      {
        cN: 'template-variable',
        b: /\{\{tpl:/, e: /\}\}/,
        r: 10
      }
    ]
  };
};
