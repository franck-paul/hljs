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
    '6.1',
    [
        'date'        => '2025-06-05T14:37:05+0200',
        'requires'    => [['core', '2.34']],
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
