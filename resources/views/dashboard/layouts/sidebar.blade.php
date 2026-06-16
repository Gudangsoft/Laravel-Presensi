<aside class="ease-nav-brand z-990 fixed inset-y-0 my-4 flex w-64 -translate-x-full flex-col overflow-y-auto rounded-2xl bg-indigo-900 shadow-2xl transition-transform duration-200 xl:left-0 xl:ml-6 xl:translate-x-0" aria-expanded="false">

    {{-- Header / Brand --}}
    <div class="flex items-center justify-between border-b border-white/10 px-6 py-5">
        <a href="{{ route('karyawan.dashboard') }}" class="flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/15 overflow-hidden">
                @if($brand?->logo)
                    <img src="{{ $brand->logoUrl() }}" alt="Logo" class="h-7 w-7 object-contain">
                @else
                    <i class="ri-map-pin-time-fill text-xl text-white"></i>
                @endif
            </div>
            <span class="text-base font-bold tracking-wide text-white">{{ $brand?->nama_aplikasi ?? 'Presensi' }}</span>
        </a>
        <i class="ri-close-large-fill cursor-pointer text-lg text-white/50 hover:text-white xl:hidden" sidenav-close></i>
    </div>

    {{-- Navigation Menu --}}
    <nav class="flex flex-1 flex-col justify-between px-4 py-4">
        <ul class="flex flex-col gap-1">

            {{-- Dashboard --}}
            <li>
                <a href="{{ route('karyawan.dashboard') }}"
                   class="relative flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium transition-all duration-150
                          {{ Request::routeIs(['karyawan.dashboard'])
                              ? 'bg-white/10 text-white before:absolute before:left-0 before:top-1/2 before:h-5 before:w-1 before:-translate-y-1/2 before:rounded-r-full before:bg-indigo-400'
                              : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                    <i class="ri-dashboard-line text-lg"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- Presensi --}}
            <li>
                <a href="{{ route('karyawan.presensi') }}"
                   class="relative flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium transition-all duration-150
                          {{ Request::routeIs(['karyawan.presensi'])
                              ? 'bg-white/10 text-white before:absolute before:left-0 before:top-1/2 before:h-5 before:w-1 before:-translate-y-1/2 before:rounded-r-full before:bg-indigo-400'
                              : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                    <i class="ri-fingerprint-line text-lg"></i>
                    <span>Presensi</span>
                </a>
            </li>

            {{-- History --}}
            <li>
                <a href="{{ route('karyawan.history') }}"
                   class="relative flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium transition-all duration-150
                          {{ Request::routeIs(['karyawan.history'])
                              ? 'bg-white/10 text-white before:absolute before:left-0 before:top-1/2 before:h-5 before:w-1 before:-translate-y-1/2 before:rounded-r-full before:bg-indigo-400'
                              : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                    <i class="ri-history-line text-lg"></i>
                    <span>History</span>
                </a>
            </li>

            {{-- Izin --}}
            <li>
                <a href="{{ route('karyawan.izin') }}"
                   class="relative flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium transition-all duration-150
                          {{ Request::routeIs(['karyawan.izin', 'karyawan.izin.create'])
                              ? 'bg-white/10 text-white before:absolute before:left-0 before:top-1/2 before:h-5 before:w-1 before:-translate-y-1/2 before:rounded-r-full before:bg-indigo-400'
                              : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                    <i class="ri-file-list-3-line text-lg"></i>
                    <span>Izin</span>
                </a>
            </li>

            {{-- Koreksi Presensi --}}
            <li>
                <a href="{{ route('karyawan.koreksi') }}"
                   class="relative flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium transition-all duration-150
                          {{ Request::routeIs(['karyawan.koreksi', 'karyawan.koreksi.create'])
                              ? 'bg-white/10 text-white before:absolute before:left-0 before:top-1/2 before:h-5 before:w-1 before:-translate-y-1/2 before:rounded-r-full before:bg-indigo-400'
                              : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                    <i class="ri-edit-box-line text-lg"></i>
                    <span>Koreksi</span>
                </a>
            </li>

            {{-- Profile --}}
            <li>
                <a href="{{ route('karyawan.profile') }}"
                   class="relative flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium transition-all duration-150
                          {{ Request::routeIs(['karyawan.profile'])
                              ? 'bg-white/10 text-white before:absolute before:left-0 before:top-1/2 before:h-5 before:w-1 before:-translate-y-1/2 before:rounded-r-full before:bg-indigo-400'
                              : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                    <i class="ri-user-line text-lg"></i>
                    <span>Profile</span>
                </a>
            </li>

        </ul>

        {{-- Logout — pinned to bottom --}}
        <div class="border-t border-white/10 pt-4">
            <form method="POST" action="{{ route('logout.auth') }}">
                @csrf
                <button type="submit"
                        class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-white/60 transition-all duration-150 hover:bg-white/5 hover:text-white">
                    <i class="ri-logout-box-line text-lg"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>

</aside>
