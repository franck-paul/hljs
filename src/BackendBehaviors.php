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
        return match ($editor) {
            'dcLegacyEditor' => Page::jsJson('hljs_editor', [
                'title'     => __('Highlighted Code'),
                'icon'      => urldecode(My::fileURL('/icon-editor.svg')),
                'icon_dark' => urldecode(My::fileURL('/icon-editor-dark.svg')),
                'open_url'  => urldecode(My::manageUrl(['popup' => 1], '&')),
            ]) .
            My::jsLoad('post.js'),

            'dcCKEditor' => Page::jsJson('hljs_editor', [
                'title'     => __('Highlighted Code'),
                'popup_url' => urldecode(My::manageUrl(['popup' => 1, 'plugin_id' => 'dcCKEditor'], '&')),
            ]),

            default => '',
        };
    }

    /**
     * @param      ArrayObject<int, mixed>  $extraPlugins  The extra plugins
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
