<?php

namespace diegocosta\craftaichat\models;

use craft\base\Model;

class Settings extends Model
{
    public string $apiKey = '';
    public string $prompt = '';
    public string $model = 'gpt-4o-mini'; 
    public string $provider = 'openai'; // Default provider
    public string $endpoint = ''; // Custom endpoint

    public function rules(): array
    {
        return [
            [['provider', 'model'], 'required'],
        ];
    }
}
