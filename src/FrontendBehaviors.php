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
        if ($settings->getBool('active')) {
            $css = '';

            $custom_css = $settings->getStr('custom_css', false);
            if ($custom_css !== '') {
                if (str_starts_with($custom_css, '/')) {
                    $css = $custom_css;
                } else {
                    $theme_url = App::blog()->settings()->get('system')->getStr('themes_url', false);
                    $theme     = App::blog()->settings()->get('system')->getStr('theme', false);
                    if ($theme_url !== '' && $theme !== '') {
                        $css = $theme_url . '/' . $theme . '/' . $custom_css;
                    }
                }
            } else {
                $theme = $settings->getStr('theme', false) ?: 'default';
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
        if ($settings->getBool('active')) {
            echo
            Html::jsJson('hljs_config', [
                'path'      => urldecode((string) App::blog()->getPF(My::id() . '/js/')),
                'mode'      => $settings->getStr('mode', false),
                'show_line' => (int) !$settings->getBool('hide_gutter', false),
                'badge'     => (int) $settings->getBool('badge', false),
                'use_ww'    => (int) $settings->getBool('web_worker', false),
                'yash'      => (int) $settings->getBool('yash', false),
                'show_copy' => (int) !$settings->getBool('hide_copy', false),
                'copy'      => __('copy'),
                'copied'    => __('copied'),
            ]);
            echo
            My::jsLoad('public.js');
        }

        return '';
    }
}
