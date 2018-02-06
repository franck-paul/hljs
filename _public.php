<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of hljs, a plugin for Dotclear 2.
#
# Copyright (c) Franck Paul and contributors
# carnet.franck.paul@gmail.com
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_RC_PATH')) {return;}

$core->addBehavior('publicHeadContent', array('hljsPublicBehaviors', 'publicHeadContent'));
$core->addBehavior('publicFooterContent', array('hljsPublicBehaviors', 'publicFooterContent'));

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
                    $css =
                    $core->blog->settings->system->themes_url . "/" .
                    $core->blog->settings->system->theme . "/" .
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
                dcUtils::cssLoad($core->blog->getPF('hljs/css/public.css')).
                dcUtils::cssLoad($css);
        }
    }

    public static function publicFooterContent()
    {
        global $core;

        $core->blog->settings->addNamespace('hljs');
        if ($core->blog->settings->hljs->active) {
            echo
            dcUtils::jsVar('hljs_path', $core->blog->getPF('hljs/js/')) .
            dcUtils::jsVar('hljs_mode', ($core->blog->settings->hljs->mode ?: '')) .
            dcUtils::jsVar('hljs_show_line', ($core->blog->settings->hljs->hide_gutter ? 0 : 1)) .
            dcUtils::jsVar('hljs_use_ww', ($core->blog->settings->hljs->web_worker ? 1 : 0)) .
            dcUtils::jsVar('hljs_yash', ($core->blog->settings->hljs->yash ? 1 : 0)) .
            dcUtils::jsLoad($core->blog->getPF('hljs/js/public.js'));
        }
    }
}
