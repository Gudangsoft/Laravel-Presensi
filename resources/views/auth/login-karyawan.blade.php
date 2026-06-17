<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}" data-theme="light" class="scroll-smooth">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Login Karyawan &mdash; {{ $brand?->nama_aplikasi ?? 'Presensi' }}</title>

        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset("img/apple-icon.png") }}" />
        <link rel="icon" type="image/png" href="{{ $brand?->faviconUrl() ?? asset('img/favicon.png') }}" />

        @include("dashboard.layouts.link")
        @vite(["resources/css/app.css", "resources/js/app.js"])
    </head>

    <body class="m-0 antialiased font-sans">
        <main class="min-h-screen bg-gradient-to-br from-slate-800 via-slate-900 to-indigo-900 flex items-center justify-center p-4 relative overflow-hidden">

            {{-- Decorative dots pattern --}}
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-0 left-0 w-full h-full opacity-10"
                     style="background-image: radial-gradient(circle, #a5b4fc 1px, transparent 1px); background-size: 28px 28px;"></div>
            </div>

            {{-- Decorative blobs --}}
            <div class="absolute top-[-80px] left-[-80px] w-80 h-80 bg-indigo-500 opacity-20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-[-80px] right-[-80px] w-96 h-96 bg-violet-600 opacity-20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute top-1/2 left-[-120px] w-64 h-64 bg-blue-500 opacity-10 rounded-full blur-2xl pointer-events-none"></div>

            {{-- Login Card --}}
            <div class="relative z-10 w-full max-w-md">
                <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-10">

                    {{-- Avatar Icon --}}
                    <div class="flex flex-col items-center mb-8">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center shadow-lg mb-4">
                            <i class="ri-user-line text-white text-4xl"></i>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Login Karyawan</h1>
                        <p class="text-sm text-gray-400 mt-1">Masukkan email dan password Anda untuk masuk</p>
                    </div>

                    {{-- Error Alert --}}
                    @error('email')
                        <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-6">
                            <i class="ri-error-warning-line text-red-500 text-xl flex-shrink-0 mt-0.5"></i>
                            <span class="text-sm font-medium">{{ $message }}</span>
                        </div>
                    @enderror

                    <form role="form" action="{{ route('login.auth') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Email Input --}}
                        <div class="mb-5">
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                    <i class="ri-mail-line text-gray-400 text-lg"></i>
                                </span>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    placeholder="Masukkan email Anda"
                                    class="w-full border border-gray-300 rounded-xl pl-10 pr-4 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('email') border-red-400 @enderror"
                                    autofocus
                                    autocomplete="email"
                                    required
                                    value="{{ old('email') }}"
                                />
                            </div>
                        </div>

                        {{-- Password Input --}}
                        <div class="mb-5">
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                    <i class="ri-lock-password-line text-gray-400 text-lg"></i>
                                </span>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    placeholder="Masukkan password Anda"
                                    class="w-full border border-gray-300 rounded-xl pl-10 pr-12 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-200 bg-gray-50 focus:bg-white"
                                    required
                                />
                                <button
                                    type="button"
                                    id="togglePassword"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-indigo-600 transition-colors duration-200 focus:outline-none"
                                    tabindex="-1"
                                >
                                    <i class="ri-eye-off-line text-lg" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Remember Me --}}
                        <div class="flex items-center mb-5">
                            <input
                                id="rememberMe"
                                name="remember"
                                type="checkbox"
                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer"
                            />
                            <label for="rememberMe" class="ml-2 text-sm text-gray-600 cursor-pointer select-none">Ingat saya</label>
                        </div>

                        {{-- CAPTCHA --}}
                        <div class="mb-6">
                            <label for="captcha_answer" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Verifikasi Keamanan
                            </label>
                            <div class="flex items-center gap-3 mb-2 bg-indigo-50 border border-indigo-200 rounded-xl px-4 py-2.5">
                                <i class="ri-shield-check-line text-indigo-500 text-lg flex-shrink-0"></i>
                                <span class="text-sm text-gray-600">Berapa hasil dari</span>
                                <span class="text-base font-bold text-indigo-700 tracking-wide">{{ $captchaQuestion }}</span>
                                <span class="text-sm text-gray-600">= ?</span>
                            </div>
                            <input
                                id="captcha_answer"
                                type="number"
                                name="captcha_answer"
                                min="0"
                                max="99"
                                placeholder="Masukkan jawaban"
                                autocomplete="off"
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('captcha_answer') border-red-400 focus:ring-red-400 focus:border-red-400 @enderror"
                            >
                            @error('captcha_answer')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <i class="ri-error-warning-line"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <button
                            type="submit"
                            class="w-full bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 text-white font-semibold rounded-xl px-4 py-3 text-sm shadow-md shadow-indigo-200 hover:shadow-lg hover:shadow-indigo-300 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 active:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            <i class="ri-login-circle-line mr-2"></i>
                            Masuk
                        </button>

                        {{-- Forgot Password --}}
                        <div class="text-center mt-4">
                            <a
                                href="{{ route('karyawan.forgot-password') }}"
                                class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-indigo-600 font-medium transition-colors duration-200"
                            >
                                <i class="ri-question-line"></i>
                                Lupa kata sandi?
                            </a>
                        </div>

                        {{-- Divider --}}
                        <div class="flex items-center gap-3 my-5">
                            <div class="flex-1 h-px bg-gray-200"></div>
                            <span class="text-xs text-gray-400 font-medium">atau masuk dengan</span>
                            <div class="flex-1 h-px bg-gray-200"></div>
                        </div>

                        {{-- Google Login --}}
                        <a href="{{ route('google.karyawan.redirect') }}"
                           class="w-full flex items-center justify-center gap-3 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 shadow-sm">
                            <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Masuk dengan Google
                        </a>
                    </form>

                    {{-- Footer --}}
                    <p class="text-center text-xs text-gray-400 mt-8">
                        &copy; {{ date('Y') }} {{ $brand?->footer_text ?? config('app.name') }}
                    </p>
                </div>
            </div>
        </main>

        @include("dashboard.layouts.script")
        @vite(["resources/js/app.js"])

        <script>
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            togglePassword.addEventListener('click', function () {
                const isHidden = passwordInput.type === 'password';
                passwordInput.type = isHidden ? 'text' : 'password';
                eyeIcon.classList.toggle('ri-eye-off-line', !isHidden);
                eyeIcon.classList.toggle('ri-eye-line', isHidden);
            });
        </script>
    </body>

</html>
