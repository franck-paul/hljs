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
if (!defined('DC_RC_PATH')) {
    return;
}

$core->addBehavior('publicHeadContent', ['hljsPublicBehaviors', 'publicHeadContent']);
$core->addBehavior('publicFooterContent', ['hljsPublicBehaviors', 'publicFooterContent']);

class hljsPublicBehaviors
{
    public static function publicHeadContent()
    {
        global $core;

        $core->blog->settings->addNamespace('hljs');
        if ($core->blog->settings->hljs->active) {
            $custom_css = $core->blog->settings->hljs->custom_css;
            if (!empty($custom_css)) {
                if (strpos('/', $custom_css) === 0) {
                    $css = $custom_css;
                } else {
                    $css = $core->blog->settings->system->themes_url . '/' .
                    $core->blog->settings->system->theme . '/' .
                        $custom_css;
                }
            } else {
                $theme = (string) $core->blog->settings->hljs->theme;
                if ($theme == '') {
                    $css = $core->blog->getPF('hljs/js/lib/css/default.css');
                } else {
                    $css = $core->blog->getPF('hljs/js/lib/css/' . $theme . '.css');
                }
            }
            echo
            dcUtils::cssLoad($core->blog->getPF('hljs/css/public.css')) .
            dcUtils::cssLoad($css);
        }
    }

    public static function publicFooterContent()
    {
        global $core;

        $core->blog->settings->addNamespace('hljs');
        if ($core->blog->settings->hljs->active) {
            echo
            dcUtils::jsJson('hljs_config', [
                'path'      => urldecode($core->blog->getPF('hljs/js/')),
                'mode'      => $core->blog->settings->hljs->mode ?: '',
                'show_line' => $core->blog->settings->hljs->hide_gutter ? 0 : 1,
                'badge'     => $core->blog->settings->hljs->badge ? 1 : 0,
                'use_ww'    => $core->blog->settings->hljs->web_worker ? 1 : 0,
                'yash'      => $core->blog->settings->hljs->yash ? 1 : 0,
            ]);
            echo
            dcUtils::jsLoad($core->blog->getPF('hljs/js/public.js'));
        }
    }
}
