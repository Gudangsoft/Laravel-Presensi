<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PresensiSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 30; $i++) {
            DB::table('presensi')->insert([
                "karyawan_id"      => 1,
                "tanggal_presensi" => date_create("2024-" . Carbon::now()->format('m') . "-" . $i)->format("Y-m-d"),
                "jam_masuk"        => date_create("07:" . rand(1, 59) . ":" . rand(1, 59))->format("H:i:s"),
                "jam_keluar"       => date_create(rand(15, 20) . ":" . rand(1, 59) . ":" . rand(1, 59))->format("H:i:s"),
                "foto_masuk"       => "1-2024-04-23-masuk.png",
                "foto_keluar"      => "1-2024-04-23-keluar.png",
                "lokasi_masuk"     => "-7.3131613, 112.7271187",
                "lokasi_keluar"    => "-7.3131613, 112.7271187",
                "created_at"       => Carbon::now(),
                "updated_at"       => Carbon::now(),
            ]);

            DB::table('presensi')->insert([
                "karyawan_id"      => 2,
                "tanggal_presensi" => date_create("2024-" . Carbon::now()->format('m') . "-" . $i)->format("Y-m-d"),
                "jam_masuk"        => date_create("07:" . rand(1, 59) . ":" . rand(1, 59))->format("H:i:s"),
                "jam_keluar"       => date_create(rand(15, 20) . ":" . rand(1, 59) . ":" . rand(1, 59))->format("H:i:s"),
                "foto_masuk"       => "2-2024-04-23-masuk.png",
                "foto_keluar"      => "2-2024-04-23-keluar.png",
                "lokasi_masuk"     => "-7.3131613, 112.7271187",
                "lokasi_keluar"    => "-7.3131613, 112.7271187",
                "created_at"       => Carbon::now(),
                "updated_at"       => Carbon::now(),
            ]);
        }
    }
}
