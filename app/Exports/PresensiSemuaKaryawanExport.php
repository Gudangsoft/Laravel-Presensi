<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Pengaturan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PresensiSemuaKaryawanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected string $bulan;

    public function __construct(string $bulan)
    {
        $this->bulan = $bulan;
    }

    public function collection(): Collection
    {
        $pengaturan = Pengaturan::first();

        return DB::table('presensi')
            ->join('karyawan', 'presensi.karyawan_id', '=', 'karyawan.id')
            ->join('departemen as d', 'karyawan.departemen_id', '=', 'd.id')
            ->whereMonth('tanggal_presensi', Carbon::make($this->bulan)->format('m'))
            ->whereYear('tanggal_presensi', Carbon::make($this->bulan)->format('Y'))
            ->select(
                'karyawan.nama_lengkap as nama_karyawan',
                'karyawan.jabatan as jabatan_karyawan',
                'd.nama as nama_departemen'
            )
            ->selectRaw('COUNT(presensi.karyawan_id) as total_kehadiran, SUM(IF(jam_masuk > ?,1,0)) as total_terlambat', [$pengaturan->jam_masuk])
            ->groupBy('presensi.karyawan_id', 'karyawan.nama_lengkap', 'karyawan.jabatan', 'd.nama')
            ->orderBy('karyawan.nama_lengkap')
            ->get();
    }

    public function headings(): array
    {
        return ['No', 'Nama Karyawan', 'Jabatan', 'Departemen', 'Total Kehadiran', 'Total Terlambat'];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $row->nama_karyawan,
            $row->jabatan_karyawan,
            $row->nama_departemen,
            $row->total_kehadiran,
            $row->total_terlambat,
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

    public function title(): string
    {
        return 'Rekap ' . Carbon::make($this->bulan)->isoFormat('MMMM YYYY');
    }
}
