<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class HariLibur extends Model
{
    protected $table    = 'hari_libur';
    protected $fillable = ['nama', 'tanggal', 'is_recurring'];
    protected $casts    = ['tanggal' => 'date', 'is_recurring' => 'boolean'];

    public static function isHoliday(string $date): bool
    {
        $c = Carbon::parse($date);
        return static::where(function ($q) use ($c, $date) {
            $q->where('tanggal', $date)
              ->orWhere(function ($q2) use ($c) {
                  $q2->where('is_recurring', true)
                     ->whereMonth('tanggal', $c->month)
                     ->whereDay('tanggal', $c->day);
              });
        })->exists();
    }

    public static function holidayDatesInMonth(int $month, int $year): array
    {
        return static::get()->filter(function ($h) use ($month, $year) {
            if ($h->is_recurring) {
                return $h->tanggal->month === $month;
            }
            return $h->tanggal->month === $month && $h->tanggal->year === $year;
        })->map(fn($h) => $h->tanggal->format('Y-m-d'))->values()->toArray();
    }
}
