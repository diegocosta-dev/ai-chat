# Ai Chat

## Requirements

This plugin requires Craft CMS 5.6.0 or later, and PHP 8.2 or later.

## Installation

You can install this plugin from the Plugin Store or with Composer.

#### From the Plugin Store

Go to the Plugin Store in your project’s Control Panel and search for “Ai Chat”. Then press “Install”.

#### With Composer

Open your terminal and run the following commands:

```bash
# go to the project directory
cd /path/to/my-project

# tell Composer to load the plugin
composer require diego-costa/craft-ai-chat

# tell Craft to install the plugin
./craft plugin/install ai-chat
```

## Usage

```twig
{{ craft.aichat.render() }}
```

## Customization with options

```twig
  {{ craft.aichat.render({
      color: '#e63946',
      placeholder: "Type something...",
      buttonLabel: "Send Now"
  }) }}
```

## Overriding Styles via CSS

```css
  .ai-chat-message.user {
    color: #000 !important;
    font-weight: bold;
  }
  
  .ai-chat-message.bot {
    color: #444 !important;
  }
```

## Settings
 - API Key: Can be entered directly or via an environment variable (e.g., $OPENAI_API_KEY).
 - Provider: Choose between OpenAI, OpenRouter, Hugging Face, Anthropic, or Ollama.
 - Model: Select the LLM model (e.g., gpt-4o-mini, claude-3-opus).
 - Custom Endpoint: Allows you to use an alternative URL for the provider.

## Features
 - Chat rendering with localStorage history (kept for 24 hours).
 - Support for multiple AI providers.

