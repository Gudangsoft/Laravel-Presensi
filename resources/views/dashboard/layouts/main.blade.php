<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}" data-theme="light" class="scroll-smooth" x-data="data()">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ $title }} &mdash; {{ $brand?->nama_aplikasi ?? 'Presensi' }}</title>

        <link rel="apple-touch-icon" sizes="76x76" href="{{ $brand?->faviconUrl() ?? asset('img/apple-icon.png') }}" />
        <link rel="icon" type="image/png" href="{{ $brand?->faviconUrl() ?? asset('img/favicon.png') }}" />
        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#4f46e5">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="{{ $brand?->nama_aplikasi ?? config('app.name') }}">

        @include("dashboard.layouts.link")
        @yield("css")
        @vite(["resources/css/app.css", "resources/js/app.js"])

        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }
        </script>
    </head>

    <body class="m-0 bg-gray-50 font-sans antialiased dark:bg-slate-900">

        {{-- Offline Banner --}}
        <div id="offline-banner" class="hidden fixed top-0 left-0 right-0 z-[999] flex items-center justify-center gap-2 py-2.5 text-sm font-semibold text-white"
             style="background:linear-gradient(90deg,#dc2626,#b91c1c);">
            <i class="ri-wifi-off-line text-base"></i>
            <span>Tidak ada koneksi — Menampilkan data tersimpan</span>
        </div>

        @include("dashboard.layouts.sidebar")
        @include("dashboard.layouts.bottom-nav")

        <main class="xl:ml-72 relative min-h-screen transition-all duration-200 ease-in-out">
            @include("dashboard.layouts.navbar")

            <div class="mx-auto w-full px-4 sm:px-6 py-5 pb-28 xl:pb-6">
                @yield("container")
                @include("dashboard.layouts.footer")
            </div>
        </main>

        @include("dashboard.layouts.script")
        @yield("js")
    </body>

</html>
