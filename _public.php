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
class hljsPublicBehaviors
{
    public static function publicHeadContent()
    {
        if (dcCore::app()->blog->settings->hljs->active) {
            $custom_css = dcCore::app()->blog->settings->hljs->custom_css;
            if (!empty($custom_css)) {
                if (strpos('/', (string) $custom_css) === 0) {
                    $css = $custom_css;
                } else {
                    $css = dcCore::app()->blog->settings->system->themes_url . '/' .
                    dcCore::app()->blog->settings->system->theme . '/' .
                        $custom_css;
                }
            } else {
                $theme = (string) dcCore::app()->blog->settings->hljs->theme;
                if ($theme == '') {
                    $css = dcCore::app()->blog->getPF('hljs/js/lib/css/default.css');
                } else {
                    $css = dcCore::app()->blog->getPF('hljs/js/lib/css/' . $theme . '.css');
                }
            }
            echo
            dcUtils::cssModuleLoad('hljs/css/public.css') .
            dcUtils::cssLoad($css);
        }
    }

    public static function publicFooterContent()
    {
        if (dcCore::app()->blog->settings->hljs->active) {
            echo
            dcUtils::jsJson('hljs_config', [
                'path'      => urldecode(dcCore::app()->blog->getPF('hljs/js/')),
                'mode'      => dcCore::app()->blog->settings->hljs->mode ?: '',
                'show_line' => dcCore::app()->blog->settings->hljs->hide_gutter ? 0 : 1,
                'badge'     => dcCore::app()->blog->settings->hljs->badge ? 1 : 0,
                'use_ww'    => dcCore::app()->blog->settings->hljs->web_worker ? 1 : 0,
                'yash'      => dcCore::app()->blog->settings->hljs->yash ? 1 : 0,
            ]);
            echo
            dcUtils::jsModuleLoad('hljs/js/public.js');
        }
    }
}

dcCore::app()->addBehaviors([
    'publicHeadContent'   => [hljsPublicBehaviors::class, 'publicHeadContent'],
    'publicFooterContent' => [hljsPublicBehaviors::class, 'publicFooterContent'],
]);
