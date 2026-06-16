<nav x-data="{ open: false, dropdownOpen: false }" class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- Left: Logo + Nav Links -->
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center me-6">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <i class="ri-fingerprint-line text-white text-lg"></i>
                        </div>
                        <span class="font-bold text-indigo-600 text-lg tracking-tight">Presensi</span>
                    </a>
                </div>

                <!-- Navigation Links Desktop -->
                <div class="hidden lg:flex items-center space-x-1">
                    <a href="{{ route('admin.dashboard') }}"
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150
                              {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <i class="ri-dashboard-line me-1.5 text-base"></i>
                        {{ __('Dashboard') }}
                    </a>

                    <a href="{{ route('admin.karyawan') }}"
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150
                              {{ request()->routeIs('admin.karyawan') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <i class="ri-team-line me-1.5 text-base"></i>
                        {{ __('Data Karyawan') }}
                    </a>

                    <a href="{{ route('admin.departemen') }}"
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150
                              {{ request()->routeIs('admin.departemen') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <i class="ri-building-line me-1.5 text-base"></i>
                        {{ __('Data Departemen') }}
                    </a>

                    <a href="{{ route('admin.monitoring-presensi') }}"
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150
                              {{ request()->routeIs('admin.monitoring-presensi') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <i class="ri-radar-line me-1.5 text-base"></i>
                        {{ __('Monitoring') }}
                    </a>

                    <a href="{{ route('admin.laporan.presensi') }}"
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150
                              {{ request()->routeIs('admin.laporan.presensi') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <i class="ri-file-chart-line me-1.5 text-base"></i>
                        {{ __('Laporan') }}
                    </a>

                    <a href="{{ route('admin.lokasi-kantor') }}"
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150
                              {{ request()->routeIs('admin.lokasi-kantor') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <i class="ri-map-pin-line me-1.5 text-base"></i>
                        {{ __('Lokasi Kantor') }}
                    </a>

                    <a href="{{ route('admin.administrasi-presensi') }}"
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150
                              {{ request()->routeIs('admin.administrasi-presensi') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <i class="ri-clipboard-line me-1.5 text-base"></i>
                        {{ __('Administrasi') }}
                    </a>

                    {{-- Dropdown: Pengaturan & Tools --}}
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open"
                                class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150
                                       {{ request()->routeIs('admin.pengaturan','admin.hari-libur','admin.activity-log','admin.brand','admin.qr-presensi','admin.koreksi-presensi','admin.backup','admin.pengumuman','admin.absensi-massal') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                            <i class="ri-settings-3-line text-base"></i>
                            <span>Pengaturan</span>
                            <i class="ri-arrow-down-s-line text-sm transition-transform" :class="{ 'rotate-180': open }"></i>
                        </button>
                        <div x-show="open" x-transition style="display:none"
                             class="absolute left-0 top-full mt-1 w-56 rounded-xl bg-white shadow-lg border border-gray-100 py-1.5 z-50">
                            <a href="{{ route('admin.pengaturan') }}"
                               class="flex items-center gap-2.5 px-4 py-2 text-sm {{ request()->routeIs('admin.pengaturan') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }} transition-colors">
                                <i class="ri-settings-3-line text-base text-gray-400"></i> Pengaturan Umum
                            </a>
                            <a href="{{ route('admin.hari-libur') }}"
                               class="flex items-center gap-2.5 px-4 py-2 text-sm {{ request()->routeIs('admin.hari-libur') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }} transition-colors">
                                <i class="ri-calendar-event-line text-base text-gray-400"></i> Hari Libur
                            </a>
                            <a href="{{ route('admin.qr-presensi') }}"
                               class="flex items-center gap-2.5 px-4 py-2 text-sm {{ request()->routeIs('admin.qr-presensi') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }} transition-colors">
                                <i class="ri-qr-code-line text-base text-gray-400"></i> QR Presensi
                            </a>
                            <a href="{{ route('admin.koreksi-presensi') }}"
                               class="flex items-center gap-2.5 px-4 py-2 text-sm {{ request()->routeIs('admin.koreksi-presensi') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }} transition-colors">
                                <i class="ri-edit-line text-base text-gray-400"></i> Koreksi Presensi
                            </a>
                            <a href="{{ route('admin.backup') }}"
                               class="flex items-center gap-2.5 px-4 py-2 text-sm {{ request()->routeIs('admin.backup') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }} transition-colors">
                                <i class="ri-database-2-line text-base text-gray-400"></i> Backup Database
                            </a>
                            <a href="{{ route('admin.activity-log') }}"
                               class="flex items-center gap-2.5 px-4 py-2 text-sm {{ request()->routeIs('admin.activity-log') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }} transition-colors">
                                <i class="ri-history-line text-base text-gray-400"></i> Log Aktivitas
                            </a>
                            <div class="my-1 border-t border-gray-100"></div>
                            <a href="{{ route('admin.pengumuman') }}"
                               class="flex items-center gap-2.5 px-4 py-2 text-sm {{ request()->routeIs('admin.pengumuman') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }} transition-colors">
                                <i class="ri-megaphone-line text-base text-gray-400"></i> Pengumuman
                            </a>
                            <a href="{{ route('admin.absensi-massal') }}"
                               class="flex items-center gap-2.5 px-4 py-2 text-sm {{ request()->routeIs('admin.absensi-massal') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }} transition-colors">
                                <i class="ri-group-line text-base text-gray-400"></i> Absensi Massal
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Bell + User Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-2">

                {{-- Notification Bell --}}
                <div class="relative" x-data="{ bellOpen: false }" @click.outside="bellOpen = false">
                    <button @click="bellOpen = !bellOpen; if(bellOpen) loadNotifs()"
                            class="relative flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 transition-colors">
                        <i class="ri-notification-3-line text-gray-600 text-lg"></i>
                        @if ($unreadNotifikasi > 0)
                            <span id="bell-badge"
                                  class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full text-[10px] font-bold text-white"
                                  style="background:#ef4444;">
                                {{ $unreadNotifikasi > 9 ? '9+' : $unreadNotifikasi }}
                            </span>
                        @else
                            <span id="bell-badge" class="hidden absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full text-[10px] font-bold text-white"
                                  style="background:#ef4444;"></span>
                        @endif
                    </button>
                    <div x-show="bellOpen" x-transition style="display:none"
                         class="absolute right-0 top-full mt-2 w-80 rounded-2xl bg-white shadow-xl border border-gray-100 z-50 overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                            <h4 class="text-sm font-bold text-gray-800">Notifikasi</h4>
                            <a href="{{ route('admin.notifikasi') }}" class="text-xs text-indigo-600 font-medium hover:underline">Lihat Semua</a>
                        </div>
                        <div id="notif-list" class="max-h-72 overflow-y-auto divide-y divide-gray-50">
                            <div class="py-8 text-center text-gray-400 text-sm" id="notif-loading">
                                <i class="ri-loader-4-line animate-spin text-xl block mb-1"></i> Memuat...
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative" x-data="{ dropdownOpen: false }" @click.outside="dropdownOpen = false">
                    <button
                        @click="dropdownOpen = !dropdownOpen"
                        class="inline-flex items-center gap-2.5 px-3 py-1.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1"
                    >
                        <div class="w-7 h-7 rounded-full bg-indigo-600 border-2 border-indigo-200 flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-xs font-semibold leading-none">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                        </div>
                        <span class="text-sm font-medium text-gray-700 max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                        <i class="ri-arrow-down-s-line text-gray-400 text-base transition-transform duration-150" :class="{ 'rotate-180': dropdownOpen }"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div
                        x-show="dropdownOpen"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="transform opacity-0 scale-95 translate-y-1"
                        x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="transform opacity-0 scale-95 translate-y-1"
                        class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-1.5 z-50"
                        style="display: none;"
                    >
                        <!-- User Info -->
                        <div class="px-4 py-3 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-indigo-600 border-2 border-indigo-200 flex items-center justify-center flex-shrink-0">
                                    <span class="text-white text-sm font-semibold">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition-colors duration-150">
                                <i class="ri-user-settings-line text-base text-gray-400"></i>
                                {{ __('Profile') }}
                            </a>
                        </div>

                        <!-- Divider + Logout -->
                        <div class="border-t border-gray-100 pt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150"
                                >
                                    <i class="ri-logout-box-r-line text-base"></i>
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            <!-- Hamburger Mobile -->
            <div class="-me-2 flex items-center lg:hidden">
                <button
                    @click="open = !open"
                    class="inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 focus:outline-none transition duration-150 ease-in-out"
                >
                    <i x-show="!open" class="ri-menu-3-line text-xl"></i>
                    <i x-show="open" class="ri-close-line text-xl" style="display: none;"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="lg:hidden border-t border-gray-100 bg-white"
        style="display: none;"
    >
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                <i class="ri-dashboard-line text-base"></i>
                {{ __('Dashboard') }}
            </a>

            <a href="{{ route('admin.karyawan') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('admin.karyawan') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                <i class="ri-team-line text-base"></i>
                {{ __('Data Karyawan') }}
            </a>

            <a href="{{ route('admin.departemen') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('admin.departemen') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                <i class="ri-building-line text-base"></i>
                {{ __('Data Departemen') }}
            </a>

            <a href="{{ route('admin.monitoring-presensi') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('admin.monitoring-presensi') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                <i class="ri-radar-line text-base"></i>
                {{ __('Monitoring Presensi') }}
            </a>

            <a href="{{ route('admin.laporan.presensi') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('admin.laporan.presensi') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                <i class="ri-file-chart-line text-base"></i>
                {{ __('Laporan Presensi') }}
            </a>

            <a href="{{ route('admin.lokasi-kantor') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('admin.lokasi-kantor') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                <i class="ri-map-pin-line text-base"></i>
                {{ __('Lokasi Kantor') }}
            </a>

            <a href="{{ route('admin.administrasi-presensi') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('admin.administrasi-presensi') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                <i class="ri-clipboard-line text-base"></i>
                {{ __('Administrasi Presensi') }}
            </a>

            <a href="{{ route('admin.pengaturan') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('admin.pengaturan') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                <i class="ri-settings-3-line text-base"></i>
                {{ __('Pengaturan') }}
            </a>

            <a href="{{ route('admin.hari-libur') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('admin.hari-libur') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                <i class="ri-calendar-event-line text-base"></i>
                {{ __('Hari Libur') }}
            </a>

            <a href="{{ route('admin.qr-presensi') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('admin.qr-presensi') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                <i class="ri-qr-code-line text-base"></i>
                {{ __('QR Presensi') }}
            </a>

            <a href="{{ route('admin.koreksi-presensi') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('admin.koreksi-presensi') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                <i class="ri-edit-line text-base"></i>
                {{ __('Koreksi Presensi') }}
            </a>

            <a href="{{ route('admin.notifikasi') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('admin.notifikasi') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                <i class="ri-notification-3-line text-base"></i>
                {{ __('Notifikasi') }}
                @if ($unreadNotifikasi > 0)
                    <span class="ml-auto inline-flex h-5 w-5 items-center justify-center rounded-full text-[10px] font-bold text-white" style="background:#ef4444;">
                        {{ $unreadNotifikasi > 9 ? '9+' : $unreadNotifikasi }}
                    </span>
                @endif
            </a>

            <a href="{{ route('admin.backup') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('admin.backup') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                <i class="ri-database-2-line text-base"></i>
                {{ __('Backup Database') }}
            </a>

            <a href="{{ route('admin.activity-log') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('admin.activity-log') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                <i class="ri-history-line text-base"></i>
                {{ __('Log Aktivitas') }}
            </a>

            <a href="{{ route('admin.pengumuman') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('admin.pengumuman') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                <i class="ri-megaphone-line text-base"></i>
                {{ __('Pengumuman') }}
            </a>

            <a href="{{ route('admin.absensi-massal') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('admin.absensi-massal') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                <i class="ri-group-line text-base"></i>
                {{ __('Absensi Massal') }}
            </a>
        </div>

        <!-- Responsive User Section -->
        <div class="border-t border-gray-100 px-4 py-4">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-indigo-600 border-2 border-indigo-200 flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-sm font-semibold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <div class="space-y-1">
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-gray-50 transition-colors duration-150">
                    <i class="ri-user-settings-line text-base"></i>
                    {{ __('Profile') }}
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="flex items-center gap-3 w-full px-3 py-2.5 rounded-xl text-sm font-medium text-red-600 hover:bg-red-50 transition-colors duration-150"
                    >
                        <i class="ri-logout-box-r-line text-base"></i>
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    // Notification bell polling
    function updateBellBadge(count) {
        const badge = document.getElementById('bell-badge');
        if (!badge) return;
        if (count > 0) {
            badge.textContent = count > 9 ? '9+' : count;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }

    function loadNotifs() {
        const list = document.getElementById('notif-list');
        fetch('{{ route('admin.notifikasi.recent') }}')
            .then(r => r.json())
            .then(data => {
                updateBellBadge(0); // all marked read by recent endpoint
                const items = data.items || [];
                if (!items.length) {
                    list.innerHTML = '<div class="py-8 text-center text-sm text-gray-400"><i class="ri-notification-3-line text-2xl block mb-1"></i>Tidak ada notifikasi</div>';
                    return;
                }
                list.innerHTML = items.map(n => {
                    const iconMap = { success: 'ri-checkbox-circle-fill', warning: 'ri-alert-fill', error: 'ri-error-warning-fill', info: 'ri-information-fill' };
                    const colorMap = { success: '#10b981', warning: '#f59e0b', error: '#ef4444', info: '#6366f1' };
                    const icon  = iconMap[n.type] || 'ri-information-fill';
                    const color = colorMap[n.type] || '#6366f1';
                    return `<div class="flex gap-3 px-4 py-3 ${n.unread ? 'bg-indigo-50' : ''}">
                        <i class="${icon} flex-shrink-0 mt-0.5" style="color:${color};font-size:1rem"></i>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-800 truncate">${n.title}</p>
                            <p class="text-xs text-gray-500 line-clamp-2">${n.message}</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">${n.time}</p>
                        </div>
                    </div>`;
                }).join('');
            })
            .catch(() => {
                list.innerHTML = '<div class="py-4 text-center text-xs text-red-400">Gagal memuat notifikasi</div>';
            });
    }

    // Poll unread count every 30s
    setInterval(() => {
        fetch('{{ route('admin.notifikasi.count') }}')
            .then(r => r.json())
            .then(data => updateBellBadge(data.count))
            .catch(() => {});
    }, 30000);
</script>
