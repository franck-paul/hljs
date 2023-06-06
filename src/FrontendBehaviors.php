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

use dcCore;
use dcUtils;

class FrontendBehaviors
{
    public static function publicHeadContent()
    {
        $settings = dcCore::app()->blog->settings->get(My::id());
        if ($settings->active) {
            $custom_css = $settings->custom_css;
            if (!empty($custom_css)) {
                if (strpos((string) $custom_css, '/') === 0) {
                    $css = $custom_css;
                } else {
                    $css = dcCore::app()->blog->settings->system->themes_url . '/' .
                    dcCore::app()->blog->settings->system->theme . '/' .
                        $custom_css;
                }
            } else {
                $theme = (string) $settings->theme;
                if ($theme == '') {
                    $css = dcCore::app()->blog->getPF(My::id() . '/js/lib/css/default.css');
                } else {
                    $css = dcCore::app()->blog->getPF(My::id() . '/js/lib/css/' . $theme . '.css');
                }
            }
            echo
            dcUtils::cssModuleLoad(My::id() . '/css/public.css') .
            dcUtils::cssLoad($css);
        }
    }

    public static function publicFooterContent()
    {
        $settings = dcCore::app()->blog->settings->get(My::id());
        if ($settings->active) {
            echo
            dcUtils::jsJson('hljs_config', [
                'path'      => urldecode(dcCore::app()->blog->getPF(My::id() . '/js/')),
                'mode'      => $settings->mode ?? '',
                'show_line' => $settings->hide_gutter ? 0 : 1,
                'badge'     => $settings->badge ? 1 : 0,
                'use_ww'    => $settings->web_worker ? 1 : 0,
                'yash'      => $settings->yash ? 1 : 0,
            ]);
            echo
            dcUtils::jsModuleLoad(My::id() . '/js/public.js');
        }
    }
}
