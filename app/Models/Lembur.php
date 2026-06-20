<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lembur extends Model
{
    protected $table = 'lembur';

    protected $fillable = [
        'karyawan_id', 'tanggal', 'jam_mulai', 'jam_selesai',
        'keterangan', 'status', 'catatan_admin',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function statusLabel(): string
    {
        return match ((int) $this->status) {
            1       => 'Disetujui',
            2       => 'Ditolak',
            default => 'Menunggu',
        };
    }

    public function statusColor(): string
    {
        return match ((int) $this->status) {
            1       => 'emerald',
            2       => 'red',
            default => 'amber',
        };
    }
}
