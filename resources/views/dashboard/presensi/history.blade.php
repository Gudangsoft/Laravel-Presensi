@extends("dashboard.layouts.main")

@section("js")
<script>
$(document).ready(function () {

    // ── AJAX Filter ─────────────────────────────────────────
    $("#searchButton").click(function (e) {
        e.preventDefault();
        const btn = $(this);
        btn.html('<i class="ri-loader-4-line animate-spin"></i> Mencari...').prop('disabled', true);
        $.ajax({
            type: "POST",
            url: "{{ route('karyawan.history.search') }}",
            data: {
                _token: "{{ csrf_token() }}",
                bulan: $("#bulan").val(),
                tahun: $("#tahun").val()
            },
            success: function (res) {
                $("#searchPresensi").html(res);
                initSwipe();
            },
            complete: function () {
                btn.html('<i class="ri-search-line"></i> Cari').prop('disabled', false);
            }
        });
    });

    // ── Swipe Gesture ────────────────────────────────────────
    function initSwipe() {
        const items = document.querySelectorAll('.swipe-item');
        items.forEach(item => {
            let startX = 0, currentX = 0, isDragging = false;
            const inner   = item.querySelector('.swipe-inner');
            const actions = item.querySelector('.swipe-actions');
            const actionW = 88; // px revealed on swipe

            function onStart(x) {
                startX    = x;
                isDragging = true;
                inner.style.transition = 'none';
            }
            function onMove(x) {
                if (!isDragging) return;
                currentX = Math.max(-(actionW), Math.min(0, x - startX));
                inner.style.transform = `translateX(${currentX}px)`;
            }
            function onEnd() {
                if (!isDragging) return;
                isDragging = false;
                inner.style.transition = 'transform .25s cubic-bezier(.4,0,.2,1)';
                if (currentX < -(actionW / 2)) {
                    inner.style.transform = `translateX(-${actionW}px)`;
                    item.classList.add('swiped');
                } else {
                    inner.style.transform = 'translateX(0)';
                    item.classList.remove('swiped');
                }
            }

            // Touch
            inner.addEventListener('touchstart', e => onStart(e.touches[0].clientX), { passive: true });
            inner.addEventListener('touchmove',  e => onMove(e.touches[0].clientX),  { passive: true });
            inner.addEventListener('touchend',   () => onEnd());

            // Mouse (desktop preview)
            inner.addEventListener('mousedown', e => { onStart(e.clientX); });
            window.addEventListener('mousemove', e => { if (isDragging) onMove(e.clientX); });
            window.addEventListener('mouseup',   () => onEnd());

            // Close on tap outside
            document.addEventListener('touchstart', e => {
                if (!item.contains(e.target) && item.classList.contains('swiped')) {
                    inner.style.transition = 'transform .25s cubic-bezier(.4,0,.2,1)';
                    inner.style.transform = 'translateX(0)';
                    item.classList.remove('swiped');
                }
            }, { passive: true });

            // Close button
            const closeBtn = actions?.querySelector('.swipe-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    inner.style.transition = 'transform .25s cubic-bezier(.4,0,.2,1)';
                    inner.style.transform = 'translateX(0)';
                    item.classList.remove('swiped');
                });
            }
        });
    }

    initSwipe();
});
</script>
@endsection

@section("container")
<div class="space-y-4">

    {{-- ── Header ── --}}
    <div class="flex items-center gap-3">
        <div class="flex h-11 w-11 items-center justify-center rounded-2xl"
             style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
            <i class="ri-calendar-check-line text-lg text-white"></i>
        </div>
        <div>
            <h1 class="text-lg font-bold text-gray-800">Riwayat Presensi</h1>
            <p class="text-xs text-gray-400">Geser kiri untuk detail • {{ $riwayatPresensi->total() }} data</p>
        </div>
    </div>

    {{-- ── Rekap PDF ── --}}
    <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-4 shadow-sm">
        <div class="flex items-center gap-2 mb-3">
            <i class="ri-file-pdf-2-line text-indigo-600 text-base"></i>
            <p class="text-xs font-semibold text-indigo-700 uppercase tracking-wider">Download Rekap PDF</p>
        </div>
        <form action="{{ route('karyawan.presensi.rekap-pdf') }}" method="GET" class="grid grid-cols-2 gap-3">
            <div class="relative">
                <select name="bulan"
                    class="w-full appearance-none rounded-xl border border-indigo-200 bg-white py-2.5 pl-3 pr-8 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @foreach (['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $nama)
                        <option value="{{ $i + 1 }}" {{ ($i + 1) == date('n') ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
                <i class="ri-arrow-down-s-line absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
            </div>
            <div class="relative">
                <select name="tahun"
                    class="w-full appearance-none rounded-xl border border-indigo-200 bg-white py-2.5 pl-3 pr-8 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @for ($t = date('Y') - 2; $t <= date('Y'); $t++)
                        <option value="{{ $t }}" {{ $t == date('Y') ? 'selected' : '' }}>{{ $t }}</option>
                    @endfor
                </select>
                <i class="ri-arrow-down-s-line absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
            </div>
            <div class="col-span-2">
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 rounded-xl py-2.5 text-sm font-semibold text-white transition active:scale-95"
                    style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                    <i class="ri-download-2-line"></i> Download PDF
                </button>
            </div>
        </form>
    </div>

    {{-- ── Filter ── --}}
    <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
        <p class="mb-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Filter</p>
        <div class="grid grid-cols-2 gap-3">
            <div class="relative">
                <select id="bulan"
                    class="w-full appearance-none rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-3 pr-8 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option disabled selected>Bulan</option>
                    @foreach ($bulan as $value => $item)
                        <option value="{{ $value + 1 }}">{{ $item }}</option>
                    @endforeach
                </select>
                <i class="ri-arrow-down-s-line absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
            </div>
            <div class="relative">
                @php $tahunMulai = $riwayatPresensi[0] ? date("Y", strtotime($riwayatPresensi[0]->tanggal_presensi)) : date("Y"); @endphp
                <select id="tahun"
                    class="w-full appearance-none rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-3 pr-8 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option disabled selected>Tahun</option>
                    @for ($t = $tahunMulai; $t <= date("Y"); $t++)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endfor
                </select>
                <i class="ri-arrow-down-s-line absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
            </div>
        </div>
        <button id="searchButton" type="button"
            class="mt-3 flex w-full items-center justify-center gap-2 rounded-xl py-2.5 text-sm font-semibold text-white transition active:scale-95"
            style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
            <i class="ri-search-line"></i> Cari
        </button>
    </div>

    {{-- ── Swipe hint (shown once) ── --}}
    <div id="swipe-hint" class="flex items-center gap-2 rounded-xl bg-indigo-50 border border-indigo-100 px-3 py-2.5">
        <i class="ri-hand-coin-line text-indigo-500 text-lg flex-shrink-0"></i>
        <p class="text-xs text-indigo-600 font-medium">Geser item ke kiri untuk melihat detail durasi kerja</p>
        <button onclick="this.closest('#swipe-hint').remove()" class="ml-auto text-indigo-400 flex-shrink-0">
            <i class="ri-close-line"></i>
        </button>
    </div>

    {{-- ── Card List ── --}}
    <div id="searchPresensi" class="space-y-2">
        @if ($riwayatPresensi->count() > 0)
            @foreach ($riwayatPresensi as $item)
                @php
                    $terlambat = $item->jam_masuk > '08:00:00';
                    $durasi    = '—';
                    if ($item->jam_masuk && $item->jam_keluar) {
                        $masuk  = \Carbon\Carbon::parse($item->jam_masuk);
                        $keluar = \Carbon\Carbon::parse($item->jam_keluar);
                        $diff   = $masuk->diff($keluar);
                        $durasi = ($diff->h > 0 ? $diff->h . 'j ' : '') . $diff->i . 'm';
                    }
                @endphp

                {{-- Swipe wrapper --}}
                <div class="swipe-item relative overflow-hidden rounded-2xl">

                    {{-- Revealed action panel (behind) --}}
                    <div class="swipe-actions absolute inset-y-0 right-0 flex w-22 items-center justify-center rounded-r-2xl"
                         style="width:88px; background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                        <div class="flex flex-col items-center gap-0.5 text-white">
                            <i class="ri-timer-line text-xl"></i>
                            <span class="text-xs font-bold">{{ $durasi }}</span>
                            <span class="text-[9px] opacity-70">Total Kerja</span>
                        </div>
                    </div>

                    {{-- Card (foreground) --}}
                    <div class="swipe-inner flex items-center gap-3 rounded-2xl border border-gray-100 bg-white px-4 py-3.5 shadow-sm"
                         style="will-change:transform;">

                        {{-- Date badge --}}
                        <div class="flex-shrink-0 flex flex-col items-center justify-center w-12 h-12 rounded-xl {{ $terlambat ? 'bg-red-50' : 'bg-indigo-50' }}">
                            <span class="text-base font-black {{ $terlambat ? 'text-red-600' : 'text-indigo-600' }} leading-none">
                                {{ date('d', strtotime($item->tanggal_presensi)) }}
                            </span>
                            <span class="text-[9px] font-semibold {{ $terlambat ? 'text-red-400' : 'text-indigo-400' }} uppercase">
                                {{ date('M', strtotime($item->tanggal_presensi)) }}
                            </span>
                        </div>

                        {{-- Middle info --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-500">
                                {{ date('l', strtotime($item->tanggal_presensi)) }}
                            </p>
                            <div class="mt-0.5 flex items-center gap-2 text-sm font-bold text-gray-800">
                                <span>{{ date('H:i', strtotime($item->jam_masuk)) }}</span>
                                <i class="ri-arrow-right-line text-gray-300 text-xs font-normal"></i>
                                <span class="{{ $item->jam_keluar ? 'text-gray-800' : 'text-gray-300' }}">
                                    {{ $item->jam_keluar ? date('H:i', strtotime($item->jam_keluar)) : '—' }}
                                </span>
                            </div>
                            <p class="mt-0.5 text-[10px] text-gray-400">Durasi: {{ $durasi }}</p>
                        </div>

                        {{-- Status + swipe hint --}}
                        <div class="flex flex-col items-end gap-1.5 flex-shrink-0">
                            @if (!$terlambat)
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-1 text-[10px] font-bold text-emerald-700">
                                    <i class="ri-check-fill"></i> Tepat
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2.5 py-1 text-[10px] font-bold text-red-700">
                                    <i class="ri-time-fill"></i> Telat
                                </span>
                            @endif
                            <i class="ri-arrow-left-s-line text-gray-300 text-sm"></i>
                        </div>

                    </div>
                </div>
            @endforeach

            {{-- Pagination --}}
            @if ($riwayatPresensi->hasPages())
                <div class="pt-2">{{ $riwayatPresensi->links() }}</div>
            @endif

        @else
            <div class="flex flex-col items-center py-16 gap-3 text-gray-300">
                <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center">
                    <i class="ri-calendar-close-line text-3xl text-gray-300"></i>
                </div>
                <p class="text-sm font-semibold text-gray-400">Belum ada data presensi</p>
                <p class="text-xs text-gray-300">Pilih bulan & tahun lalu klik Cari</p>
            </div>
        @endif
    </div>

</div>
@endsection
