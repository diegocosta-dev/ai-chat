<?php
namespace diegocosta\craftaichat;

use Craft;
use craft\base\Model;
use craft\base\Plugin as BasePlugin;
use diegocosta\craftaichat\models\Settings;
use craft\web\twig\variables\CraftVariable;
use yii\base\Event;
use diegocosta\craftaichat\resources\AiChatAsset;

/**
 * Ai Chat plugin
 *
 * @method static Plugin getInstance()
 * @method Settings getSettings()
 * @author Diego Costa <diegoarthurdev@gmail.com>
 * @copyright Diego Costa
 * @license MIT
 */
class Plugin extends BasePlugin
{
    public string $schemaVersion = '1.0.0';
    public bool $hasCpSettings = true;

    public static function config(): array
    {
        return [
            'components' => [
                'chat' => \diegocosta\craftaichat\services\ChatService::class,
            ],
        ];
    }

    public function init(): void
    {
        parent::init();

        Craft::$app->view->registerAssetBundle(AiChatAsset::class);
        $this->attachEventHandlers();

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('aichat', \diegocosta\craftaichat\variables\AiChatVariable::class);
            }
        );
    }

    protected function createSettingsModel(): ?Model
    {
        return Craft::createObject(Settings::class);
    }

    protected function settingsHtml(): ?string
    {
        return Craft::$app->view->renderTemplate('ai-chat/_settings.twig', [
            'plugin' => $this,
            'settings' => $this->getSettings(),
        ]);
    }

    private function attachEventHandlers(): void
    {
        // Register event handlers here ...
        // (see https://craftcms.com/docs/5.x/extend/events.html to get started)
    }
}
