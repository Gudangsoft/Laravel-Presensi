<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KoreksiPresensi extends Model
{
    protected $table    = 'koreksi_presensi';
    protected $fillable = ['karyawan_id', 'tanggal', 'jam_masuk', 'jam_keluar', 'keterangan', 'status', 'catatan_admin'];
    protected $casts    = ['tanggal' => 'date'];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function statusLabel(): string
    {
        return match ((int) $this->status) {
            0 => 'Pending',
            1 => 'Disetujui',
            2 => 'Ditolak',
            default => '-',
        };
    }

    public function statusColor(): string
    {
        return match ((int) $this->status) {
            0 => 'bg-amber-100 text-amber-700',
            1 => 'bg-emerald-100 text-emerald-700',
            2 => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-600',
        };
    }
}
