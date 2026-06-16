<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table    = 'notifikasi';
    protected $fillable = ['type', 'title', 'message', 'url', 'read_at'];
    protected $casts    = ['read_at' => 'datetime'];

    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    public static function send(string $title, string $message, string $type = 'info', ?string $url = null): void
    {
        static::create(compact('title', 'message', 'type', 'url'));
    }

    public static function unreadCount(): int
    {
        return static::whereNull('read_at')->count();
    }
}
