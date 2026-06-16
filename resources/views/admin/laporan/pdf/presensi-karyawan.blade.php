<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>{{ $title . ' ' . $karyawan->nama_lengkap . '.pdf' }}</title>

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

        .identitas-karyawan { margin-top: 2rem; }
        .identitas-karyawan td { padding: 0.25rem; }

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

        {{-- Identitas Karyawan --}}
        <table class="identitas-karyawan">
            <tr>
                <td rowspan="7">
                    @if ($karyawan->foto)
                        <img src="{{ public_path('storage/unggah/karyawan/' . $karyawan->foto) }}" alt="foto-karyawan" width="100" height="150" style="border-radius: 0.5rem; object-fit: cover;" />
                    @else
                        <div style="width:100px;height:150px;background:#e0e7ff;border-radius:0.5rem;display:flex;align-items:center;justify-content:center;">
                            <span style="font-size:36px;color:#4f46e5;font-weight:800;">{{ mb_strtoupper(mb_substr($karyawan->nama_lengkap, 0, 1)) }}</span>
                        </div>
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width:130px;">Nama Karyawan</td><td>:</td><td>{{ $karyawan->nama_lengkap }}</td>
            </tr>
            <tr>
                <td>Jabatan</td><td>:</td><td>{{ $karyawan->jabatan }}</td>
            </tr>
            <tr>
                <td>Departemen</td><td>:</td><td>{{ $karyawan->departemen->nama }}</td>
            </tr>
            <tr>
                <td>Email</td><td>:</td><td>{{ $karyawan->email }}</td>
            </tr>
            <tr>
                <td>Telepon</td><td>:</td><td>{{ $karyawan->telepon ?? '-' }}</td>
            </tr>
        </table>

        {{-- Tabel Presensi --}}
        <table class="presensi-karyawan">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Foto</th>
                    <th>Jam Keluar</th>
                    <th>Foto</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($riwayatPresensi as $value => $item)
                    @php
                        $terlambat = $item->jam_masuk > \Carbon\Carbon::make('08:00:00')->format('H:i:s');
                        if ($terlambat) {
                            $masuk   = \Carbon\Carbon::make($item->jam_masuk);
                            $batas   = \Carbon\Carbon::make('08:00:00');
                            $diff    = $masuk->diff($batas);
                            $selisih = ($diff->h > 0 ? $diff->h.'j ' : '') . $diff->i.'m';
                        }
                    @endphp
                    <tr>
                        <td>{{ $value + 1 }}.</td>
                        <td>{{ \Carbon\Carbon::make($item->tanggal_presensi)->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::make($item->jam_masuk)->format('H:i') }}</td>
                        <td>
                            @if ($item->foto_masuk)
                                <img src="{{ public_path('storage/unggah/presensi/' . $item->foto_masuk) }}" width="50" height="50" style="border-radius: 0.5rem; object-fit: cover;" />
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $item->jam_keluar ? \Carbon\Carbon::make($item->jam_keluar)->format('H:i') : 'Belum Presensi' }}</td>
                        <td>
                            @if ($item->foto_keluar)
                                <img src="{{ public_path('storage/unggah/presensi/' . $item->foto_keluar) }}" width="50" height="50" style="border-radius: 0.5rem; object-fit: cover;" />
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $terlambat ? 'Terlambat ' . $selisih : 'Tepat Waktu' }}</td>
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
                    <u>{{ $karyawan->nama_lengkap }}</u><br>
                    <b>Karyawan</b>
                </td>
            </tr>
        </table>
    </section>
</body>
</html>
