<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $phone_country = '+62';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone_country . $this->phone,
            'phone_country' => $this->phone_country,
            'password' => Hash::make($this->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('dashboard'));
    }

    public function getCountryCodes(): array
    {
        return [
            ['code' => '+62', 'flag' => '🇮🇩', 'name' => 'Indonesia'],
            ['code' => '+60', 'flag' => '🇲🇾', 'name' => 'Malaysia'],
            ['code' => '+966', 'flag' => '🇸🇦', 'name' => 'Saudi Arabia'],
            ['code' => '+971', 'flag' => '🇦🇪', 'name' => 'UAE'],
            ['code' => '+1', 'flag' => '🇺🇸', 'name' => 'USA'],
            ['code' => '+44', 'flag' => '🇬🇧', 'name' => 'UK'],
            ['code' => '+91', 'flag' => '🇮🇳', 'name' => 'India'],
            ['code' => '+92', 'flag' => '🇵🇰', 'name' => 'Pakistan'],
            ['code' => '+880', 'flag' => '🇧🇩', 'name' => 'Bangladesh'],
            ['code' => '+20', 'flag' => '🇪🇬', 'name' => 'Egypt'],
            ['code' => '+234', 'flag' => '🇳🇬', 'name' => 'Nigeria'],
            ['code' => '+63', 'flag' => '🇵🇭', 'name' => 'Philippines'],
            ['code' => '+65', 'flag' => '🇸🇬', 'name' => 'Singapore'],
            ['code' => '+66', 'flag' => '🇹🇭', 'name' => 'Thailand'],
            ['code' => '+84', 'flag' => '🇻🇳', 'name' => 'Vietnam'],
            ['code' => '+61', 'flag' => '🇦🇺', 'name' => 'Australia'],
            ['code' => '+81', 'flag' => '🇯🇵', 'name' => 'Japan'],
            ['code' => '+82', 'flag' => '🇰🇷', 'name' => 'Korea'],
        ];
    }
}; ?>

<div>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Buat Akun Baru</h2>
        <p class="text-sm text-gray-500 mt-1">Daftar untuk mulai menggunakan NgaOS</p>
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

    <form wire:submit.prevent="register">
        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input wire:model.live="name" id="name"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500"
                    type="text" required autofocus placeholder="Masukkan nama lengkap">
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                <div class="flex space-x-2">
                    <select wire:model.live="phone_country" class="w-24 border border-gray-200 rounded-xl px-3 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
                        @foreach($this->getCountryCodes() as $country)
                            <option value="{{ $country['code'] }}">{{ $country['flag'] }} {{ $country['code'] }}</option>
                        @endforeach
                    </select>
                    <input wire:model.live="phone" id="phone"
                        class="flex-1 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500"
                        type="tel" required placeholder="08xxxxxxxxxx">
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input wire:model.live="email" id="email"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500"
                    type="email" required placeholder="email@example.com">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input wire:model.live="password" id="password"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500"
                    type="password" required placeholder="Minimal 8 karakter">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                <input wire:model.live="password_confirmation" id="password_confirmation"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500"
                    type="password" required placeholder="Ulangi password">
            </div>
        </div>

        <button type="submit"
            class="w-full mt-6 bg-teal-600 text-white py-3 rounded-xl font-medium hover:bg-teal-700 transition disabled:opacity-50"
            wire:loading.attr="disabled">
            <span wire:loading.remove>Daftar</span>
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
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-teal-600 font-medium hover:text-teal-700">Masuk di sini</a>
        </p>
    </div>
</div>
