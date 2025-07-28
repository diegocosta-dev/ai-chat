# AI Chat

AI Chat is a Craft CMS plugin that adds an AI-powered chat widget to your website frontend.  
It allows you to integrate with language models such as **OpenAI**, **OpenRouter**, **Hugging Face**, **Anthropic**, and **Ollama**, providing a ready-to-use and highly customizable chat interface.

<img width="1692" height="1561" alt="image" src="https://github.com/user-attachments/assets/f7671550-e199-40b8-bf68-2af3d8ea1a11" />
---

## Requirements

- Craft CMS 5.6.0 or later.
- PHP 8.2 or later.

---

## Installation

Open `composer.json` in your CraftCMS project and add:

```json
"repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/diegocosta-dev/ai-chat.git"
    }
]
```

Run the following commands in your terminal:

```bash
# Go to your project directory
cd /path/to/my-project

# Add the plugin via Composer
ddev composer require diego-costa/craft-ai-chat:dev-main

# Install the plugin in Craft CMS
ddev craft plugin/install ai-chat
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
      placeholder: "Type something...",
      buttonLabel: "Send Now"
  }) }}
```

## Custom CSS Variables
You can override the appearance of the AI Chat widget using CSS custom properties. Define these globally (e.g., inside :root) or within a specific container.

## 📦 Container

| Variable                 | Description       | Default                         |
| ------------------------ | ----------------- | ------------------------------- |
| `--ai-chat-border-color` | Main border color | `#5219C8`                       |
| `--ai-chat-radius`       | Border radius     | `10px`                          |
| `--ai-chat-padding`      | Container padding | `10px`                          |
| `--ai-chat-max-width`    | Max width         | `400px`                         |
| `--ai-chat-height`       | Fixed height      | `300px`                         |
| `--ai-chat-bg`           | Background color  | `#fff`                          |
| `--ai-chat-shadow`       | Box shadow        | `0 4px 8px rgba(0, 0, 0, 0.05)` |

## 💬 Messages Area

| Variable                           | Description                    | Default |
| ---------------------------------- | ------------------------------ | ------- |
| `--ai-chat-messages-padding`       | Padding inside message area    | `8px`   |
| `--ai-chat-messages-margin-bottom` | Space below messages container | `10px`  |
| `--ai-chat-message-margin-bottom`  | Space between each message     | `8px`   |

## 👤 User Message

| Variable                     | Description | Default   |
| ---------------------------- | ----------- | --------- |
| `--ai-chat-user-color`       | Text color  | `#5219C8` |
| `--ai-chat-user-font-weight` | Font weight | `bold`    |

## 🤖 Bot Message

| Variable                    | Description | Default  |
| --------------------------- | ----------- | -------- |
| `--ai-chat-bot-color`       | Text color  | `#000`   |
| `--ai-chat-bot-font-weight` | Font weight | `normal` |


## ⌨️ Input Field

| Variable                  | Description                  | Default |
| ------------------------- | ---------------------------- | ------- |
| `--ai-chat-input-gap`     | Gap between input and button | `5px`   |
| `--ai-chat-input-padding` | Input padding                | `5px`   |
| `--ai-chat-input-radius`  | Input border radius          | `5px`   |

## 🟦 Send Button

| Variable                       | Description          | Default    |
| ------------------------------ | -------------------- | ---------- |
| `--ai-chat-button-padding`     | Button padding       | `5px 10px` |
| `--ai-chat-button-radius`      | Button border radius | `5px`      |
| `--ai-chat-button-bg`          | Background color     | `#5219C8`  |
| `--ai-chat-button-text-color`  | Text color           | `#fff`     |
| `--ai-chat-button-font-weight` | Font weight          | `bold`     |

## Example

```css
  :root {
  --ai-chat-border-color: #e63946;
  --ai-chat-max-width: 500px;
  --ai-chat-height: 400px;
  --ai-chat-button-bg: #000;
  --ai-chat-button-radius: 999px;
  --ai-chat-user-color: #1D4ED8;
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

- 💬 **AI Chat Interface**: Drop-in chat widget ready to use on the frontend.
- 🧠 **Agent Behavior Prompt**: Define a system prompt to customize your assistant’s tone and purpose.
- 💾 **LocalStorage History**: Conversations are stored for 24h and restored on reload.
- 🎨 **Fully Customizable**: Style with CSS variables or pass options to the render function.
- 🤖 **Multi-Provider Support**: OpenAI, OpenRouter, Anthropic, Hugging Face, Ollama.
- 🔐 **Secure API Key Input**: Supports environment variables via Craft’s `parseEnv`.
- 🧩 **Custom Endpoint Support**: Easily plug into self-hosted or proxy APIs.

