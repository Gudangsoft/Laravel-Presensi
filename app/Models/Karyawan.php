<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Karyawan extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = "karyawan";
    protected $guard = "karyawan";

    protected $fillable = [
        'departemen_id',
        'nama_lengkap',
        'foto',
        'jabatan',
        'telepon',
        'tanggal_lahir',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password'      => 'hashed',
            'tanggal_lahir' => 'date',
        ];
    }

    public function departemen()
    {
        return $this->belongsTo(Departemen::class);
    }
}
