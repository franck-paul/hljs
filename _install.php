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

if (!dcCore::app()->newVersion(basename(__DIR__), dcCore::app()->plugins->moduleInfo(basename(__DIR__), 'version'))) {
    return;
}

try {
    dcCore::app()->blog->settings->hljs->put('active', false, 'boolean', '', false, true);
    dcCore::app()->blog->settings->hljs->put('mode', '', 'string', '', false, true);
    dcCore::app()->blog->settings->hljs->put('theme', '', 'string', '', false, true);
    dcCore::app()->blog->settings->hljs->put('custom_css', '', 'string', '', false, true);
    dcCore::app()->blog->settings->hljs->put('hide_gutter', false, 'boolean', '', false, true);
    dcCore::app()->blog->settings->hljs->put('web_worker', false, 'boolean', '', false, true);
    dcCore::app()->blog->settings->hljs->put('yash', true, 'boolean', '', false, true);
    dcCore::app()->blog->settings->hljs->put('syntaxehl', false, 'boolean', '', false, true);
    dcCore::app()->blog->settings->hljs->put('code', true, 'boolean', '', false, true);
    dcCore::app()->blog->settings->hljs->put('badge', false, 'boolean', '', false, true);

    return true;
} catch (Exception $e) {
    dcCore::app()->error->add($e->getMessage());
}

return false;
