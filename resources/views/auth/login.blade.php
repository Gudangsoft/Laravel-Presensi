<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login &mdash; {{ $brand?->nama_aplikasi ?? config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ $brand?->faviconUrl() ?? asset('img/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gray-50 flex">

    {{-- LEFT: Hero Section --}}
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-700 flex-col items-center justify-center p-12">

        {{-- Decorative SVG dots pattern --}}
        <svg class="absolute inset-0 w-full h-full opacity-10" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="dots" x="0" y="0" width="30" height="30" patternUnits="userSpaceOnUse">
                    <circle cx="3" cy="3" r="2.5" fill="white" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#dots)" />
        </svg>

        {{-- Decorative blobs --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-white opacity-5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-purple-500 opacity-20 rounded-full translate-x-1/3 translate-y-1/3"></div>
        <div class="absolute top-1/2 right-0 w-48 h-48 bg-indigo-400 opacity-20 rounded-full translate-x-1/2 -translate-y-1/2"></div>

        {{-- Content --}}
        <div class="relative z-10 text-center">
            {{-- Logo Icon --}}
            <div class="mx-auto mb-8 w-24 h-24 bg-white bg-opacity-20 rounded-3xl flex items-center justify-center shadow-2xl backdrop-blur-sm border border-white border-opacity-30">
                @if ($brand?->logo)
                    <img src="{{ $brand->logoUrl() }}" alt="Logo" class="h-16 w-16 object-contain p-1">
                @else
                    <i class="ri-fingerprint-line text-5xl text-white"></i>
                @endif
            </div>

            <h1 class="text-4xl font-extrabold text-white mb-3 tracking-tight">{{ $brand?->nama_aplikasi ?? config('app.name') }}</h1>
            <p class="text-indigo-200 text-lg font-medium mb-2">{{ $brand?->tagline ?? 'Sistem Manajemen Kehadiran' }}</p>
            <p class="text-indigo-300 text-sm max-w-xs mx-auto leading-relaxed">Kelola presensi karyawan dengan mudah, akurat, dan real-time dari mana saja.</p>

            {{-- Feature badges --}}
            <div class="mt-10 flex flex-col gap-4 items-center">
                <div class="flex items-center gap-3 bg-white bg-opacity-10 backdrop-blur-sm border border-white border-opacity-20 rounded-2xl px-5 py-3 w-64">
                    <div class="w-9 h-9 bg-emerald-400 bg-opacity-30 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="ri-map-pin-line text-emerald-200 text-lg"></i>
                    </div>
                    <span class="text-white text-sm font-medium text-left">Presensi Berbasis Lokasi</span>
                </div>
                <div class="flex items-center gap-3 bg-white bg-opacity-10 backdrop-blur-sm border border-white border-opacity-20 rounded-2xl px-5 py-3 w-64">
                    <div class="w-9 h-9 bg-amber-400 bg-opacity-30 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="ri-bar-chart-2-line text-amber-200 text-lg"></i>
                    </div>
                    <span class="text-white text-sm font-medium text-left">Laporan & Statistik Lengkap</span>
                </div>
                <div class="flex items-center gap-3 bg-white bg-opacity-10 backdrop-blur-sm border border-white border-opacity-20 rounded-2xl px-5 py-3 w-64">
                    <div class="w-9 h-9 bg-blue-400 bg-opacity-30 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="ri-shield-check-line text-blue-200 text-lg"></i>
                    </div>
                    <span class="text-white text-sm font-medium text-left">Pengajuan Izin Terstruktur</span>
                </div>
            </div>
        </div>

        {{-- Bottom credit --}}
        <p class="absolute bottom-6 left-0 right-0 text-center text-indigo-300 text-xs">&copy; {{ date('Y') }} {{ $brand?->footer_text ?? config('app.name') }}. All rights reserved.</p>
    </div>

    {{-- RIGHT: Form Section --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
        <div class="w-full max-w-md">

            {{-- Mobile brand header (visible only on small screens) --}}
            <div class="lg:hidden text-center mb-8">
                <div class="mx-auto mb-4 w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-lg overflow-hidden">
                    @if ($brand?->logo)
                        <img src="{{ $brand->logoUrl() }}" alt="Logo" class="h-12 w-12 object-contain">
                    @else
                        <i class="ri-fingerprint-line text-3xl text-white"></i>
                    @endif
                </div>
                <h1 class="text-2xl font-extrabold text-gray-800">{{ $brand?->nama_aplikasi ?? config('app.name') }}</h1>
                <p class="text-gray-500 text-sm mt-1">{{ $brand?->tagline ?? 'Sistem Manajemen Kehadiran' }}</p>
            </div>

            {{-- Card --}}
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">

                {{-- Header --}}
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800">Selamat Datang!</h2>
                    <p class="text-gray-500 text-sm mt-1">Silakan masuk ke akun Anda untuk melanjutkan.</p>
                </div>

                {{-- Session Status --}}
                @if (session('status'))
                    <div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl px-4 py-3">
                        <i class="ri-checkbox-circle-line text-lg flex-shrink-0"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Email Field --}}
                    <div class="mb-5">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                <i class="ri-mail-line text-gray-400 text-lg"></i>
                            </span>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="contoh@email.com"
                                class="w-full border border-gray-300 rounded-xl pl-10 pr-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('email') border-red-400 focus:ring-red-400 focus:border-red-400 @enderror"
                            >
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="ri-error-warning-line"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password Field --}}
                    <div class="mb-5">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                <i class="ri-lock-password-line text-gray-400 text-lg"></i>
                            </span>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan kata sandi"
                                class="w-full border border-gray-300 rounded-xl pl-10 pr-11 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('password') border-red-400 focus:ring-red-400 focus:border-red-400 @enderror"
                            >
                            <button
                                type="button"
                                id="togglePassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600 transition-colors"
                                aria-label="Toggle password visibility"
                            >
                                <i id="eyeIcon" class="ri-eye-off-line text-lg"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="ri-error-warning-line"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="mb-6">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                            <input
                                id="remember_me"
                                type="checkbox"
                                name="remember"
                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 cursor-pointer"
                            >
                            <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl px-4 py-3 text-sm shadow-lg shadow-indigo-200 hover:shadow-indigo-300 transition-all duration-200 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center justify-center gap-2"
                    >
                        <i class="ri-login-circle-line text-lg"></i>
                        Masuk
                    </button>

                    {{-- Forgot Password --}}
                    @if (Route::has('password.request'))
                        <div class="text-center mt-4">
                            <a
                                href="{{ route('password.request') }}"
                                class="text-sm text-indigo-600 hover:text-indigo-800 font-medium hover:underline focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded transition-colors"
                            >
                                <i class="ri-question-line"></i>
                                Lupa kata sandi?
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            {{-- Footer note --}}
            <p class="text-center text-xs text-gray-400 mt-6">
                &copy; {{ date('Y') }} {{ $brand?->footer_text ?? config('app.name') }} &mdash; Hak Cipta Dilindungi
            </p>
        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                eyeIcon.className = isPassword ? 'ri-eye-line text-lg' : 'ri-eye-off-line text-lg';
            });
        }
    </script>
</body>
</html>
