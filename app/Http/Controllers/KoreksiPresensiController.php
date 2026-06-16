<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\KoreksiPresensi;
use App\Models\Notifikasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KoreksiPresensiController extends Controller
{
    // ── Karyawan ────────────────────────────────────────────────

    public function karyawanIndex()
    {
        $title    = 'Koreksi Presensi';
        $koreksis = KoreksiPresensi::where('karyawan_id', Auth::guard('karyawan')->id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('dashboard.presensi.koreksi.index', compact('title', 'koreksis'));
    }

    public function create()
    {
        $title = 'Ajukan Koreksi Presensi';
        return view('dashboard.presensi.koreksi.create', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'    => 'required|date|before_or_equal:today',
            'jam_masuk'  => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i|after:jam_masuk',
            'keterangan' => 'required|string|max:500',
        ]);

        $karyawanId = Auth::guard('karyawan')->id();

        // Cegah duplikat pengajuan pada tanggal yang sama yang masih pending
        $existing = KoreksiPresensi::where('karyawan_id', $karyawanId)
            ->where('tanggal', $request->tanggal)
            ->where('status', 0)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Anda sudah memiliki pengajuan koreksi pending untuk tanggal tersebut.');
        }

        KoreksiPresensi::create([
            'karyawan_id' => $karyawanId,
            'tanggal'     => $request->tanggal,
            'jam_masuk'   => $request->jam_masuk,
            'jam_keluar'  => $request->jam_keluar,
            'keterangan'  => $request->keterangan,
            'status'      => 0,
        ]);

        $nama = Auth::guard('karyawan')->user()->nama_lengkap;
        Notifikasi::send(
            '📋 Koreksi Presensi Baru',
            $nama . ' mengajukan koreksi presensi tanggal ' . Carbon::parse($request->tanggal)->format('d M Y'),
            'warning',
            route('admin.koreksi-presensi')
        );

        return to_route('karyawan.koreksi')->with('success', 'Pengajuan koreksi berhasil dikirim.');
    }

    // ── Admin ────────────────────────────────────────────────────

    public function adminIndex(Request $request)
    {
        $title = 'Koreksi Presensi';
        $query = KoreksiPresensi::with('karyawan')->orderBy('created_at', 'desc');

        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }
        if ($request->cari) {
            $query->whereHas('karyawan', fn($q) => $q->where('nama_lengkap', 'like', '%' . $request->cari . '%'));
        }

        $koreksis = $query->paginate(20);
        return view('admin.koreksi-presensi.index', compact('title', 'koreksis'));
    }

    public function approve(Request $request)
    {
        $koreksi = KoreksiPresensi::with('karyawan')->findOrFail($request->id);

        if ($koreksi->status !== 0) {
            return redirect()->route('admin.koreksi-presensi')->with('error', 'Pengajuan sudah diproses.');
        }

        $existing = DB::table('presensi')
            ->where('karyawan_id', $koreksi->karyawan_id)
            ->where('tanggal_presensi', $koreksi->tanggal->format('Y-m-d'))
            ->first();

        if ($existing) {
            $update = [];
            if ($koreksi->jam_masuk)  $update['jam_masuk']  = $koreksi->jam_masuk;
            if ($koreksi->jam_keluar) $update['jam_keluar'] = $koreksi->jam_keluar;
            $update['updated_at'] = Carbon::now();
            DB::table('presensi')->where('id', $existing->id)->update($update);
        } else {
            DB::table('presensi')->insert([
                'karyawan_id'      => $koreksi->karyawan_id,
                'tanggal_presensi' => $koreksi->tanggal->format('Y-m-d'),
                'jam_masuk'        => $koreksi->jam_masuk ?? '00:00:00',
                'jam_keluar'       => $koreksi->jam_keluar,
                'lokasi_masuk'     => 'Koreksi',
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ]);
        }

        $koreksi->update(['status' => 1]);

        ActivityLog::record('setujui_koreksi', 'Menyetujui koreksi presensi ' . $koreksi->karyawan->nama_lengkap . ' tgl ' . $koreksi->tanggal->format('d-m-Y'));

        return redirect()->route('admin.koreksi-presensi')->with('success', 'Koreksi presensi disetujui dan data presensi diperbarui.');
    }

    public function reject(Request $request)
    {
        $koreksi = KoreksiPresensi::with('karyawan')->findOrFail($request->id);

        if ($koreksi->status !== 0) {
            return redirect()->route('admin.koreksi-presensi')->with('error', 'Pengajuan sudah diproses.');
        }

        $koreksi->update([
            'status'        => 2,
            'catatan_admin' => $request->catatan_admin,
        ]);

        ActivityLog::record('tolak_koreksi', 'Menolak koreksi presensi ' . $koreksi->karyawan->nama_lengkap . ' tgl ' . $koreksi->tanggal->format('d-m-Y'));

        return redirect()->route('admin.koreksi-presensi')->with('success', 'Koreksi presensi ditolak.');
    }
}
