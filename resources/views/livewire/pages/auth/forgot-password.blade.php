<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';
    public string $otp = '';
    public string $password = '';
    public string $password_confirmation = '';
    public int $step = 1;
    public bool $otpSent = false;
    public string $resetToken = '';

    public function sendOtp(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $user = User::where('email', $this->email)->first();

        if (!$user) {
            $this->addError('email', 'Email tidak terdaftar.');
            return;
        }

        $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $this->email],
            [
                'token' => bcrypt($token),
                'otp' => $otp,
                'otp_expires_at' => now()->addMinutes(10),
                'created_at' => now(),
            ]
        );

        try {
            Mail::raw("Kode OTP NgaOS Anda: {$otp}\n\nKode ini berlaku selama 10 menit.\n\nJangan bagikan kode ini kepada siapapun.", function ($message) {
                $message->to($this->email)
                    ->subject('Kode OTP Reset Password - NgaOS');
            });

            $this->resetToken = $token;
            $this->otpSent = true;
            $this->step = 2;
        } catch (\Exception $e) {
            $this->addError('email', 'Gagal mengirim OTP. Silakan coba lagi.');
        }
    }

    public function verifyOtp(): void
    {
        $this->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $this->email)
            ->first();

        if (!$reset || $reset->otp !== $this->otp) {
            $this->addError('otp', 'Kode OTP tidak valid.');
            return;
        }

        if (Carbon::parse($reset->otp_expires_at)->isPast()) {
            $this->addError('otp', 'Kode OTP sudah kedaluwarsa.');
            return;
        }

        $this->step = 3;
    }

    public function resetPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::where('email', $this->email)->first();

        if (!$user) {
            $this->addError('email', 'User tidak ditemukan.');
            return;
        }

        $user->update([
            'password' => bcrypt($this->password),
        ]);

        DB::table('password_reset_tokens')
            ->where('email', $this->email)
            ->delete();

        session()->flash('status', 'Password berhasil diubah! Silakan login.');

        $this->redirect(route('login'), navigate: true);
    }
}; ?>

<div>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Reset Password</h2>
        <p class="text-sm text-gray-500 mt-1" x-text="step === 1 ? 'Masukkan email untuk menerima OTP' : step === 2 ? 'Masukkan kode OTP yang dikirim ke email' : 'Buat password baru'"></p>
    </div>

    @if (session('status'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4">
            <p class="text-green-700 text-sm">{{ session('status') }}</p>
        </div>
    @endif

    <!-- Step 1: Email -->
    @if($step === 1)
        <form wire:submit="sendOtp">
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input wire:model="email" id="email"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        type="email" name="email" required autofocus
                        placeholder="email@example.com">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <button type="submit"
                class="w-full mt-6 bg-teal-600 text-white py-3 rounded-xl font-medium hover:bg-teal-700 transition disabled:opacity-50"
                wire:loading.attr="disabled">
                <span wire:loading.remove>Kirim OTP</span>
                <span wire:loading>Mengirim...</span>
            </button>
        </form>
    @endif

    <!-- Step 2: OTP -->
    @if($step === 2)
        <form wire:submit="verifyOtp">
            <div class="space-y-4">
                <div>
                    <label for="otp" class="block text-sm font-medium text-gray-700 mb-1">Kode OTP</label>
                    <input wire:model="otp" id="otp"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-center text-2xl tracking-widest focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        type="text" name="otp" required maxlength="6" pattern="[0-9]{6}"
                        placeholder="_ _ _ _ _ _">
                    @error('otp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-400 mt-1">Kode dikirim ke <span class="font-medium" x-text="'{{ $email }}'"></span></p>
                </div>
            </div>

            <button type="submit"
                class="w-full mt-6 bg-teal-600 text-white py-3 rounded-xl font-medium hover:bg-teal-700 transition disabled:opacity-50"
                wire:loading.attr="disabled">
                <span wire:loading.remove>Verifikasi</span>
                <span wire:loading>Memverifikasi...</span>
            </button>

            <button type="button" wire:click="$set('step', 1)"
                class="w-full mt-3 bg-gray-100 text-gray-700 py-3 rounded-xl font-medium hover:bg-gray-200 transition">
                Ganti Email
            </button>
        </form>
    @endif

    <!-- Step 3: New Password -->
    @if($step === 3)
        <form wire:submit="resetPassword">
            <div class="space-y-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <input wire:model="password" id="password"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        type="password" name="password" required
                        placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                    <input wire:model="password_confirmation" id="password_confirmation"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        type="password" name="password_confirmation" required
                        placeholder="Ulangi password">
                </div>
            </div>

            <button type="submit"
                class="w-full mt-6 bg-teal-600 text-white py-3 rounded-xl font-medium hover:bg-teal-700 transition disabled:opacity-50"
                wire:loading.attr="disabled">
                <span wire:loading.remove>Reset Password</span>
                <span wire:loading>Memproses...</span>
            </button>
        </form>
    @endif

    <!-- Back to Login -->
    <div class="text-center mt-6 pt-4 border-t border-gray-100">
        <a href="{{ route('login') }}" class="text-sm text-teal-600 font-medium hover:text-teal-700" wire:navigate>
            ← Kembali ke Login
        </a>
    </div>
</div>
