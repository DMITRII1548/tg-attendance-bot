<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class SetStudentWebhookCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webhook:student';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set student telegram webhook';

    /**
     * Execute the console command.
     */
    public function handle(TelegramService $telegramService): void
    {
        $telegramService->setWebhook(
            config('services.telegram.student.key'),
            route('telegram.student.webhook')
        );
    }
}
