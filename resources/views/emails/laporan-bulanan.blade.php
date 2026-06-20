<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
body { font-family: Arial, sans-serif; color: #374151; font-size: 14px; }
h2 { color: #4f46e5; }
table { width: 100%; border-collapse: collapse; margin-top: 16px; }
th { background: #4f46e5; color: white; padding: 10px 12px; text-align: left; font-size: 12px; }
td { padding: 8px 12px; border-bottom: 1px solid #e5e7eb; font-size: 13px; }
tr:nth-child(even) { background: #f9fafb; }
.badge-red { color: #dc2626; font-weight: bold; }
.footer { margin-top: 24px; font-size: 12px; color: #9ca3af; }
</style>
</head>
<body>
<h2>Laporan Kehadiran Bulanan</h2>
<p>Periode: <strong>{{ $label }}</strong></p>
<p>Berikut rekap kehadiran seluruh karyawan:</p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Karyawan</th>
            <th>Departemen</th>
            <th>Hari Hadir</th>
            <th>Terlambat</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekap as $i => $row)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $row->nama_lengkap }}</td>
            <td>{{ $row->departemen }}</td>
            <td>{{ $row->hadir }}</td>
            <td class="{{ $row->terlambat > 0 ? 'badge-red' : '' }}">{{ $row->terlambat }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    <p>Email ini dikirim otomatis oleh sistem presensi. Jangan balas email ini.</p>
</div>
</body>
</html>
