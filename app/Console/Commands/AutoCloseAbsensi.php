<?php

namespace App\Console\Commands;

use App\Models\Notifikasi;
use App\Models\Pengaturan;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutoCloseAbsensi extends Command
{
    protected $signature   = 'absensi:auto-close {--date= : Tanggal (Y-m-d), default hari ini}';
    protected $description = 'Set jam_keluar otomatis untuk karyawan yang belum presensi keluar';

    public function handle(): int
    {
        $tanggal    = $this->option('date') ?? Carbon::now()->format('Y-m-d');
        $pengaturan = Pengaturan::first();

        if (!$pengaturan) {
            $this->error('Pengaturan tidak ditemukan.');
            return Command::FAILURE;
        }

        $jamPulang = $pengaturan->jam_pulang;

        $updated = DB::table('presensi')
            ->whereDate('tanggal_presensi', $tanggal)
            ->whereNull('jam_keluar')
            ->update([
                'jam_keluar'  => $jamPulang,
                'updated_at'  => Carbon::now(),
            ]);

        if ($updated > 0) {
            Notifikasi::send(
                'Auto-close Absensi',
                "Jam keluar otomatis ({$jamPulang}) diterapkan untuk {$updated} karyawan pada {$tanggal}.",
                'info'
            );
        }

        $this->info("Auto-close selesai: {$updated} data diperbarui untuk tanggal {$tanggal}.");
        return Command::SUCCESS;
    }
}
