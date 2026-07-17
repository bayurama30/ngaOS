<?php

namespace App\Livewire\Pages\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Component;

class Login extends Component
{
    public string $login = '';
    public string $password = '';
    public bool $remember = false;

    protected function rules(): array
    {
        return [
            'login' => 'required|string',
            'password' => 'required|string',
        ];
    }

    public function login(): void
    {
        $this->validate();

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

    public function render()
    {
        return view('livewire.pages.auth.login');
    }
}
