<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Log Aktivitas';
        $query = ActivityLog::orderBy('created_at', 'desc');

        if ($request->aksi) {
            $query->where('action', $request->aksi);
        }
        if ($request->cari) {
            $query->where(function ($q) use ($request) {
                $q->where('user_name', 'like', '%' . $request->cari . '%')
                  ->orWhere('description', 'like', '%' . $request->cari . '%');
            });
        }
        if ($request->tanggal) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $logs    = $query->paginate(30);
        $actions = ActivityLog::distinct()->orderBy('action')->pluck('action');

        return view('admin.activity-log.index', compact('title', 'logs', 'actions'));
    }

    public function clear()
    {
        ActivityLog::where('created_at', '<', now()->subDays(90))->delete();
        return to_route('admin.activity-log')->with('success', 'Log lebih dari 90 hari telah dihapus.');
    }
}
