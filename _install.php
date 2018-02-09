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

if (!defined('DC_CONTEXT_ADMIN')) {return;}

$new_version = $core->plugins->moduleInfo('hljs', 'version');
$old_version = $core->getVersion('hljs');

if (version_compare($old_version, $new_version, '>=')) {return;}

try
{
    $core->blog->settings->addNamespace('hljs');
    $core->blog->settings->hljs->put('active', false, 'boolean', '', false, true);
    $core->blog->settings->hljs->put('mode', '', 'string', '', false, true);
    $core->blog->settings->hljs->put('theme', '', 'string', '', false, true);
    $core->blog->settings->hljs->put('custom_css', '', 'string', '', false, true);
    $core->blog->settings->hljs->put('hide_gutter', false, 'boolean', '', false, true);
    $core->blog->settings->hljs->put('web_worker', false, 'boolean', '', false, true);
    $core->blog->settings->hljs->put('yash', true, 'boolean', '', false, true);
    $core->blog->settings->hljs->put('syntaxehl', false, 'boolean', '', false, true);
    $core->blog->settings->hljs->put('badge', false, 'boolean', '', false, true);

    $core->setVersion('hljs', $new_version);

    return true;
} catch (Exception $e) {
    $core->error->add($e->getMessage());
}
return false;
