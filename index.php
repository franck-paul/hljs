<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of hljs, a plugin for Dotclear 2.
#
# Copyright (c) Franck Paul and contributors
# carnet.franck.paul@gmail.com
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_CONTEXT_ADMIN')) {exit;}

// Getting current parameters if any (get global parameters if not)
$core->blog->settings->addNamespace('hljs');
$active      = (boolean) $core->blog->settings->hljs->active;
$mode        = (string) $core->blog->settings->hljs->mode;
$theme       = (string) $core->blog->settings->hljs->theme;
$custom_css  = (string) $core->blog->settings->hljs->custom_css;
$hide_gutter = (boolean) $core->blog->settings->hljs->hide_gutter;
$web_worker  = (boolean) $core->blog->settings->hljs->web_worker;
$yash        = (boolean) $core->blog->settings->hljs->yash;
$syntaxehl   = (boolean) $core->blog->settings->hljs->syntaxehl;

if (!empty($_REQUEST['popup'])) {
    $hljs_brushes = array(
        'plain'       => __('Plain Text'),
        'applescript' => __('AppleScript'),
        'as3'         => __('ActionScript3'),
        'bash'        => __('Bash/shell'),
        'cf'          => __('ColdFusion'),
        'csharp'      => __('C#'),
        'cpp'         => __('C/C++'),
        'css'         => __('CSS'),
        'delphi'      => __('Delphi'),
        'diff'        => __('Diff/Patch'),
        'erl'         => __('Erlang'),
        'groovy'      => __('Groovy'),
        'haxe'        => __('Haxe'),
        'js'          => __('Javascript/JSON'),
        'java'        => __('Java'),
        'jfx'         => __('JavaFX'),
        'pl'          => __('Perl'),
        'php'         => __('PHP'),
        'ps'          => __('PowerShell'),
        'python'      => __('Python'),
        'ruby'        => __('Ruby'),
        'sass'        => __('SASS'),
        'scala'       => __('Scala'),
        'sql'         => __('SQL'),
        'tap'         => __('Tap'),
        'ts'          => __('TypeScript'),
        'vb'          => __('Visual Basic'),
        'xml'         => __('XML/XSLT/XHTML/HTML'),
        'yaml'        => __('Yaml')
    );

    echo
    '<html>' .
    '<head>' .
    '<title>' . __('Code highlight - Syntax Selector') . '</title>' .
    dcPage::jsLoad(urldecode(dcPage::getPF('hljs/js/popup.js')), $core->getVersion('hljs')) .
    '</head>' .
    '<body>' .
    '<h2>' . __('Code highlight - Syntax Selector') . '</h2>' .
    '<form id="hljs-form" action="' . $p_url . '&amp;popup=1" method="get">' .
    '<p><label>' . __('Select the primary syntax of your code snippet.') .
    form::combo('syntax', array_flip($hljs_brushes)) . '</label></p>' .
    '<p><button id="hljs-cancel">' . __('Cancel') . '</button> - ' .
    '<button id="hljs-ok"><strong>' . __('Ok') . '</strong></button></p>' .
        '</form>' .
        '</body>' .
        '</html>';
    return;
}

// Saving new configuration
if (!empty($_POST['saveconfig'])) {
    try
    {
        $core->blog->settings->addNameSpace('hljs');

        $active      = (empty($_POST['active'])) ? false : true;
        $mode        = (empty($_POST['mode'])) ? '' : $_POST['mode'];
        $theme       = (empty($_POST['theme'])) ? '' : $_POST['theme'];
        $custom_css  = (empty($_POST['custom_css'])) ? '' : html::sanitizeURL($_POST['custom_css']);
        $hide_gutter = (empty($_POST['hide_gutter'])) ? false : true;
        $web_worker  = (empty($_POST['web_worker'])) ? false : true;
        $yash        = (empty($_POST['yash'])) ? false : true;
        $syntaxehl   = (empty($_POST['syntaxehl'])) ? false : true;

        $core->blog->settings->hljs->put('active', $active, 'boolean');
        $core->blog->settings->hljs->put('mode', $mode, 'mode');
        $core->blog->settings->hljs->put('theme', $theme, 'string');
        $core->blog->settings->hljs->put('custom_css', $custom_css, 'string');
        $core->blog->settings->hljs->put('hide_gutter', $hide_gutter, 'boolean');
        $core->blog->settings->hljs->put('web_worker', $web_worker, 'boolean');
        $core->blog->settings->hljs->put('yash', $yash, 'boolean');
        $core->blog->settings->hljs->put('syntaxehl', $syntaxehl, 'boolean');

        $core->blog->triggerBlog();

        dcPage::addSuccessNotice(__('Configuration successfully updated.'));
        http::redirect($p_url);
    } catch (Exception $e) {
        $core->error->add($e->getMessage());
    }
}
?>
<html>
<head>
  <title><?php echo __('Code highlight'); ?></title>
</head>

<body>
<?php
echo dcPage::breadcrumb(
    array(
        html::escapeHTML($core->blog->name) => '',
        __('Code highlight')                => ''
    ));
echo dcPage::notices();

$combo_mode = array(
    __('Default (46 languages, 86 Kb)') => '',
    __('Minimum (23 languages, 45 Kb)') => 'min',
    __('Common (92 languages, 240 Kb)') => 'common',
    __('Full (176 languages, 515 Kb)')  => 'full'
);

$combo_theme = array(
    __('Default') => ''
);
// Populate theme list
$themes_list = array();
$themes_root = dirname(__FILE__) . '/js/lib/css/';
if (is_dir($themes_root) && is_readable($themes_root)) {
    if (($d = @dir($themes_root)) !== false) {
        while (($entry = $d->read()) !== false) {
            if ($entry != '.' && $entry != '..' && substr($entry, 0, 1) != '.' && is_readable($themes_root . '/' . $entry)) {
                if (substr($entry, -4) == '.css') {
                    $themes_list[] = substr($entry, 0, -4); // remove .css extension
                }
            }
        }
        sort($themes_list);
    }
}
foreach ($themes_list as $theme_id) {
    if ($theme_id != 'default') {
        // Capitalize each word, replace dash by space, add a space before numbers
        $theme_name               = preg_replace('/([0-9]+)/', ' $1', ucwords(str_replace(array('-', '.', '_'), ' ', $theme_id)));
        $combo_theme[$theme_name] = $theme_id;
    }
}
?>

<div id="hljs_options">
  <form method="post" action="<?php http::getSelfURI();?>">
    <p>
      <?php echo form::checkbox('active', 1, $active); ?>
      <label class="classic" for="active">&nbsp;<?php echo __('Enable Code highlight'); ?></label>
    </p>

    <h3><?php echo __('Presentation'); ?></h3>
    <div class="two-cols clearfix">
      <div class="col">
        <p class="field"><label for="theme" class="classic"><?php echo __('Theme:'); ?> </label>
          <?php echo form::combo('theme', $combo_theme, $theme); ?>
        </p>
        <p class="field"><label for="mode" class="classic"><?php echo __('Set of languages:'); ?> </label>
          <?php echo form::combo('mode', $combo_mode, $mode); ?>
        </p>
      </div>
      <div class="col">
<pre><code id="hljs-sample">function findSequence(goal) {
  function find(start, history) {
    if (start == goal)
      return history;
    else if (start > goal)
      return null;
    else
      return find(start + 5, "(" + history + " + 5)") ||
             find(start * 3, "(" + history + " * 3)");
  }
  return find(1, "1");
}</code></pre>
      </div>
    </div>
    <h3><?php echo __('Options'); ?></h3>
    <p>
      <?php echo form::checkbox('hide_gutter', 1, $hide_gutter); ?>
      <label class="classic" for="hide_gutter">&nbsp;<?php echo __('Hide gutter with line numbers'); ?></label>
    </p>
    <p>
      <?php echo form::checkbox('web_worker', 1, $web_worker); ?>
      <label class="classic" for="web_worker">&nbsp;<?php echo __('Use web workers'); ?></label>
    </p>
    <p class="info">
      <?php echo __('Will use web workers if the browser supports them and provide faster treatment of code snippets but may consume lot more memory.'); ?>
    </p>
    <p class="field">
      <label for="custom_css" class="classic"><?php echo __('Use custom CSS:'); ?> </label>
      <?php echo form::field('custom_css', 40, 128, $custom_css); ?>
    </p>
    <p class="info">
      <?php echo __('You can use a custom CSS by providing its location.'); ?><br />
      <?php echo __('A location beginning with a / is treated as absolute, else it is treated as relative to the blog\'s current theme URL'); ?>
    </p>
    <h3><?php echo __('Compatibiliy'); ?></h3>
    <p>
      <?php echo form::checkbox('yash', 1, $yash); ?>
      <label class="classic" for="yash">&nbsp;<?php echo __('Yash compatibility mode'); ?></label>
    </p>
    <p class="info">
      <?php echo __('Will be applied on future edition of posts containing YASH macros (///yash …///).'); ?><br /><?php echo __('Some of YASH languages are not supported by Code highlight (see documentation).'); ?>
    </p>
    <p>
      <?php echo form::checkbox('syntaxehl', 1, $syntaxehl); ?>
      <label class="classic" for="syntaxehl">&nbsp;<?php echo __('SyntaxeHL compatibility mode'); ?></label>
    </p>
    <p class="info">
      <?php echo __('Will be applied on future edition of posts containing SyntaxeHL macros (///[language]…///).'); ?><br /><?php echo __('All SyntaxeHL languages are not supported by Code highlight (see documentation).'); ?>
    </p>
    <p><input type="hidden" name="p" value="hljs" />
      <?php echo $core->formNonce(); ?>
      <input type="submit" name="saveconfig" value="<?php echo __('Save configuration'); ?>" />
    </p>
  </form>
</div>

</body>
</html>
