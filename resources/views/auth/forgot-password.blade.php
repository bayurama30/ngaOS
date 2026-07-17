<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Reset Password</h2>
        <p class="text-sm text-gray-500 mt-1">
            @if(session('otp_sent'))
                Masukkan kode OTP yang dikirim ke email Anda
            @else
                Masukkan email untuk menerima kode OTP
            @endif
        </p>
    </div>

    @if (session('status'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4">
            <p class="text-sm text-green-600">{{ session('status') }}</p>
        </div>
    @endif

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

    {{-- Step 1: Email --}}
    @if(!session('otp_sent'))
        <form method="POST" action="{{ route('password.otp.send') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input id="email" name="email" value="{{ old('email') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500"
                        type="email" required autofocus placeholder="email@example.com">
                </div>
            </div>

            <button type="submit"
                class="w-full mt-6 bg-teal-600 text-white py-3 rounded-xl font-medium hover:bg-teal-700 transition">
                Kirim OTP
            </button>
        </form>

    {{-- Step 2: OTP Verification --}}
    @else
        <form method="POST" action="{{ route('password.otp.verify') }}">
            @csrf
            <input type="hidden" name="email" value="{{ session('email') }}">
            <input type="hidden" name="token" value="{{ session('token') }}">

            <div class="space-y-4">
                <div>
                    <label for="otp" class="block text-sm font-medium text-gray-700 mb-1">Kode OTP</label>
                    <input id="otp" name="otp"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-center text-2xl tracking-[0.5em] focus:outline-none focus:ring-2 focus:ring-teal-500"
                        type="text" required maxlength="6" pattern="[0-9]{6}" inputmode="numeric"
                        placeholder="• • • • • •" autocomplete="one-time-code">
                    <p class="text-xs text-gray-400 mt-2">
                        Kode dikirim ke <span class="font-medium">{{ session('email') }}</span>
                    </p>
                </div>
            </div>

            <button type="submit"
                class="w-full mt-6 bg-teal-600 text-white py-3 rounded-xl font-medium hover:bg-teal-700 transition">
                Verifikasi OTP
            </button>

            <a href="{{ route('password.request') }}"
                class="block w-full mt-3 bg-gray-100 text-gray-700 py-3 rounded-xl font-medium hover:bg-gray-200 transition text-center">
                Ganti Email
            </a>
        </form>
    @endif

    <div class="text-center mt-6 pt-4 border-t border-gray-100">
        <a href="{{ route('login') }}" class="text-sm text-teal-600 font-medium hover:text-teal-700">
            ← Kembali ke Login
        </a>
    </div>
</x-guest-layout>
