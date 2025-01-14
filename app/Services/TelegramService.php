<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramService
{
    public function setWebhook(string $botToken, string $webhookUri): void
    {
        Http::post("https://api.telegram.org/bot$botToken/setWebhook", [
            'url' => $webhookUri,
        ]);
    }

    public function sendMessage(
        string $text,
        string $botToken,
        int $chatId,
        ?array $replyMarkup = null,
        string $parseMode = 'html',
    ): void {
        $data = [
            'text' => $text,
            'parse_mode' => $parseMode,
            'chat_id' => $chatId,
        ];

        if ($replyMarkup) {
            $data['reply_markup'] = $replyMarkup;
        }

        Http::post("https://api.telegram.org/bot$botToken/sendMessage", $data);
    }

    public function deleteMessage(string $botToken, int $chatId, int $messageId): void
    {
        Http::post("https://api.telegram.org/bot$botToken/deleteMessage", [
            'chat_id'    => $chatId,
            'message_id' => $messageId,
        ]);
    }

}
