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

if (!defined('DC_CONTEXT_ADMIN')) {return;}

$new_version = $core->plugins->moduleInfo('hljs', 'version');
$old_version = $core->getVersion('hljs');

if (version_compare($old_version, $new_version, '>=')) {return;}

try
{
    $core->blog->settings->addNamespace('hljs');
    $core->blog->settings->hljs->put('active', false, 'boolean', '', false, true);
    $core->blog->settings->hljs->put('mode', '', 'string', '', false, true);
    $core->blog->settings->hljs->put('theme', '', 'string', '', false, true);
    $core->blog->settings->hljs->put('custom_css', '', 'string', '', false, true);
    $core->blog->settings->hljs->put('hide_gutter', false, 'boolean', '', false, true);
    $core->blog->settings->hljs->put('web_worker', false, 'boolean', '', false, true);
    $core->blog->settings->hljs->put('yash', true, 'boolean', '', false, true);
    $core->blog->settings->hljs->put('syntaxehl', false, 'boolean', '', false, true);
    $core->blog->settings->hljs->put('badge', false, 'boolean', '', false, true);

    $core->setVersion('hljs', $new_version);

    return true;
} catch (Exception $e) {
    $core->error->add($e->getMessage());
}
return false;
