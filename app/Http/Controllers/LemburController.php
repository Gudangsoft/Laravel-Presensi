<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Lembur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LemburController extends Controller
{
    // ── Karyawan ────────────────────────────────────────────────────────

    public function index()
    {
        $karyawan = Auth::guard('karyawan')->user();
        $lembur   = Lembur::where('karyawan_id', $karyawan->id)
            ->orderByDesc('tanggal')->paginate(15);
        $title = 'Pengajuan Lembur';
        return view('dashboard.lembur.index', compact('title', 'lembur'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'keterangan'  => 'required|string|max:500',
        ]);

        $karyawan = Auth::guard('karyawan')->user();

        Lembur::create([
            'karyawan_id' => $karyawan->id,
            'tanggal'     => $request->tanggal,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'keterangan'  => $request->keterangan,
        ]);

        return back()->with('success', 'Pengajuan lembur berhasil dikirim.');
    }

    // ── Admin ────────────────────────────────────────────────────────────

    public function adminIndex(Request $request)
    {
        $query = Lembur::with('karyawan')->orderByDesc('tanggal');

        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $lembur = $query->paginate(20)->withQueryString();
        $title  = 'Manajemen Lembur';
        return view('admin.lembur.index', compact('title', 'lembur'));
    }

    public function approve(Request $request)
    {
        $lembur = Lembur::findOrFail($request->id);
        $lembur->update(['status' => 1, 'catatan_admin' => $request->catatan]);
        ActivityLog::record('lembur_disetujui', "Lembur {$lembur->karyawan->nama_lengkap} tgl {$lembur->tanggal} disetujui");
        return back()->with('success', 'Lembur disetujui.');
    }

    public function reject(Request $request)
    {
        $lembur = Lembur::findOrFail($request->id);
        $lembur->update(['status' => 2, 'catatan_admin' => $request->catatan]);
        ActivityLog::record('lembur_ditolak', "Lembur {$lembur->karyawan->nama_lengkap} tgl {$lembur->tanggal} ditolak");
        return back()->with('success', 'Lembur ditolak.');
    }
}
