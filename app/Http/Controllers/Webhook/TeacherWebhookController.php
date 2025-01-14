<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Services\StudentWebhookService;
use App\Services\TeacherWebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TeacherWebhookController extends Controller
{
    public function webhook(Request $request, TeacherWebhookService $teacherWebhookService): void
    {
        $data = $request->all();

        if (isset($data['message'])) {
            $message = $data['message'];
            $chatId = (int)$message['chat']['id'];
            $text = $message['text'];

            if ($text === '/start') {
                $teacherWebhookService->handleStart($chatId);
                return;
            }

            if ($text === '/report') {
                $teacherWebhookService->handleReport($chatId);
                return;
            }
        }

        if (isset($data['callback_query'])) {
            $callbackQuery = $data['callback_query'];
            $chatId = (int)$callbackQuery['from']['id'];
            $status = $callbackQuery['data'];

            if (in_array($status, ['/report'])) {
                $teacherWebhookService->handleReport($chatId);
            }
        }
    }
}
