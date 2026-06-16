<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        $title      = 'Pengumuman';
        $pengumuman = Pengumuman::orderByDesc('is_pinned')->orderByDesc('created_at')->paginate(20);
        return view('admin.pengumuman.index', compact('title', 'pengumuman'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi'   => 'required|string',
        ]);

        $p = Pengumuman::create([
            'judul'     => $request->judul,
            'isi'       => $request->isi,
            'is_active' => true,
            'is_pinned' => $request->boolean('is_pinned'),
        ]);

        ActivityLog::record('tambah_pengumuman', 'Menambahkan pengumuman: ' . $p->judul);

        return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'    => 'required|exists:pengumuman,id',
            'judul' => 'required|string|max:255',
            'isi'   => 'required|string',
        ]);

        $p = Pengumuman::findOrFail($request->id);
        $p->update([
            'judul'     => $request->judul,
            'isi'       => $request->isi,
            'is_pinned' => $request->boolean('is_pinned'),
        ]);

        ActivityLog::record('ubah_pengumuman', 'Memperbarui pengumuman: ' . $p->judul);

        return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function delete(Request $request)
    {
        $p     = Pengumuman::findOrFail($request->id);
        $judul = $p->judul;
        $p->delete();

        ActivityLog::record('hapus_pengumuman', 'Menghapus pengumuman: ' . $judul);

        return response()->json(['success' => true]);
    }

    public function toggle(Request $request)
    {
        $p = Pengumuman::findOrFail($request->id);
        $p->update(['is_active' => !$p->is_active]);
        return response()->json(['success' => true, 'is_active' => $p->is_active]);
    }
}
