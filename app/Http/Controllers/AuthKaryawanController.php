<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginKaryawanRequest;
use App\Models\Karyawan;
use App\Models\Notifikasi;
use App\Services\CaptchaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthKaryawanController extends Controller
{
    public function create(): View
    {
        $captchaQuestion = CaptchaService::generate();
        return view('auth.login-karyawan', compact('captchaQuestion'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginKaryawanRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('karyawan.dashboard', absolute: false));
    }

    public function forgotPassword(): View
    {
        return view('auth.lupa-sandi-karyawan');
    }

    public function sendResetNotification(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        $karyawan = Karyawan::where('email', $request->email)->first();
        if ($karyawan) {
            Notifikasi::send(
                'Permintaan Reset Password',
                "Karyawan {$karyawan->nama_lengkap} ({$karyawan->email}) meminta reset password. Silakan reset melalui menu Kelola Karyawan.",
                'warning'
            );
        }

        return back()->with('status', 'Jika email Anda terdaftar, notifikasi telah dikirim ke Admin. Silakan hubungi Admin atau HRD Anda untuk konfirmasi.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('karyawan')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login-karyawan');
    }
}
