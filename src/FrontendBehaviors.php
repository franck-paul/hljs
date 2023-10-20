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

use Dotclear\App;
use Dotclear\Helper\Html\Html;

class FrontendBehaviors
{
    public static function publicHeadContent(): string
    {
        $settings = My::settings();
        if ($settings->active) {
            $custom_css = $settings->custom_css;
            if (!empty($custom_css)) {
                if (str_starts_with((string) $custom_css, '/')) {
                    $css = $custom_css;
                } else {
                    $css = App::blog()->settings()->system->themes_url . '/' .
                    App::blog()->settings()->system->theme . '/' .
                        $custom_css;
                }
            } else {
                $theme = (string) $settings->theme;
                if ($theme == '') {
                    $css = App::blog()->getPF(My::id() . '/js/lib/css/default.css');
                } else {
                    $css = App::blog()->getPF(My::id() . '/js/lib/css/' . $theme . '.css');
                }
            }
            echo
            My::cssLoad('public.css') .
            App::plugins()->cssLoad($css);
        }

        return '';
    }

    public static function publicFooterContent(): string
    {
        $settings = My::settings();
        if ($settings->active) {
            echo
            Html::jsJson('hljs_config', [
                'path'      => urldecode(App::blog()->getPF(My::id() . '/js/')),
                'mode'      => $settings->mode ?? '',
                'show_line' => $settings->hide_gutter ? 0 : 1,
                'badge'     => $settings->badge ? 1 : 0,
                'use_ww'    => $settings->web_worker ? 1 : 0,
                'yash'      => $settings->yash ? 1 : 0,
            ]);
            echo
            My::jsLoad('public.js');
        }

        return '';
    }
}
