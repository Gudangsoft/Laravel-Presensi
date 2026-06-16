@extends("dashboard.layouts.main")

@section("container")
<div class="space-y-4 max-w-lg mx-auto">

    <div class="flex items-center gap-3">
        <div class="flex h-11 w-11 items-center justify-center rounded-2xl"
             style="background:linear-gradient(135deg,#f59e0b,#d97706);">
            <i class="ri-edit-line text-lg text-white"></i>
        </div>
        <div>
            <h1 class="text-lg font-bold text-gray-800">Koreksi Presensi</h1>
            <p class="text-xs text-gray-400">Ajukan koreksi / lupa absen</p>
        </div>
    </div>

    @if (session('error'))
        <div class="flex items-center gap-2 rounded-2xl border border-red-200 bg-red-50 px-4 py-3">
            <i class="ri-error-warning-line text-red-500"></i>
            <p class="text-sm text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm space-y-4">

        <form action="{{ route('karyawan.koreksi.store') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tanggal Presensi</label>
                    <input type="date" name="tanggal" max="{{ date('Y-m-d') }}" value="{{ old('tanggal') }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition bg-gray-50">
                    @error('tanggal')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Jam Masuk <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <input type="time" name="jam_masuk" value="{{ old('jam_masuk') }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition bg-gray-50">
                        @error('jam_masuk')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Jam Keluar <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <input type="time" name="jam_keluar" value="{{ old('jam_keluar') }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition bg-gray-50">
                        @error('jam_keluar')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Keterangan / Alasan</label>
                    <textarea name="keterangan" rows="4" required placeholder="Jelaskan alasan koreksi presensi..."
                              class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition bg-gray-50 resize-none">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rounded-xl bg-amber-50 border border-amber-200 px-4 py-3 flex gap-2.5">
                    <i class="ri-information-line text-amber-500 flex-shrink-0 mt-0.5"></i>
                    <p class="text-xs text-amber-700">Pengajuan akan ditinjau oleh admin. Isi minimal salah satu dari jam masuk atau jam keluar.</p>
                </div>

                <div class="flex gap-3 pt-1">
                    <a href="{{ route('karyawan.koreksi') }}"
                       class="flex-1 flex items-center justify-center rounded-xl border border-gray-300 py-3 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="flex-1 flex items-center justify-center gap-2 rounded-xl py-3 text-sm font-bold text-white transition active:scale-95"
                            style="background:linear-gradient(135deg,#f59e0b,#d97706);">
                        <i class="ri-send-plane-line"></i> Kirim Pengajuan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
