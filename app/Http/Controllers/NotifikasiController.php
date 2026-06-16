<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Notifikasi';
        $notifikasis = Notifikasi::orderBy('created_at', 'desc')->paginate(30);
        return view('admin.notifikasi.index', compact('title', 'notifikasis'));
    }

    public function unreadCount()
    {
        return response()->json(['count' => Notifikasi::unreadCount()]);
    }

    public function recent()
    {
        $items = Notifikasi::orderBy('created_at', 'desc')->limit(8)->get()
            ->map(fn($n) => [
                'id'         => $n->id,
                'type'       => $n->type,
                'title'      => $n->title,
                'message'    => $n->message,
                'url'        => $n->url,
                'unread'     => $n->isUnread(),
                'time'       => $n->created_at->diffForHumans(),
            ]);

        Notifikasi::whereNull('read_at')->update(['read_at' => Carbon::now()]);
        Notifikasi::clearCache();

        return response()->json(['items' => $items, 'unread' => 0]);
    }

    public function markAllRead()
    {
        Notifikasi::whereNull('read_at')->update(['read_at' => Carbon::now()]);
        Notifikasi::clearCache();
        return response()->json(['success' => true]);
    }
}
