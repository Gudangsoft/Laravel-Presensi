<?php

namespace App\Http\Controllers;

use App\Exports\KaryawanExport;
use App\Exports\KaryawanTemplateExport;
use App\Imports\KaryawanImport;
use App\Models\ActivityLog;
use App\Models\Departemen;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class KaryawanController extends Controller
{
    public function index()
    {
        $title = "Profile";
        $karyawan = auth()->guard('karyawan')->user();
        return view('dashboard.profile.index', compact('title', 'karyawan'));
    }

    public function update(Request $request)
    {
        $karyawan = auth()->guard('karyawan')->user();

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'telepon'      => 'required|string|max:15',
            'email'        => ['required', 'email', Rule::unique('karyawan')->ignore($karyawan->id)],
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $foto = $karyawan->foto;

        // Cropped base64 avatar takes priority
        if ($request->filled('foto_cropped')) {
            $base64 = preg_replace('/^data:image\/\w+;base64,/', '', $request->foto_cropped);
            $foto   = $karyawan->id . '.jpg';
            Storage::put("public/unggah/karyawan/{$foto}", base64_decode($base64));
        } elseif ($request->hasFile('foto')) {
            $foto = $karyawan->id . '.' . $request->file('foto')->getClientOriginalExtension();
            $request->file('foto')->storeAs('public/unggah/karyawan/', $foto);
        }

        Karyawan::where('id', $karyawan->id)->update([
            'nama_lengkap' => $request->nama_lengkap,
            'telepon'      => $request->telepon,
            'email'        => $request->email,
            'foto'         => $foto,
            'updated_at'   => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password'      => 'required',
            'new_password'          => 'required|min:6|confirmed',
        ], [
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'new_password.min'       => 'Password minimal 6 karakter.',
        ]);

        $karyawan = auth()->guard('karyawan')->user();

        if (!Hash::check($request->current_password, $karyawan->password)) {
            return redirect()->back()->with('password_error', 'Password lama tidak sesuai.');
        }

        Karyawan::where('id', $karyawan->id)->update([
            'password'   => Hash::make($request->new_password),
            'updated_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('password_success', 'Password berhasil diubah.');
    }

    public function indexAdmin(Request $request)
    {
        $title = "Data Karyawan";

        $departemen = Departemen::withCount('karyawan')->get();

        $query = Karyawan::join('departemen as d', 'karyawan.departemen_id', '=', 'd.id')
            ->select('karyawan.*', 'd.kode')
            ->orderBy('d.kode', 'asc')
            ->orderBy('karyawan.nama_lengkap', 'asc');

        if ($request->nama_karyawan) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }
        if ($request->kode_departemen) {
            $query->where('d.kode', 'like', '%' . $request->kode_departemen . '%');
        }

        $perPage = in_array((int) $request->input('per_page', 10), [10, 25, 50, 100]) ? (int) $request->input('per_page', 10) : 10;
        $karyawan = $query->paginate($perPage)->withQueryString();

        return view('admin.karyawan.index', compact('title', 'karyawan', 'departemen'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'departemen_id' => 'required',
            'nama_lengkap'  => 'required|string|max:255',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'jabatan'       => 'required|string|max:255',
            'telepon'       => 'required|string|max:15',
            'email'         => 'required|string|email|max:255|unique:karyawan,email',
            'password'      => 'required',
        ]);
        $data['password'] = Hash::make($data['password']);
        unset($data['foto']);

        $create = Karyawan::create($data);

        if ($create) {
            if ($request->hasFile('foto')) {
                $foto = $create->id . "." . $request->file('foto')->getClientOriginalExtension();
                $create->update(['foto' => $foto]);
                $request->file('foto')->storeAs("public/unggah/karyawan/", $foto);
            }
            ActivityLog::record('tambah_karyawan', 'Menambahkan karyawan: ' . $create->nama_lengkap);
            return to_route('admin.karyawan')->with('success', 'Data Karyawan berhasil disimpan');
        } else {
            return to_route('admin.karyawan')->with('error', 'Data Karyawan gagal disimpan');
        }
    }

    public function edit(Request $request)
    {
        $data = Karyawan::where('id', $request->id)->first();
        return $data;
    }

    public function updateAdmin(Request $request)
    {
        $karyawan = Karyawan::where('id', $request->id)->first();

        $data = $request->validate([
            'departemen_id' => 'required',
            'nama_lengkap'  => 'required|string|max:255',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'jabatan'       => 'required|string|max:255',
            'telepon'       => 'required|string|max:15',
            'email'         => ['required', 'email', Rule::unique('karyawan')->ignore($karyawan)],
            'password'      => 'nullable|string|min:6',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $karyawan->id . "." . $request->file('foto')->getClientOriginalExtension();
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $update = Karyawan::where('id', $request->id)->update($data);

        if ($update) {
            if ($request->hasFile('foto')) {
                $request->file('foto')->storeAs("public/unggah/karyawan/", $data['foto']);
            }
            ActivityLog::record('ubah_karyawan', 'Memperbarui karyawan: ' . $karyawan->nama_lengkap);
            return to_route('admin.karyawan')->with('success', 'Data Karyawan berhasil diperbarui');
        } else {
            return to_route('admin.karyawan')->with('error', 'Data Karyawan gagal diperbarui');
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'id'           => 'required|exists:karyawan,id',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'new_password.min'       => 'Password minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $karyawan = Karyawan::findOrFail($request->id);
        $karyawan->update(['password' => Hash::make($request->new_password)]);

        ActivityLog::record('reset_password_karyawan', 'Reset password karyawan: ' . $karyawan->nama_lengkap);

        return response()->json(['success' => true, 'message' => 'Password karyawan berhasil direset.']);
    }

    public function delete(Request $request)
    {
        $data   = Karyawan::where('id', $request->id)->first();
        $delete = Karyawan::where('id', $request->id)->delete();

        if ($delete && $data->foto) {
            Storage::delete("public/unggah/karyawan/" . $data->foto);
        }

        if ($delete) {
            ActivityLog::record('hapus_karyawan', 'Menghapus karyawan: ' . ($data->nama_lengkap ?? 'ID ' . $request->id));
            return response()->json(['success' => true,  'message' => 'Data Karyawan Berhasil dihapus']);
        } else {
            return response()->json(['success' => false, 'message' => 'Data Karyawan Gagal dihapus']);
        }
    }

    public function export(Request $request)
    {
        $departemenId = $request->departemen_id ? (int) $request->departemen_id : null;
        return Excel::download(new KaryawanExport($departemenId), 'data-karyawan-' . date('Y-m-d') . '.xlsx');
    }

    public function template()
    {
        return Excel::download(new KaryawanTemplateExport(), 'template-import-karyawan.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ], [
            'file.required' => 'Pilih file Excel terlebih dahulu.',
            'file.mimes'    => 'Format file harus xlsx, xls, atau csv.',
            'file.max'      => 'Ukuran file maksimal 2MB.',
        ]);

        $import = new KaryawanImport();
        Excel::import($import, $request->file('file'));

        $failures   = $import->failures();
        $exceptions = $import->errors();
        $totalGagal = $failures->count() + count($exceptions);

        if ($totalGagal > 0) {
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            foreach ($exceptions as $e) {
                $errorMessages[] = $e->getMessage();
            }
            return to_route('admin.karyawan')
                ->with('import_errors', $errorMessages)
                ->with('error', 'Import selesai dengan ' . $totalGagal . ' baris gagal.');
        }

        return to_route('admin.karyawan')->with('success', 'Data karyawan berhasil diimport.');
    }
}
