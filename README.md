# AI Chat

AI Chat is a Craft CMS plugin that adds an AI-powered chat widget to your website frontend.  
It allows you to integrate with language models such as **OpenAI**, **OpenRouter**, **Hugging Face**, **Anthropic**, and **Ollama**, providing a ready-to-use and highly customizable chat interface.

---

## Requirements

- Craft CMS 5.6.0 or later.
- PHP 8.2 or later.

---

## Installation

You can install this plugin from the **Plugin Store** or via **Composer**.

### **From the Plugin Store**

1. Go to the **Plugin Store** in your project's Control Panel.
2. Search for **"AI Chat"**.
3. Click **"Install"**.

### **With Composer**

Run the following commands in your terminal:

```bash
# Go to your project directory
cd /path/to/my-project

# Add the plugin via Composer
composer require diego-costa/craft-ai-chat

# Install the plugin in Craft CMS
./craft plugin/install ai-chat
```

## Usage
Render the chat in any template with:

```twig
{{ craft.aichat.render() }}
```

## Customization with options
You can customize the placeholder text, button label, and chat color rendering:

```twig
  {{ craft.aichat.render({
      color: '#e63946',
      placeholder: "Type something...",
      buttonLabel: "Send Now"
  }) }}
```

## Overriding Styles via CSS
You can also override styles directly via your own CSS:

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
Within the Craft CMS control panel, the plugin provides the following settings:

 - API Key: Can be set directly or via an environment variable (e.g., $OPENAI_API_KEY).
 - Provider: Choose between OpenAI, OpenRouter, Hugging Face, Anthropic, or Ollama.
 - Model: Select the LLM model (e.g., gpt-4o-mini, claude-3-opus).
 - Prompt: Define the agent’s behavior by setting an initial prompt (e.g., "You are a polite and helpful assistant.").
 - Custom Endpoint: Allows you to specify an alternative provider endpoint. 

## Features
 - Agent Behavior Prompt: Configure the initial system prompt to define the assistant’s tone and personality.
 - Simple Rendering: Include the chat widget with a single line of code: ```{{ craft.aichat.render() }}```.
 - Conversation History: Conversations are saved in the browser's localStorage for 24 hours.
 - Multi-Provider AI Support: Compatible with OpenAI, OpenRouter, Hugging Face, Anthropic, and Ollama.

