@extends("dashboard.layouts.main")

@section("css")
<style>
:root {
    --kal-ground:   #F8F9FF;
    --kal-text:     #1E1B4B;
    --kal-accent:   #4F46E5;
    --kal-emerald:  #10B981;
    --kal-red:      #EF4444;
    --kal-amber:    #F59E0B;
    --kal-slate:    #94A3B8;
}

.kal-wrap {
    max-width: 480px;
    margin: 0 auto;
}

/* month nav */
.kal-nav-btn {
    width: 36px; height: 36px;
    border-radius: 10px;
    border: 1.5px solid #E2E8F0;
    background: white;
    display: flex; align-items: center; justify-content: center;
    color: #64748B;
    text-decoration: none;
    transition: border-color 0.15s, color 0.15s, box-shadow 0.15s;
}
.kal-nav-btn:hover {
    border-color: var(--kal-accent);
    color: var(--kal-accent);
    box-shadow: 0 2px 8px rgba(79,70,229,0.15);
}
.kal-today-btn {
    height: 36px; padding: 0 14px;
    border-radius: 10px;
    background: var(--kal-accent);
    color: white; font-size: 11px; font-weight: 800;
    letter-spacing: 0.04em; text-transform: uppercase;
    text-decoration: none;
    display: flex; align-items: center;
    box-shadow: 0 3px 10px rgba(79,70,229,0.3);
    transition: background 0.15s, box-shadow 0.15s;
}
.kal-today-btn:hover {
    background: #4338CA;
    box-shadow: 0 4px 14px rgba(79,70,229,0.4);
}

/* rate bar card */
.kal-rate-card {
    background: white;
    border-radius: 16px;
    border: 1.5px solid #EEF0FF;
    padding: 14px 16px;
    box-shadow: 0 2px 8px rgba(79,70,229,0.06);
}
.kal-rate-track {
    height: 6px; border-radius: 99px;
    background: #F1F5F9;
    overflow: hidden;
}
.kal-rate-fill {
    height: 100%; border-radius: 99px;
    transition: width 0.7s cubic-bezier(0.4,0,0.2,1);
}

/* calendar card */
.kal-card {
    position: relative; overflow: hidden;
    background: white;
    border-radius: 20px;
    border: 1.5px solid #EEF0FF;
    box-shadow: 0 4px 20px rgba(79,70,229,0.07);
}
.kal-watermark {
    position: absolute;
    bottom: -10px; right: -8px;
    font-size: 120px; font-weight: 900; line-height: 1;
    color: var(--kal-accent);
    opacity: 0.035;
    letter-spacing: -5px;
    pointer-events: none; user-select: none; aria-hidden: true;
}

/* day headers */
.kal-day-header {
    display: grid; grid-template-columns: repeat(7, 1fr);
    border-bottom: 1.5px solid #F1F5F9;
}
.kal-day-label {
    padding: 10px 0 9px;
    text-align: center;
    font-size: 10px; font-weight: 800;
    letter-spacing: 0.06em;
}

/* grid */
.kal-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
}
.kal-cell {
    position: relative;
    aspect-ratio: 1;
    min-height: 46px;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    border-right: 1px solid #F8FAFC;
    border-bottom: 1px solid #F8FAFC;
    cursor: default;
    transition: transform 0.12s;
}
@media (prefers-reduced-motion: reduce) { .kal-cell { transition: none; } }

.kal-num {
    font-size: 13px; line-height: 1;
    font-weight: 600;
    color: var(--kal-text);
}
.kal-num-muted { color: var(--kal-slate); font-weight: 400; }
.kal-num-sun   { color: #F87171; }
.kal-num-sat   { color: #818CF8; }

/* status washes */
.kal-cell-tepat    { background: rgba(16,185,129,0.09); }
.kal-cell-terlambat{ background: rgba(239,68,68,0.08); }
.kal-cell-izin     { background: rgba(245,158,11,0.09); }
.kal-cell-absen    { background: rgba(148,163,184,0.06); }
.kal-cell-weekend  { background: rgba(99,102,241,0.03); }

/* today pip */
.kal-today-pip {
    width: 34px; height: 34px;
    border-radius: 11px;
    background: var(--kal-accent);
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    box-shadow: 0 4px 12px rgba(79,70,229,0.4);
}

/* dot */
.kal-dot {
    width: 4px; height: 4px;
    border-radius: 50%;
    margin-top: 3px;
}

/* jam masuk small */
.kal-jam {
    font-size: 7.5px;
    color: var(--kal-slate);
    line-height: 1;
    margin-top: 2px;
    letter-spacing: 0;
}

/* legend pills */
.kal-legend-pill {
    display: inline-flex; align-items: center; gap: 5px;
    border-radius: 99px;
    padding: 4px 10px;
    font-size: 11px; font-weight: 600;
}

/* stat cards */
.kal-stat {
    background: white;
    border-radius: 16px;
    border: 1.5px solid #EEF0FF;
    padding: 16px 12px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(79,70,229,0.06);
}
.kal-stat-num {
    font-size: 28px; font-weight: 900; line-height: 1;
    letter-spacing: -1px;
}
.kal-stat-label {
    font-size: 11px; color: var(--kal-slate);
    font-weight: 600; margin-top: 4px; line-height: 1.2;
}
</style>
@endsection

@section("container")
@php
    use Carbon\Carbon;
    $tgl      = Carbon::create($tahun, $bulan, 1);
    $awal     = $tgl->copy()->startOfMonth();
    $akhir    = $tgl->copy()->endOfMonth();
    $startDay = $awal->dayOfWeek;
    $totalDays= $akhir->day;
    $hari     = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
    $prev     = $tgl->copy()->subMonth();
    $next     = $tgl->copy()->addMonth();

    $jamKerja     = $pengaturan->jam_masuk ?? '08:00:00';
    $jmlHadir     = $presensi->count();
    $jmlTerlambat = $presensi->filter(fn($j) => $j > $jamKerja)->count();
    $jmlTepat     = $jmlHadir - $jmlTerlambat;
    $jmlIzin      = $izin->count();

    $hariKerja = 0;
    for ($d = 1; $d <= $totalDays; $d++) {
        $dow = Carbon::create($tahun, $bulan, $d)->dayOfWeek;
        if ($dow !== 0 && $dow !== 6) $hariKerja++;
    }
    $pctHadir = $hariKerja > 0 ? round($jmlHadir / $hariKerja * 100) : 0;
    $isBulanIni = now()->year == $tahun && now()->month == $bulan;
    $rateColor = $pctHadir >= 90 ? '#10b981' : ($pctHadir >= 70 ? '#f59e0b' : '#ef4444');
@endphp

<div class="kal-wrap">

    {{-- ── Header ── --}}
    <div class="flex items-start justify-between mb-4">
        <div>
            <p style="font-size:10px;font-weight:800;letter-spacing:0.1em;text-transform:uppercase;color:#818CF8;margin-bottom:2px;">
                Rekam Kehadiran
            </p>
            <h1 style="font-size:22px;font-weight:900;color:#1E1B4B;line-height:1.1;letter-spacing:-0.5px;">
                {{ $tgl->isoFormat('MMMM') }}<br>
                <span style="color:#94A3B8;font-weight:600;font-size:17px;">{{ $tahun }}</span>
            </h1>
        </div>
        <div class="flex items-center gap-1.5 mt-1">
            <a href="{{ route('karyawan.kalender', ['bulan'=>$prev->month,'tahun'=>$prev->year]) }}" class="kal-nav-btn">
                <i class="ri-arrow-left-s-line" style="font-size:18px;"></i>
            </a>
            <a href="{{ route('karyawan.kalender', ['bulan'=>now()->month,'tahun'=>now()->year]) }}" class="kal-today-btn">
                Hari Ini
            </a>
            <a href="{{ route('karyawan.kalender', ['bulan'=>$next->month,'tahun'=>$next->year]) }}" class="kal-nav-btn">
                <i class="ri-arrow-right-s-line" style="font-size:18px;"></i>
            </a>
        </div>
    </div>

    {{-- ── Attendance rate bar ── --}}
    <div class="kal-rate-card mb-4">
        <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:8px;">
            <span style="font-size:12px;color:#94A3B8;font-weight:600;">
                {{ $isBulanIni ? 'Kehadiran bulan ini' : 'Total kehadiran' }}
            </span>
            <span style="font-size:18px;font-weight:900;color:{{ $rateColor }};letter-spacing:-0.5px;">
                {{ $pctHadir }}%
            </span>
        </div>
        <div class="kal-rate-track">
            <div class="kal-rate-fill" style="width:{{ $pctHadir }}%;background:{{ $rateColor }};"></div>
        </div>
        <div style="display:flex;justify-content:space-between;margin-top:7px;">
            <span style="font-size:10px;color:#CBD5E1;font-weight:500;">
                {{ $jmlHadir }} hadir · {{ $hariKerja }} hari kerja
            </span>
            <span style="font-size:10px;font-weight:700;color:{{ $rateColor }};">
                {{ $isBulanIni ? 'Berjalan' : 'Final' }}
            </span>
        </div>
    </div>

    {{-- ── Calendar card ── --}}
    <div class="kal-card mb-4">

        {{-- Watermark --}}
        <div class="kal-watermark" aria-hidden="true">
            {{ strtoupper($tgl->isoFormat('MMM')) }}
        </div>

        {{-- Day headers --}}
        <div class="kal-day-header">
            @foreach($hari as $h)
            <div class="kal-day-label" style="color: {{ $h==='Min' ? '#F87171' : ($h==='Sab' ? '#818CF8' : '#94A3B8') }}">
                {{ $h }}
            </div>
            @endforeach
        </div>

        {{-- Grid --}}
        <div class="kal-grid">
            {{-- Leading blanks --}}
            @for($i = 0; $i < $startDay; $i++)
                <div class="kal-cell kal-cell-weekend"></div>
            @endfor

            @for($d = 1; $d <= $totalDays; $d++)
            @php
                $tglStr     = Carbon::create($tahun, $bulan, $d)->format('Y-m-d');
                $dayOfWeek  = Carbon::create($tahun, $bulan, $d)->dayOfWeek;
                $isToday    = $tglStr === now()->format('Y-m-d');
                $isFuture   = $tglStr > now()->format('Y-m-d');
                $hadir      = isset($presensi[$tglStr]);
                $isIzin     = isset($izin[$tglStr]);
                $isSunday   = $dayOfWeek === 0;
                $isSaturday = $dayOfWeek === 6;
                $isWeekend  = $isSunday || $isSaturday;
                $jamMasuk   = $hadir ? substr($presensi[$tglStr], 0, 5) : null;
                $terlambat  = $hadir && $presensi[$tglStr] > $jamKerja;

                if ($isToday)                        $cellClass = '';
                elseif ($hadir && !$terlambat)       $cellClass = 'kal-cell-tepat';
                elseif ($hadir && $terlambat)        $cellClass = 'kal-cell-terlambat';
                elseif ($isIzin)                     $cellClass = 'kal-cell-izin';
                elseif ($isWeekend)                  $cellClass = 'kal-cell-weekend';
                elseif (!$isFuture)                  $cellClass = 'kal-cell-absen';
                else                                 $cellClass = '';

                if ($hadir && !$terlambat)    $dotColor = '#10b981';
                elseif ($hadir && $terlambat) $dotColor = '#ef4444';
                elseif ($isIzin)              $dotColor = '#f59e0b';
                else                          $dotColor = null;
            @endphp

            <div class="kal-cell {{ $cellClass }}">
                @if($isToday)
                    <div class="kal-today-pip">
                        <span style="font-size:13px;font-weight:800;color:white;line-height:1;">{{ $d }}</span>
                        @if($dotColor)
                            <span style="width:4px;height:4px;border-radius:50%;background:rgba(255,255,255,0.65);margin-top:2px;"></span>
                        @endif
                    </div>
                @else
                    <span class="kal-num {{ $isSunday ? 'kal-num-sun' : ($isSaturday ? 'kal-num-sat' : ($dotColor ? '' : 'kal-num-muted')) }}"
                          style="{{ $dotColor && !$isSunday && !$isSaturday ? 'color:#1E1B4B;' : '' }}">
                        {{ $d }}
                    </span>
                    @if($dotColor)
                        <span class="kal-dot" style="background:{{ $dotColor }};opacity:0.75;"></span>
                    @endif
                    @if($hadir)
                        <span class="kal-jam">{{ $jamMasuk }}</span>
                    @endif
                @endif
            </div>
            @endfor

            {{-- Trailing blanks --}}
            @php $trailing = (7 - (($startDay + $totalDays) % 7)) % 7; @endphp
            @for($i = 0; $i < $trailing; $i++)
                <div class="kal-cell kal-cell-weekend"></div>
            @endfor
        </div>
    </div>

    {{-- ── Legend ── --}}
    <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:16px;">
        <span class="kal-legend-pill" style="background:rgba(16,185,129,0.1);border:1.5px solid rgba(16,185,129,0.2);color:#059669;">
            <span style="width:6px;height:6px;border-radius:50%;background:#10b981;"></span> Tepat Waktu
        </span>
        <span class="kal-legend-pill" style="background:rgba(239,68,68,0.08);border:1.5px solid rgba(239,68,68,0.15);color:#DC2626;">
            <span style="width:6px;height:6px;border-radius:50%;background:#ef4444;"></span> Terlambat
        </span>
        <span class="kal-legend-pill" style="background:rgba(245,158,11,0.09);border:1.5px solid rgba(245,158,11,0.2);color:#D97706;">
            <span style="width:6px;height:6px;border-radius:50%;background:#f59e0b;"></span> Izin / Sakit
        </span>
        <span class="kal-legend-pill" style="background:rgba(148,163,184,0.08);border:1.5px solid rgba(148,163,184,0.15);color:#94A3B8;">
            <span style="width:6px;height:6px;border-radius:50%;background:#CBD5E1;"></span> Tidak Hadir
        </span>
    </div>

    {{-- ── Stat cards ── --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
        <div class="kal-stat">
            <div class="kal-stat-num" style="color:#10B981;">{{ $jmlTepat }}</div>
            <div class="kal-stat-label">Tepat<br>Waktu</div>
        </div>
        <div class="kal-stat">
            <div class="kal-stat-num" style="color:#EF4444;">{{ $jmlTerlambat }}</div>
            <div class="kal-stat-label">Terlambat</div>
        </div>
        <div class="kal-stat">
            <div class="kal-stat-num" style="color:#F59E0B;">{{ $jmlIzin }}</div>
            <div class="kal-stat-label">Izin /<br>Sakit</div>
        </div>
    </div>

</div>
@endsection
