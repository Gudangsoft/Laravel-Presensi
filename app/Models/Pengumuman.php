<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table    = 'pengumuman';
    protected $fillable = ['judul', 'isi', 'is_active', 'is_pinned'];
    protected $casts    = ['is_active' => 'boolean', 'is_pinned' => 'boolean'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at');
    }
}
