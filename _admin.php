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

// dead but useful code, in order to have translations
__('Code highlight') . __('highlight.js for Dotclear');

dcCore::app()->menu[dcAdmin::MENU_BLOG]->addItem(
    __('Syntax highlighting'),
    'plugin.php?p=hljs',
    urldecode(dcPage::getPF('hljs/icon.svg')),
    preg_match('/plugin.php\?p=hljs(&.*)?$/', $_SERVER['REQUEST_URI']),
    dcCore::app()->auth->check(dcCore::app()->auth->makePermissions([
        dcAuth::PERMISSION_CONTENT_ADMIN,
    ]), dcCore::app()->blog->id)
);

dcCore::app()->addBehavior('adminPostEditor', [hljsBehaviors::class, 'adminPostEditor']);
dcCore::app()->addBehavior('ckeditorExtraPlugins', [hljsBehaviors::class, 'ckeditorExtraPlugins']);
