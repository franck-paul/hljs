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
use Dotclear\Core\Backend\Page;

class BackendBehaviors
{
    public static function adminPostEditor($editor = '')
    {
        if ($editor != 'dcLegacyEditor' && $editor != 'dcCKEditor') {
            return;
        }

        if ($editor == 'dcLegacyEditor') {
            return
            Page::jsJson('hljs_editor', [
                'title'    => __('Highlighted Code'),
                'icon'     => urldecode(Page::getPF(My::id() . '/icon.svg')),
                'open_url' => dcCore::app()->admin->url->get('admin.plugin.' . My::id(), ['popup' => 1], '&'),
            ]) .
            My::jsLoad('post.js');
        }

        $url = dcCore::app()->admin->url->get('admin.plugin.hljs', ['popup' => 1, 'plugin_id' => 'dcCKEditor'], '&');
        $url = urldecode($url);

        return
            Page::jsJson('hljs_editor', [
                'title'     => __('Highlighted Code'),
                'popup_url' => $url,
            ]);
    }

    public static function ckeditorExtraPlugins(ArrayObject $extraPlugins)
    {
        $extraPlugins[] = [
            'name'   => 'hljs',
            'button' => 'hljs',
            'url'    => urldecode(DC_ADMIN_URL . Page::getPF(My::id() . '/cke-addon/')),
        ];
    }
}
