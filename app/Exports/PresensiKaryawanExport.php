<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PresensiKaryawanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected int $karyawanId;
    protected string $bulan;
    protected string $jamMasukBatas;

    public function __construct(int $karyawanId, string $bulan, string $jamMasukBatas = '08:00:00')
    {
        $this->karyawanId    = $karyawanId;
        $this->bulan         = $bulan;
        $this->jamMasukBatas = $jamMasukBatas;
    }

    public function collection(): Collection
    {
        return DB::table('presensi')
            ->where('karyawan_id', $this->karyawanId)
            ->whereMonth('tanggal_presensi', Carbon::make($this->bulan)->format('m'))
            ->whereYear('tanggal_presensi', Carbon::make($this->bulan)->format('Y'))
            ->orderBy('tanggal_presensi')
            ->get();
    }

    public function headings(): array
    {
        return ['No', 'Tanggal', 'Hari', 'Jam Masuk', 'Jam Keluar', 'Durasi Kerja', 'Status'];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        $masuk  = Carbon::parse($row->jam_masuk);
        $keluar = $row->jam_keluar ? Carbon::parse($row->jam_keluar) : null;
        $durasi = $keluar ? $masuk->diff($keluar)->format('%H:%I') : '-';

        $terlambat = $row->jam_masuk > $this->jamMasukBatas;

        return [
            $no,
            Carbon::parse($row->tanggal_presensi)->format('d/m/Y'),
            Carbon::parse($row->tanggal_presensi)->isoFormat('dddd'),
            Carbon::parse($row->jam_masuk)->format('H:i'),
            $keluar ? $keluar->format('H:i') : '-',
            $durasi,
            $terlambat ? 'Terlambat' : 'Tepat Waktu',
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
        return 'Presensi ' . Carbon::make($this->bulan)->isoFormat('MMMM YYYY');
    }
}
