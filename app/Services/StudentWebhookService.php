<?php

namespace App\Services;

use App\Enums\StudentStatusEnum;
use App\Models\Student;
use Illuminate\Support\Facades\Cache;

class StudentWebhookService
{
    private string $botToken;

    public function __construct(
        private TelegramService $telegramService
    ) {
        $this->botToken = config('services.telegram.student.key');
    }

    public function handleStart(int $chatId): void
    {
        $this->telegramService->sendMessage(
            'Введите ваше имя:',
            $this->botToken,
            $chatId
        );

        Cache::put("telegram_state_$chatId", 'awaiting_name');
    }

    public function handleStoringName(string $name, int $chatId): void
    {
        Student::updateOrCreate([
            'chat_id' => $chatId,
        ], [
            'name' => $name,
            'chat_id' => $chatId,
        ]);

        $this->telegramService->sendMessage(
            'Ваше имя сохранено',
            $this->botToken,
            $chatId,
            [
                'inline_keyboard' => [
                    [
                        [
                            'text' => 'Опоздаю',
                            'callback_data' => '/late',
                        ],

                        [
                            'text' => 'Пришел',
                            'callback_data' => '/came',
                        ],
                    ],
                ],
            ],
        );

        Cache::put("telegram_state_$chatId", 'awaiting_status');
    }

    public function handleUpdateStatus(string $status, int $chatId): void
    {

        $student = Student::query()
            ->where('chat_id', $chatId)
            ->firstOrFail();

        if ($status === '/late') {
            $student->update([
                'status' => StudentStatusEnum::LATE->value
            ]);
        }

        if ($status === '/came') {
            $student->update([
                'status' => StudentStatusEnum::CAME->value
            ]);
        }

        $this->telegramService->sendMessage(
            'Ваш статус успешно принят',
            $this->botToken,
            $chatId,
            [
                'inline_keyboard' => [
                    [
                        [
                            'text' => 'Опоздаю',
                            'callback_data' => '/late',
                        ],

                        [
                            'text' => 'Пришел',
                            'callback_data' => '/came',
                        ],
                    ],
                ],
            ],
        );
    }
}
