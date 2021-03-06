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

$__autoload['hljsBehaviors'] = dirname(__FILE__) . '/inc/hljs.behaviors.php';

$core->addBehavior('coreInitWikiPost', ['hljsBehaviors', 'coreInitWikiPost']);
