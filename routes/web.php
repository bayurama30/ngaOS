<?php

use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\HadithController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PrayerTimeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QiblaController;
use App\Http\Controllers\QuranController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Quran Routes
    Route::get('/quran', [QuranController::class, 'index'])->name('quran.index');
    Route::get('/quran/{number}', [QuranController::class, 'surah'])->name('quran.surah');
    Route::get('/quran/search', [QuranController::class, 'search'])->name('quran.search');

    // Hadith Routes
    Route::get('/hadith', [HadithController::class, 'index'])->name('hadith.index');
    Route::get('/hadith/collection/{key}', [HadithController::class, 'collection'])->name('hadith.collection');
    Route::get('/hadith/{id}', [HadithController::class, 'show'])->name('hadith.show');
    Route::get('/hadith/search', [HadithController::class, 'search'])->name('hadith.search');

    // Prayer Times Routes
    Route::get('/prayer', [PrayerTimeController::class, 'index'])->name('prayer.index');

    // Qibla Routes
    Route::get('/qibla', [QiblaController::class, 'index'])->name('qibla.index');

    // Forum Routes
    Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
    Route::post('/forum', [ForumController::class, 'store'])->name('forum.store');
    Route::get('/forum/{post}', [ForumController::class, 'show'])->name('forum.show');
    Route::post('/forum/{post}/comment', [ForumController::class, 'comment'])->name('forum.comment');
    Route::post('/forum/{post}/like', [ForumController::class, 'like'])->name('forum.like');
    Route::post('/forum/{post}/bookmark', [ForumController::class, 'bookmark'])->name('forum.bookmark');

    // Chatbot Routes
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
    Route::post('/chatbot/chat', [ChatbotController::class, 'chat'])->name('chatbot.chat');
    Route::get('/chatbot/history', [ChatbotController::class, 'history'])->name('chatbot.history');
    Route::delete('/chatbot/history', [ChatbotController::class, 'clearHistory'])->name('chatbot.clear');
    Route::get('/chatbot/prompts', [ChatbotController::class, 'quickPrompts'])->name('chatbot.prompts');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__.'/auth.php';
