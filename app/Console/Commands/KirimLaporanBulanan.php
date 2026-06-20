<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class KirimLaporanBulanan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laporan:bulanan {--bulan=} {--tahun=}';
    protected $description = 'Kirim laporan kehadiran bulanan via email ke admin';

    public function handle(): void
    {
        $bulan  = $this->option('bulan')  ?: now()->subMonth()->month;
        $tahun  = $this->option('tahun')  ?: now()->subMonth()->year;
        $label  = \Carbon\Carbon::create($tahun, $bulan, 1)->isoFormat('MMMM Y');

        $pengaturan = \App\Models\Pengaturan::first();
        $adminEmail = config('mail.from.address');

        $rekap = \Illuminate\Support\Facades\DB::table('presensi')
            ->join('karyawan', 'karyawan.id', '=', 'presensi.karyawan_id')
            ->join('departemen', 'departemen.id', '=', 'karyawan.departemen_id')
            ->selectRaw("
                karyawan.nama_lengkap,
                departemen.nama as departemen,
                COUNT(*) as hadir,
                SUM(IF(presensi.jam_masuk > ?, 1, 0)) as terlambat
            ", [$pengaturan->jam_masuk ?? '08:00:00'])
            ->whereMonth('presensi.tanggal_presensi', $bulan)
            ->whereYear('presensi.tanggal_presensi', $tahun)
            ->groupBy('karyawan.id', 'karyawan.nama_lengkap', 'departemen.nama')
            ->orderBy('departemen.nama')
            ->orderBy('karyawan.nama_lengkap')
            ->get();

        if ($rekap->isEmpty()) {
            $this->warn("Tidak ada data presensi untuk {$label}.");
            return;
        }

        $html = view('emails.laporan-bulanan', compact('rekap', 'label'))->render();

        \Illuminate\Support\Facades\Mail::html($html, function ($msg) use ($adminEmail, $label) {
            $msg->to($adminEmail)
                ->subject("Laporan Kehadiran Bulanan – {$label}");
        });

        $this->info("Laporan {$label} berhasil dikirim ke {$adminEmail}.");
    }
}
