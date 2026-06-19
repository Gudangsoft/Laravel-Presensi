<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningScore extends Model
{
    protected $fillable = ['karyawan_id', 'level', 'score', 'correct', 'total', 'played_at'];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
