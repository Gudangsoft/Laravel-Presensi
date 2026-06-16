<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>{{ $title . '.pdf' }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <style>
        @page { size: A4 }

        .title {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            font-weight: 800;
            line-height: 1.5rem;
        }

        table { border-collapse: collapse; }

        .presensi-karyawan { width: 100%; margin-top: 1.5rem; }
        .presensi-karyawan tbody > tr > td { text-align: center; padding: 0.5rem; }
        .presensi-karyawan th { font-weight: bold; background: #818cf8; color: #fff; padding: 0.5rem; font-size: 14px; }
        .presensi-karyawan > tbody > tr > td { font-size: 12px; }
        .presensi-karyawan,
        .presensi-karyawan > thead > tr > th,
        .presensi-karyawan > tbody > tr > td { border: 1px solid black; padding: 0.5rem; }

        .pengesahan-atasan { width: 100%; margin-top: 2rem; }
        .atasan td { text-align: center; vertical-align: bottom; height: 10rem; }
        .tempat td { text-align: right; }
    </style>
</head>

<body class="A4">
    <section class="sheet padding-10mm">
        {{-- Header: Logo + Nama Perusahaan --}}
        <table style="width: 100%">
            <tr>
                <td style="width: 110px; vertical-align: middle;">
                    @if ($brand?->logoPath())
                        <img src="{{ $brand->logoPath() }}" alt="logo" width="100" height="100" style="border-radius: 8px; object-fit: contain;" />
                    @else
                        <div style="width:100px;height:100px;background:#4f46e5;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                            <span style="color:#fff;font-size:32px;font-weight:800;">{{ mb_strtoupper(mb_substr($brand?->nama_aplikasi ?? 'P', 0, 1)) }}</span>
                        </div>
                    @endif
                </td>
                <td style="padding-left: 0.75rem;">
                    <span class="title">{{ strtoupper($title) }}<br></span>
                    <span class="title">PERIODE {{ strtoupper(\Carbon\Carbon::make($bulan)->translatedFormat('F')) }} TAHUN {{ \Carbon\Carbon::make($bulan)->format('Y') }}<br></span>
                    <span class="title" style="color:#4f46e5;">{{ strtoupper($brand?->nama_aplikasi ?? config('app.name')) }}<br></span>
                    @if ($brand?->tagline)
                        <span style="font-size: 12px; color: #6b7280; font-style: italic;">{{ $brand->tagline }}</span>
                    @endif
                </td>
            </tr>
        </table>

        {{-- Tabel Presensi Semua Karyawan --}}
        <table class="presensi-karyawan">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Karyawan</th>
                    <th>Jabatan / Departemen</th>
                    <th>Jumlah Kehadiran</th>
                    <th>Jumlah Terlambat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($riwayatPresensi as $value => $item)
                    <tr>
                        <td>{{ $value + 1 }}.</td>
                        <td style="text-align: left;">{{ $item->nama_karyawan }}</td>
                        <td style="text-align: left;">{{ $item->jabatan_karyawan }} – {{ $item->nama_departemen }}</td>
                        <td>{{ $item->total_kehadiran }}</td>
                        <td>{{ $item->total_terlambat }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Tanda Tangan --}}
        <table class="pengesahan-atasan">
            <tr class="tempat">
                <td colspan="2">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
            </tr>
            <tr class="atasan">
                <td>
                    <u>( _________________ )</u><br>
                    <b>HRD / Manager</b>
                </td>
                <td>
                    <u>( _________________ )</u><br>
                    <b>Direktur</b>
                </td>
            </tr>
        </table>
    </section>
</body>
</html>
