<?php
namespace diegocosta\craftaichat\resources;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class AiChatAsset extends AssetBundle
{
    public function init()
    {
        $this->sourcePath = __DIR__; // points to the resources folder

        $this->css = ['css/ai-chat.css'];
        $this->js = ['js/ai-chat.js'];

        parent::init();
    }
}
