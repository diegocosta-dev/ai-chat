<?php

namespace diegocosta\craftaichat\controllers;

use Craft;
use craft\web\Controller;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use diegocosta\craftaichat\Plugin;

class ChatController extends Controller
{
    protected array|bool|int $allowAnonymous = self::ALLOW_ANONYMOUS_LIVE;

    public function actionAsk(): Response
    {
        $this->requirePostRequest();

        $message = Craft::$app->getRequest()->getBodyParam('message');
        if (!$message) {
            throw new BadRequestHttpException('Message not provided.');
        }

        $reply = Plugin::getInstance()->chat->ask($message);

        return $this->asJson(['reply' => $reply]);
    }
}
