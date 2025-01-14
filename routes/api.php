<?php

use App\Http\Controllers\Webhook\StudentWebhookController;
use App\Http\Controllers\Webhook\TeacherWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('telegram')->group(function () {
    Route::prefix('webhook')->group(function () {
        Route::post('/student', [StudentWebhookController::class, 'webhook'])->name('telegram.webhook.student');
        Route::post('/teacher', [TeacherWebhookController::class, 'webhook'])->name('telegram.webhook.teacher');
    });
});
