<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $brand?->nama_aplikasi ?? config('app.name', config('app.name')) }}</title>

    <link rel="icon" type="image/png" href="{{ $brand?->faviconUrl() ?? asset('img/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" crossorigin="anonymous"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">

    {{-- ══════════ SIDEBAR OVERLAY (mobile) ══════════ --}}
    <div id="sidebar-overlay"
         class="fixed inset-0 z-20 bg-black/50 backdrop-blur-sm hidden lg:hidden"
         onclick="closeSidebar()"></div>

    {{-- ══════════ SIDEBAR ══════════ --}}
    <aside id="sidebar"
           class="fixed top-0 left-0 z-30 flex h-full w-64 flex-col bg-indigo-950 transition-transform duration-300 -translate-x-full lg:translate-x-0">

        {{-- Logo --}}
        <div class="flex items-center gap-3 border-b border-indigo-900 px-5 py-5">
            @if ($brand?->logo)
                <img src="{{ $brand->logoUrl() }}" alt="Logo" class="h-9 w-9 rounded-xl object-contain bg-white/10 p-1 flex-shrink-0" />
            @else
                <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-indigo-500 shadow-lg shadow-indigo-900/50">
                    <i class="ri-fingerprint-line text-xl text-white"></i>
                </div>
            @endif
            <div>
                <p class="text-base font-bold leading-none text-white">{{ $brand?->nama_aplikasi ?? 'Presensi' }}</p>
                <p class="mt-0.5 text-xs text-indigo-400">Admin Panel</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4">
            <p class="mb-2 px-3 text-[10px] font-semibold uppercase tracking-widest text-indigo-500">Menu Utama</p>

            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="ri-dashboard-3-line text-lg"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.karyawan') }}"
               class="sidebar-link {{ request()->routeIs('admin.karyawan') ? 'active' : '' }}">
                <i class="ri-team-line text-lg"></i>
                <span>Data Karyawan</span>
            </a>

            <a href="{{ route('admin.departemen') }}"
               class="sidebar-link {{ request()->routeIs('admin.departemen') ? 'active' : '' }}">
                <i class="ri-building-2-line text-lg"></i>
                <span>Departemen</span>
            </a>

            <p class="mb-2 mt-5 px-3 text-[10px] font-semibold uppercase tracking-widest text-indigo-500">Presensi</p>

            <a href="{{ route('admin.monitoring-presensi') }}"
               class="sidebar-link {{ request()->routeIs('admin.monitoring-presensi') ? 'active' : '' }}">
                <i class="ri-radar-line text-lg"></i>
                <span>Monitoring</span>
            </a>

            <a href="{{ route('admin.administrasi-presensi') }}"
               class="sidebar-link {{ request()->routeIs('admin.administrasi-presensi') ? 'active' : '' }}">
                <i class="ri-clipboard-line text-lg"></i>
                <span>Administrasi</span>
            </a>

            <a href="{{ route('admin.laporan.presensi') }}"
               class="sidebar-link {{ request()->routeIs('admin.laporan.presensi') ? 'active' : '' }}">
                <i class="ri-file-chart-line text-lg"></i>
                <span>Laporan</span>
            </a>

            <p class="mb-2 mt-5 px-3 text-[10px] font-semibold uppercase tracking-widest text-indigo-500">Konfigurasi</p>

            <a href="{{ route('admin.lokasi-kantor') }}"
               class="sidebar-link {{ request()->routeIs('admin.lokasi-kantor') ? 'active' : '' }}">
                <i class="ri-map-pin-line text-lg"></i>
                <span>Lokasi Kantor</span>
            </a>

            <a href="{{ route('admin.pengaturan') }}"
               class="sidebar-link {{ request()->routeIs('admin.pengaturan') ? 'active' : '' }}">
                <i class="ri-settings-3-line text-lg"></i>
                <span>Pengaturan</span>
            </a>

            <a href="{{ route('admin.brand') }}"
               class="sidebar-link {{ request()->routeIs('admin.brand') ? 'active' : '' }}">
                <i class="ri-palette-line text-lg"></i>
                <span>Brand & Tampilan</span>
            </a>
        </nav>

        {{-- User Footer --}}
        <div class="border-t border-indigo-900 px-3 py-4">
            <div class="flex items-center gap-3 rounded-xl px-3 py-2.5">
                <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-indigo-600 text-sm font-bold text-white">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                    <p class="truncate text-xs text-indigo-400">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <div class="mt-2 space-y-0.5">
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm text-indigo-300 hover:bg-indigo-900 hover:text-white transition-colors">
                    <i class="ri-user-settings-line text-base"></i>
                    <span>Profil</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-sm text-red-400 hover:bg-red-900/30 hover:text-red-300 transition-colors">
                        <i class="ri-logout-box-r-line text-base"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ══════════ MAIN WRAPPER ══════════ --}}
    <div class="lg:ml-64 flex flex-col min-h-screen">

        {{-- ── Topbar ── --}}
        <header class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-200 bg-white px-5 py-3.5 shadow-sm">
            {{-- Hamburger (mobile) --}}
            <button onclick="openSidebar()"
                    class="flex h-9 w-9 items-center justify-center rounded-xl text-gray-500 hover:bg-gray-100 transition-colors lg:hidden">
                <i class="ri-menu-3-line text-xl"></i>
            </button>

            {{-- Page Header --}}
            <div class="flex-1 lg:flex-none min-w-0 px-3 lg:px-0">
                @isset($header)
                    {{ $header }}
                @endisset
            </div>

            {{-- Right: user chip --}}
            <div class="hidden lg:flex items-center gap-2 ml-4">
                <div class="flex items-center gap-2.5 rounded-xl border border-gray-200 bg-gray-50 px-3 py-1.5">
                    <div class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-600 text-xs font-bold text-white">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="text-sm font-medium text-gray-700 max-w-[140px] truncate">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </header>

        {{-- ── Page Content ── --}}
        <main class="flex-1">
            {{ $slot }}
        </main>

        {{-- ── Footer ── --}}
        <footer class="border-t border-gray-200 bg-white px-6 py-3 text-center text-xs text-gray-400">
            © {{ date('Y') }} {{ $brand?->footer_text ?? config('app.name') }}. All rights reserved.
        </footer>
    </div>

    <script>
        function openSidebar() {
            document.getElementById('sidebar').classList.remove('-translate-x-full');
            document.getElementById('sidebar-overlay').classList.remove('hidden');
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.add('-translate-x-full');
            document.getElementById('sidebar-overlay').classList.add('hidden');
        }
    </script>
</body>
</html>
