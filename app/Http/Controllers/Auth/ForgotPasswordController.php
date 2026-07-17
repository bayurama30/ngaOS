<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function show()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar.']);
        }

        $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => bcrypt($token),
                'otp' => $otp,
                'otp_expires_at' => Carbon::now()->addMinutes(10),
                'created_at' => Carbon::now(),
            ]
        );

        try {
            Mail::raw(
                "Kode OTP NgaOS Anda: {$otp}\n\n" .
                "Kode ini berlaku selama 10 menit.\n\n" .
                "Jangan bagikan kode ini kepada siapapun.",
                function ($message) use ($request) {
                    $message->to($request->email)
                        ->subject('Kode OTP Reset Password - NgaOS');
                }
            );

            return back()->with([
                'otp_sent' => true,
                'email' => $request->email,
                'token' => $token,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Gagal mengirim OTP. Silakan coba lagi.']);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
            'token' => 'required|string',
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$reset || $reset->otp !== $request->otp) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.'])->with([
                'otp_sent' => true,
                'email' => $request->email,
                'token' => $request->token,
            ]);
        }

        if (Carbon::parse($reset->otp_expires_at)->isPast()) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kedaluwarsa.'])->with([
                'otp_sent' => true,
                'email' => $request->email,
                'token' => $request->token,
            ]);
        }

        return view('auth.reset-password', [
            'email' => $request->email,
            'token' => $request->token,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$reset) {
            return back()->withErrors(['email' => 'Token tidak valid.']);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User tidak ditemukan.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return redirect()->route('login')->with('status', 'Password berhasil diubah! Silakan login.');
    }
}
