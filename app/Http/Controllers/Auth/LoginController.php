<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $this->ensureIsNotRateLimited($request);

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (! Auth::attempt([$field => $request->login, 'password' => $request->password], $request->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey($request));

            throw ValidationException::withMessages([
                'login' => 'Email/HP atau password salah.',
            ]);
        }

        RateLimiter::clear($this->throttleKey($request));

        $request->session()->regenerate();

        // Laravel is mounted under the /ngaos sub-path behind the Funnel
        // reverse proxy. The session's "url.intended" is captured by the auth
        // middleware from the proxied request (http://127.0.0.1:8082/<path>)
        // so it lacks both the correct scheme and the /ngaos prefix. Normalise
        // it to the Funnel root (APP_URL, which carries the /ngaos prefix) so
        // the post-login redirect lands on the right sub-path, otherwise users
        // get bounced to the root path (404) e.g. /quran instead of /ngaos/quran.
        // route('dashboard') is the fallback when absent.
        //
        // On localhost there is no Funnel proxy: APP_URL=http://localhost has
        // no :8000 port and the captured intended is already a correct absolute
        // http://127.0.0.1:8000/<path> URL. Rewriting its host to APP_URL would
        // send the browser to port 80 ("localhost refused to connect"), so the
        // Funnel-only normalisation is skipped in local.
        if (! app()->environment('local')) {
            $root = rtrim(config('app.url'), '/');
            $intended = session('url.intended');
            if (is_string($intended) && $intended !== '') {
                $intended = preg_replace('#^https?://[^/]+#', $root, $intended);
                session(['url.intended' => $intended]);
            }
        }

        return redirect()->intended(route('dashboard'));
    }

    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'login' => 'Terlalu banyak percobaan. Coba lagi dalam '.ceil($seconds / 60).' menit.',
        ]);
    }

    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('login')).'|'.$request->ip());
    }
}
