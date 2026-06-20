<?php

use App\Http\Controllers\AbsensiMassalController;
use App\Http\Controllers\LearningController;
use App\Http\Controllers\LemburController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthKaryawanController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\BrandSettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\HariLiburController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KoreksiPresensiController;
use App\Http\Controllers\LokasiKantorController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrSessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login.view');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group([
    'prefix' => 'login-karyawan',
    'middleware' => ['login-karyawan'],
], function () {
    Route::get('/', [AuthKaryawanController::class, 'create'])->name('login.view');
    Route::post('/', [AuthKaryawanController::class, 'store'])->name('login.auth')->middleware('throttle:5,1');
    Route::get('/lupa-sandi', [AuthKaryawanController::class, 'forgotPassword'])->name('karyawan.forgot-password');
    Route::post('/lupa-sandi', [AuthKaryawanController::class, 'sendResetNotification'])->name('karyawan.forgot-password.send')->middleware('throttle:3,1');
});

Route::group([
    'prefix' => 'karyawan',
    'middleware' => ['karyawan'],
], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('karyawan.dashboard');
    Route::post('/logout', [AuthKaryawanController::class, 'destroy'])->name('logout.auth');

    Route::group([
        'prefix' => 'presensi',
    ], function () {
        Route::get('/', [PresensiController::class, 'index'])->name('karyawan.presensi');
        Route::post('/', [PresensiController::class, 'store'])->name('karyawan.presensi.store')->middleware('throttle:5,1');

        Route::group([
            'prefix' => 'history',
        ], function () {
            Route::get('/', [PresensiController::class, 'history'])->name('karyawan.history');
            Route::post('/search-history', [PresensiController::class, 'searchHistory'])->name('karyawan.history.search');
        });

        Route::group([
            'prefix' => 'izin',
        ], function () {
            Route::get('/', [PresensiController::class, 'pengajuanPresensi'])->name('karyawan.izin');
            Route::get('/pengajuan-presensi', [PresensiController::class, 'pengajuanPresensiCreate'])->name('karyawan.izin.create');
            Route::post('/pengajuan-presensi', [PresensiController::class, 'pengajuanPresensiStore'])->name('karyawan.izin.store');
            Route::post('/search-history', [PresensiController::class, 'searchPengajuanHistory'])->name('karyawan.izin.search');
            Route::get('/koreksi', [KoreksiPresensiController::class, 'karyawanIndex'])->name('karyawan.koreksi');
            Route::get('/koreksi/buat', [KoreksiPresensiController::class, 'create'])->name('karyawan.koreksi.create');
            Route::post('/koreksi/buat', [KoreksiPresensiController::class, 'store'])->name('karyawan.koreksi.store');
        });
    });

    Route::group([
        'prefix' => 'profile',
    ], function () {
        Route::get('/', [KaryawanController::class, 'index'])->name('karyawan.profile');
        Route::post('/update', [KaryawanController::class, 'update'])->name('karyawan.profile.update');
        Route::post('/ganti-password', [KaryawanController::class, 'changePassword'])->name('karyawan.profile.password');
    });

    Route::get('/presensi/rekap-pdf', [PresensiController::class, 'rekapPdf'])->name('karyawan.presensi.rekap-pdf');

    // Smart Learning English
    Route::get('/learning', [LearningController::class, 'index'])->name('karyawan.learning');
    Route::get('/learning/words', [LearningController::class, 'words'])->name('karyawan.learning.words');
    Route::post('/learning/score', [LearningController::class, 'saveScore'])->name('karyawan.learning.score');

    // Lembur
    Route::get('/lembur', [LemburController::class, 'index'])->name('karyawan.lembur');
    Route::post('/lembur', [LemburController::class, 'store'])->name('karyawan.lembur.store');

    // Direktori Karyawan
    Route::get('/direktori', function (\Illuminate\Http\Request $req) {
        $q = $req->input('q');
        $karyawan = \App\Models\Karyawan::with('departemen')
            ->when($q, fn($query) => $query->where('nama_lengkap', 'like', "%{$q}%")
                ->orWhereHas('departemen', fn($d) => $d->where('nama', 'like', "%{$q}%")))
            ->orderBy('nama_lengkap')->paginate(20);
        return view('dashboard.direktori.index', ['title' => 'Direktori Karyawan', 'karyawan' => $karyawan]);
    })->name('karyawan.direktori');

    // Kalender Kehadiran
    Route::get('/kalender', function () {
        $user  = \Illuminate\Support\Facades\Auth::guard('karyawan')->user();
        $bulan = request('bulan', now()->month);
        $tahun = request('tahun', now()->year);
        $presensi = \Illuminate\Support\Facades\DB::table('presensi')
            ->where('karyawan_id', $user->id)
            ->whereMonth('tanggal_presensi', $bulan)
            ->whereYear('tanggal_presensi', $tahun)
            ->pluck('jam_masuk', 'tanggal_presensi');
        $izin = \Illuminate\Support\Facades\DB::table('pengajuan_presensi')
            ->where('karyawan_id', $user->id)
            ->where('status_approved', 2)
            ->whereMonth('tanggal_pengajuan', $bulan)
            ->whereYear('tanggal_pengajuan', $tahun)
            ->pluck('status', 'tanggal_pengajuan');
        $pengaturan = \App\Models\Pengaturan::first();
        return view('dashboard.kalender.index', compact('presensi', 'izin', 'bulan', 'tahun', 'pengaturan') + ['title' => 'Kalender Kehadiran']);
    })->name('karyawan.kalender');

    // QR scan (karyawan must be logged in)
    Route::get('/scan/{token}', [QrSessionController::class, 'scan'])->name('karyawan.scan');
});

Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth'],
], function () {
    Route::get('/dashboard', [DashboardController::class, 'indexAdmin'])->name('admin.dashboard');

    Route::get('/karyawan', [KaryawanController::class, 'indexAdmin'])->name('admin.karyawan');
    Route::post('/karyawan/tambah', [KaryawanController::class, 'store'])->name('admin.karyawan.store');
    Route::get('/karyawan/perbarui', [KaryawanController::class, 'edit'])->name('admin.karyawan.edit');
    Route::post('/karyawan/perbarui', [KaryawanController::class, 'updateAdmin'])->name('admin.karyawan.update');
    Route::post('/karyawan/hapus', [KaryawanController::class, 'delete'])->name('admin.karyawan.delete');
    Route::post('/karyawan/reset-password', [KaryawanController::class, 'resetPassword'])->name('admin.karyawan.reset-password');
    Route::get('/karyawan/export', [KaryawanController::class, 'export'])->name('admin.karyawan.export');
    Route::get('/karyawan/template', [KaryawanController::class, 'template'])->name('admin.karyawan.template');
    Route::post('/karyawan/import', [KaryawanController::class, 'import'])->name('admin.karyawan.import');

    Route::get('/departemen', [DepartemenController::class, 'index'])->name('admin.departemen');
    Route::post('/departemen/tambah', [DepartemenController::class, 'store'])->name('admin.departemen.store');
    Route::get('/departemen/perbarui', [DepartemenController::class, 'edit'])->name('admin.departemen.edit');
    Route::post('/departemen/perbarui', [DepartemenController::class, 'update'])->name('admin.departemen.update');
    Route::post('/departemen/hapus', [DepartemenController::class, 'delete'])->name('admin.departemen.delete');

    Route::get('/monitoring-presensi', [PresensiController::class, 'monitoringPresensi'])->name('admin.monitoring-presensi');
    Route::post('/monitoring-presensi', [PresensiController::class, 'viewLokasi'])->name('admin.monitoring-presensi.lokasi');

    Route::get('/laporan/presensi', [PresensiController::class, 'laporan'])->name('admin.laporan.presensi');
    Route::get('/laporan/presensi/karyawan/view', [PresensiController::class, 'laporanPresensiKaryawanView'])->name('admin.laporan.presensi.karyawan.view');
    Route::get('/laporan/presensi/semua-karyawan/view', [PresensiController::class, 'laporanPresensiSemuaKaryawanView'])->name('admin.laporan.presensi.semua-karyawan.view');
    Route::post('/laporan/presensi/karyawan', [PresensiController::class, 'laporanPresensiKaryawan'])->name('admin.laporan.presensi.karyawan');
    Route::post('/laporan/presensi/semua-karyawan', [PresensiController::class, 'laporanPresensiSemuaKaryawan'])->name('admin.laporan.presensi.semua-karyawan');
    Route::post('/laporan/presensi/karyawan/excel', [PresensiController::class, 'laporanPresensiKaryawanExcel'])->name('admin.laporan.presensi.karyawan.excel');
    Route::post('/laporan/presensi/semua-karyawan/excel', [PresensiController::class, 'laporanPresensiSemuaKaryawanExcel'])->name('admin.laporan.presensi.semua-karyawan.excel');

    Route::get('/lokasi', [LokasiKantorController::class, 'index'])->name('admin.lokasi-kantor');
    Route::post('/lokasi/tambah', [LokasiKantorController::class, 'store'])->name('admin.lokasi-kantor.store');
    Route::get('/lokasi/perbarui', [LokasiKantorController::class, 'edit'])->name('admin.lokasi-kantor.edit');
    Route::post('/lokasi/perbarui', [LokasiKantorController::class, 'update'])->name('admin.lokasi-kantor.update');
    Route::post('/lokasi/hapus', [LokasiKantorController::class, 'delete'])->name('admin.lokasi-kantor.delete');

    Route::get('/administrasi-presensi', [PresensiController::class, 'indexAdmin'])->name('admin.administrasi-presensi');
    Route::post('/administrasi-presensi/status', [PresensiController::class, 'persetujuanPresensi'])->name('admin.administrasi-presensi.persetujuan');
    Route::post('/administrasi-presensi/bulk', [PresensiController::class, 'bulkPersetujuanPresensi'])->name('admin.administrasi-presensi.bulk');

    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('admin.pengaturan');
    Route::post('/pengaturan', [PengaturanController::class, 'update'])->name('admin.pengaturan.update');

    Route::get('/brand', [BrandSettingController::class, 'index'])->name('admin.brand');
    Route::post('/brand', [BrandSettingController::class, 'update'])->name('admin.brand.update');

    // Dashboard real-time AJAX
    Route::get('/dashboard/stats', [DashboardController::class, 'dashboardStats'])->name('admin.dashboard.stats');
    Route::get('/dashboard/chart', [DashboardController::class, 'chartData'])->name('admin.dashboard.chart');

    // Hari Libur
    Route::get('/hari-libur', [HariLiburController::class, 'index'])->name('admin.hari-libur');
    Route::post('/hari-libur/tambah', [HariLiburController::class, 'store'])->name('admin.hari-libur.store');
    Route::post('/hari-libur/perbarui', [HariLiburController::class, 'update'])->name('admin.hari-libur.update');
    Route::post('/hari-libur/hapus', [HariLiburController::class, 'delete'])->name('admin.hari-libur.delete');

    // Log Aktivitas
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('admin.activity-log');
    Route::post('/activity-log/clear', [ActivityLogController::class, 'clear'])->name('admin.activity-log.clear');

    // QR Presensi
    Route::get('/qr-presensi', [QrSessionController::class, 'adminIndex'])->name('admin.qr-presensi');
    Route::post('/qr-presensi/generate', [QrSessionController::class, 'generate'])->name('admin.qr-presensi.generate');
    Route::post('/qr-presensi/invalidate', [QrSessionController::class, 'invalidate'])->name('admin.qr-presensi.invalidate');

    // Koreksi Presensi
    Route::get('/koreksi-presensi', [KoreksiPresensiController::class, 'adminIndex'])->name('admin.koreksi-presensi');
    Route::post('/koreksi-presensi/setujui', [KoreksiPresensiController::class, 'approve'])->name('admin.koreksi-presensi.approve');
    Route::post('/koreksi-presensi/tolak', [KoreksiPresensiController::class, 'reject'])->name('admin.koreksi-presensi.reject');

    // Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('admin.notifikasi');
    Route::get('/notifikasi/unread-count', [NotifikasiController::class, 'unreadCount'])->name('admin.notifikasi.count');
    Route::get('/notifikasi/recent', [NotifikasiController::class, 'recent'])->name('admin.notifikasi.recent');
    Route::post('/notifikasi/baca-semua', [NotifikasiController::class, 'markAllRead'])->name('admin.notifikasi.baca-semua');

    // Backup
    Route::get('/backup', [BackupController::class, 'index'])->name('admin.backup');
    Route::post('/backup/run', [BackupController::class, 'run'])->name('admin.backup.run');
    Route::get('/backup/download/{filename}', [BackupController::class, 'download'])->name('admin.backup.download')->where('filename', '.*');
    Route::post('/backup/delete', [BackupController::class, 'delete'])->name('admin.backup.delete');

    // Pengumuman
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('admin.pengumuman');
    Route::post('/pengumuman/tambah', [PengumumanController::class, 'store'])->name('admin.pengumuman.store');
    Route::post('/pengumuman/perbarui', [PengumumanController::class, 'update'])->name('admin.pengumuman.update');
    Route::post('/pengumuman/hapus', [PengumumanController::class, 'delete'])->name('admin.pengumuman.delete');
    Route::post('/pengumuman/toggle', [PengumumanController::class, 'toggle'])->name('admin.pengumuman.toggle');

    // Absensi Massal
    Route::get('/absensi-massal', [AbsensiMassalController::class, 'index'])->name('admin.absensi-massal');
    Route::post('/absensi-massal', [AbsensiMassalController::class, 'store'])->name('admin.absensi-massal.store');

    // Lembur
    Route::get('/lembur', [LemburController::class, 'adminIndex'])->name('admin.lembur');
    Route::post('/lembur/setujui', [LemburController::class, 'approve'])->name('admin.lembur.approve');
    Route::post('/lembur/tolak', [LemburController::class, 'reject'])->name('admin.lembur.reject');

    // Monitoring Keterlambatan
    Route::get('/monitoring/keterlambatan', [MonitoringController::class, 'keterlambatan'])->name('admin.monitoring.keterlambatan');
});

require __DIR__.'/auth.php';
