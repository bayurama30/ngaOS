<?php

use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\PrayerTimeController;
use App\Http\Controllers\QiblaController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/chatbot/chat', [ChatbotController::class, 'chat']);
    Route::get('/chatbot/history', [ChatbotController::class, 'history']);
    Route::delete('/chatbot/history', [ChatbotController::class, 'clearHistory']);
});

Route::get('/prayer/timings', [PrayerTimeController::class, 'timings']);
Route::get('/prayer/calendar', [PrayerTimeController::class, 'calendar']);
Route::get('/prayer/next', [PrayerTimeController::class, 'nextPrayer']);

Route::get('/qibla/direction', [QiblaController::class, 'direction']);
