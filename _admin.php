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

// dead but useful code, in order to have translations
__('Code highlight') . __('highlight.js for Dotclear');

$_menu['Blog']->addItem(__('Syntax highlighting'),
    'plugin.php?p=hljs',
    urldecode(dcPage::getPF('hljs/icon.png')),
    preg_match('/plugin.php\?p=hljs(&.*)?$/', $_SERVER['REQUEST_URI']),
    $core->auth->check('contentadmin', $core->blog->id));

$core->addBehavior('adminPostEditor', array('hljsBehaviors', 'adminPostEditor'));
$core->addBehavior('ckeditorExtraPlugins', array('hljsBehaviors', 'ckeditorExtraPlugins'));
