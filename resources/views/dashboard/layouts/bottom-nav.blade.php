{{-- Bottom Navigation (mobile/tablet only) --}}
<nav class="fixed bottom-0 left-0 right-0 z-50 xl:hidden"
     style="padding-bottom: env(safe-area-inset-bottom, 0px);">

    <div class="relative" style="background:rgba(255,255,255,0.95);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-top:1px solid rgba(0,0,0,0.07);box-shadow:0 -4px 24px rgba(0,0,0,0.08);">

        <div class="mx-auto flex max-w-sm h-16 items-center px-1">

            {{-- Home --}}
            @php $a1 = Request::routeIs('karyawan.dashboard'); @endphp
            <a href="{{ route('karyawan.dashboard') }}"
               class="flex flex-1 flex-col items-center justify-center gap-0.5 h-full transition-all duration-200 {{ $a1 ? 'text-indigo-600' : 'text-gray-400' }}">
                <span class="flex h-8 w-8 items-center justify-center rounded-xl {{ $a1 ? 'bg-indigo-50' : '' }}">
                    <i class="{{ $a1 ? 'ri-home-5-fill' : 'ri-home-5-line' }} text-xl"></i>
                </span>
                <span class="text-[10px] {{ $a1 ? 'font-bold text-indigo-600' : 'font-semibold' }} leading-none">Home</span>
            </a>

            {{-- Riwayat --}}
            @php $a2 = Request::routeIs('karyawan.history'); @endphp
            <a href="{{ route('karyawan.history') }}"
               class="flex flex-1 flex-col items-center justify-center gap-0.5 h-full transition-all duration-200 {{ $a2 ? 'text-indigo-600' : 'text-gray-400' }}">
                <span class="flex h-8 w-8 items-center justify-center rounded-xl {{ $a2 ? 'bg-indigo-50' : '' }}">
                    <i class="ri-history-line text-xl"></i>
                </span>
                <span class="text-[10px] {{ $a2 ? 'font-bold text-indigo-600' : 'font-semibold' }} leading-none">Riwayat</span>
            </a>

            {{-- Kalender --}}
            @php $a7 = Request::routeIs('karyawan.kalender'); @endphp
            <a href="{{ route('karyawan.kalender') }}"
               class="flex flex-1 flex-col items-center justify-center gap-0.5 h-full transition-all duration-200 {{ $a7 ? 'text-indigo-600' : 'text-gray-400' }}">
                <span class="flex h-8 w-8 items-center justify-center rounded-xl {{ $a7 ? 'bg-indigo-50' : '' }}">
                    <i class="{{ $a7 ? 'ri-calendar-check-fill' : 'ri-calendar-check-line' }} text-xl"></i>
                </span>
                <span class="text-[10px] {{ $a7 ? 'font-bold text-indigo-600' : 'font-semibold' }} leading-none">Kalender</span>
            </a>

            {{-- Center slot: Presensi FAB --}}
            <div class="flex-1 relative" style="height:64px;">
                <a href="{{ route('karyawan.presensi') }}"
                   style="position:absolute; top:-24px; left:50%; transform:translateX(-50%); width:54px; height:54px; border-radius:16px; background:linear-gradient(135deg,#4f46e5,#7c3aed); box-shadow:0 6px 20px rgba(79,70,229,0.5); display:flex; align-items:center; justify-content:center; text-decoration:none;">
                    <i class="ri-fingerprint-line text-white" style="font-size:26px;"></i>
                </a>
                <span style="position:absolute; bottom:6px; left:50%; transform:translateX(-50%); white-space:nowrap; font-size:10px; font-weight:700; line-height:1; {{ Request::routeIs('karyawan.presensi') ? 'color:#4f46e5;' : 'color:#9ca3af;' }}">
                    Presensi
                </span>
            </div>

            {{-- Izin --}}
            @php $a4 = Request::routeIs(['karyawan.izin', 'karyawan.izin.create', 'karyawan.koreksi', 'karyawan.koreksi.create']); @endphp
            <a href="{{ route('karyawan.izin') }}"
               class="flex flex-1 flex-col items-center justify-center gap-0.5 h-full transition-all duration-200 {{ $a4 ? 'text-indigo-600' : 'text-gray-400' }}">
                <span class="flex h-8 w-8 items-center justify-center rounded-xl {{ $a4 ? 'bg-indigo-50' : '' }}">
                    <i class="{{ $a4 ? 'ri-file-list-3-fill' : 'ri-file-list-3-line' }} text-xl"></i>
                </span>
                <span class="text-[10px] {{ $a4 ? 'font-bold text-indigo-600' : 'font-semibold' }} leading-none">Izin</span>
            </a>

            {{-- Smart Learning --}}
            @php $a6 = Request::routeIs('karyawan.learning'); @endphp
            <a href="{{ route('karyawan.learning') }}"
               class="flex flex-1 flex-col items-center justify-center gap-0.5 h-full transition-all duration-200 {{ $a6 ? 'text-indigo-600' : 'text-gray-400' }}">
                <span class="relative flex h-8 w-8 items-center justify-center rounded-xl {{ $a6 ? 'bg-indigo-50' : '' }}">
                    <i class="ri-translate-2 text-xl"></i>
                    @if(!$a6)
                        <span class="absolute -top-1 -right-1 h-2 w-2 rounded-full bg-emerald-500"></span>
                    @endif
                </span>
                <span class="text-[10px] {{ $a6 ? 'font-bold text-indigo-600' : 'font-semibold' }} leading-none">English</span>
            </a>

            {{-- Profil --}}
            @php $a5 = Request::routeIs('karyawan.profile'); @endphp
            <a href="{{ route('karyawan.profile') }}"
               class="flex flex-1 flex-col items-center justify-center gap-0.5 h-full transition-all duration-200 {{ $a5 ? 'text-indigo-600' : 'text-gray-400' }}">
                <span class="flex h-8 w-8 items-center justify-center rounded-xl {{ $a5 ? 'bg-indigo-50' : '' }}">
                    <i class="{{ $a5 ? 'ri-user-3-fill' : 'ri-user-3-line' }} text-xl"></i>
                </span>
                <span class="text-[10px] {{ $a5 ? 'font-bold text-indigo-600' : 'font-semibold' }} leading-none">Profil</span>
            </a>

        </div>
    </div>
</nav>
