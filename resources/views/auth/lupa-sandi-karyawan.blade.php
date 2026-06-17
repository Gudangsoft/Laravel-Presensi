<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi &mdash; {{ $brand?->nama_aplikasi ?? 'Presensi' }}</title>
    <link rel="icon" type="image/png" href="{{ $brand?->faviconUrl() ?? asset('img/favicon.png') }}" />
    @include("dashboard.layouts.link")
    @vite(["resources/css/app.css", "resources/js/app.js"])
</head>
<body class="m-0 antialiased font-sans">
    <main class="min-h-screen bg-gradient-to-br from-slate-800 via-slate-900 to-indigo-900 flex items-center justify-center p-4 relative overflow-hidden">

        {{-- Dots pattern --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-0 left-0 w-full h-full opacity-10"
                 style="background-image: radial-gradient(circle, #a5b4fc 1px, transparent 1px); background-size: 28px 28px;"></div>
        </div>

        {{-- Blobs --}}
        <div class="absolute top-[-80px] left-[-80px] w-80 h-80 bg-violet-500 opacity-20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-[-80px] right-[-80px] w-96 h-96 bg-indigo-600 opacity-20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 right-[-120px] w-64 h-64 bg-purple-500 opacity-10 rounded-full blur-2xl pointer-events-none"></div>

        {{-- Card --}}
        <div class="relative z-10 w-full max-w-md">
            <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-10">

                @if (session('status'))
                    {{-- SUCCESS STATE --}}
                    <div class="flex flex-col items-center text-center py-4">
                        <div class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center mb-5 shadow-lg">
                            <i class="ri-checkbox-circle-line text-4xl text-emerald-500"></i>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-800 mb-2">Permintaan Terkirim</h1>
                        <p class="text-sm text-gray-500 leading-relaxed mb-6">
                            {{ session('status') }}
                        </p>
                        <div class="w-full bg-amber-50 border border-amber-200 rounded-2xl px-4 py-3 text-sm text-amber-700 flex items-start gap-2 text-left mb-7">
                            <i class="ri-information-line flex-shrink-0 mt-0.5 text-amber-500"></i>
                            <span>Admin akan mengkonfirmasi dan mereset password Anda. Anda akan dihubungi setelah proses selesai.</span>
                        </div>
                        <a
                            href="{{ route('login.view') }}"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 text-white font-semibold rounded-xl px-6 py-2.5 text-sm shadow-md shadow-indigo-200 hover:shadow-lg transition-all duration-200 hover:-translate-y-0.5"
                        >
                            <i class="ri-arrow-left-line"></i>
                            Kembali ke Login
                        </a>
                    </div>
                @else
                    {{-- FORM STATE --}}

                    {{-- Header --}}
                    <div class="flex flex-col items-center mb-8">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-violet-500 to-indigo-700 flex items-center justify-center shadow-lg mb-4">
                            <i class="ri-lock-unlock-line text-white text-4xl"></i>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Lupa Kata Sandi?</h1>
                        <p class="text-sm text-gray-400 mt-1 text-center leading-relaxed">
                            Masukkan email Anda. Admin akan menerima notifikasi untuk mereset password Anda.
                        </p>
                    </div>

                    {{-- Info box --}}
                    <div class="flex items-start gap-3 bg-indigo-50 border border-indigo-200 rounded-2xl px-4 py-3 mb-6">
                        <i class="ri-shield-user-line text-indigo-500 text-xl flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-xs font-semibold text-indigo-700 mb-0.5">Proses Reset Password</p>
                            <p class="text-xs text-indigo-600 leading-relaxed">
                                Permintaan Anda akan diteruskan ke Admin/HRD. Password baru akan dikonfirmasi langsung oleh Admin.
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('karyawan.forgot-password.send') }}">
                        @csrf

                        {{-- Email Input --}}
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Email Karyawan
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
                                    placeholder="Masukkan email Anda"
                                    autofocus
                                    required
                                    class="w-full border border-gray-300 rounded-xl pl-10 pr-4 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('email') border-red-400 @enderror"
                                />
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
                            class="w-full bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 text-white font-semibold rounded-xl px-4 py-3 text-sm shadow-md shadow-indigo-200 hover:shadow-lg hover:shadow-indigo-300 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 flex items-center justify-center gap-2"
                        >
                            <i class="ri-send-plane-line text-base"></i>
                            Kirim Permintaan Reset
                        </button>

                        {{-- Back link --}}
                        <div class="text-center mt-5">
                            <a
                                href="{{ route('login.view') }}"
                                class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-indigo-600 font-medium transition-colors"
                            >
                                <i class="ri-arrow-left-line"></i>
                                Kembali ke halaman login
                            </a>
                        </div>
                    </form>
                @endif

                {{-- Footer --}}
                <p class="text-center text-xs text-gray-400 mt-8">
                    &copy; {{ date('Y') }} {{ $brand?->footer_text ?? config('app.name') }}
                </p>
            </div>
        </div>
    </main>

    @include("dashboard.layouts.script")
</body>
</html>
