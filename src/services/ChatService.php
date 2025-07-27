<?php

namespace diegocosta\craftaichat\services;

use Craft;
use craft\base\Component;
use craft\helpers\App;
use GuzzleHttp\Client;
use diegocosta\craftaichat\Plugin;

class ChatService extends Component
{
    // Cache time-to-live (in seconds) for responses
    private const CACHE_TTL = 300; // 5 minutes of cache

    /**
     * Sends a message to the configured LLM provider and retrieves a response.
     *
     * This method:
     *  - Validates API key and message length.
     *  - Builds the message array with system prompts and history.
     *  - Uses a caching layer to avoid redundant API calls.
     *  - Sends an HTTP request to the LLM provider and parses the response.
     *
     * @param string $message  The user's message.
     * @param array $history   An array of previous messages for conversation context.
     * @return string|null     The response from the LLM or an error message.
     */
    public function ask(string $message, array $history = []): ?string
    {
        $settings = Plugin::getInstance()->getSettings();
        $apiKey = App::parseEnv($settings->apiKey);

        // Ensure API key is set (except for local models like Ollama)
        if (empty($apiKey) && $settings->provider !== 'ollama') {
            Craft::error('API Key not configured or environment variable not found.', __METHOD__);
            return 'Error: API Key not configured.';
        }

        // Validate message length to prevent overly long requests
        if (mb_strlen($message) > 2000) {
            Craft::error('Message too long.', __METHOD__);
            return 'Error: message too long.';
        }

        // Build the message stack (system prompt + history + user input)
        $messages = $this->buildMessages($settings->prompt, $history, $message);

        // Create a unique cache key based on provider, model, and message content
        $cacheKey = 'aichat_' . md5($settings->provider . $settings->model . json_encode($messages));

        // Check if a cached response exists
        if ($cached = Craft::$app->cache->get($cacheKey)) {
            Craft::info("Response retrieved from cache for the message: $message", __METHOD__);
            return $cached;
        }

        // Prepare HTTP client and endpoint
        $endpoint = $this->getEndpoint($settings);
        $client = new Client(['timeout' => 60]);

        try {
            // Build the request payload and headers
            $payload = $this->buildPayload($settings, $messages);
            $headers = $this->getHeaders($settings->provider, $apiKey);

            // Send POST request to LLM provider
            $response = $client->post($endpoint, [
                'headers' => $headers,
                'json' => $payload,
            ]);

            // Parse raw response
            $body = $response->getBody()->getContents();
            Craft::info("Raw response from provider [{$settings->provider}]: $body", __METHOD__);

            // Extract the response text
            $reply = $this->parseResponse($settings->provider, $body);

            // Cache the response for future requests
            Craft::$app->cache->set($cacheKey, $reply, self::CACHE_TTL);

            return $reply;
        } catch (\Exception $e) {
            Craft::error('Error connecting to LLM: ' . $e->getMessage(), __METHOD__);
            return 'Error connecting to LLM: ' . $e->getMessage();
        }
    }

    /**
     * Builds the message array for the LLM.
     *
     * The structure typically includes:
     *  - A system prompt (for instructions).
     *  - A list of past messages (user and assistant).
     *  - The current user message.
     *
     * @param string $systemPrompt
     * @param array  $history
     * @param string $message
     * @return array
     */
    private function buildMessages(string $systemPrompt, array $history, string $message): array
    {
        $messages = [['role' => 'system', 'content' => $systemPrompt]];

        foreach ($history as $item) {
            $messages[] = [
                'role' => $item['role'] === 'user' ? 'user' : 'assistant',
                'content' => $item['content'],
            ];
        }

        $messages[] = ['role' => 'user', 'content' => $message];
        return $messages;
    }

    /**
     * Returns the HTTP headers required by the LLM provider.
     *
     * @param string $provider The provider name (e.g., openai, ollama).
     * @param string|null $apiKey
     * @return array
     */
    private function getHeaders(string $provider, ?string $apiKey): array
    {
        $headers = ['Content-Type' => 'application/json'];

        // Only add Authorization header if needed
        if ($provider !== 'ollama' && !empty($apiKey)) {
            $headers['Authorization'] = 'Bearer ' . $apiKey;
        }

        return $headers;
    }

    /**
     * Determines the correct endpoint for the selected LLM provider.
     *
     * @param object $settings
     * @return string
     */
    private function getEndpoint($settings): string
    {
        // If a custom endpoint is set, use it
        if (!empty($settings->endpoint)) {
            return App::parseEnv($settings->endpoint);
        }

        // Otherwise, use the default endpoint for each provider
        return match ($settings->provider) {
            'openrouter'  => 'https://openrouter.ai/api/v1/chat/completions',
            'anthropic'   => 'https://api.anthropic.com/v1/messages',
            'ollama'      => 'http://localhost:11434/api/chat',
            'huggingface' => 'https://api-inference.huggingface.co/models/' . $settings->model,
            default       => 'https://api.openai.com/v1/chat/completions',
        };
    }

    /**
     * Builds the payload required by each LLM provider.
     *
     * Some providers (e.g., HuggingFace) expect plain text, while others
     * use structured message arrays.
     *
     * @param object $settings
     * @param array $messages
     * @return array
     */
    private function buildPayload($settings, array $messages): array
    {
        return match ($settings->provider) {
            'anthropic'   => [
                'model'      => $settings->model,
                'max_tokens' => 1024,
                'messages'   => $messages,
            ],
            'ollama'      => [
                'model'      => $settings->model,
                'messages'   => $messages,
            ],
            'huggingface' => [
                'inputs' => $this->messagesToText($messages),
            ],
            default       => [ // OpenAI and OpenRouter
                'model'    => $settings->model,
                'messages' => $messages,
            ],
        };
    }

    /**
     * Converts a structured message history into plain text.
     *
     * Used for models like HuggingFace that require raw text input.
     *
     * @param array $messages
     * @return string
     */
    private function messagesToText(array $messages): string
    {
        $output = '';
        foreach ($messages as $m) {
            $role = $m['role'] === 'user' ? 'User' : 'Bot';
            $output .= "$role: {$m['content']}\n";
        }
        return $output;
    }

    /**
     * Parses the raw JSON response from the LLM provider.
     *
     * @param string $provider
     * @param string $body
     * @return string|null
     */
    private function parseResponse(string $provider, string $body): ?string
    {
        $data = json_decode($body, true);

        // Check for invalid JSON or unexpected response structure
        if (!is_array($data)) {
            Craft::error("Invalid response from provider [$provider]: $body", __METHOD__);
            return 'Error: invalid LLM response.';
        }

        // Extract text depending on provider's response format
        return match ($provider) {
            'huggingface' => $data[0]['generated_text'] ?? 'Error in HuggingFace response.',
            'anthropic'   => $data['content'][0]['text'] ?? 'Error in Anthropic response.',
            'ollama'      => $data['message']['content'] ?? 'Error in Ollama response.',
            default       => $data['choices'][0]['message']['content'] ?? 'Error in response.',
        };
    }
}
