<?php

namespace App\Providers;

use App\Services\StudentWebhookService;
use App\Services\TelegramService;
use Illuminate\Support\ServiceProvider;
use Psy\CodeCleaner\FunctionReturnInWriteContextPass;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TelegramService::class, fn () => new TelegramService());
        $this->app->bind(StudentWebhookService::class, fn () => new StudentWebhookService(new TelegramService()));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
