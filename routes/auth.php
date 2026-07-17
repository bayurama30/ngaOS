<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('register', 'pages.auth.register')
        ->name('register');

    Route::get('login', [\App\Http\Controllers\Auth\LoginController::class, 'show'])->name('login');
    Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'store']);

    // Forgot Password with OTP
    Route::get('forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'show'])->name('password.request');
    Route::post('forgot-password/otp', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendOtp'])->name('password.otp.send');
    Route::post('forgot-password/verify', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'verifyOtp'])->name('password.otp.verify');
    Route::post('forgot-password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'resetPassword'])->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');

    Route::post('logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});
