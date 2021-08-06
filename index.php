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
if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

$plugin_version = $core->getVersion('hljs');

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
$badge       = (boolean) $core->blog->settings->hljs->badge;

if (!empty($_REQUEST['popup'])) {
    $hljs_brushes = [
        // Index = label
        // Value = language code
        __('Automatic')  => '',
        __('Plain Text') => 'plain'
    ];

    echo
    '<html><head>' .
    '<title>' . __('Code highlight - Syntax Selector') . '</title>' .
    dcPage::jsJson('hljs_config', [
        'path' => dcPage::getPF('hljs/js/'),
        'mode' => $mode
    ]) .
    dcPage::jsLoad(urldecode(dcPage::getPF('hljs/js/popup.js')), $plugin_version);
    if (!empty($_REQUEST['plugin_id']) && ($_REQUEST['plugin_id'] == 'dcCKEditor')) {
        echo
        dcPage::jsLoad(urldecode(dcPage::getPF('hljs/js/popup_cke.js')), $plugin_version);
    } else {
        echo
        dcPage::jsLoad(urldecode(dcPage::getPF('hljs/js/popup_leg.js')), $plugin_version);
    }
    echo
    '</head><body>' .
    '<h2>' . __('Code highlight - Syntax Selector') . '</h2>' .
    '<form id="hljs-form" action="' . $p_url . '&amp;popup=1" method="get">' .
    '<p><label>' . __('Select the primary syntax of your code snippet:') . ' ' .
    form::combo('syntax', $hljs_brushes, ['extra_html' => 'autofocus']) . '</label></p>' .
    '<p>' .
    '<button type="button" id="hljs-ok" class="submit">' . __('Ok') . '</button>' .
    ' ' .
    '<button type="button" id="hljs-cancel">' . __('Cancel') . '</button>' .
        '</p>' .
        '</form></body></html>';

    return;
}

// Saving new configuration
if (!empty($_POST['saveconfig'])) {
    try {
        $core->blog->settings->addNameSpace('hljs');

        $active      = (empty($_POST['active'])) ? false : true;
        $mode        = (empty($_POST['mode'])) ? '' : $_POST['mode'];
        $theme       = (empty($_POST['theme'])) ? '' : $_POST['theme'];
        $custom_css  = (empty($_POST['custom_css'])) ? '' : html::sanitizeURL($_POST['custom_css']);
        $hide_gutter = (empty($_POST['hide_gutter'])) ? false : true;
        $web_worker  = (empty($_POST['web_worker'])) ? false : true;
        $yash        = (empty($_POST['yash'])) ? false : true;
        $syntaxehl   = (empty($_POST['syntaxehl'])) ? false : true;
        $badge       = (empty($_POST['badge'])) ? false : true;

        $core->blog->settings->hljs->put('active', $active, 'boolean');
        $core->blog->settings->hljs->put('mode', $mode, 'mode');
        $core->blog->settings->hljs->put('theme', $theme, 'string');
        $core->blog->settings->hljs->put('custom_css', $custom_css, 'string');
        $core->blog->settings->hljs->put('hide_gutter', $hide_gutter, 'boolean');
        $core->blog->settings->hljs->put('web_worker', $web_worker, 'boolean');
        $core->blog->settings->hljs->put('yash', $yash, 'boolean');
        $core->blog->settings->hljs->put('syntaxehl', $syntaxehl, 'boolean');
        $core->blog->settings->hljs->put('badge', $badge, 'boolean');

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
    [
        html::escapeHTML($core->blog->name) => '',
        __('Code highlight')                => ''
    ]);
echo dcPage::notices();

$combo_mode = [
    __('Minimum (23 languages, 53 Kb)') => 'min',
    __('Default (46 languages, 93 Kb)') => '',
    __('Common (92 languages, 284 Kb)') => 'common',
    __('Full (185 languages, 731 Kb)')  => 'full'
];

$combo_theme = [
    __('Default') => ''
];
// Populate theme list
$themes_list = [];
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
        $theme_name               = preg_replace('/([0-9]+)/', ' $1', ucwords(str_replace(['-', '.', '_'], ' ', $theme_id)));
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
          <?php echo form::combo('theme', $combo_theme, ['default' => $theme]); ?>
        </p>
        <p class="field"><label for="mode" class="classic"><?php echo __('Set of languages:'); ?> </label>
          <?php echo form::combo('mode', $combo_mode, ['default' => $mode]); ?>
        </p>
        <p class="info"><?php echo __('List of languages:'); ?><br /><span id="syntaxes"></span>
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
<?php
echo
dcPage::cssLoad(urldecode(dcPage::getPF('hljs/css/public.css')), 'screen', $plugin_version) .
dcPage::cssLoad(urldecode(dcPage::getPF('hljs/css/admin.css')), 'screen', $plugin_version) .
dcPage::cssLoad(urldecode(dcPage::getPF('hljs/js/lib/css/' . ($theme ? $theme : 'default') . '.css')), 'screen', $plugin_version) .
dcPage::jsJson('hljs_config', [
    'path'           => urldecode(dcPage::getPF('hljs/js/')),
    'mode'           => $mode,
    'current_mode'   => $mode,
    'list'           => [],
    'show_line'      => $hide_gutter ? 0 : 1,
    'badge'          => $badge ? 1 : 0,
    'use_ww'         => $web_worker ? 1 : 0,
    'yash'           => $yash ? 1 : 0,
    'theme'          => $theme ? $theme : 'default',
    'previous_theme' => $theme ? $theme : 'default'
]) .
dcPage::jsLoad(urldecode(dcPage::getPF('hljs/js/public.js')), $plugin_version) .
dcPage::jsLoad(urldecode(dcPage::getPF('hljs/js/admin.js')), $plugin_version);
?>
      </div>
    </div>
    <h3><?php echo __('Options'); ?></h3>
    <p>
      <?php echo form::checkbox('hide_gutter', 1, $hide_gutter); ?>
      <label class="classic" for="hide_gutter">&nbsp;<?php echo __('Hide gutter with line numbers'); ?></label>
    </p>
    <p>
      <?php echo form::checkbox('badge', 1, $badge); ?>
      <label class="classic" for="badge">&nbsp;<?php echo __('Show syntax badge'); ?></label>
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
