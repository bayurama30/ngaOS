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

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Masuk ke Akun</h2>
        <p class="text-sm text-gray-500 mt-1">Silakan masuk untuk melanjutkan</p>
    </div>

    <form wire:submit="login">
        <div class="space-y-4">
            <!-- Email or Phone -->
            <div>
                <label for="login" class="block text-sm font-medium text-gray-700 mb-1">Email atau No. HP</label>
                <input wire:model="form.login" id="login"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                    type="text" name="login" required autofocus autocomplete="username"
                    placeholder="email@example.com atau 08xxxxxxxxxx">
                @error('form.login')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    @if (Route::has('password.request'))
                        <a class="text-xs text-teal-600 hover:text-teal-700" href="{{ route('password.request') }}" wire:navigate>
                            Lupa password?
                        </a>
                    @endif
                </div>
                <input wire:model="form.password" id="password"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                    type="password" name="password" required autocomplete="current-password"
                    placeholder="Masukkan password">
                @error('form.password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <label for="remember" class="inline-flex items-center cursor-pointer">
                    <input wire:model="form.remember" id="remember" type="checkbox"
                        class="rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit"
            class="w-full mt-6 bg-teal-600 text-white py-3 rounded-xl font-medium hover:bg-teal-700 transition focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 disabled:opacity-50"
            wire:loading.attr="disabled">
            <span wire:loading.remove>Masuk</span>
            <span wire:loading class="inline-flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            </span>
        </button>
    </form>

    <!-- Register Link -->
    <div class="text-center mt-6 pt-4 border-t border-gray-100">
        <p class="text-sm text-gray-500">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-teal-600 font-medium hover:text-teal-700" wire:navigate>
                Daftar di sini
            </a>
        </p>
    </div>
</div>
