<?php

use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\MuslimController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/chatbot/chat', [ChatbotController::class, 'chat']);
    Route::get('/chatbot/history', [ChatbotController::class, 'history']);
    Route::delete('/chatbot/history', [ChatbotController::class, 'clearHistory']);
});

Route::get('/muslim/city/search', [MuslimController::class, 'searchCity']);
Route::get('/muslim/city/all', [MuslimController::class, 'allCities']);
Route::get('/muslim/prayer', [MuslimController::class, 'prayerSchedule']);
Route::get('/muslim/qibla', [MuslimController::class, 'qiblaDirection']);
Route::get('/muslim/hijri/today', [MuslimController::class, 'todayHijri']);
Route::get('/muslim/hadis/random', [MuslimController::class, 'randomHadis']);
Route::get('/muslim/hadis/explore', [MuslimController::class, 'exploreHadis']);
Route::get('/muslim/hadis/search', [MuslimController::class, 'searchHadis']);
Route::get('/muslim/hadis/{id}', [MuslimController::class, 'getHadis']);
Route::get('/muslim/quran/random', [MuslimController::class, 'randomAyah']);
Route::get('/muslim/quran/list', [MuslimController::class, 'surahList']);
Route::get('/muslim/quran/surah/{number}', [MuslimController::class, 'getSurah']);
