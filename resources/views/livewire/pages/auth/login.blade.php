<?php

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $login = '';
    public string $password = '';
    public bool $remember = false;

    public function login(): void
    {
        $this->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $this->ensureIsNotRateLimited();

        $field = filter_var($this->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (!Auth::attempt([$field => $this->login, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            $this->addError('login', 'Email/HP atau password salah.');
            $this->reset('password');
            return;
        }

        RateLimiter::clear($this->throttleKey());

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard'));
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        $this->addError('login', 'Terlalu banyak percobaan. Coba lagi dalam ' . ceil($seconds / 60) . ' menit.');

        $this->stopPropagation();
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->login) . '|' . request()->ip());
    }
}; ?>

<div>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Masuk ke Akun</h2>
        <p class="text-sm text-gray-500 mt-1">Silakan masuk untuk melanjutkan</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
            <ul class="text-sm text-red-600 space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="flex items-start">
                        <svg class="w-4 h-4 mr-1.5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $error }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit.prevent="login">
        <div class="space-y-4">
            <div>
                <label for="login" class="block text-sm font-medium text-gray-700 mb-1">Email atau No. HP</label>
                <input wire:model.live="login" id="login"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500"
                    type="text" required autofocus placeholder="email@example.com atau 08xxxxxxxxxx">
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
                <input wire:model.live="password" id="password"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500"
                    type="password" required placeholder="Masukkan password">
            </div>

            <div class="flex items-center">
                <label for="remember" class="inline-flex items-center cursor-pointer">
                    <input wire:model.live="remember" id="remember" type="checkbox"
                        class="rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500">
                    <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
                </label>
            </div>
        </div>

        <button type="submit"
            class="w-full mt-6 bg-teal-600 text-white py-3 rounded-xl font-medium hover:bg-teal-700 transition disabled:opacity-50"
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

    <div class="text-center mt-6 pt-4 border-t border-gray-100">
        <p class="text-sm text-gray-500">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-teal-600 font-medium hover:text-teal-700">Daftar di sini</a>
        </p>
    </div>
</div>
