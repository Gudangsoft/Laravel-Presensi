<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function keterlambatan(Request $request)
    {
        $bulan  = $request->input('bulan', now()->month);
        $tahun  = $request->input('tahun', now()->year);
        $pengaturan = \App\Models\Pengaturan::first();

        $data = \Illuminate\Support\Facades\DB::table('presensi')
            ->join('karyawan', 'karyawan.id', '=', 'presensi.karyawan_id')
            ->join('departemen', 'departemen.id', '=', 'karyawan.departemen_id')
            ->selectRaw("
                karyawan.id,
                karyawan.nama_lengkap,
                karyawan.foto,
                departemen.nama as departemen,
                COUNT(*) as total_hadir,
                SUM(IF(presensi.jam_masuk > ?, 1, 0)) as total_terlambat,
                MAX(presensi.jam_masuk) as jam_terlambat_max,
                GROUP_CONCAT(
                    IF(presensi.jam_masuk > ?,
                        CONCAT(DATE_FORMAT(presensi.tanggal_presensi,'%d/%m'), ' ', TIME_FORMAT(presensi.jam_masuk,'%H:%i')),
                        NULL
                    ) ORDER BY presensi.tanggal_presensi SEPARATOR ', '
                ) as detail_terlambat
            ", [$pengaturan->jam_masuk, $pengaturan->jam_masuk])
            ->whereMonth('presensi.tanggal_presensi', $bulan)
            ->whereYear('presensi.tanggal_presensi', $tahun)
            ->groupBy('karyawan.id', 'karyawan.nama_lengkap', 'karyawan.foto', 'departemen.nama')
            ->having('total_terlambat', '>', 0)
            ->orderByDesc('total_terlambat')
            ->get();

        $title = 'Monitoring Keterlambatan';
        return view('admin.monitoring.keterlambatan', compact('title', 'data', 'bulan', 'tahun', 'pengaturan'));
    }
}
