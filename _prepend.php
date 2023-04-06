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

use Dotclear\Helper\Clearbricks;

Clearbricks::lib()->autoload(['hljsBehaviors' => __DIR__ . '/inc/hljs.behaviors.php']);

dcCore::app()->addBehavior('coreInitWikiPost', [hljsBehaviors::class, 'coreInitWikiPost']);
