<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $pengaturan = Pengaturan::first();
        return view('admin.pengaturan.index', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'jam_masuk'  => 'required',
            'jam_pulang' => 'required',
            'toleransi'  => 'required|integer|min:0|max:120',
        ]);

        Pengaturan::first()->update([
            'jam_masuk'  => $request->jam_masuk,
            'jam_pulang' => $request->jam_pulang,
            'toleransi'  => $request->toleransi,
        ]);

        return redirect()->back()->with('success', 'Pengaturan jam kerja berhasil disimpan');
    }
}
