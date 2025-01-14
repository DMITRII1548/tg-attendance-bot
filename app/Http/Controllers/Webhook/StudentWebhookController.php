<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Services\StudentWebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StudentWebhookController extends Controller
{
    public function webhook(Request $request, StudentWebhookService $studentWebhookService)
    {
        $data = $request->all();

        if (isset($data['message'])) {
            $message = $data['message'];
            $chatId = (int)$message['chat']['id'];
            $text = $message['text'];

            $state = Cache::get("telegram_state_$chatId", '');

            if ($text === '/start') {
                $studentWebhookService->handleStart($chatId);
                return;
            }

            if ($state === 'awaiting_name') {
                $studentWebhookService->handleStoringName($text, $chatId);
                return;
            }

            if ($state === 'awaiting_status') {
                $studentWebhookService->handleUpdateStatus($text, $chatId);
                return;
            }
        }

        if (isset($data['callback_query'])) {
            $callbackQuery = $data['callback_query'];
            $chatId = (int)$callbackQuery['from']['id'];
            $status = $callbackQuery['data'];

            if (in_array($status, ['/came', '/late'])) {
                $studentWebhookService->handleUpdateStatus($status, $chatId);
            }
        }
    }
}
