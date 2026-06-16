<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Presensi - {{ $karyawan->nama_lengkap }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #1f2937; background: #fff; }
        @page { size: A4; margin: 20mm 15mm; }

        .header { text-align: center; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #4f46e5; }
        .header h1 { font-size: 16px; font-weight: 800; color: #4f46e5; letter-spacing: 0.5px; }
        .header p { font-size: 11px; color: #6b7280; margin-top: 3px; }

        .info-grid { display: table; width: 100%; margin-bottom: 18px; }
        .info-row { display: table-row; }
        .info-cell { display: table-cell; padding: 3px 0; font-size: 11px; vertical-align: top; }
        .info-label { color: #6b7280; width: 120px; }
        .info-sep { width: 10px; }
        .info-value { font-weight: 600; color: #1f2937; }

        .summary { display: table; width: 100%; margin-bottom: 18px; border-spacing: 8px 0; }
        .summary-card { display: table-cell; border: 1px solid #e5e7eb; border-radius: 6px; padding: 10px 12px; text-align: center; }
        .summary-card .num { font-size: 22px; font-weight: 800; color: #4f46e5; }
        .summary-card .lbl { font-size: 10px; color: #6b7280; margin-top: 2px; }

        table.presensi { width: 100%; border-collapse: collapse; font-size: 11px; }
        table.presensi thead tr { background: #4f46e5; color: #fff; }
        table.presensi thead th { padding: 7px 8px; text-align: left; font-weight: 600; font-size: 10px; letter-spacing: 0.3px; }
        table.presensi tbody tr:nth-child(even) { background: #f9fafb; }
        table.presensi tbody td { padding: 6px 8px; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
        .badge { display: inline-block; padding: 2px 7px; border-radius: 20px; font-size: 9px; font-weight: 700; }
        .badge-ok   { background: #d1fae5; color: #065f46; }
        .badge-late { background: #fee2e2; color: #991b1b; }
        .badge-izin { background: #dbeafe; color: #1e40af; }
        .badge-sakit{ background: #fce7f3; color: #9d174d; }

        .footer { margin-top: 24px; text-align: right; font-size: 10px; color: #9ca3af; }
        .sign-area { margin-top: 40px; display: table; width: 100%; }
        .sign-cell  { display: table-cell; text-align: center; font-size: 11px; }
        .sign-cell .sign-name { margin-top: 50px; font-weight: 700; border-top: 1px solid #374151; display: inline-block; padding-top: 4px; min-width: 140px; }
    </style>
</head>
<body>

    <div class="header">
        @if ($brand?->logoPath())
            <img src="{{ $brand->logoPath() }}" alt="logo" style="height:48px;object-fit:contain;margin-bottom:8px;">
        @endif
        <h1>{{ strtoupper($brand?->nama_aplikasi ?? config('app.name')) }}</h1>
        <p style="font-size:13px;font-weight:700;color:#374151;margin-top:4px;">REKAP PRESENSI KARYAWAN</p>
        <p>Periode: {{ $bulanNama[$bulan] }} {{ $tahun }}</p>
    </div>

    {{-- Info Karyawan --}}
    <div class="info-grid">
        <div class="info-row">
            <div class="info-cell info-label">Nama</div>
            <div class="info-cell info-sep">:</div>
            <div class="info-cell info-value">{{ $karyawan->nama_lengkap }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell info-label">Jabatan</div>
            <div class="info-cell info-sep">:</div>
            <div class="info-cell info-value">{{ $karyawan->jabatan ?? '-' }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell info-label">Email</div>
            <div class="info-cell info-sep">:</div>
            <div class="info-cell info-value">{{ $karyawan->email }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell info-label">Jam Kerja</div>
            <div class="info-cell info-sep">:</div>
            <div class="info-cell info-value">
                {{ \Carbon\Carbon::parse($pengaturan->jam_masuk)->format('H:i') }} –
                {{ \Carbon\Carbon::parse($pengaturan->jam_pulang)->format('H:i') }} WIB
            </div>
        </div>
    </div>

    {{-- Summary --}}
    <div class="summary">
        <div class="summary-card">
            <div class="num">{{ $totalHadir }}</div>
            <div class="lbl">Hari Hadir</div>
        </div>
        <div class="summary-card">
            <div class="num" style="color:#dc2626;">{{ $totalTerlambat }}</div>
            <div class="lbl">Terlambat</div>
        </div>
        <div class="summary-card">
            <div class="num" style="color:#2563eb;">{{ $izin->filter(fn($i) => $i->status === 'I')->count() }}</div>
            <div class="lbl">Hari Izin</div>
        </div>
        <div class="summary-card">
            <div class="num" style="color:#db2777;">{{ $izin->filter(fn($i) => $i->status === 'S')->count() }}</div>
            <div class="lbl">Hari Sakit</div>
        </div>
    </div>

    {{-- Detail Table --}}
    <table class="presensi">
        <thead>
            <tr>
                <th style="width:30px;">No</th>
                <th style="width:80px;">Tanggal</th>
                <th style="width:70px;">Hari</th>
                <th style="width:65px;">Jam Masuk</th>
                <th style="width:65px;">Jam Keluar</th>
                <th>Keterangan</th>
                <th style="width:70px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; $totalDays = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth; @endphp
            @for ($d = 1; $d <= $totalDays; $d++)
                @php
                    $tgl    = \Carbon\Carbon::createFromDate($tahun, $bulan, $d);
                    $tglStr = $tgl->format('Y-m-d');
                    $p      = $presensi->firstWhere('tanggal_presensi', $tglStr);
                    $izinRow = $izin->get($tglStr);
                    $isSunday = $tgl->dayOfWeek === 0;
                    if (!$p && !$izinRow && !$isSunday) continue;
                    if ($isSunday && !$p && !$izinRow) continue;
                @endphp
                @if ($p)
                    @php
                        $late = $p->jam_masuk > $pengaturan->jam_masuk;
                    @endphp
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $tgl->format('d/m/Y') }}</td>
                        <td>{{ $tgl->isoFormat('ddd') }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->jam_masuk)->format('H:i') }}</td>
                        <td>{{ $p->jam_keluar ? \Carbon\Carbon::parse($p->jam_keluar)->format('H:i') : '—' }}</td>
                        <td>{{ $p->lokasi_masuk ?? '-' }}</td>
                        <td>
                            @if ($late)
                                <span class="badge badge-late">Terlambat</span>
                            @else
                                <span class="badge badge-ok">Tepat Waktu</span>
                            @endif
                        </td>
                    </tr>
                @elseif ($izinRow)
                    <tr style="background:#fafbff;">
                        <td>{{ $no++ }}</td>
                        <td>{{ $tgl->format('d/m/Y') }}</td>
                        <td>{{ $tgl->isoFormat('ddd') }}</td>
                        <td colspan="2" style="text-align:center;color:#6b7280;">—</td>
                        <td>{{ $izinRow->keterangan ?? '-' }}</td>
                        <td>
                            @if ($izinRow->status === 'I')
                                <span class="badge badge-izin">Izin</span>
                            @else
                                <span class="badge badge-sakit">Sakit</span>
                            @endif
                        </td>
                    </tr>
                @endif
            @endfor
        </tbody>
    </table>

    <div class="sign-area">
        <div class="sign-cell"></div>
        <div class="sign-cell">
            <div>Mengetahui,</div>
            <div class="sign-name">Admin / HRD</div>
        </div>
        <div class="sign-cell">
            <div>Karyawan,</div>
            <div class="sign-name">{{ $karyawan->nama_lengkap }}</div>
        </div>
    </div>

    <div class="footer">Dicetak: {{ now()->translatedFormat('d F Y H:i') }} WIB</div>

</body>
</html>
