<?php

namespace App\Http\Controllers;

use App\Models\BrandSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandSettingController extends Controller
{
    public function index()
    {
        $brand = BrandSetting::first();
        return view('admin.brand.index', compact('brand'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_aplikasi' => 'required|string|max:100',
            'tagline'       => 'nullable|string|max:200',
            'footer_text'   => 'nullable|string|max:200',
            'logo'          => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'favicon'       => 'nullable|image|mimes:jpeg,png,jpg,ico,svg|max:512',
        ]);

        $brand = BrandSetting::first();

        $data = [
            'nama_aplikasi' => $request->nama_aplikasi,
            'tagline'       => $request->tagline,
            'footer_text'   => $request->footer_text,
        ];

        if ($request->hasFile('logo')) {
            if ($brand->logo) {
                Storage::delete('public/brand/' . $brand->logo);
            }
            $file        = $request->file('logo');
            $filename    = 'logo.' . $file->getClientOriginalExtension();
            $file->storeAs('public/brand', $filename);
            $data['logo'] = $filename;
        }

        if ($request->boolean('hapus_logo')) {
            Storage::delete('public/brand/' . $brand->logo);
            $data['logo'] = null;
        }

        if ($request->hasFile('favicon')) {
            if ($brand->favicon) {
                Storage::delete('public/brand/' . $brand->favicon);
            }
            $file          = $request->file('favicon');
            $filename      = 'favicon.' . $file->getClientOriginalExtension();
            $file->storeAs('public/brand', $filename);
            $data['favicon'] = $filename;
        }

        if ($request->boolean('hapus_favicon')) {
            Storage::delete('public/brand/' . $brand->favicon);
            $data['favicon'] = null;
        }

        $brand->update($data);

        return redirect()->back()->with('success', 'Pengaturan brand berhasil disimpan.');
    }
}
