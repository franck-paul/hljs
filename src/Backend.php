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
declare(strict_types=1);

namespace Dotclear\Plugin\hljs;

use Dotclear\App;
use Dotclear\Core\Process;

class Backend extends Process
{
    public static function init(): bool
    {
        // dead but useful code, in order to have translations
        __('Code highlight');
        __('highlight.js for Dotclear');

        return self::status(My::checkContext(My::BACKEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        My::addBackendMenuItem(App::backend()->menus()::MENU_BLOG);

        $settings = My::settings();
        if ($settings->active) {
            App::behavior()->addBehaviors([
                'adminPostEditor'      => BackendBehaviors::adminPostEditor(...),
                'ckeditorExtraPlugins' => BackendBehaviors::ckeditorExtraPlugins(...),
            ]);
        }

        return true;
    }
}
