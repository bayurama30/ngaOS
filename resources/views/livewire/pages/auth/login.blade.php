<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard'));
    }
}; ?>

<div>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Masuk ke Akun</h2>
        <p class="text-sm text-gray-500 mt-1">Silakan masuk untuk melanjutkan</p>
    </div>

    <form wire:submit.prevent="login">
        <div class="space-y-4">
            <div>
                <label for="login" class="block text-sm font-medium text-gray-700 mb-1">Email atau No. HP</label>
                <input wire:model.live="form.login" id="login"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500"
                    type="text" required autofocus placeholder="email@example.com atau 08xxxxxxxxxx">
                @error('form.login') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    @if (Route::has('password.request'))
                        <a class="text-xs text-teal-600 hover:text-teal-700" href="{{ route('password.request') }}">
                            Lupa password?
                        </a>
                    @endif
                </div>
                <input wire:model.live="form.password" id="password"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500"
                    type="password" required placeholder="Masukkan password">
                @error('form.password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center">
                <label for="remember" class="inline-flex items-center cursor-pointer">
                    <input wire:model.live="form.remember" id="remember" type="checkbox"
                        class="rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500">
                    <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
                </label>
            </div>
        </div>

        <button type="submit"
            class="w-full mt-6 bg-teal-600 text-white py-3 rounded-xl font-medium hover:bg-teal-700 transition disabled:opacity-50"
            wire:loading.attr="disabled">
            <span wire:loading.remove>Masuk</span>
            <span wire:loading>Memproses...</span>
        </button>
    </form>

    <div class="text-center mt-6 pt-4 border-t border-gray-100">
        <p class="text-sm text-gray-500">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-teal-600 font-medium hover:text-teal-700">Daftar di sini</a>
        </p>
    </div>
</div>
