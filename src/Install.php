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

use dcCore;
use dcNamespace;
use Dotclear\Core\Process;
use Exception;

class Install extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::INSTALL));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        try {
            // Init
            $settings = My::settings();
            $settings->put('active', false, dcNamespace::NS_BOOL, '', false, true);
            $settings->put('mode', '', dcNamespace::NS_STRING, '', false, true);
            $settings->put('theme', '', dcNamespace::NS_STRING, '', false, true);
            $settings->put('custom_css', '', dcNamespace::NS_STRING, '', false, true);
            $settings->put('hide_gutter', false, dcNamespace::NS_BOOL, '', false, true);
            $settings->put('web_worker', false, dcNamespace::NS_BOOL, '', false, true);
            $settings->put('yash', true, dcNamespace::NS_BOOL, '', false, true);
            $settings->put('syntaxehl', false, dcNamespace::NS_BOOL, '', false, true);
            $settings->put('code', true, dcNamespace::NS_BOOL, '', false, true);
            $settings->put('badge', false, dcNamespace::NS_BOOL, '', false, true);
        } catch (Exception $e) {
            dcCore::app()->error->add($e->getMessage());
        }

        return true;
    }
}
