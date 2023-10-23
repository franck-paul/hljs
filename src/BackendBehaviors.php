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
use Dotclear\App;
use Dotclear\Core\Backend\Page;

class BackendBehaviors
{
    public static function adminPostEditor(string $editor = ''): string
    {
        if ($editor != 'dcLegacyEditor' && $editor != 'dcCKEditor') {
            return '';
        }

        if ($editor == 'dcLegacyEditor') {
            return
            Page::jsJson('hljs_editor', [
                'title'    => __('Highlighted Code'),
                'icon'     => urldecode(My::fileURL('/icon.svg')),
                'open_url' => urldecode(My::manageUrl(['popup' => 1], '&')),
            ]) .
            My::jsLoad('post.js');
        }

        return
            Page::jsJson('hljs_editor', [
                'title'     => __('Highlighted Code'),
                'popup_url' => urldecode(My::manageUrl(['popup' => 1, 'plugin_id' => 'dcCKEditor'], '&')),
            ]);
    }

    /**
     * @param      ArrayObject<int, mixed>  $extraPlugins  The extra plugins
     *
     * @return     string
     */
    public static function ckeditorExtraPlugins(ArrayObject $extraPlugins): string
    {
        $extraPlugins[] = [
            'name'   => 'hljs',
            'button' => 'hljs',
            'url'    => urldecode(App::config()->adminUrl() . Page::getPF(My::id() . '/cke-addon/')),
        ];

        return '';
    }
}
