<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KaryawanTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        return [
            ['IT',  'Budi Santoso',   'Staff IT',    '08123456789', 'budi@example.com',   'password'],
            ['HR',  'Siti Rahayu',    'HRD Officer', '08987654321', 'siti@example.com',   'password'],
        ];
    }

    public function headings(): array
    {
        return ['kode_departemen', 'nama_lengkap', 'jabatan', 'telepon', 'email', 'password'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '4F46E5']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
