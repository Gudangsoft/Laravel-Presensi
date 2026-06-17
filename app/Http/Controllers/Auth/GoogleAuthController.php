<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    // ── ADMIN ─────────────────────────────────────────────────────────

    public function redirectAdmin(): RedirectResponse
    {
        return Socialite::driver('google')
            ->with(['state' => 'admin'])
            ->redirect();
    }

    public function callbackAdmin(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable) {
            return redirect('/login')->withErrors(['email' => 'Login Google dibatalkan atau gagal. Coba lagi.']);
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            return redirect('/login')->withErrors([
                'email' => 'Email ' . $googleUser->getEmail() . ' tidak terdaftar sebagai Admin.',
            ]);
        }

        Auth::guard('web')->login($user, true);

        ActivityLog::record('login_google', 'Login via Google: ' . $user->email);

        return redirect()->intended(route('admin.dashboard'));
    }

    // ── KARYAWAN ───────────────────────────────────────────────────────

    public function redirectKaryawan(): RedirectResponse
    {
        return Socialite::driver('google')
            ->with(['state' => 'karyawan'])
            ->redirect();
    }

    public function callbackKaryawan(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable) {
            return redirect('/login-karyawan')->withErrors(['email' => 'Login Google dibatalkan atau gagal. Coba lagi.']);
        }

        $karyawan = Karyawan::where('email', $googleUser->getEmail())->first();

        if (!$karyawan) {
            return redirect('/login-karyawan')->withErrors([
                'email' => 'Email ' . $googleUser->getEmail() . ' tidak terdaftar. Hubungi Admin atau HRD.',
            ]);
        }

        Auth::guard('karyawan')->login($karyawan, true);

        ActivityLog::record('login_google_karyawan', 'Karyawan login via Google: ' . $karyawan->email);

        return redirect()->intended(route('karyawan.dashboard'));
    }
}
