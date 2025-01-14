<?php

use App\Http\Controllers\Webhook\StudentWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('telegram')->group(function () {
    Route::prefix('student')->group(function () {
        Route::post('webhook', [StudentWebhookController::class, 'webhook'])->name('telegram.student.webhook');
    });
});
