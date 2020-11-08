<?php
/**
 * Zoom plugin for Craft CMS 3.x
 *
 * Video Conferencing plugin for zoom
 *
 * @link      www.fatfish.com.au
 * @copyright Copyright (c) 2020 Fatfish
 */

namespace fatfish\zoom;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterCpNavItemsEvent;
use craft\services\Fields;
use craft\web\twig\variables\Cp;
use craft\web\twig\variables\CraftVariable;
use fatfish\zoom\fieldtypes\zoomfield as zoomfieldAlias;
use fatfish\zoom\models\Settings;
use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;

use fatfish\zoom\services\MeetingService;
use fatfish\zoom\services\UserService;
use fatfish\zoom\services\ZoomService;
use fatfish\zoom\variables\zoomvariable;
use yii\base\Event;
use Zoomfield;

/**
 * Class Zoom
 *
 * @author    Fatfish
 * @package   Zoom
 * @since     1.0.0
 *
 */
class Zoom extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var Zoom
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.1';

    /**
     * @var bool
     */
    public $hasCpSettings = true;

    /**
     * @var bool
     */
    public $hasCpSection = true;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['zoommeeting'] = 'zoom/meeting/meet';

            }
        );

        $this->setComponents([
                'zoomservice'=>ZoomService::class,
                'zoomuser'=>UserService::class,
                'zoomeeting'=>MeetingService::class,
        ]);


        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['meeting'] = 'zoom/meeting/index';
                $event->rules['create'] = 'zoom/meeting/create-meeting';
                $event->rules['zoom/instant'] = 'zoom/meeting/instant-meeting';
                $event->rules['zoom/webinar'] = 'zoom/webinar/index';
                $event->rules['deletemeeting'] = 'zoom/meeting/delete-meeting';
                $event->rules['update/<id:\d+>']='zoom/meeting/update-meeting';
                $event->rules['zoom/user']='zoom/user/index';
                $event->rules['zoom/user/add']='zoom/user/add-user';
                $event->rules['user/saveuser']='zoom/user/save-user';
                $event->rules['zoom/user/update']='zoom/user/update-user';
                $event->rules['zoom/user/delete']='zoom/user/delete-user';
            }
        );

        Event::on(CraftVariable::class,CraftVariable::EVENT_INIT,function(Event $e){

            $variable=$e->sender;
            $variable->set('zoom',zoomvariable::class);


        });


        /*
         * Register Field Type for zoom
         */


        Event::on(Fields::class,Fields::EVENT_REGISTER_FIELD_TYPES,function(RegisterComponentTypesEvent $event){

                $event->types[]= zoomfieldAlias::class;

        });


        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'zoom',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );

    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'zoom/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }

    public function getCpNavItem()
    {
        $item = parent::getCpNavItem();
        $item['subnav'] = [
                'meetings' => ['label' => 'Meetings', 'url' => 'zoom'],
                'user' => ['label' => 'Users', 'url' => 'zoom/user'],
        ];
        return $item;
    }
}
