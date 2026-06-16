<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KaryawanSeeder extends Seeder
{
    public function run(): void
    {
        Karyawan::create([
            'departemen_id' => '1',
            'nama_lengkap'  => 'Ucup',
            'foto'          => null,
            'jabatan'       => 'Karyawan',
            'telepon'       => '08123456789',
            'email'         => 'ucup@gmail.com',
            'password'      => Hash::make('password'),
        ]);

        Karyawan::create([
            'departemen_id' => '2',
            'nama_lengkap'  => 'Wati',
            'jabatan'       => 'Karyawan',
            'telepon'       => '08123456780',
            'email'         => 'wati@gmail.com',
            'password'      => Hash::make('password'),
        ]);

        Karyawan::create([
            'departemen_id' => '3',
            'nama_lengkap'  => 'Mawar',
            'jabatan'       => 'Karyawan',
            'telepon'       => '08123456781',
            'email'         => 'mawar@gmail.com',
            'password'      => Hash::make('password'),
        ]);
    }
}
