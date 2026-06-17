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
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class KaryawanImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    public function model(array $row): ?Karyawan
    {
        $departemen = Departemen::where('kode', strtoupper(trim($row['kode_departemen'] ?? '')))
            ->orWhere('nama', trim($row['kode_departemen'] ?? ''))
            ->first();

        // Fallback ke departemen pertama yang ada jika tidak ditemukan
        if (!$departemen) {
            $departemen = Departemen::first();
        }

        if (!$departemen) {
            return null;
        }

        return new Karyawan([
            'departemen_id' => $departemen->id,
            'nama_lengkap'  => trim($row['nama_lengkap']),
            'jabatan'       => trim($row['jabatan'] ?? 'Karyawan'),
            'telepon'       => trim($row['telepon'] ?? '-'),
            'email'         => strtolower(trim($row['email'])),
            'password'      => Hash::make($row['password'] ?? 'password'),
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'required|email|unique:karyawan,email',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.max'      => 'Nama lengkap maksimal 255 karakter.',
            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah terdaftar di sistem.',
        ];
    }

    public function customValidationAttributes(): array
    {
        return [
            'nama_lengkap' => 'Nama Lengkap',
            'email'        => 'Email',
        ];
    }
}
