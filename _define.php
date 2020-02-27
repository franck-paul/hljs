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

if (!defined('DC_RC_PATH')) {return;}

$this->registerModule(
    "Code highlight",            // Name
    "highlight.js for Dotclear", // Description
    "Franck Paul",               // Author
    '0.6',                       // Version
    [
        'requires'    => [['core', '2.14']],
        'permissions' => 'contentadmin',
        'priority'    => 1001, // Must be higher than dcLegacyEditor priority (ie 1000)
        'details'     => 'https://open-time.net/docs/plugins/hljs/',
        'support'     => 'https://github.com/franck-paul/hljs',
        'type'        => 'plugin'
    ]
);
