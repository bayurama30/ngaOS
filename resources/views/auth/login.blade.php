<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Masuk ke Akun</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Silakan masuk untuk melanjutkan</p>
    </div>

    @if (session('status'))
        <div class="glass-card p-4 mb-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
            <p class="text-sm text-green-600 dark:text-green-400">{{ session('status') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="glass-card p-4 mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1">
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

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="space-y-4">
            <div>
                <label for="login" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email atau No. HP</label>
                <input id="login" name="login" value="{{ old('login') }}"
                    class="input"
                    type="text" required autofocus placeholder="email@example.com atau 08xxxxxxxxxx">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                    @if (Route::has('password.request'))
                        <a class="text-xs text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300" href="{{ route('password.request') }}">
                            Lupa password?
                        </a>
                    @endif
                </div>
                <input id="password" name="password"
                    class="input"
                    type="password" required placeholder="Masukkan password">
            </div>

            <div class="flex items-center">
                <label for="remember" class="inline-flex items-center cursor-pointer">
                    <input id="remember" name="remember" type="checkbox" value="1"
                        class="rounded border-gray-300 dark:border-gray-600 text-teal-600 shadow-sm focus:ring-teal-500"
                        {{ old('remember') ? 'checked' : '' }}>
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Ingat saya</span>
                </label>
            </div>
        </div>

        <button type="submit" class="w-full mt-6 btn-primary py-3">
            Masuk
        </button>
    </form>

    <div class="text-center mt-6 pt-4 border-t border-gray-100 dark:border-gray-800">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-teal-600 dark:text-teal-400 font-medium hover:text-teal-700 dark:hover:text-teal-300">Daftar di sini</a>
        </p>
    </div>
</x-guest-layout>
