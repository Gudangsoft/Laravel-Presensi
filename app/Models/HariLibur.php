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
        return static::where(function ($q) use ($month, $year) {
            $q->where(function ($q1) use ($month) {
                $q1->where('is_recurring', true)
                   ->whereMonth('tanggal', $month);
            })->orWhere(function ($q2) use ($month, $year) {
                $q2->where('is_recurring', false)
                   ->whereMonth('tanggal', $month)
                   ->whereYear('tanggal', $year);
            });
        })->get()
          ->map(fn($h) => $h->tanggal->format('Y-m-d'))
          ->values()
          ->toArray();
    }
}
