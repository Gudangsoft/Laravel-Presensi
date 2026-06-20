<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Backup otomatis setiap hari pukul 02:00
Schedule::command('backup:database')->dailyAt('02:00');

// Auto-close absensi: set jam_keluar untuk yang belum presensi keluar (jam 23:55)
Schedule::command('absensi:auto-close')->dailyAt('23:55')->timezone('Asia/Jakarta');

// Laporan kehadiran bulanan — kirim email ke admin setiap tanggal 1 jam 06:00
Schedule::command('laporan:bulanan')->monthlyOn(1, '06:00')->timezone('Asia/Jakarta');
