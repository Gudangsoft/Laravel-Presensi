@extends("dashboard.layouts.main")

@section("container")
@php
    use Carbon\Carbon;
    $tgl      = Carbon::create($tahun, $bulan, 1);
    $awal     = $tgl->copy()->startOfMonth();
    $akhir    = $tgl->copy()->endOfMonth();
    $startDay = $awal->dayOfWeek; // 0=Sun
    $totalDays= $akhir->day;
    $hari     = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
@endphp

<div class="max-w-2xl mx-auto">

    <div class="flex items-center gap-3 mb-5">
        <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-indigo-400 to-violet-600 flex items-center justify-center shadow">
            <i class="ri-calendar-check-line text-white text-xl"></i>
        </div>
        <div>
            <h1 class="text-lg font-bold text-gray-800">Kalender Kehadiran</h1>
            <p class="text-xs text-gray-400">{{ $tgl->isoFormat('MMMM Y') }}</p>
        </div>
    </div>

    {{-- Month navigation --}}
    <div class="flex items-center justify-between mb-4 bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-3">
        @php
            $prev = $tgl->copy()->subMonth();
            $next = $tgl->copy()->addMonth();
        @endphp
        <a href="{{ route('karyawan.kalender', ['bulan'=>$prev->month,'tahun'=>$prev->year]) }}"
           class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-gray-100 text-gray-500 transition-colors">
            <i class="ri-arrow-left-s-line text-xl"></i>
        </a>
        <span class="text-sm font-bold text-gray-800">{{ $tgl->isoFormat('MMMM Y') }}</span>
        <a href="{{ route('karyawan.kalender', ['bulan'=>$next->month,'tahun'=>$next->year]) }}"
           class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-gray-100 text-gray-500 transition-colors">
            <i class="ri-arrow-right-s-line text-xl"></i>
        </a>
    </div>

    {{-- Legend --}}
    <div class="flex items-center gap-4 mb-3 flex-wrap">
        <span class="flex items-center gap-1.5 text-xs text-gray-500"><span class="w-3 h-3 rounded-full bg-emerald-400"></span> Tepat waktu</span>
        <span class="flex items-center gap-1.5 text-xs text-gray-500"><span class="w-3 h-3 rounded-full bg-red-400"></span> Terlambat</span>
        <span class="flex items-center gap-1.5 text-xs text-gray-500"><span class="w-3 h-3 rounded-full bg-amber-400"></span> Izin/Sakit</span>
        <span class="flex items-center gap-1.5 text-xs text-gray-500"><span class="w-3 h-3 rounded-full bg-gray-200"></span> Tidak hadir</span>
    </div>

    {{-- Calendar grid --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        {{-- Header hari --}}
        <div class="grid grid-cols-7 border-b border-gray-100">
            @foreach($hari as $h)
            <div class="py-2.5 text-center text-[11px] font-bold {{ $h === 'Min' ? 'text-red-400' : ($h === 'Sab' ? 'text-blue-400' : 'text-gray-500') }}">
                {{ $h }}
            </div>
            @endforeach
        </div>

        {{-- Days --}}
        <div class="grid grid-cols-7">
            {{-- Empty cells before start --}}
            @for($i = 0; $i < $startDay; $i++)
                <div class="aspect-square p-1 border-b border-r border-gray-50"></div>
            @endfor

            @for($d = 1; $d <= $totalDays; $d++)
                @php
                    $tglStr   = Carbon::create($tahun, $bulan, $d)->format('Y-m-d');
                    $dayOfWeek= Carbon::create($tahun, $bulan, $d)->dayOfWeek;
                    $isToday  = $tglStr === now()->format('Y-m-d');
                    $hadir    = isset($presensi[$tglStr]);
                    $isIzin   = isset($izin[$tglStr]);
                    $isSunday = $dayOfWeek === 0;
                    $isSaturday = $dayOfWeek === 6;
                    $jamMasuk = $hadir ? $presensi[$tglStr] : null;
                    $terlambat= $hadir && $jamMasuk > ($pengaturan->jam_masuk ?? '08:00:00');

                    if ($hadir)          $dotClass = $terlambat ? 'bg-red-400' : 'bg-emerald-400';
                    elseif ($isIzin)     $dotClass = 'bg-amber-400';
                    elseif ($isSunday || $isSaturday) $dotClass = '';
                    else                 $dotClass = 'bg-gray-200';
                @endphp
                <div class="aspect-square p-1 border-b border-r border-gray-50 relative flex flex-col items-center justify-center
                    {{ $isToday ? 'bg-indigo-50' : '' }}">
                    <span class="text-xs {{ $isToday ? 'font-black text-indigo-600' : ($isSunday ? 'text-red-400' : ($isSaturday ? 'text-blue-400' : 'text-gray-700')) }} font-semibold">
                        {{ $d }}
                    </span>
                    @if($dotClass)
                        <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }} mt-0.5"></span>
                    @endif
                    @if($hadir)
                        <span class="text-[8px] text-gray-400 leading-none mt-0.5">{{ substr($jamMasuk,0,5) }}</span>
                    @endif
                </div>
            @endfor

            {{-- Trailing empty cells --}}
            @php $trailing = (7 - (($startDay + $totalDays) % 7)) % 7; @endphp
            @for($i = 0; $i < $trailing; $i++)
                <div class="aspect-square p-1 border-b border-r border-gray-50"></div>
            @endfor
        </div>
    </div>

    {{-- Summary bulan ini --}}
    @php
        $jmlHadir    = $presensi->count();
        $jmlTerlambat= $presensi->filter(fn($j) => $j > ($pengaturan->jam_masuk ?? '08:00:00'))->count();
        $jmlIzin     = $izin->count();
    @endphp
    <div class="grid grid-cols-3 gap-3 mt-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-black text-emerald-600">{{ $jmlHadir }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Hari Hadir</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-black text-red-500">{{ $jmlTerlambat }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Terlambat</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-black text-amber-500">{{ $jmlIzin }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Izin/Sakit</p>
        </div>
    </div>

</div>
@endsection
