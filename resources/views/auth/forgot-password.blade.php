<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Kata Sandi &mdash; {{ $brand?->nama_aplikasi ?? config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ $brand?->faviconUrl() ?? asset('img/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gray-50 flex">

    {{-- LEFT: Hero Section --}}
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-violet-600 via-purple-700 to-indigo-700 flex-col items-center justify-center p-12">

        {{-- Dots pattern --}}
        <svg class="absolute inset-0 w-full h-full opacity-10" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="dots" x="0" y="0" width="30" height="30" patternUnits="userSpaceOnUse">
                    <circle cx="3" cy="3" r="2.5" fill="white" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#dots)" />
        </svg>

        {{-- Blobs --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-white opacity-5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-violet-500 opacity-20 rounded-full translate-x-1/3 translate-y-1/3"></div>
        <div class="absolute top-1/2 right-0 w-48 h-48 bg-indigo-400 opacity-20 rounded-full translate-x-1/2 -translate-y-1/2"></div>

        {{-- Content --}}
        <div class="relative z-10 text-center">
            <div class="mx-auto mb-8 w-24 h-24 bg-white bg-opacity-20 rounded-3xl flex items-center justify-center shadow-2xl backdrop-blur-sm border border-white border-opacity-30">
                <i class="ri-lock-unlock-line text-5xl text-white"></i>
            </div>

            <h1 class="text-4xl font-extrabold text-white mb-3 tracking-tight">Lupa Kata Sandi?</h1>
            <p class="text-purple-200 text-lg font-medium mb-2">Tenang, kami bantu!</p>
            <p class="text-purple-300 text-sm max-w-xs mx-auto leading-relaxed">Masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.</p>

            {{-- Steps --}}
            <div class="mt-10 flex flex-col gap-4 items-center">
                <div class="flex items-center gap-3 bg-white bg-opacity-10 backdrop-blur-sm border border-white border-opacity-20 rounded-2xl px-5 py-3 w-72">
                    <div class="w-8 h-8 bg-purple-400 bg-opacity-50 rounded-full flex items-center justify-center flex-shrink-0 text-white text-sm font-bold">1</div>
                    <span class="text-white text-sm font-medium text-left">Masukkan alamat email Anda</span>
                </div>
                <div class="flex items-center gap-3 bg-white bg-opacity-10 backdrop-blur-sm border border-white border-opacity-20 rounded-2xl px-5 py-3 w-72">
                    <div class="w-8 h-8 bg-purple-400 bg-opacity-50 rounded-full flex items-center justify-center flex-shrink-0 text-white text-sm font-bold">2</div>
                    <span class="text-white text-sm font-medium text-left">Cek email untuk tautan reset</span>
                </div>
                <div class="flex items-center gap-3 bg-white bg-opacity-10 backdrop-blur-sm border border-white border-opacity-20 rounded-2xl px-5 py-3 w-72">
                    <div class="w-8 h-8 bg-purple-400 bg-opacity-50 rounded-full flex items-center justify-center flex-shrink-0 text-white text-sm font-bold">3</div>
                    <span class="text-white text-sm font-medium text-left">Buat kata sandi baru Anda</span>
                </div>
            </div>
        </div>

        <p class="absolute bottom-6 left-0 right-0 text-center text-purple-300 text-xs">&copy; {{ date('Y') }} {{ $brand?->footer_text ?? config('app.name') }}. All rights reserved.</p>
    </div>

    {{-- RIGHT: Form Section --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
        <div class="w-full max-w-md">

            {{-- Mobile brand header --}}
            <div class="lg:hidden text-center mb-8">
                <div class="mx-auto mb-4 w-16 h-16 bg-violet-600 rounded-2xl flex items-center justify-center shadow-lg overflow-hidden">
                    @if ($brand?->logo)
                        <img src="{{ $brand->logoUrl() }}" alt="Logo" class="h-12 w-12 object-contain">
                    @else
                        <i class="ri-lock-unlock-line text-3xl text-white"></i>
                    @endif
                </div>
                <h1 class="text-2xl font-extrabold text-gray-800">{{ $brand?->nama_aplikasi ?? config('app.name') }}</h1>
                <p class="text-gray-500 text-sm mt-1">Reset Kata Sandi</p>
            </div>

            {{-- Card --}}
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">

                @if (session('status'))
                    {{-- SUCCESS STATE --}}
                    <div class="text-center py-4">
                        <div class="mx-auto mb-5 w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center">
                            <i class="ri-mail-check-line text-4xl text-emerald-500"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Email Terkirim!</h2>
                        <p class="text-gray-500 text-sm leading-relaxed mb-6">
                            Tautan reset kata sandi telah dikirim ke email Anda. Silakan cek kotak masuk (dan folder spam jika tidak ditemukan).
                        </p>
                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 text-sm text-emerald-700 flex items-start gap-2 text-left mb-6">
                            <i class="ri-information-line flex-shrink-0 mt-0.5"></i>
                            <span>{{ __($message = session('status')) }}</span>
                        </div>
                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-800 font-semibold hover:underline"
                        >
                            <i class="ri-arrow-left-line"></i>
                            Kembali ke halaman login
                        </a>
                    </div>
                @else
                    {{-- FORM STATE --}}
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center">
                                <i class="ri-lock-unlock-line text-violet-600 text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">Reset Kata Sandi</h2>
                                <p class="text-gray-400 text-xs mt-0.5">Khusus untuk akun Administrator</p>
                            </div>
                        </div>
                        <p class="text-gray-500 text-sm leading-relaxed">
                            Masukkan alamat email yang terdaftar. Kami akan mengirimkan tautan untuk membuat kata sandi baru.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        {{-- Email Field --}}
                        <div class="mb-6">
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
                                    placeholder="contoh@email.com"
                                    class="w-full border border-gray-300 rounded-xl pl-10 pr-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-colors duration-200 @error('email') border-red-400 focus:ring-red-400 @enderror"
                                >
                            </div>
                            @error('email')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <i class="ri-error-warning-line"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <button
                            type="submit"
                            class="w-full bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white font-semibold rounded-xl px-4 py-3 text-sm shadow-lg shadow-violet-200 hover:shadow-violet-300 transition-all duration-200 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 flex items-center justify-center gap-2"
                        >
                            <i class="ri-send-plane-line text-base"></i>
                            Kirim Tautan Reset
                        </button>

                        {{-- Back to login --}}
                        <div class="text-center mt-5">
                            <a
                                href="{{ route('login') }}"
                                class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-indigo-600 font-medium transition-colors"
                            >
                                <i class="ri-arrow-left-line"></i>
                                Kembali ke halaman login
                            </a>
                        </div>
                    </form>
                @endif
            </div>

            <p class="text-center text-xs text-gray-400 mt-6">
                &copy; {{ date('Y') }} {{ $brand?->footer_text ?? config('app.name') }} &mdash; Hak Cipta Dilindungi
            </p>
        </div>
    </div>

</body>
</html>
