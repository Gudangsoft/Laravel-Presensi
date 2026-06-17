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
    public function redirectAdmin(): RedirectResponse
    {
        return Socialite::driver('google')
            ->with(['state' => 'admin'])
            ->redirect();
    }

    public function redirectKaryawan(): RedirectResponse
    {
        return Socialite::driver('google')
            ->with(['state' => 'karyawan'])
            ->redirect();
    }

    /**
     * Satu callback URL untuk kedua guard.
     * Google Console hanya perlu mendaftarkan: /google/callback
     * State param (admin|karyawan) menentukan guard mana yang dipakai.
     */
    public function handleCallback(): RedirectResponse
    {
        $state = request('state', 'karyawan');

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable) {
            $redirect = $state === 'admin' ? '/login' : '/login-karyawan';
            return redirect($redirect)->withErrors([
                'email' => 'Login Google dibatalkan atau gagal. Coba lagi.',
            ]);
        }

        if ($state === 'admin') {
            return $this->loginAdmin($googleUser->getEmail());
        }

        return $this->loginKaryawan($googleUser->getEmail());
    }

    private function loginAdmin(string $email): RedirectResponse
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect('/login')->withErrors([
                'email' => "Email {$email} tidak terdaftar sebagai Admin.",
            ]);
        }

        Auth::guard('web')->login($user, true);
        ActivityLog::record('login_google', "Login Admin via Google: {$email}");

        return redirect()->intended(route('admin.dashboard'));
    }

    private function loginKaryawan(string $email): RedirectResponse
    {
        $karyawan = Karyawan::where('email', $email)->first();

        if (!$karyawan) {
            return redirect('/login-karyawan')->withErrors([
                'email' => "Email {$email} tidak terdaftar. Hubungi Admin atau HRD.",
            ]);
        }

        Auth::guard('karyawan')->login($karyawan, true);
        ActivityLog::record('login_google_karyawan', "Karyawan login via Google: {$email}");

        return redirect()->intended(route('karyawan.dashboard'));
    }
}
