<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\HariLibur;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HariLiburController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Hari Libur';
        $query = HariLibur::orderBy('tanggal', 'asc');

        if ($request->tahun) {
            $query->whereYear('tanggal', $request->tahun);
        } else {
            $query->whereYear('tanggal', date('Y'))
                  ->orWhere('is_recurring', true);
        }

        $hariLibur = $query->paginate(20);
        $tahunList = range(date('Y') - 1, date('Y') + 2);

        return view('admin.hari-libur.index', compact('title', 'hariLibur', 'tahunList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'         => 'required|string|max:100',
            'tanggal'      => 'required|date',
            'is_recurring' => 'nullable|boolean',
        ]);
        $data['is_recurring'] = $request->boolean('is_recurring');

        HariLibur::create($data);
        ActivityLog::record('tambah_hari_libur', 'Menambahkan hari libur: ' . $data['nama'] . ' (' . $data['tanggal'] . ')');

        return to_route('admin.hari-libur')->with('success', 'Hari libur berhasil ditambahkan.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id'           => 'required|exists:hari_libur,id',
            'nama'         => 'required|string|max:100',
            'tanggal'      => 'required|date',
            'is_recurring' => 'nullable|boolean',
        ]);
        $data['is_recurring'] = $request->boolean('is_recurring');

        HariLibur::where('id', $data['id'])->update([
            'nama'         => $data['nama'],
            'tanggal'      => $data['tanggal'],
            'is_recurring' => $data['is_recurring'],
        ]);
        ActivityLog::record('ubah_hari_libur', 'Mengubah hari libur ID ' . $data['id'] . ': ' . $data['nama']);

        return to_route('admin.hari-libur')->with('success', 'Hari libur berhasil diperbarui.');
    }

    public function delete(Request $request)
    {
        $hl = HariLibur::findOrFail($request->id);
        ActivityLog::record('hapus_hari_libur', 'Menghapus hari libur: ' . $hl->nama . ' (' . $hl->tanggal->format('Y-m-d') . ')');
        $hl->delete();

        return response()->json(['success' => true, 'message' => 'Hari libur berhasil dihapus.']);
    }
}
