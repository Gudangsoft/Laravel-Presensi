<?php

namespace App\Imports;

use App\Models\Departemen;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class KaryawanImport implements ToModel, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    public function model(array $row): ?Karyawan
    {
        if (empty($row['nama_lengkap']) || empty($row['email'])) {
            return null;
        }

        // Skip if email already exists
        if (Karyawan::where('email', $row['email'])->exists()) {
            return null;
        }

        $departemen = Departemen::where('kode', strtoupper(trim($row['kode_departemen'] ?? '')))
            ->orWhere('nama', $row['kode_departemen'] ?? '')
            ->first();

        return new Karyawan([
            'departemen_id' => $departemen?->id ?? 1,
            'nama_lengkap'  => $row['nama_lengkap'],
            'jabatan'       => $row['jabatan'] ?? 'Karyawan',
            'telepon'       => $row['telepon'] ?? '-',
            'email'         => $row['email'],
            'password'      => Hash::make($row['password'] ?? 'password'),
        ]);
    }
}
