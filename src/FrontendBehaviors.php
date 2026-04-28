<?php

/**
 * @brief hljs, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul and contributors
 *
 * @copyright Franck Paul contact@open-time.net
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
            $css = '';

            $custom_css = is_string($custom_css = $settings->custom_css) ? $custom_css : '';
            if ($custom_css !== '') {
                if (str_starts_with($custom_css, '/')) {
                    $css = $custom_css;
                } else {
                    $theme_url = is_string($theme_url = App::blog()->settings()->system->themes_url) ? $theme_url : '';
                    $theme     = is_string($theme = App::blog()->settings()->system->theme) ? $theme : '';
                    if ($theme_url !== '' && $theme !== '') {
                        $css = $theme_url . '/' . $theme . '/' . $custom_css;
                    }
                }
            } else {
                $theme = is_string($theme = $settings->theme) ? $theme : 'default';
                $css   = App::blog()->getPF(My::id() . '/js/lib/css/' . $theme . '.css');
            }

            echo
            My::cssLoad('public.css');

            if ($css !== '') {
                echo
                App::plugins()->cssLoad($css);
            }
        }

        return '';
    }

    public static function publicFooterContent(): string
    {
        $settings = My::settings();
        if ($settings->active) {
            echo
            Html::jsJson('hljs_config', [
                'path'      => urldecode((string) App::blog()->getPF(My::id() . '/js/')),
                'mode'      => $settings->mode ?? '',
                'show_line' => $settings->hide_gutter ? 0 : 1,
                'badge'     => $settings->badge ? 1 : 0,
                'use_ww'    => $settings->web_worker ? 1 : 0,
                'yash'      => $settings->yash ? 1 : 0,
                'show_copy' => $settings->hide_copy ? 0 : 1,
                'copy'      => __('copy'),
                'copied'    => __('copied'),
            ]);
            echo
            My::jsLoad('public.js');
        }

        return '';
    }
}
