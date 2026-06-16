<?php

namespace App\Http\Controllers;

use App\Enums\StatusPengajuanPresensi;
use App\Models\ActivityLog;
use App\Models\Departemen;
use App\Models\Karyawan;
use App\Models\Notifikasi;
use App\Models\LokasiKantor;
use App\Models\Pengaturan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
{
    public function index()
    {
        $title = 'Presensi';
        $presensiKaryawan = DB::table('presensi')
            ->where('karyawan_id', auth()->guard('karyawan')->user()->id)
            ->where('tanggal_presensi', date('Y-m-d'))
            ->first();
        $lokasiKantors = LokasiKantor::where('is_used', true)->get();
        return view('dashboard.presensi.index', compact('title', 'presensiKaryawan', 'lokasiKantors'));
    }

    public function store(Request $request)
    {
        $jenisPresensi = $request->jenis;
        $karyawanId    = auth()->guard('karyawan')->user()->id;
        $tglPresensi   = date('Y-m-d');
        $jam           = date('H:i:s');

        // Duplicate / double-submit guard
        $existingPresensi = DB::table('presensi')
            ->where('karyawan_id', $karyawanId)
            ->where('tanggal_presensi', $tglPresensi)
            ->first();

        if ($jenisPresensi === 'masuk' && $existingPresensi) {
            return response()->json([
                'status'  => 422,
                'success' => false,
                'message' => 'Anda sudah melakukan presensi masuk hari ini.',
            ]);
        }

        if ($jenisPresensi === 'keluar') {
            if (!$existingPresensi) {
                return response()->json([
                    'status'  => 422,
                    'success' => false,
                    'message' => 'Belum ada presensi masuk hari ini.',
                ]);
            }
            if ($existingPresensi->jam_keluar) {
                return response()->json([
                    'status'  => 422,
                    'success' => false,
                    'message' => 'Anda sudah melakukan presensi keluar hari ini.',
                ]);
            }
        }

        $lokasi     = $request->lokasi;
        $folderPath = "public/unggah/presensi/";
        $folderName = $karyawanId . "-" . $tglPresensi . "-" . $jenisPresensi;

        $lokasiUser    = explode(",", $lokasi);
        $langtitudeUser = $lokasiUser[0];
        $longtitudeUser = $lokasiUser[1];

        $lokasiKantors  = LokasiKantor::where('is_used', true)->get();
        $dalamRadius    = false;
        $jarakTerdekat  = null;

        foreach ($lokasiKantors as $lokasiKantor) {
            $jarak = round($this->validation_radius_presensi(
                $lokasiKantor->latitude, $lokasiKantor->longitude,
                $langtitudeUser, $longtitudeUser
            ), 2);

            if ($jarak <= $lokasiKantor->radius) {
                $dalamRadius = true;
                break;
            }

            if ($jarakTerdekat === null || $jarak < $jarakTerdekat) {
                $jarakTerdekat = $jarak;
            }
        }

        if (!$dalamRadius) {
            return response()->json([
                'status'      => 500,
                'success'     => false,
                'message'     => "Anda berada di luar radius kantor. Jarak terdekat Anda " . $jarakTerdekat . " meter dari kantor",
                'jenis_error' => "radius",
            ]);
        }

        $image      = $request->image;
        $imageParts = explode(";base64", $image);
        $imageBase64 = base64_decode($imageParts[1]);

        $fileName = $folderName . ".png";
        $file     = $folderPath . $fileName;

        if ($jenisPresensi == "masuk") {
            $data = [
                "karyawan_id"     => $karyawanId,
                "tanggal_presensi" => $tglPresensi,
                "jam_masuk"        => $jam,
                "foto_masuk"       => $fileName,
                "lokasi_masuk"     => $lokasi,
                "created_at"       => Carbon::now(),
                "updated_at"       => Carbon::now(),
            ];
            $store = DB::table('presensi')->insert($data);
        } elseif ($jenisPresensi == "keluar") {
            $data = [
                "jam_keluar"   => $jam,
                "foto_keluar"  => $fileName,
                "lokasi_keluar" => $lokasi,
                "updated_at"   => Carbon::now(),
            ];
            $store = DB::table('presensi')
                ->where('karyawan_id', $karyawanId)
                ->where('tanggal_presensi', date('Y-m-d'))
                ->update($data);
        }

        if ($store) {
            Storage::put($file, $imageBase64);
        } else {
            return response()->json([
                'status'  => 500,
                'success' => false,
                'message' => "Gagal presensi",
            ]);
        }

        return response()->json([
            'status'         => 200,
            'data'           => $data,
            'success'        => true,
            'message'        => "Berhasil presensi",
            'jenis_presensi' => $jenisPresensi,
        ]);
    }

    public function validation_radius_presensi($langtitudeKantor, $longtitudeKantor, $langtitudeUser, $longtitudeUser)
    {
        $theta          = $longtitudeKantor - $longtitudeUser;
        $hitungKoordinat = (sin(deg2rad($langtitudeKantor)) * sin(deg2rad($langtitudeUser))) + (cos(deg2rad($langtitudeKantor)) * cos(deg2rad($langtitudeUser)) * cos(deg2rad($theta)));
        $miles           = rad2deg(acos($hitungKoordinat)) * 60 * 1.1515;
        $kilometers      = $miles * 1.609344;
        $meters          = $kilometers * 1000;
        return $meters;
    }

    public function history()
    {
        $title = 'Riwayat Presensi';
        $riwayatPresensi = DB::table("presensi")
            ->where('karyawan_id', auth()->guard('karyawan')->user()->id)
            ->orderBy("tanggal_presensi", "asc")
            ->paginate(10);
        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return view('dashboard.presensi.history', compact('title', 'riwayatPresensi', 'bulan'));
    }

    public function searchHistory(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $data  = DB::table('presensi')
            ->where('karyawan_id', auth()->guard('karyawan')->user()->id)
            ->whereMonth('tanggal_presensi', $bulan)
            ->whereYear('tanggal_presensi', $tahun)
            ->orderBy("tanggal_presensi", "asc")
            ->get();
        return view('dashboard.presensi.search-history', compact('data'));
    }

    public function rekapPdf(Request $request)
    {
        $karyawan   = auth()->guard('karyawan')->user();
        $bulan      = (int) ($request->bulan ?? date('n'));
        $tahun      = (int) ($request->tahun ?? date('Y'));
        $pengaturan = Pengaturan::first();

        $bulanNama = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                      'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $presensi = DB::table('presensi')
            ->where('karyawan_id', $karyawan->id)
            ->whereMonth('tanggal_presensi', $bulan)
            ->whereYear('tanggal_presensi', $tahun)
            ->orderBy('tanggal_presensi')
            ->get();

        $izin = DB::table('pengajuan_presensi')
            ->where('karyawan_id', $karyawan->id)
            ->whereMonth('tanggal_pengajuan', $bulan)
            ->whereYear('tanggal_pengajuan', $tahun)
            ->where('status_approved', 1)
            ->get()
            ->keyBy('tanggal_pengajuan');

        $totalHadir    = $presensi->count();
        $totalTerlambat = $presensi->filter(fn($p) => $p->jam_masuk > $pengaturan->jam_masuk)->count();

        $pdf = Pdf::loadView('dashboard.presensi.rekap-pdf', compact(
            'karyawan', 'presensi', 'izin', 'bulan', 'tahun', 'bulanNama', 'pengaturan', 'totalHadir', 'totalTerlambat'
        ))->setPaper('a4', 'portrait');

        $nama = str_replace(' ', '-', $karyawan->nama_lengkap);
        return $pdf->download("Rekap-{$nama}-{$bulanNama[$bulan]}-{$tahun}.pdf");
    }

    public function pengajuanPresensi()
    {
        $title = "Izin Karyawan";
        $riwayatPengajuanPresensi = DB::table("pengajuan_presensi")
            ->where('karyawan_id', auth()->guard('karyawan')->user()->id)
            ->orderBy("tanggal_pengajuan", "asc")
            ->paginate(10);
        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return view('dashboard.presensi.izin.index', compact('title', 'riwayatPengajuanPresensi', 'bulan'));
    }

    public function pengajuanPresensiCreate()
    {
        $title         = "Form Pengajuan Presensi";
        $statusPengajuan = StatusPengajuanPresensi::cases();
        return view('dashboard.presensi.izin.create', compact('title', 'statusPengajuan'));
    }

    public function pengajuanPresensiStore(Request $request)
    {
        $karyawanId      = auth()->guard('karyawan')->user()->id;
        $tanggal_pengajuan = $request->tanggal_pengajuan;
        $status          = $request->status;
        $keterangan      = $request->keterangan;

        $cekPengajuan = DB::table('pengajuan_presensi')
            ->where('karyawan_id', $karyawanId)
            ->whereDate('tanggal_pengajuan', Carbon::make($tanggal_pengajuan)->format('Y-m-d'))
            ->where(function (Builder $query) {
                $query->where('status_approved', 0)
                    ->orWhere('status_approved', 1);
            })
            ->first();

        if ($cekPengajuan) {
            return to_route('karyawan.izin')->with("error", "Anda sudah menambahkan pengajuan pada tanggal " . Carbon::make($tanggal_pengajuan)->format('d-m-Y'));
        } else {
            $store = DB::table('pengajuan_presensi')->insert([
                'karyawan_id'      => $karyawanId,
                'tanggal_pengajuan' => $tanggal_pengajuan,
                'status'           => $status,
                'keterangan'       => $keterangan,
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ]);
        }

        if ($store) {
            $nama        = auth()->guard('karyawan')->user()->nama_lengkap;
            $statusLabel = $status === 'I' ? 'Izin' : 'Sakit';
            Notifikasi::send(
                "📋 Pengajuan {$statusLabel} Baru",
                "{$nama} mengajukan {$statusLabel} tanggal " . Carbon::parse($tanggal_pengajuan)->format('d M Y'),
                'warning',
                route('admin.administrasi-presensi')
            );
            return to_route('karyawan.izin')->with("success", "Berhasil menambahkan pengajuan");
        } else {
            return to_route('karyawan.izin')->with("error", "Gagal menambahkan pengajuan");
        }
    }

    public function searchPengajuanHistory(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $data  = DB::table('pengajuan_presensi')
            ->where('karyawan_id', auth()->guard('karyawan')->user()->id)
            ->whereMonth('tanggal_pengajuan', $bulan)
            ->whereYear('tanggal_pengajuan', $tahun)
            ->orderBy("tanggal_pengajuan", "asc")
            ->get();
        return view('dashboard.presensi.izin.search-history', compact('data'));
    }

    public function monitoringPresensi(Request $request)
    {
        $query = DB::table('presensi as p')
            ->join('karyawan as k', 'p.karyawan_id', '=', 'k.id')
            ->join('departemen as d', 'k.departemen_id', '=', 'd.id')
            ->orderBy('k.nama_lengkap', 'asc')
            ->orderBy('d.kode', 'asc')
            ->select('p.*', 'k.nama_lengkap as nama_karyawan', 'd.nama as nama_departemen');

        if ($request->tanggal_presensi) {
            $query->whereDate('p.tanggal_presensi', $request->tanggal_presensi);
        } else {
            $query->whereDate('p.tanggal_presensi', Carbon::now());
        }

        $monitoring   = $query->paginate(10);
        $lokasiKantor = LokasiKantor::where('is_used', true)->first();
        $pengaturan   = Pengaturan::first();

        return view('admin.monitoring-presensi.index', compact('monitoring', 'lokasiKantor', 'pengaturan'));
    }

    public function viewLokasi(Request $request)
    {
        if ($request->tipe == "lokasi_masuk") {
            $data = DB::table('presensi')->where('id', $request->id)->first('lokasi_masuk');
            return $data;
        } elseif ($request->tipe == "lokasi_keluar") {
            $data = DB::table('presensi')->where('id', $request->id)->first('lokasi_keluar');
            return $data;
        }
    }

    public function laporan(Request $request)
    {
        $bulan    = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $karyawan = Karyawan::orderBy('nama_lengkap', 'asc')->get();
        return view('admin.laporan.presensi', compact('bulan', 'karyawan'));
    }

    public function laporanPresensiKaryawan(Request $request)
    {
        $title    = 'Laporan Presensi Karyawan';
        $bulan    = $request->bulan;
        $karyawan = Karyawan::query()
            ->with('departemen')
            ->where('id', $request->karyawan)
            ->first();
        $riwayatPresensi = DB::table("presensi")
            ->where('karyawan_id', $request->karyawan)
            ->whereMonth('tanggal_presensi', Carbon::make($bulan)->format('m'))
            ->whereYear('tanggal_presensi', Carbon::make($bulan)->format('Y'))
            ->orderBy("tanggal_presensi", "asc")
            ->get();

        $pdf = Pdf::loadView('admin.laporan.pdf.presensi-karyawan', compact('title', 'bulan', 'karyawan', 'riwayatPresensi'));
        return $pdf->stream($title . ' ' . $karyawan->nama_lengkap . '.pdf');
    }

    public function laporanPresensiSemuaKaryawan(Request $request)
    {
        $title           = 'Laporan Presensi Semua Karyawan';
        $bulan           = $request->bulan;
        $pengaturan      = Pengaturan::first();
        $riwayatPresensi = DB::table("presensi as p")
            ->join('karyawan as k', 'p.karyawan_id', '=', 'k.id')
            ->join('departemen as d', 'k.departemen_id', '=', 'd.id')
            ->whereMonth('tanggal_presensi', Carbon::make($bulan)->format('m'))
            ->whereYear('tanggal_presensi', Carbon::make($bulan)->format('Y'))
            ->select(
                'p.karyawan_id',
                'k.nama_lengkap as nama_karyawan',
                'k.jabatan as jabatan_karyawan',
                'd.nama as nama_departemen'
            )
            ->selectRaw("COUNT(p.karyawan_id) as total_kehadiran, SUM(IF (jam_masuk > ?,1,0)) as total_terlambat", [$pengaturan->jam_masuk])
            ->groupBy(
                'p.karyawan_id',
                'k.nama_lengkap',
                'k.jabatan',
                'd.nama'
            )
            ->orderBy("tanggal_presensi", "asc")
            ->get();

        $pdf = Pdf::loadView('admin.laporan.pdf.presensi-semua-karyawan', compact('title', 'bulan', 'riwayatPresensi'));
        return $pdf->stream($title . '.pdf');
    }

    public function indexAdmin(Request $request)
    {
        $title      = 'Administrasi Presensi';
        $departemen = Departemen::get();

        $query = DB::table('pengajuan_presensi as p')
            ->join('karyawan as k', 'k.id', '=', 'p.karyawan_id')
            ->join('departemen as d', 'k.departemen_id', '=', 'd.id')
            ->where('p.tanggal_pengajuan', '>=', Carbon::now()->startOfMonth()->format("Y-m-d"))
            ->where('p.tanggal_pengajuan', '<=', Carbon::now()->endOfMonth()->format("Y-m-d"))
            ->select('p.*', 'k.nama_lengkap as nama_karyawan', 'd.nama as nama_departemen', 'd.id as id_departemen')
            ->orderBy('p.tanggal_pengajuan', 'asc');

        if ($request->karyawan) {
            $query->where('k.nama_lengkap', 'LIKE', '%' . $request->karyawan . '%');
        }
        if ($request->departemen) {
            $query->where('d.id', $request->departemen);
        }
        if ($request->tanggal_awal) {
            $query->whereDate('p.tanggal_pengajuan', '>=', Carbon::parse($request->tanggal_awal)->format('Y-m-d'));
        }
        if ($request->tanggal_akhir) {
            $query->whereDate('p.tanggal_pengajuan', '<=', Carbon::parse($request->tanggal_akhir)->format('Y-m-d'));
        }
        if ($request->status) {
            $query->where('p.status', $request->status);
        }
        if ($request->status_approved) {
            $query->where('p.status_approved', $request->status_approved);
        }

        $pengajuan = $query->paginate(10);

        return view('admin.monitoring-presensi.administrasi-presensi', compact('title', 'pengajuan', 'departemen'));
    }

    public function persetujuanPresensi(Request $request)
    {
        $label = match ($request->ajuan) {
            'terima' => ['status' => 2, 'msg_ok' => 'diterima',   'msg_fail' => 'gagal diterima'],
            'tolak'  => ['status' => 3, 'msg_ok' => 'ditolak',    'msg_fail' => 'gagal ditolak'],
            'batal'  => ['status' => 1, 'msg_ok' => 'dibatalkan', 'msg_fail' => 'gagal dibatalkan'],
            default  => null,
        };

        if (!$label) {
            return response()->json(['success' => false, 'message' => 'Aksi tidak valid.']);
        }

        $pengajuan = DB::table('pengajuan_presensi')->where('id', $request->id)->update(['status_approved' => $label['status']]);

        if ($pengajuan) {
            ActivityLog::record('persetujuan_izin', 'Pengajuan ID ' . $request->id . ' ' . $label['msg_ok']);
        }

        return response()->json($pengajuan
            ? ['success' => true,  'message' => 'Pengajuan presensi telah ' . $label['msg_ok']]
            : ['success' => false, 'message' => 'Pengajuan presensi ' . $label['msg_fail']]);
    }

    public function bulkPersetujuanPresensi(Request $request)
    {
        $ids   = $request->ids;
        $ajuan = $request->ajuan;

        if (empty($ids) || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada pengajuan yang dipilih.'], 422);
        }

        $status = match ($ajuan) {
            'terima' => 2,
            'tolak'  => 3,
            'batal'  => 1,
            default  => null,
        };

        if (!$status) {
            return response()->json(['success' => false, 'message' => 'Aksi tidak valid.'], 422);
        }

        $updated = DB::table('pengajuan_presensi')
            ->whereIn('id', $ids)
            ->update(['status_approved' => $status, 'updated_at' => Carbon::now()]);

        $label = match ($ajuan) {
            'terima' => 'diterima',
            'tolak'  => 'ditolak',
            'batal'  => 'dibatalkan',
        };

        return response()->json([
            'success' => true,
            'message' => $updated . ' pengajuan berhasil ' . $label . '.',
        ]);
    }
}
