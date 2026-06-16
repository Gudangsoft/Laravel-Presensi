@extends("dashboard.layouts.main")

@section("container")
@php
    $hour = (int) \Carbon\Carbon::now('Asia/Jakarta')->format('H');
    if      ($hour >= 0  && $hour < 11) $greeting = 'Pagi';
    elseif  ($hour >= 11 && $hour < 15) $greeting = 'Siang';
    elseif  ($hour >= 15 && $hour < 19) $greeting = 'Sore';
    else                                 $greeting = 'Malam';
    $user = Auth::guard('karyawan')->user();

    // Today status
    $sudahMasuk  = $presensiHariIni != null;
    $sudahKeluar = $sudahMasuk && $presensiHariIni->jam_keluar != null;

    // Masuk badge
    if ($sudahMasuk) {
        $jamMasukKaryawan = \Carbon\Carbon::parse($presensiHariIni->jam_masuk);
        $batasJamMasuk    = \Carbon\Carbon::parse($pengaturan->jam_masuk);
        $batasToleransi   = $batasJamMasuk->copy()->addMinutes($pengaturan->toleransi);
        if ($jamMasukKaryawan->lt($batasJamMasuk)) {
            $selisihM       = $jamMasukKaryawan->diff($batasJamMasuk);
            $masukBadge     = 'Lebih Awal ' . ($selisihM->h > 0 ? $selisihM->h.'j ' : '') . $selisihM->i.'m';
            $masukBadgeColor = 'emerald';
            $masukIcon      = 'ri-arrow-up-circle-fill';
        } elseif ($jamMasukKaryawan->gt($batasToleransi)) {
            $selisihM       = $batasJamMasuk->diff($jamMasukKaryawan);
            $masukBadge     = 'Terlambat ' . ($selisihM->h > 0 ? $selisihM->h.'j ' : '') . $selisihM->i.'m';
            $masukBadgeColor = 'red';
            $masukIcon      = 'ri-alarm-warning-fill';
        } else {
            $masukBadge     = 'Tepat Waktu';
            $masukBadgeColor = 'blue';
            $masukIcon      = 'ri-check-fill';
        }
    }

    // Keluar badge
    if ($sudahKeluar) {
        $jamKeluar      = \Carbon\Carbon::parse($presensiHariIni->jam_keluar);
        $batasKeluar    = \Carbon\Carbon::parse($pengaturan->jam_pulang);
        $selisihK       = $jamKeluar->diff($batasKeluar);
        $durasiK        = ($selisihK->h > 0 ? $selisihK->h.'j ' : '') . $selisihK->i.'m';
        if ($jamKeluar->lt($batasKeluar)) {
            $keluarBadge     = 'Lebih Awal '.$durasiK;
            $keluarBadgeColor = 'red';
            $keluarIcon      = 'ri-arrow-down-circle-fill';
        } elseif ($jamKeluar->gt($batasKeluar)) {
            $keluarBadge     = 'Lembur '.$durasiK;
            $keluarBadgeColor = 'emerald';
            $keluarIcon      = 'ri-trophy-line';
        } else {
            $keluarBadge     = 'Tepat Waktu';
            $keluarBadgeColor = 'blue';
            $keluarIcon      = 'ri-check-fill';
        }
    }
@endphp

<div class="space-y-4">

    {{-- ══════════ HERO GREETING CARD ══════════ --}}
    <div class="relative overflow-hidden rounded-3xl p-5 shadow-xl"
         style="background: linear-gradient(135deg, #3730a3 0%, #4f46e5 45%, #7c3aed 100%);">

        {{-- Decorative blobs --}}
        <div style="position:absolute;top:-40px;right:-40px;width:160px;height:160px;border-radius:50%;background:rgba(255,255,255,0.08);"></div>
        <div style="position:absolute;bottom:-30px;left:60px;width:100px;height:100px;border-radius:50%;background:rgba(255,255,255,0.06);"></div>
        <div style="position:absolute;top:50%;right:30px;width:60px;height:60px;border-radius:50%;background:rgba(255,255,255,0.05);"></div>

        <div class="relative">
            {{-- Baris atas: avatar + nama --}}
            <div class="flex items-center gap-3 mb-3">
                {{-- Avatar --}}
                <div class="flex-shrink-0">
                    @if ($user->foto)
                        <img src="{{ asset('storage/unggah/karyawan/' . $user->foto) }}"
                             alt="{{ $user->nama_lengkap }}"
                             class="h-14 w-14 rounded-2xl object-cover ring-2 ring-white/30 shadow-lg">
                    @else
                        <div class="h-14 w-14 rounded-2xl flex items-center justify-center text-xl font-bold text-indigo-700 shadow-lg"
                             style="background:rgba(255,255,255,0.9);">
                            {{ mb_strtoupper(mb_substr($user->nama_lengkap, 0, 1)) }}
                        </div>
                    @endif
                </div>

                {{-- Name + Greeting --}}
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-indigo-200 leading-none mb-1">Selamat {{ $greeting }} 👋</p>
                    <h2 class="text-base font-bold text-white leading-snug" style="word-break:break-word;">{{ $user->nama_lengkap }}</h2>
                    <p class="text-xs text-indigo-300 mt-0.5">{{ $user->jabatan ?: 'Karyawan' }}</p>
                </div>
            </div>

            {{-- Baris bawah: tanggal kiri + jam kanan --}}
            <div class="flex items-center justify-between gap-2">
                <div class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1.5 backdrop-blur-sm">
                    <i class="ri-calendar-line text-white text-xs"></i>
                    <span class="text-xs font-medium text-white whitespace-nowrap">{{ \Carbon\Carbon::now('Asia/Jakarta')->isoFormat('D MMM Y') }}</span>
                </div>

                <div class="rounded-2xl px-3 py-2" style="background:rgba(255,255,255,0.15);backdrop-filter:blur(8px);">
                    <p class="text-[10px] font-semibold text-indigo-200 uppercase tracking-widest text-center mb-0.5">WIB</p>
                    <p id="live-clock" class="text-xl font-bold text-white font-mono tracking-wider leading-none"></p>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════ TODAY ATTENDANCE STATUS ══════════ --}}
    <div class="rounded-3xl overflow-hidden shadow-sm border border-gray-100 bg-white">
        <div class="px-4 pt-4 pb-2 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-800">Presensi Hari Ini</h3>
            <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">
                {{ \Carbon\Carbon::now('Asia/Jakarta')->isoFormat('dddd') }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-3 px-4 pb-4">
            {{-- Masuk --}}
            <div class="rounded-2xl p-4 {{ $sudahMasuk ? 'bg-indigo-50 border border-indigo-100' : 'bg-gray-50 border border-gray-100' }}">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ $sudahMasuk ? 'bg-indigo-500' : 'bg-gray-300' }}">
                        <i class="ri-login-circle-line text-white text-xs"></i>
                    </div>
                    <span class="text-[11px] font-semibold {{ $sudahMasuk ? 'text-indigo-600' : 'text-gray-400' }}">MASUK</span>
                </div>
                @if ($sudahMasuk)
                    <p class="text-xl font-bold text-gray-800 leading-none">{{ date('H:i', strtotime($presensiHariIni->jam_masuk)) }}</p>
                    <p class="text-[10px] text-gray-400 mb-2">WIB</p>
                    <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-semibold
                        {{ $masukBadgeColor === 'emerald' ? 'bg-emerald-100 text-emerald-700' : ($masukBadgeColor === 'red' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                        <i class="{{ $masukIcon }} text-[10px]"></i> {{ $masukBadge }}
                    </span>
                @else
                    <p class="text-2xl font-bold text-gray-300 leading-none">—</p>
                    <p class="text-[10px] text-gray-400 mt-1">Belum masuk</p>
                @endif
            </div>

            {{-- Keluar --}}
            <div class="rounded-2xl p-4 {{ $sudahKeluar ? 'bg-rose-50 border border-rose-100' : 'bg-gray-50 border border-gray-100' }}">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ $sudahKeluar ? 'bg-rose-500' : 'bg-gray-300' }}">
                        <i class="ri-logout-circle-r-line text-white text-xs"></i>
                    </div>
                    <span class="text-[11px] font-semibold {{ $sudahKeluar ? 'text-rose-600' : 'text-gray-400' }}">PULANG</span>
                </div>
                @if ($sudahKeluar)
                    <p class="text-xl font-bold text-gray-800 leading-none">{{ date('H:i', strtotime($presensiHariIni->jam_keluar)) }}</p>
                    <p class="text-[10px] text-gray-400 mb-2">WIB</p>
                    <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-semibold
                        {{ $keluarBadgeColor === 'emerald' ? 'bg-emerald-100 text-emerald-700' : ($keluarBadgeColor === 'red' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                        <i class="{{ $keluarIcon }} text-[10px]"></i> {{ $keluarBadge }}
                    </span>
                @else
                    <p class="text-2xl font-bold text-gray-300 leading-none">—</p>
                    <p class="text-[10px] text-gray-400 mt-1">Belum pulang</p>
                @endif
            </div>
        </div>

        {{-- CTA: Presensi button if not done --}}
        @if (!$sudahMasuk || !$sudahKeluar)
            <div class="px-4 pb-4">
                <a href="{{ route('karyawan.presensi') }}"
                   style="background: linear-gradient(135deg, #4f46e5, #7c3aed);"
                   class="flex w-full items-center justify-center gap-2 rounded-2xl py-3.5 text-sm font-bold text-white shadow-md active:scale-95 transition-transform">
                    <i class="ri-fingerprint-line text-xl"></i>
                    {{ !$sudahMasuk ? 'Presensi Masuk Sekarang' : 'Presensi Pulang Sekarang' }}
                </a>
            </div>
        @else
            <div class="border-t border-gray-50 px-4 py-3 flex items-center justify-center gap-2 text-emerald-600">
                <i class="ri-shield-check-line"></i>
                <span class="text-xs font-semibold">Presensi hari ini sudah selesai</span>
            </div>
        @endif
    </div>

    {{-- ══════════ JADWAL KERJA ══════════ --}}
    <div class="rounded-2xl bg-white border border-gray-100 shadow-sm flex items-center">

        {{-- Masuk --}}
        <div class="flex flex-1 items-center gap-3 px-4 py-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background:#eef2ff;">
                <i class="ri-login-circle-line text-lg" style="color:#4f46e5;"></i>
            </div>
            <div>
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Masuk</p>
                <p class="text-xl font-black text-gray-800 leading-tight">{{ \Carbon\Carbon::parse($pengaturan->jam_masuk)->format('H:i') }}</p>
            </div>
        </div>

        {{-- Divider --}}
        <div class="w-px h-10 bg-gray-100 flex-shrink-0"></div>

        {{-- Pulang --}}
        <div class="flex flex-1 items-center gap-3 px-4 py-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background:#fff1f2;">
                <i class="ri-logout-circle-r-line text-lg" style="color:#f43f5e;"></i>
            </div>
            <div>
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Pulang</p>
                <p class="text-xl font-black text-gray-800 leading-tight">{{ \Carbon\Carbon::parse($pengaturan->jam_pulang)->format('H:i') }}</p>
            </div>
        </div>

    </div>

    {{-- ══════════ QUICK ACTIONS ══════════ --}}
    <div class="grid grid-cols-2 gap-3">
        <a href="{{ route('karyawan.izin.create') }}"
           class="flex items-center gap-3 rounded-2xl p-4 bg-white border border-gray-100 shadow-sm hover:shadow-md transition-shadow active:scale-95">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background:linear-gradient(135deg,#ddd6fe,#c4b5fd);">
                <i class="ri-file-list-3-line text-lg" style="color:#7c3aed;"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-800">Ajukan Izin</p>
                <p class="text-[10px] text-gray-400">Izin / Sakit</p>
            </div>
        </a>
        <a href="{{ route('karyawan.koreksi.create') }}"
           class="flex items-center gap-3 rounded-2xl p-4 bg-white border border-gray-100 shadow-sm hover:shadow-md transition-shadow active:scale-95">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background:linear-gradient(135deg,#fef3c7,#fde68a);">
                <i class="ri-edit-box-line text-lg" style="color:#d97706;"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-800">Koreksi</p>
                <p class="text-[10px] text-gray-400">Lupa absen</p>
            </div>
        </a>
    </div>

    {{-- ══════════ PENGUMUMAN ══════════ --}}
    @if ($pengumuman->isNotEmpty())
        <div class="space-y-2">
            @foreach ($pengumuman as $p)
                <div class="rounded-2xl border overflow-hidden shadow-sm
                            {{ $p->is_pinned ? 'border-amber-200 bg-amber-50' : 'border-indigo-100 bg-indigo-50' }}">
                    <div class="flex items-start gap-3 px-4 py-3.5">
                        <div class="flex-shrink-0 w-8 h-8 rounded-xl flex items-center justify-center
                                    {{ $p->is_pinned ? 'bg-amber-400' : 'bg-indigo-500' }}">
                            <i class="{{ $p->is_pinned ? 'ri-pushpin-fill' : 'ri-megaphone-fill' }} text-white text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold {{ $p->is_pinned ? 'text-amber-800' : 'text-indigo-800' }} truncate">{{ $p->judul }}</p>
                            <p class="text-xs {{ $p->is_pinned ? 'text-amber-700' : 'text-indigo-700' }} mt-0.5 line-clamp-2">{{ $p->isi }}</p>
                            <p class="text-[10px] {{ $p->is_pinned ? 'text-amber-500' : 'text-indigo-400' }} mt-1">{{ $p->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- ══════════ REKAP BULAN INI ══════════ --}}
    <div class="rounded-3xl overflow-hidden shadow-md border border-gray-100">

        {{-- Header gradient --}}
        <div class="flex items-center justify-between px-5 py-4"
             style="background: linear-gradient(135deg, #312e81 0%, #4f46e5 60%, #7c3aed 100%);">
            <div>
                <h3 class="text-sm font-bold text-white">Rekap Bulan Ini</h3>
                <p class="text-[11px] text-indigo-200 mt-0.5">{{ \Carbon\Carbon::now('Asia/Jakarta')->isoFormat('MMMM Y') }}</p>
            </div>
            <div class="flex items-center gap-1.5 rounded-full px-3 py-1.5" style="background:rgba(255,255,255,0.15);">
                <i class="ri-bar-chart-box-line text-white text-sm"></i>
                <span class="text-[11px] font-semibold text-white">Statistik</span>
            </div>
        </div>

        {{-- 2×2 grid — gap-px on gray bg creates crisp dividers --}}
        <div class="grid grid-cols-2 gap-px bg-gray-100">

            {{-- Hadir --}}
            <div class="bg-white flex flex-col items-center py-5 px-3 text-center">
                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl"
                     style="background: linear-gradient(135deg, #dbeafe, #bfdbfe);">
                    <i class="ri-user-follow-line text-xl" style="color:#2563eb;"></i>
                </div>
                <p class="text-4xl font-black leading-none" style="color:#1e40af;">{{ $rekapPresensi->jml_kehadiran ?? 0 }}</p>
                <p class="mt-1.5 text-xs font-semibold text-gray-500">Hari Hadir</p>
                <div class="mt-2.5 h-1 w-10 rounded-full" style="background:#bfdbfe;"></div>
            </div>

            {{-- Terlambat --}}
            <div class="bg-white flex flex-col items-center py-5 px-3 text-center">
                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl"
                     style="background: linear-gradient(135deg, #fee2e2, #fecaca);">
                    <i class="ri-alarm-warning-line text-xl" style="color:#dc2626;"></i>
                </div>
                <p class="text-4xl font-black leading-none" style="color:#991b1b;">{{ $rekapPresensi->jml_terlambat ?? 0 }}</p>
                <p class="mt-1.5 text-xs font-semibold text-gray-500">Terlambat</p>
                <div class="mt-2.5 h-1 w-10 rounded-full" style="background:#fecaca;"></div>
            </div>

            {{-- Sakit --}}
            <div class="bg-white flex flex-col items-center py-5 px-3 text-center">
                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl"
                     style="background: linear-gradient(135deg, #d1fae5, #a7f3d0);">
                    <i class="ri-heart-pulse-line text-xl" style="color:#059669;"></i>
                </div>
                <p class="text-4xl font-black leading-none" style="color:#065f46;">{{ $rekapPengajuanPresensi->jml_sakit ?? 0 }}</p>
                <p class="mt-1.5 text-xs font-semibold text-gray-500">Hari Sakit</p>
                <div class="mt-2.5 h-1 w-10 rounded-full" style="background:#a7f3d0;"></div>
            </div>

            {{-- Izin --}}
            <div class="bg-white flex flex-col items-center py-5 px-3 text-center">
                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl"
                     style="background: linear-gradient(135deg, #fef3c7, #fde68a);">
                    <i class="ri-file-list-3-line text-xl" style="color:#d97706;"></i>
                </div>
                <p class="text-4xl font-black leading-none" style="color:#92400e;">{{ $rekapPengajuanPresensi->jml_izin ?? 0 }}</p>
                <p class="mt-1.5 text-xs font-semibold text-gray-500">Hari Izin</p>
                <div class="mt-2.5 h-1 w-10 rounded-full" style="background:#fde68a;"></div>
            </div>

        </div>
    </div>

    {{-- ══════════ RIWAYAT PRESENSI (card list) ══════════ --}}
    <div class="rounded-3xl border border-gray-100 bg-white shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3.5 border-b border-gray-50">
            <div>
                <h3 class="text-sm font-bold text-gray-800">Riwayat Presensi</h3>
                <p class="text-[11px] text-gray-400">{{ \Carbon\Carbon::now('Asia/Jakarta')->isoFormat('MMMM Y') }}</p>
            </div>
            <a href="{{ route('karyawan.history') }}"
               class="text-[11px] font-semibold text-indigo-600 flex items-center gap-1">
                Lihat Semua <i class="ri-arrow-right-s-line"></i>
            </a>
        </div>

        <div class="divide-y divide-gray-50">
            @forelse ($riwayatPresensi as $item)
                <div class="flex items-center gap-3 px-4 py-3">
                    {{-- Date badge --}}
                    <div class="flex-shrink-0 w-11 h-11 rounded-xl bg-indigo-50 flex flex-col items-center justify-center">
                        <span class="text-base font-bold text-indigo-700 leading-none">{{ date('d', strtotime($item->tanggal_presensi)) }}</span>
                        <span class="text-[9px] font-semibold text-indigo-400 uppercase">{{ date('M', strtotime($item->tanggal_presensi)) }}</span>
                    </div>

                    {{-- Times --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-gray-500 mb-0.5">{{ date('l', strtotime($item->tanggal_presensi)) }}</p>
                        <div class="flex items-center gap-2 text-xs text-gray-700 font-medium">
                            <span class="flex items-center gap-1">
                                <i class="ri-login-circle-line text-indigo-400 text-xs"></i>
                                {{ date('H:i', strtotime($item->jam_masuk)) }}
                            </span>
                            <i class="ri-arrow-right-line text-gray-300 text-xs"></i>
                            <span class="flex items-center gap-1">
                                <i class="ri-logout-circle-r-line text-rose-400 text-xs"></i>
                                {{ $item->jam_keluar ? date('H:i', strtotime($item->jam_keluar)) : '—' }}
                            </span>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="flex-shrink-0">
                        @if ($item->jam_masuk <= $pengaturan->jam_masuk)
                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-[10px] font-bold text-emerald-700">
                                <i class="ri-check-fill mr-0.5"></i>Tepat
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-1 text-[10px] font-bold text-red-700">
                                <i class="ri-time-fill mr-0.5"></i>Terlambat
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center py-10 gap-2 text-gray-300">
                    <i class="ri-calendar-close-line text-4xl"></i>
                    <p class="text-sm font-medium">Belum ada riwayat bulan ini</p>
                </div>
            @endforelse
        </div>

        @if ($riwayatPresensi->hasPages())
            <div class="border-t border-gray-50 px-4 py-3">
                {{ $riwayatPresensi->links() }}
            </div>
        @endif
    </div>

    {{-- ══════════ LEADERBOARD ══════════ --}}
    <div class="rounded-3xl border border-gray-100 bg-white shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3.5 border-b border-gray-50">
            <div>
                <h3 class="text-sm font-bold text-gray-800">Leaderboard Hari Ini</h3>
                <p class="text-[11px] text-gray-400">{{ \Carbon\Carbon::now('Asia/Jakarta')->isoFormat('D MMMM Y') }}</p>
            </div>
            <div class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center">
                <i class="ri-trophy-line text-amber-600"></i>
            </div>
        </div>

        <div class="divide-y divide-gray-50">
            @forelse ($leaderboard as $idx => $item)
                @php $rank = $leaderboard->firstItem() + $idx; @endphp
                <div class="flex items-center gap-3 px-4 py-3">
                    {{-- Rank badge --}}
                    @if ($rank === 1)
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 text-sm font-bold text-white"
                             style="background: linear-gradient(135deg,#facc15,#f59e0b);">{{ $rank }}</div>
                    @elseif ($rank === 2)
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 text-sm font-bold text-white"
                             style="background: linear-gradient(135deg,#d1d5db,#9ca3af);">{{ $rank }}</div>
                    @elseif ($rank === 3)
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 text-sm font-bold text-white"
                             style="background: linear-gradient(135deg,#fb923c,#b45309);">{{ $rank }}</div>
                    @else
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 text-sm font-semibold text-gray-400 bg-gray-100">{{ $rank }}</div>
                    @endif

                    {{-- Avatar + name --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $item->nama_lengkap }}</p>
                        <p class="text-[11px] text-gray-400 truncate">{{ $item->jabatan }}</p>
                    </div>

                    {{-- Time --}}
                    <div class="flex-shrink-0 text-right">
                        <p class="text-xs font-bold text-indigo-600">{{ date('H:i', strtotime($item->jam_masuk)) }}</p>
                        <p class="text-[10px] text-gray-400">{{ $item->jam_keluar ? date('H:i', strtotime($item->jam_keluar)) : '—' }}</p>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center py-10 gap-2 text-gray-300">
                    <i class="ri-user-search-line text-4xl"></i>
                    <p class="text-sm font-medium">Belum ada yang presensi hari ini</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

@endsection

@section("js")
<script>
    function updateClock() {
        const now   = new Date();
        const parts = now.toLocaleString('id-ID', {
            timeZone: 'Asia/Jakarta',
            hour: '2-digit', minute: '2-digit', second: '2-digit',
            hour12: false
        }).split('.');
        const el = document.getElementById('live-clock');
        if (el) el.textContent = parts.join(':');
    }
    updateClock();
    setInterval(updateClock, 1000);
</script>
@endsection
