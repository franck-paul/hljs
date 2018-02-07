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

$this->registerModule(
    "Code highlight",            // Name
    "highlight.js for Dotclear", // Description
    "Franck Paul",               // Author
    '0.1',                       // Version
    array(
        'requires'    => array(array('core', '2.13')),
        'permissions' => 'contentadmin',
        'priority'    => 1001, // Must be higher than dcLegacyEditor priority (ie 1000)
        'details'     => 'https://open-time.net/docs/plugins/hljs/',
        'type'        => 'plugin'
    )
);
