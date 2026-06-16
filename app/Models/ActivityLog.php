<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'user_name', 'action', 'description', 'ip_address'];

    public static function record(string $action, string $description): void
    {
        $user = Auth::user();
        static::create([
            'user_id'     => $user?->id,
            'user_name'   => $user?->name ?? 'System',
            'action'      => $action,
            'description' => $description,
            'ip_address'  => request()->ip(),
        ]);
    }
}
