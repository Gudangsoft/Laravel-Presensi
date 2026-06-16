<?php

namespace App\Exports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KaryawanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected ?int $departemenId;

    public function __construct(?int $departemenId = null)
    {
        $this->departemenId = $departemenId;
    }

    public function collection()
    {
        $query = Karyawan::with('departemen')->orderBy('nama_lengkap');
        if ($this->departemenId) {
            $query->where('departemen_id', $this->departemenId);
        }
        return $query->get();
    }

    public function headings(): array
    {
        return ['No', 'Nama Lengkap', 'Departemen', 'Jabatan', 'Telepon', 'Email'];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $row->nama_lengkap,
            $row->departemen->nama ?? '-',
            $row->jabatan,
            $row->telepon,
            $row->email,
        ];
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
