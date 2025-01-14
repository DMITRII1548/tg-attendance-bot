<?php

namespace App\Services;

use App\Enums\StudentStatusEnum;
use App\Models\Student;

class TeacherWebhookService
{
    private string $botToken;

    public function __construct(
        private TelegramService $telegramService
    ) {
        $this->botToken = config('services.telegram.teacher.key');
    }

    public function handleStart(int $chatId): void
    {
        $this->telegramService->sendMessage(
            'Добро пожаловать',
            $this->botToken,
            $chatId,
            [
                'inline_keyboard' => [
                    [
                        [
                            'text' => 'Получить отчет',
                            'callback_data' => '/report',
                        ],
                    ],
                ],
            ],
        );
    }

    public function handleReport(int $chatId): void
    {
        $report = '';

        $this->telegramService->sendMessage(
            $this->buildAttendanceReport(),
            $this->botToken,
            $chatId,
            [
                'inline_keyboard' => [
                    [
                        [
                            'text' => 'Получить отчет',
                            'callback_data' => '/report',
                        ],
                    ],
                ],
            ],
        );
    }

    private function buildAttendanceReport(): string
    {
        $data = '';

        $data .= $this->buildAttendanceStatusReport('Пришли', StudentStatusEnum::CAME->value);
        $data .= $this->buildAttendanceStatusReport('Опоздают', StudentStatusEnum::LATE->value);
        $data .= $this->buildAttendanceStatusReport('Неизвестно', null);

        return $data;
    }

    private function buildAttendanceStatusReport(string $title, ?string $status): string
    {
        $data = PHP_EOL.'';

        $data .= PHP_EOL."<b>$title:</b>".PHP_EOL.PHP_EOL;

        $students = Student::query()
            ->where('status', $status)
            ->get();

        foreach ($students as $student) {
            $data .= $student->name.PHP_EOL;
        }

        return $data;
    }
}
