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
$this->registerModule(
    'Code highlight',
    'highlight.js for Dotclear',
    'Franck Paul',
    '5.8',
    [
        'date'        => '2025-03-05T01:18:04+0100',
        'requires'    => [['core', '2.29']],
        'permissions' => 'My',
        'priority'    => 1010,  // Must be higher than dcLegacyEditor/dcCKEditor priority (ie 1000)
        'type'        => 'plugin',
        'settings'    => [
            'self' => '',
        ],

        'details'    => 'https://open-time.net/docs/plugins/hljs/',
        'support'    => 'https://github.com/franck-paul/hljs',
        'repository' => 'https://raw.githubusercontent.com/franck-paul/hljs/main/dcstore.xml',
        'license'    => 'gpl2',
    ]
);
