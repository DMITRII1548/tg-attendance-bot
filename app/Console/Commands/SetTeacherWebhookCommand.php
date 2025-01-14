<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class SetTeacherWebhookCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webhook:teacher';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set teacher telegram webhook';

    /**
     * Execute the console command.
     */
    public function handle(TelegramService $telegramService): void
    {
        $telegramService->setWebhook(
            config('services.telegram.teacher.key'),
            route('telegram.webhook.teacher')
        );
    }
}
