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
declare(strict_types=1);

namespace Dotclear\Plugin\hljs;

use ArrayObject;
use dcCore;
use dcPage;

class BackendBehaviors
{
    public static function adminPostEditor($editor = '')
    {
        if ($editor != 'dcLegacyEditor' && $editor != 'dcCKEditor') {
            return;
        }

        if ($editor == 'dcLegacyEditor') {
            return
            dcPage::jsJson('hljs_editor', [
                'title' => __('Highlighted Code'),
            ]) .
            dcPage::jsModuleLoad(My::id() . '/js/post.js', dcCore::app()->getVersion(My::id()));
        }

        $url = dcCore::app()->adminurl->get('admin.plugin.hljs', ['popup' => 1, 'plugin_id' => 'dcCKEditor'], '&');
        $url = urldecode($url);

        return
            dcPage::jsJson('hljs_editor', [
                'title'     => __('Highlighted Code'),
                'popup_url' => $url,
            ]);
    }

    public static function ckeditorExtraPlugins(ArrayObject $extraPlugins)
    {
        $extraPlugins[] = [
            'name'   => 'hljs',
            'button' => 'hljs',
            'url'    => urldecode(DC_ADMIN_URL . dcPage::getPF(My::id() . '/cke-addon/')),
        ];
    }
}
