<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandSetting extends Model
{
    protected $table = 'brand_settings';

    protected $fillable = ['nama_aplikasi', 'tagline', 'logo', 'favicon', 'footer_text'];

    public function logoUrl(): ?string
    {
        return $this->logo ? asset('storage/brand/' . $this->logo) : null;
    }

    public function faviconUrl(): ?string
    {
        return $this->favicon ? asset('storage/brand/' . $this->favicon) : asset('img/favicon.png');
    }

    /** Local filesystem path for DomPDF image rendering */
    public function logoPath(): ?string
    {
        return $this->logo ? public_path('storage/brand/' . $this->logo) : null;
    }
}
