<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class QrSession extends Model
{
    protected $table    = 'qr_sessions';
    protected $fillable = ['token', 'jenis', 'expires_at', 'used_at', 'karyawan_id', 'created_by'];
    protected $casts    = ['expires_at' => 'datetime', 'used_at' => 'datetime'];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->token = (string) Str::uuid();
        });
    }

    public function isExpired(): bool
    {
        return Carbon::now()->isAfter($this->expires_at);
    }

    public function isUsed(): bool
    {
        return !is_null($this->used_at);
    }

    public function isActive(): bool
    {
        return !$this->isExpired() && !$this->isUsed();
    }

    public function scanUrl(): string
    {
        return url('/karyawan/scan/' . $this->token);
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
}
