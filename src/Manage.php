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
use Dotclear\Core\Backend\Notices;
use Dotclear\Core\Backend\Page;
use Dotclear\Core\Process;
use Dotclear\Helper\Html\Form\Checkbox;
use Dotclear\Helper\Html\Form\Fieldset;
use Dotclear\Helper\Html\Form\Form;
use Dotclear\Helper\Html\Form\Input;
use Dotclear\Helper\Html\Form\Label;
use Dotclear\Helper\Html\Form\Legend;
use Dotclear\Helper\Html\Form\Para;
use Dotclear\Helper\Html\Form\Select;
use Dotclear\Helper\Html\Form\Submit;
use Dotclear\Helper\Html\Form\Text;
use Dotclear\Helper\Html\Html;
use Exception;

class Manage extends Process
{
    /**
     * Initializes the page.
     */
    public static function init(): bool
    {
        return self::status(My::checkContext(My::MANAGE));
    }

    /**
     * Processes the request(s).
     */
    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        if (!empty($_POST['saveconfig'])) {
            try {
                $active      = (empty($_POST['active'])) ? false : true;
                $mode        = (empty($_POST['mode'])) ? '' : $_POST['mode'];
                $theme       = (empty($_POST['theme'])) ? '' : $_POST['theme'];
                $custom_css  = (empty($_POST['custom_css'])) ? '' : Html::sanitizeURL($_POST['custom_css']);
                $hide_gutter = (empty($_POST['hide_gutter'])) ? false : true;
                $web_worker  = (empty($_POST['web_worker'])) ? false : true;
                $yash        = (empty($_POST['yash'])) ? false : true;
                $syntaxehl   = (empty($_POST['syntaxehl'])) ? false : true;
                $code        = (empty($_POST['code'])) ? false : true;
                $badge       = (empty($_POST['badge'])) ? false : true;

                $settings = My::settings();

                $settings->put('active', $active, dcNamespace::NS_BOOL);
                $settings->put('mode', $mode, dcNamespace::NS_STRING);
                $settings->put('theme', $theme, dcNamespace::NS_STRING);
                $settings->put('custom_css', $custom_css, dcNamespace::NS_STRING);
                $settings->put('hide_gutter', $hide_gutter, dcNamespace::NS_BOOL);
                $settings->put('web_worker', $web_worker, dcNamespace::NS_BOOL);
                $settings->put('yash', $yash, dcNamespace::NS_BOOL);
                $settings->put('syntaxehl', $syntaxehl, dcNamespace::NS_BOOL);
                $settings->put('code', $code, dcNamespace::NS_BOOL);
                $settings->put('badge', $badge, dcNamespace::NS_BOOL);

                dcCore::app()->blog->triggerBlog();

                Notices::addSuccessNotice(__('Configuration successfully updated.'));
                dcCore::app()->admin->url->redirect('admin.plugin.' . My::id());
            } catch (Exception $e) {
                dcCore::app()->error->add($e->getMessage());
            }
        }

        return true;
    }

    /**
     * Renders the page.
     */
    public static function render(): void
    {
        if (!self::status()) {
            return;
        }

        // Getting current parameters if any (get global parameters if not)

        $settings = My::settings();

        $active      = (bool) $settings->active;
        $mode        = (string) $settings->mode;
        $theme       = (string) $settings->theme;
        $custom_css  = (string) $settings->custom_css;
        $hide_gutter = (bool) $settings->hide_gutter;
        $web_worker  = (bool) $settings->web_worker;
        $yash        = (bool) $settings->yash;
        $syntaxehl   = (bool) $settings->syntaxehl;
        $code        = (bool) $settings->code;
        $badge       = (bool) $settings->badge;

        if (!empty($_REQUEST['popup'])) {
            $hljs_brushes = [
                // Index = label
                // Value = language code
                __('Automatic')  => '',
                __('Plain Text') => 'plain',
            ];

            $head = Page::jsJson('hljs_config', [
                'path' => Page::getPF('hljs/js/'),
                'mode' => $mode,
            ]) .
            My::jsLoad('popup.js');
            if (!empty($_REQUEST['plugin_id']) && ($_REQUEST['plugin_id'] == 'dcCKEditor')) {
                $head .= My::jsLoad('popup_cke.js');
            } else {
                $head .= My::jsLoad('popup_leg.js');
            }

            Page::openModule(My::name() . ' - ' . __('Syntax Selector'), $head);

            echo
            (new Form('hljs-form'))
                ->action(dcCore::app()->admin->getPageURL() . '&amp;popup=1')
                ->method('get')
                ->fields([
                    (new Para())
                    ->items([
                        (new Select('syntax'))
                            ->items($hljs_brushes)
                            ->autofocus(true)
                            ->label((new Label(__('Select the primary syntax of your code snippet:'), Label::INSIDE_TEXT_BEFORE))),
                    ]),
                    (new Para())
                    ->separator(' ')
                    ->items([
                        (new Submit('hljs-cancel'))
                            ->value(__('Cancel')),
                        (new Submit('hljs-ok'))
                            ->value(__('Ok')),
                        ... My::hiddenFields([
                            'popup' => '1',
                        ]),
                    ]),
                ])
            ->render();

            Page::closeModule();

            return;
        }

        $combo_mode = [
            __('Minimum (23 languages, 53 Kb)') => 'min',
            __('Default (46 languages, 93 Kb)') => '',
            __('Common (92 languages, 284 Kb)') => 'common',
            __('Full (185 languages, 731 Kb)')  => 'full',
        ];

        $combo_theme = [
            __('Default') => '',
        ];
        // Populate theme list
        $themes_list = [];
        $themes_root = My::path() . '/js/lib/css/';
        if (is_dir($themes_root) && is_readable($themes_root)) {
            if (($d = @dir($themes_root)) !== false) {
                while (($entry = $d->read()) !== false) {
                    if ($entry != '.' && $entry != '..' && substr($entry, 0, 1) != '.' && is_readable($themes_root . '/' . $entry)) {
                        if (substr($entry, -4) == '.css') {
                            $themes_list[] = substr($entry, 0, -4); // remove .css extension
                        }
                    }
                }
                sort($themes_list);
            }
        }
        foreach ($themes_list as $theme_id) {
            if ($theme_id != 'default') {
                // Capitalize each word, replace dash by space, add a space before numbers
                $theme_name               = preg_replace('/(\d+)/', ' $1', ucwords(str_replace(['-', '.', '_'], ' ', $theme_id)));
                $combo_theme[$theme_name] = $theme_id;
            }
        }

        $head = My::cssLoad('public.css') .
        My::cssLoad('admin.css') .
        My::cssLoad('/js/lib/css/' . ($theme ?: 'default') . '.css') .
        Page::jsJson('hljs_config', [
            'path'           => urldecode(Page::getPF(My::id() . '/js/')),
            'mode'           => $mode,
            'current_mode'   => $mode,
            'list'           => [],
            'show_line'      => $hide_gutter ? 0 : 1,
            'badge'          => $badge ? 1 : 0,
            'use_ww'         => $web_worker ? 1 : 0,
            'yash'           => $yash ? 1 : 0,
            'theme'          => $theme ?: 'default',
            'previous_theme' => $theme ?: 'default',
        ]) .
        My::jsLoad('public.js') .
        My::jsLoad('admin.js');

        Page::openModule(My::name(), $head);

        echo Page::breadcrumb(
            [
                Html::escapeHTML(dcCore::app()->blog->name) => '',
                __('Code highlight')                        => '',
            ]
        );
        echo Notices::getNotices();

        $sample = self::sample();

        // Form
        echo
        (new Form('hljs_options'))
            ->action(dcCore::app()->admin->getPageURL())
            ->method('post')
            ->fields([
                (new Para())->items([
                    (new Checkbox('active', $active))
                        ->value(1)
                        ->label((new Label(__('Enable Code highlight'), Label::INSIDE_TEXT_AFTER))),
                ]),

                (new Fieldset())
                ->legend((new Legend(__('Presentation'))))
                ->fields([
                    (new Para())->items([
                        (new Select('theme'))
                            ->items($combo_theme)
                            ->default($theme)
                            ->label((new Label(__('Theme:'), Label::INSIDE_TEXT_BEFORE))),
                    ]),
                    (new Para(null, 'pre'))->items([
                        (new Text(null, $sample)),
                    ]),
                    (new Para())->items([
                        (new Select('mode'))
                            ->items($combo_mode)
                            ->default($mode)
                            ->label((new Label(__('Set of languages:'), Label::INSIDE_TEXT_BEFORE))),
                    ]),
                    (new Para())->class('info')->items([
                        (new Text(null, __('List of languages:') . '<br >')),
                        (new Text('span'))->id('syntaxes'),
                    ]),
                ]),

                (new Fieldset())
                ->legend((new Legend(__('Options'))))
                ->fields([
                    (new Para())->items([
                        (new Checkbox('hide_gutter', $hide_gutter))
                            ->value(1)
                            ->label((new Label(__('Hide gutter with line numbers'), Label::INSIDE_TEXT_AFTER))),
                    ]),
                    (new Para())->items([
                        (new Checkbox('badge', $badge))
                            ->value(1)
                            ->label((new Label(__('Show syntax badge'), Label::INSIDE_TEXT_AFTER))),
                    ]),
                    (new Para())->items([
                        (new Checkbox('web_worker', $web_worker))
                            ->value(1)
                            ->label((new Label(__('Use web workers'), Label::INSIDE_TEXT_AFTER))),
                    ]),
                    (new Para())->class('info')->items([
                        (new Text(null, __('Will use web workers if the browser supports them and provide faster treatment of code snippets but may consume lot more memory.'))),
                    ]),
                    (new Para())->items([
                        (new Input('custom_css'))
                            ->size(40)
                            ->maxlength(256)
                            ->value(Html::escapeHTML($custom_css))
                            ->label((new Label(__('Use custom CSS:'), Label::INSIDE_TEXT_BEFORE))),
                    ]),
                    (new Para())->class('info')->items([
                        (new Text(null, __('You can use a custom CSS by providing its location.') . '<br />' . __('A location beginning with a / is treated as absolute, else it is treated as relative to the blog\'s current theme URL'))),
                    ]),
                ]),

                (new Fieldset())
                ->legend((new Legend(__('Compatibiliy'))))
                ->fields([
                    (new Para())->items([
                        (new Checkbox('yash', $yash))
                            ->value(1)
                            ->label((new Label(__('Yash compatibility mode'), Label::INSIDE_TEXT_AFTER))),
                    ]),
                    (new Para())->class('info')->items([
                        (new Text(null, __('Will be applied on future edition of posts containing YASH macros (///yash …///).') . '<br />' . __('Some of YASH languages are not supported by Code highlight (see documentation).'))),
                    ]),
                    (new Para())->items([
                        (new Checkbox('syntaxehl', $syntaxehl))
                            ->value(1)
                            ->label((new Label(__('SyntaxeHL compatibility mode'), Label::INSIDE_TEXT_AFTER))),
                    ]),
                    (new Para())->class('info')->items([
                        (new Text(null, __('Will be applied on future edition of posts containing SyntaxeHL macros (///[language]…///).') . '<br />' . __('All SyntaxeHL languages are not supported by Code highlight (see documentation).'))),
                    ]),
                    (new Para())->items([
                        (new Checkbox('code', $code))
                            ->value(1)
                            ->label((new Label(__('Generic code compatibility mode'), Label::INSIDE_TEXT_AFTER))),
                    ]),
                    (new Para())->class('info')->items([
                        (new Text(null, __('Will be applied on future edition of posts containing generic code macros (///code [language]…///).'))),
                    ]),
                ]),

                (new Para())->items([
                    (new Submit(['saveconfig'], __('Save configuration')))
                        ->accesskey('s'),
                    ... My::hiddenFields(),
                ]),
            ])
        ->render();

        Page::closeModule();
    }

    private static function sample(): string
    {
        // Tricky code to avoid xgettext bug on indented end heredoc identifier (see https://savannah.gnu.org/bugs/?62158)
        // Warning: don't use <<< if there is some __() l10n calls after as xgettext will not find them
        return <<<EOT
            <code id="hljs-sample">function findSequence(goal) {
                // Local scope find function
                function find(start, history) {
                if (start == goal)
                  return history;
                else if (start > goal)
                  return null;
                else
                  return find(start + 5, "(" + history + " + 5)") ||
                         find(start * 3, "(" + history + " * 3)");
                }
                return find(1, "1");
            }</code>
            EOT;
    }
}
