<?php
/**
 * @brief hljs, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul and contributors
 *
 * @copyright Franck Paul carnet.franck.paul@gmail.com
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class hljsBehaviors
{
    public static function adminPostEditor($editor = '', $context = '', array $tags = [], $syntax = '')
    {
        global $core;

        if ($editor != 'dcLegacyEditor' && $editor != 'dcCKEditor') {
            return;
        }

        if ($editor == 'dcLegacyEditor') {
            return
            dcPage::jsJson('hljs_editor', [
                'title' => __('Highlighted Code'),
            ]) .
            dcPage::jsLoad(urldecode(dcPage::getPF('hljs/js/post.js')), $core->getVersion('hljs'));
        }
        $url = $core->adminurl->get('admin.plugin.hljs', ['popup' => 1, 'plugin_id' => 'dcCKEditor'], '&');
        $url = urldecode($url);

        return
            dcPage::jsJson('hljs_editor', [
                'title'     => __('Highlighted Code'),
                'popup_url' => $url,
            ]);
    }

    public static function ckeditorExtraPlugins(ArrayObject $extraPlugins, $context = '')
    {
        $extraPlugins[] = [
            'name'   => 'hljs',
            'button' => 'hljs',
            'url'    => DC_ADMIN_URL . 'index.php?pf=hljs/cke-addon/',
        ];
    }

    public static function coreInitWikiPost($wiki2xhtml)
    {
        global $core;

        $wiki2xhtml->registerFunction('macro:hljs', ['hljsBehaviors', 'transform']);
        if ((bool) $core->blog->settings->hljs->code) {
            $wiki2xhtml->registerFunction('macro:code', ['hljsBehaviors', 'transform']);
        }

        $core->blog->settings->addNameSpace('hljs');

        if ((bool) $core->blog->settings->hljs->yash) {
            // Add Yash compatibility macro
            $wiki2xhtml->registerFunction('macro:yash', ['hljsBehaviors', 'transformYash']);
        }

        if ((bool) $core->blog->settings->hljs->syntaxehl) {
            // Add syntaxehl compatibility macros
            foreach (self::$syntaxehl_brushes as $brush => $alias) {
                $wiki2xhtml->registerFunction('macro:[' . $brush . ']', ['hljsBehaviors', 'transformSyntaxehl']);
            }
        }
    }

    public static function transform($text, $args)
    {
        $text      = trim($text);
        $real_args = explode(' ', $args);
        $class     = empty($real_args[1]) ? '' : ' class="language-' . $real_args[1] . '"';

        return '<pre><code' . $class . '>' . htmlspecialchars($text) . '</code></pre>';
    }

    public static function transformYash($text, $args)
    {
        // Try to find a supported language, if not do not add class and let highlight engine doing syntax recognition
        $text      = trim($text);
        $real_args = explode(' ', $args);
        $syntax    = empty($real_args[1]) ? 'plain' : $real_args[1];
        $class     = array_key_exists($syntax, self::$yash_brushes) && self::$yash_brushes[$syntax] != '' ?
        ' class="language-' . self::$yash_brushes[$syntax] . '"' :
        '';

        return '<pre><code' . $class . '>' . htmlspecialchars($text) . '</code></pre>';
    }

    public static function transformSyntaxehl($text, $args)
    {
        // Try to find a supported language, if not set original
        $text      = trim($text);
        $real_args = preg_replace('/^(\[(.*)\]$)/', '$2', $args);
        $class     = array_key_exists($real_args, self::$syntaxehl_brushes) && self::$syntaxehl_brushes[$real_args] != '' ? self::$syntaxehl_brushes[$real_args] : $real_args;

        return '<pre><code class="language-' . $class . '">' . htmlspecialchars($text) . '</code></pre>';
    }

    // Private

    // List of Yash aliases
    private static $yash_brushes = [
        'plain'       => 'plain',
        'txt'         => 'plain',
        'applescript' => 'applescript',
        'as3'         => 'actionscript',
        'bash'        => 'bash',
        'cf'          => '',
        'csharp'      => 'cs',
        'cpp'         => 'cpp',
        'css'         => 'css',
        'delphi'      => 'delphi',
        'diff'        => 'diff',
        'erl'         => 'erlang',
        'groovy'      => 'groovy',
        'haxe'        => 'haxe',
        'js'          => 'javascript',
        'java'        => 'java',
        'jfx'         => '',
        'pl'          => 'perl',
        'php'         => 'php',
        'ps'          => 'powershell',
        'python'      => 'python',
        'ruby'        => 'ruby',
        'sass'        => 'scss',
        'scala'       => 'scala',
        'sql'         => 'sql',
        'tap'         => 'tap',
        'ts'          => 'typescript',
        'vb'          => 'vbnet',
        'xml'         => 'xml',
        'yaml'        => 'yaml',
    ];

    // List of SyntaxHL aliases
    private static $syntaxehl_brushes = [
        '4cs'           => '',
        'abap'          => '',
        'actionscript'  => 'actionscript',
        'ada'           => 'ada',
        'apache'        => 'apache',
        'applescript'   => 'applescript',
        'apt_sources'   => '',
        'asm'           => 'x86asm',
        'asp'           => '',
        'autoconf'      => '',
        'autohotkey'    => 'autohotkey',
        'autoit'        => 'autoit',
        'avisynth'      => '',
        'awk'           => 'awk',
        'bash'          => 'bash',
        'basic4gl'      => 'basic',
        'bf'            => 'brainfuck',
        'bibtex'        => '',
        'blitzbasic'    => 'basic',
        'bnf'           => 'bnf',
        'boo'           => '',
        'c'             => 'cpp',
        'c_mac'         => 'cpp',
        'caddcl'        => '',
        'cadlisp'       => 'lisp',
        'cfdg'          => '',
        'cfm'           => '',
        'chaiscript'    => '',
        'cil'           => '',
        'clojure'       => 'clojure',
        'cmake'         => 'cmake',
        'cobol'         => '',
        'cpp-qt'        => 'cpp',
        'cpp'           => 'cpp',
        'csharp'        => 'cs',
        'css'           => 'css',
        'cuesheet'      => '',
        'd'             => 'd',
        'dcs'           => '',
        'delphi'        => 'delphi',
        'diff'          => 'diff',
        'div'           => '',
        'dos'           => 'dos',
        'dot'           => '',
        'ecmascript'    => 'javasript',
        'eiffel'        => '',
        'email'         => '',
        'erlang'        => 'erlang',
        'fo'            => '',
        'fortran'       => 'fortran',
        'freebasic'     => 'basic',
        'fsharp'        => 'fsharp',
        'gambas'        => '',
        'gdb'           => '',
        'genero'        => '',
        'genie'         => '',
        'gettext'       => '',
        'glsl'          => 'glsl',
        'gml'           => '',
        'gnuplot'       => '',
        'groovy'        => 'groovy',
        'gwbasic'       => 'basic',
        'haskell'       => 'haskell',
        'hicest'        => '',
        'hq9plus'       => '',
        'html4strict'   => 'xml',
        'icon'          => '',
        'idl'           => '',
        'ini'           => 'ini',
        'inno'          => 'delphi',
        'intercal'      => '',
        'io'            => '',
        'j'             => '',
        'java'          => 'java',
        'java5'         => 'java',
        'javascript'    => 'javascript',
        'jquery'        => 'javascript',
        'kixtart'       => '',
        'klonec'        => 'cpp',
        'klonecpp'      => 'cpp',
        'latex'         => '',
        'lisp'          => 'lisp',
        'locobasic'     => 'basic',
        'logtalk'       => '',
        'lolcode'       => '',
        'lotusformulas' => '',
        'lotusscript'   => '',
        'lscript'       => '',
        'lsl2'          => 'lsl',
        'lua'           => 'lua',
        'm68k'          => '',
        'magiksf'       => '',
        'make'          => 'makefile',
        'mapbasic'      => '',
        'matlab'        => 'matlab',
        'mirc'          => '',
        'mmix'          => '',
        'modula2'       => '',
        'modula3'       => '',
        'mpasm'         => '',
        'mxml'          => 'xml',
        'mysql'         => 'sql',
        'newlisp'       => 'lisp',
        'nsis'          => 'nsis',
        'oberon2'       => '',
        'objc'          => 'objectivec',
        'ocaml-brief'   => 'ocaml',
        'ocaml'         => 'ocaml',
        'oobas'         => '',
        'oracle11'      => 'sql',
        'oracle8'       => 'sql',
        'oxygene'       => 'oxygene',
        'oz'            => '',
        'pascal'        => '',
        'pcre'          => '',
        'per'           => '',
        'perl'          => 'perl',
        'perl6'         => 'perl',
        'pf'            => 'pf',
        'php-brief'     => 'php',
        'php'           => 'php',
        'pic16'         => '',
        'pike'          => '',
        'pixelbender'   => '',
        'plsql'         => 'sql',
        'postgresql'    => 'sql',
        'povray'        => '',
        'powerbuilder'  => '',
        'powershell'    => 'powershell',
        'progress'      => '',
        'prolog'        => 'prolog',
        'properties'    => '',
        'providex'      => '',
        'purebasic'     => 'purebasic',
        'python'        => 'python',
        'q'             => 'q',
        'qbasic'        => 'basic',
        'rails'         => 'ruby',
        'rebol'         => '',
        'reg'           => '',
        'robots'        => '',
        'rpmspec'       => '',
        'rsplus'        => '',
        'ruby'          => 'ruby',
        'sas'           => '',
        'scala'         => 'scala',
        'scheme'        => 'scheme',
        'scilab'        => 'scilab',
        'sdlbasic'      => 'basic',
        'smalltalk'     => 'smalltalk',
        'smarty'        => '',
        'sql'           => 'sql',
        'systemverilog' => 'verilog',
        'tcl'           => 'tcl',
        'teraterm'      => '',
        'text'          => 'text',
        'thinbasic'     => 'basic',
        'tsql'          => 'sql',
        'typoscript'    => '',
        'unicon'        => '',
        'vala'          => 'vala',
        'vb'            => 'vbnet',
        'vbnet'         => 'vbnet',
        'verilog'       => 'verilog',
        'vhdl'          => 'vhdl',
        'vim'           => 'vim',
        'visualfoxpro'  => '',
        'visualprolog'  => 'prolog',
        'whitespace'    => '',
        'whois'         => '',
        'winbatch'      => '',
        'xbasic'        => '',
        'xml'           => 'xml',
        'xorg_conf'     => '',
        'xpp'           => '',
        'z80'           => '',
    ];
}
