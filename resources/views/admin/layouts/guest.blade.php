<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $brand?->nama_aplikasi ?? config('app.name') }}</title>
        <link rel="icon" type="image/png" href="{{ $brand?->faviconUrl() ?? asset('img/favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="{{ route('login.view') }}">
                    @if ($brand?->logoUrl())
                        <img src="{{ $brand->logoUrl() }}" alt="{{ $brand->nama_aplikasi }}" class="h-16 w-auto">
                    @else
                        <span class="text-xl font-bold text-indigo-600">{{ $brand?->nama_aplikasi ?? config('app.name') }}</span>
                    @endif
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
