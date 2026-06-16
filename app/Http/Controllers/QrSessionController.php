<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\KoreksiPresensi;
use App\Models\Karyawan;
use App\Models\LokasiKantor;
use App\Models\Notifikasi;
use App\Models\QrSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QrSessionController extends Controller
{
    public function adminIndex()
    {
        $title      = 'QR Presensi';
        $sessions   = QrSession::with('karyawan')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        $activeQr   = QrSession::whereNull('used_at')
            ->where('expires_at', '>', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->first();

        return view('admin.qr-presensi.index', compact('title', 'sessions', 'activeQr'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'jenis'   => 'required|in:masuk,keluar',
            'durasi'  => 'required|integer|in:5,10,15,30',
        ]);

        $session = QrSession::create([
            'jenis'      => $request->jenis,
            'expires_at' => Carbon::now()->addMinutes((int) $request->durasi),
            'created_by' => Auth::id(),
        ]);

        ActivityLog::record('generate_qr', 'Membuat QR presensi ' . $request->jenis . ' (durasi ' . $request->durasi . ' menit)');

        return response()->json([
            'success'    => true,
            'token'      => $session->token,
            'scan_url'   => $session->scanUrl(),
            'jenis'      => $session->jenis,
            'expires_at' => $session->expires_at->timestamp * 1000, // ms for JS
        ]);
    }

    public function scan($token)
    {
        $session = QrSession::where('token', $token)->first();

        if (!$session) {
            return view('dashboard.presensi.scan-result', [
                'title'   => 'QR Tidak Valid',
                'success' => false,
                'message' => 'QR Code tidak ditemukan atau sudah tidak berlaku.',
            ]);
        }

        if ($session->isUsed()) {
            return view('dashboard.presensi.scan-result', [
                'title'   => 'QR Sudah Digunakan',
                'success' => false,
                'message' => 'QR Code ini sudah digunakan untuk presensi ' . $session->jenis . '.',
            ]);
        }

        if ($session->isExpired()) {
            return view('dashboard.presensi.scan-result', [
                'title'   => 'QR Kedaluwarsa',
                'success' => false,
                'message' => 'QR Code sudah kedaluwarsa. Minta admin untuk generate QR baru.',
            ]);
        }

        $karyawanId  = Auth::guard('karyawan')->user()->id;
        $karyawan    = Auth::guard('karyawan')->user();
        $tglPresensi = Carbon::now()->format('Y-m-d');
        $jam         = Carbon::now()->format('H:i:s');

        // Same duplicate guard as normal presensi
        $existing = DB::table('presensi')
            ->where('karyawan_id', $karyawanId)
            ->where('tanggal_presensi', $tglPresensi)
            ->first();

        if ($session->jenis === 'masuk' && $existing) {
            return view('dashboard.presensi.scan-result', [
                'title'   => 'Sudah Presensi',
                'success' => false,
                'message' => 'Anda sudah melakukan presensi masuk hari ini pukul ' . substr($existing->jam_masuk, 0, 5) . '.',
            ]);
        }

        if ($session->jenis === 'keluar') {
            if (!$existing) {
                return view('dashboard.presensi.scan-result', [
                    'title'   => 'Belum Masuk',
                    'success' => false,
                    'message' => 'Anda belum melakukan presensi masuk hari ini.',
                ]);
            }
            if ($existing->jam_keluar) {
                return view('dashboard.presensi.scan-result', [
                    'title'   => 'Sudah Keluar',
                    'success' => false,
                    'message' => 'Anda sudah melakukan presensi keluar hari ini pukul ' . substr($existing->jam_keluar, 0, 5) . '.',
                ]);
            }
        }

        // Record presensi
        if ($session->jenis === 'masuk') {
            DB::table('presensi')->insert([
                'karyawan_id'      => $karyawanId,
                'tanggal_presensi' => $tglPresensi,
                'jam_masuk'        => $jam,
                'lokasi_masuk'     => 'QR',
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ]);
        } else {
            DB::table('presensi')
                ->where('karyawan_id', $karyawanId)
                ->where('tanggal_presensi', $tglPresensi)
                ->update(['jam_keluar' => $jam, 'lokasi_keluar' => 'QR', 'updated_at' => Carbon::now()]);
        }

        // Mark session as used
        $session->update(['used_at' => Carbon::now(), 'karyawan_id' => $karyawanId]);

        return view('dashboard.presensi.scan-result', [
            'title'   => 'Presensi Berhasil',
            'success' => true,
            'jenis'   => $session->jenis,
            'jam'     => substr($jam, 0, 5),
            'nama'    => $karyawan->nama_lengkap,
            'message' => 'Presensi ' . $session->jenis . ' berhasil dicatat pukul ' . substr($jam, 0, 5) . ' WIB.',
        ]);
    }

    public function invalidate(Request $request)
    {
        $session = QrSession::findOrFail($request->id);
        $session->update(['expires_at' => Carbon::now()->subSecond()]);
        ActivityLog::record('invalidate_qr', 'Menonaktifkan QR session ID ' . $request->id);
        return response()->json(['success' => true, 'message' => 'QR berhasil dinonaktifkan.']);
    }
}
