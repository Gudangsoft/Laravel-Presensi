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
                        <div class="flex items-center mb-7">
                            <input
                                id="rememberMe"
                                name="remember"
                                type="checkbox"
                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer"
                            />
                            <label for="rememberMe" class="ml-2 text-sm text-gray-600 cursor-pointer select-none">Ingat saya</label>
                        </div>

                        {{-- Submit Button --}}
                        <button
                            type="submit"
                            class="w-full bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 text-white font-semibold rounded-xl px-4 py-3 text-sm shadow-md shadow-indigo-200 hover:shadow-lg hover:shadow-indigo-300 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 active:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            <i class="ri-login-circle-line mr-2"></i>
                            Masuk
                        </button>
                    </form>

                    {{-- Footer --}}
                    <p class="text-center text-xs text-gray-400 mt-8">
                        &copy; {{ date('Y') }} {{ $brand?->footer_text ?? 'Sistem Presensi Karyawan' }}
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
