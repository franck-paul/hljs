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
    '2.0.2',
    [
        'requires'    => [['core', '2.26']],
        'permissions' => dcCore::app()->auth->makePermissions([
            dcAuth::PERMISSION_CONTENT_ADMIN,
        ]),
        'priority' => 1001, // Must be higher than dcLegacyEditor priority (ie 1000)
        'type'     => 'plugin',
        'settings' => [
            'self' => '',
        ],

        'details'    => 'https://open-time.net/docs/plugins/hljs/',
        'support'    => 'https://github.com/franck-paul/hljs',
        'repository' => 'https://raw.githubusercontent.com/franck-paul/hljs/master/dcstore.xml',
    ]
);
