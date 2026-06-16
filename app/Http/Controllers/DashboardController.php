<?php

namespace App\Http\Controllers;

use App\Models\HariLibur;
use App\Models\Karyawan;
use App\Models\Pengaturan;
use App\Models\Pengumuman;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $title = "Dashboard";

        $hariIni = Carbon::now()->format("Y-m-d");
        $user    = Auth::guard('karyawan')->user();

        $presensiHariIni = DB::table("presensi")
            ->where('karyawan_id', $user->id)
            ->where('tanggal_presensi', $hariIni)
            ->first();

        $riwayatPresensi = DB::table("presensi")
            ->where('karyawan_id', $user->id)
            ->whereMonth('tanggal_presensi', date('m'))
            ->whereYear('tanggal_presensi', date('Y'))
            ->orderBy("tanggal_presensi", "desc")
            ->paginate(10);

        $rekapPresensi = DB::table("presensi")
            ->selectRaw("COUNT(karyawan_id) as jml_kehadiran, SUM(IF (jam_masuk > '08:00',1,0)) as jml_terlambat")
            ->where('karyawan_id', $user->id)
            ->whereRaw("MONTH(tanggal_presensi)='" . date('m') . "'")
            ->whereRaw("YEAR(tanggal_presensi)='"  . date('Y') . "'")
            ->first();

        $rekapPengajuanPresensi = DB::table("pengajuan_presensi")
            ->selectRaw("SUM(IF (status = 'I',1,0)) as jml_izin, SUM(IF (status = 'S',1,0)) as jml_sakit")
            ->where('karyawan_id', $user->id)
            ->where('status_approved', 1)
            ->whereRaw("MONTH(tanggal_pengajuan)='" . date('m') . "'")
            ->whereRaw("YEAR(tanggal_pengajuan)='"  . date('Y') . "'")
            ->first();

        $leaderboard = DB::table("presensi")
            ->join('karyawan', 'karyawan.id', '=', 'presensi.karyawan_id')
            ->where('presensi.tanggal_presensi', $hariIni)
            ->orderBy('presensi.jam_masuk', 'asc')
            ->select('karyawan.nama_lengkap', 'karyawan.jabatan', 'karyawan.foto', 'presensi.jam_masuk', 'presensi.jam_keluar')
            ->paginate(10);

        $pengaturan  = Pengaturan::first();
        $pengumuman  = Pengumuman::active()->limit(5)->get();

        return view("dashboard.index", compact("title", "presensiHariIni", "riwayatPresensi", "rekapPresensi", "rekapPengajuanPresensi", "leaderboard", "pengaturan", "pengumuman"));
    }

    public function indexAdmin()
    {
        $title   = "Dashboard Admin";
        $hariIni = Carbon::now()->format("Y-m-d");

        $totalKaryawan = Karyawan::count();

        $pengaturan = Pengaturan::first();

        $rekapPresensi = DB::table("presensi")
            ->selectRaw("COUNT(*) as jml_kehadiran, SUM(IF(jam_masuk > ?,1,0)) as jml_terlambat", [$pengaturan->jam_masuk])
            ->where('tanggal_presensi', $hariIni)
            ->first();

        $rekapPengajuanPresensi = DB::table("pengajuan_presensi")
            ->selectRaw("SUM(IF(status='I',1,0)) as jml_izin, SUM(IF(status='S',1,0)) as jml_sakit")
            ->where('status_approved', 1)
            ->where('tanggal_pengajuan', $hariIni)
            ->first();

        $totalTidakHadir = max(0, $totalKaryawan
            - ($rekapPresensi->jml_kehadiran ?? 0)
            - ($rekapPengajuanPresensi->jml_izin ?? 0)
            - ($rekapPengajuanPresensi->jml_sakit ?? 0));

        $raw7Hari = DB::table("presensi")
            ->selectRaw("DATE(tanggal_presensi) as tgl, COUNT(*) as total")
            ->whereBetween('tanggal_presensi', [Carbon::now()->subDays(6)->format('Y-m-d'), $hariIni])
            ->groupBy('tgl')
            ->orderBy('tgl')
            ->pluck('total', 'tgl');

        $labels7Hari = [];
        $data7Hari   = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl           = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels7Hari[] = Carbon::now()->subDays($i)->isoFormat('D MMM');
            $data7Hari[]   = $raw7Hari[$tgl] ?? 0;
        }

        $karyawanHadir = DB::table("presensi")
            ->join('karyawan', 'karyawan.id', '=', 'presensi.karyawan_id')
            ->where('presensi.tanggal_presensi', $hariIni)
            ->select('karyawan.nama_lengkap', 'karyawan.jabatan', 'karyawan.foto', 'presensi.jam_masuk', 'presensi.jam_keluar')
            ->orderBy('presensi.jam_masuk')
            ->get();

        $rekapBulanIni = DB::table("presensi")
            ->selectRaw("COUNT(*) as total")
            ->whereMonth('tanggal_presensi', date('m'))
            ->whereYear('tanggal_presensi', date('Y'))
            ->value('total');

        // Flag hari libur pada 7 hari terakhir
        $holidayDates = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = Carbon::now()->subDays($i)->format('Y-m-d');
            if (HariLibur::isHoliday($tgl)) {
                $holidayDates[] = $tgl;
            }
        }

        return view("admin.dashboard", compact(
            "title", "totalKaryawan", "rekapPresensi", "rekapPengajuanPresensi",
            "totalTidakHadir", "karyawanHadir", "labels7Hari", "data7Hari", "rekapBulanIni",
            "pengaturan", "holidayDates"
        ));
    }

    public function dashboardStats(): JsonResponse
    {
        $hariIni    = Carbon::now()->format('Y-m-d');
        $pengaturan = Pengaturan::first();

        $totalKaryawan = Karyawan::count();

        $rekapPresensi = DB::table('presensi')
            ->selectRaw('COUNT(*) as jml_kehadiran, SUM(IF(jam_masuk > ?,1,0)) as jml_terlambat', [$pengaturan->jam_masuk])
            ->where('tanggal_presensi', $hariIni)
            ->first();

        $rekapPengajuan = DB::table('pengajuan_presensi')
            ->selectRaw("SUM(IF(status='I',1,0)) as jml_izin, SUM(IF(status='S',1,0)) as jml_sakit")
            ->where('status_approved', 2)
            ->where('tanggal_pengajuan', $hariIni)
            ->first();

        $isHoliday = HariLibur::isHoliday($hariIni);

        $totalTidakHadir = $isHoliday ? 0 : max(0,
            $totalKaryawan
            - ($rekapPresensi->jml_kehadiran ?? 0)
            - ($rekapPengajuan->jml_izin ?? 0)
            - ($rekapPengajuan->jml_sakit ?? 0)
        );

        return response()->json([
            'hadir'       => $rekapPresensi->jml_kehadiran ?? 0,
            'terlambat'   => $rekapPresensi->jml_terlambat ?? 0,
            'tidak_hadir' => $totalTidakHadir,
            'sakit'       => $rekapPengajuan->jml_sakit ?? 0,
            'izin'        => $rekapPengajuan->jml_izin ?? 0,
            'is_holiday'  => $isHoliday,
            'pct_hadir'   => $totalKaryawan > 0
                ? round(($rekapPresensi->jml_kehadiran ?? 0) / $totalKaryawan * 100)
                : 0,
        ]);
    }

    public function chartData(Request $request): JsonResponse
    {
        $period = $request->period ?? '7days';

        if ($period === '4weeks') {
            $raw = DB::table('presensi')
                ->selectRaw('YEARWEEK(tanggal_presensi, 1) as minggu, MIN(tanggal_presensi) as tgl_awal, COUNT(*) as total')
                ->whereBetween('tanggal_presensi', [Carbon::now()->subWeeks(3)->startOfWeek()->format('Y-m-d'), Carbon::now()->format('Y-m-d')])
                ->groupBy('minggu')
                ->orderBy('minggu')
                ->get();

            $labels = $raw->map(fn($r) => Carbon::parse($r->tgl_awal)->isoFormat('D MMM'))->toArray();
            $data   = $raw->pluck('total')->toArray();

        } elseif ($period === '12months') {
            $raw = DB::table('presensi')
                ->selectRaw("DATE_FORMAT(tanggal_presensi,'%Y-%m') as bulan, COUNT(*) as total")
                ->whereBetween('tanggal_presensi', [Carbon::now()->subMonths(11)->startOfMonth()->format('Y-m-d'), Carbon::now()->format('Y-m-d')])
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get();

            $labels = $raw->map(fn($r) => Carbon::parse($r->bulan . '-01')->isoFormat('MMM YY'))->toArray();
            $data   = $raw->pluck('total')->toArray();

        } else {
            // default 7days
            $rawMap = DB::table('presensi')
                ->selectRaw('DATE(tanggal_presensi) as tgl, COUNT(*) as total')
                ->whereBetween('tanggal_presensi', [Carbon::now()->subDays(6)->format('Y-m-d'), Carbon::now()->format('Y-m-d')])
                ->groupBy('tgl')
                ->orderBy('tgl')
                ->pluck('total', 'tgl');

            $labels = [];
            $data   = [];
            for ($i = 6; $i >= 0; $i--) {
                $tgl      = Carbon::now()->subDays($i)->format('Y-m-d');
                $labels[] = Carbon::now()->subDays($i)->isoFormat('D MMM');
                $data[]   = $rawMap[$tgl] ?? 0;
            }
        }

        return response()->json(compact('labels', 'data'));
    }
}
