<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginKaryawanRequest;
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

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('karyawan')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login-karyawan');
    }
}
