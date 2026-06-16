<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Departemen;
use App\Models\Karyawan;
use App\Models\Pengaturan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsensiMassalController extends Controller
{
    public function index(Request $request)
    {
        $title        = 'Absensi Massal';
        $tanggal      = $request->tanggal ?? date('Y-m-d');
        $departemenId = $request->departemen_id;
        $departemen   = Departemen::orderBy('nama_departemen')->get();
        $pengaturan   = Pengaturan::first();

        $query = Karyawan::with('departemen')->orderBy('nama_lengkap');
        if ($departemenId) {
            $query->where('departemen_id', $departemenId);
        }
        $karyawans = $query->get();

        $existing = DB::table('presensi')
            ->where('tanggal_presensi', $tanggal)
            ->get()
            ->keyBy('karyawan_id');

        return view('admin.absensi-massal.index',
            compact('title', 'karyawans', 'tanggal', 'existing', 'departemen', 'departemenId', 'pengaturan'));
    }

    public function store(Request $request)
    {
        $request->validate(['tanggal' => 'required|date']);

        $tanggal    = $request->tanggal;
        $items      = $request->items ?? [];
        $pengaturan = Pengaturan::first();
        $count      = 0;

        foreach ($items as $karyawanId => $data) {
            $status = $data['status'] ?? 'skip';

            if ($status === 'hadir') {
                DB::table('presensi')->updateOrInsert(
                    ['karyawan_id' => $karyawanId, 'tanggal_presensi' => $tanggal],
                    [
                        'jam_masuk'    => $data['jam_masuk']  ?? $pengaturan->jam_masuk,
                        'jam_keluar'   => $data['jam_keluar'] ?: null,
                        'lokasi_masuk' => 'Absensi Massal',
                        'updated_at'   => Carbon::now(),
                        'created_at'   => Carbon::now(),
                    ]
                );
                $count++;
            } elseif ($status === 'hapus') {
                DB::table('presensi')
                    ->where('karyawan_id', $karyawanId)
                    ->where('tanggal_presensi', $tanggal)
                    ->delete();
            }
        }

        ActivityLog::record('absensi_massal', "Absensi massal {$tanggal}: {$count} karyawan diperbarui.");

        return redirect()->route('admin.absensi-massal', ['tanggal' => $tanggal])
            ->with('success', "{$count} data presensi berhasil disimpan untuk " . Carbon::parse($tanggal)->translatedFormat('d F Y') . '.');
    }
}
