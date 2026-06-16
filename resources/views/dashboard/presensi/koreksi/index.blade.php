@extends("dashboard.layouts.main")

@section("container")
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl"
                 style="background:linear-gradient(135deg,#f59e0b,#d97706);">
                <i class="ri-edit-line text-lg text-white"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-gray-800">Koreksi Presensi</h1>
                <p class="text-xs text-gray-400">Riwayat pengajuan koreksi</p>
            </div>
        </div>
        <a href="{{ route('karyawan.koreksi.create') }}"
           class="flex items-center gap-1.5 rounded-xl px-4 py-2.5 text-sm font-bold text-white transition active:scale-95"
           style="background:linear-gradient(135deg,#f59e0b,#d97706);">
            <i class="ri-add-line"></i> Ajukan
        </a>
    </div>

    @if (session('success'))
        <div class="flex items-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3">
            <i class="ri-checkbox-circle-line text-emerald-500"></i>
            <p class="text-sm text-emerald-700">{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="flex items-center gap-2 rounded-2xl border border-red-200 bg-red-50 px-4 py-3">
            <i class="ri-error-warning-line text-red-500"></i>
            <p class="text-sm text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    @forelse ($koreksis as $k)
        @php
            $badgeColor = match($k->status) {
                1 => 'background:#d1fae5;color:#065f46',
                2 => 'background:#fee2e2;color:#991b1b',
                default => 'background:#fef3c7;color:#92400e',
            };
        @endphp
        <div class="rounded-2xl border border-gray-100 bg-white shadow-sm p-4">
            <div class="flex items-start justify-between gap-3">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="ri-calendar-line text-gray-400 text-sm"></i>
                        <span class="text-sm font-bold text-gray-800">
                            {{ \Carbon\Carbon::parse($k->tanggal)->translatedFormat('d M Y') }}
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-3 text-xs text-gray-500 mb-2">
                        @if ($k->jam_masuk)
                            <span class="flex items-center gap-1">
                                <i class="ri-login-circle-line text-emerald-500"></i>
                                Masuk: <strong class="text-gray-700">{{ $k->jam_masuk }}</strong>
                            </span>
                        @endif
                        @if ($k->jam_keluar)
                            <span class="flex items-center gap-1">
                                <i class="ri-logout-circle-line text-amber-500"></i>
                                Keluar: <strong class="text-gray-700">{{ $k->jam_keluar }}</strong>
                            </span>
                        @endif
                    </div>

                    <p class="text-xs text-gray-500 line-clamp-2">{{ $k->keterangan }}</p>
                    <p class="text-[10px] text-gray-400 mt-1.5">Diajukan {{ $k->created_at->diffForHumans() }}</p>
                </div>
                <span class="inline-flex flex-shrink-0 items-center rounded-full px-3 py-1 text-[11px] font-bold"
                      style="{{ $badgeColor }}">
                    {{ $k->statusLabel() }}
                </span>
            </div>

            @if ($k->status == 2 && $k->catatan_admin)
                <div class="mt-3 rounded-xl bg-red-50 border border-red-100 px-3 py-2 flex gap-2">
                    <i class="ri-information-line text-red-400 flex-shrink-0 text-sm mt-0.5"></i>
                    <p class="text-xs text-red-600">{{ $k->catatan_admin }}</p>
                </div>
            @endif
        </div>
    @empty
        <div class="rounded-2xl border border-gray-100 bg-white p-12 text-center shadow-sm">
            <div class="mx-auto mb-3 flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-50">
                <i class="ri-edit-line text-2xl text-amber-400"></i>
            </div>
            <p class="text-sm font-semibold text-gray-600">Belum ada pengajuan koreksi</p>
            <p class="mt-1 text-xs text-gray-400">Ajukan jika ada kesalahan atau lupa absen</p>
            <a href="{{ route('karyawan.koreksi.create') }}"
               class="mt-4 inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-bold text-white transition active:scale-95"
               style="background:linear-gradient(135deg,#f59e0b,#d97706);">
                <i class="ri-add-line"></i> Ajukan Sekarang
            </a>
        </div>
    @endforelse

    @if ($koreksis->hasPages())
        <div class="pt-2">{{ $koreksis->links() }}</div>
    @endif

</div>
@endsection
