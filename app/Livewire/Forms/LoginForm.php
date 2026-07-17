<?php

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required|string')]
    public string $login = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $field = filter_var($this->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (!Auth::attempt([$field => $this->login, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'form.login' => 'Email/HP atau password salah.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.login' => 'Terlalu banyak percobaan. Coba lagi dalam ' . ceil($seconds / 60) . ' menit.',
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->login) . '|' . request()->ip());
    }
}
