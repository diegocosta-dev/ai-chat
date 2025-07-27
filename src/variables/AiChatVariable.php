<?php
declare(strict_types=1);

namespace diegocosta\craftaichat\variables;

use Craft;
use craft\web\View;
use Twig\Markup;

class AiChatVariable
{
    /**
     * Renders the chat component with support for custom options.
     *
     * @param array $options
     *  Example:
     *  [
     *      'resetCss' => true,
     *      'classes' => [
     *          'chat-container'   => 'text-red flex w-full',
     *          'chat-message-user'=> 'bg-blue-100',
     *          'chat-message-bot' => 'bg-gray-100'
     *      ]
     *  ]
     * @return Markup
     */
    
    public function render(array $options = []): Markup
    {
        // Render the Twig template for the chat, passing the options array
        $html = Craft::$app->getView()->renderTemplate('ai-chat/_chat.twig', [
            'options' => $options,
        ], View::TEMPLATE_MODE_CP);

        return new Markup($html, Craft::$app->charset);
    }

    /**
     * Legacy method kept for backward compatibility with getChat().
     *
     * @param array $options
     * @return Markup
     */

    public function getChat(array $options = []): Markup
    {
        return $this->render($options);
    }
}
