<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Pengaturan Jam Kerja</h2>
                <p class="mt-0.5 text-sm text-gray-500">Atur jam masuk, jam pulang, dan toleransi keterlambatan</p>
            </div>
        </div>
    </x-slot>

    <div class="container mx-auto px-5 pt-6 pb-10 max-w-2xl">

        @if (session('success'))
            <div class="mb-5 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                <i class="ri-checkbox-circle-fill text-lg flex-shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.pengaturan.update') }}" method="POST">
            @csrf

            {{-- Jam Kerja --}}
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                <div class="flex items-center gap-3 border-b border-gray-100 px-6 py-4">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-100">
                        <i class="ri-time-line text-indigo-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">Jam Kerja</h3>
                        <p class="text-xs text-gray-400">Tentukan waktu masuk dan pulang karyawan</p>
                    </div>
                </div>
                <div class="px-6 py-6 space-y-5">

                    {{-- Jam Masuk --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">
                            <i class="ri-login-box-line text-indigo-400 mr-1"></i>
                            Jam Masuk
                        </label>
                        <input type="time" name="jam_masuk"
                               value="{{ \Carbon\Carbon::parse($pengaturan->jam_masuk)->format('H:i') }}"
                               class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition @error('jam_masuk') border-red-400 @enderror" />
                        @error('jam_masuk')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400">Karyawan diwajibkan hadir sebelum atau tepat pada jam ini</p>
                    </div>

                    {{-- Jam Pulang --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">
                            <i class="ri-logout-box-line text-red-400 mr-1"></i>
                            Jam Pulang
                        </label>
                        <input type="time" name="jam_pulang"
                               value="{{ \Carbon\Carbon::parse($pengaturan->jam_pulang)->format('H:i') }}"
                               class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition @error('jam_pulang') border-red-400 @enderror" />
                        @error('jam_pulang')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400">Batas waktu karyawan melakukan presensi pulang</p>
                    </div>

                    {{-- Toleransi --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">
                            <i class="ri-timer-line text-amber-400 mr-1"></i>
                            Toleransi Keterlambatan
                        </label>
                        <div class="relative">
                            <input type="number" name="toleransi" min="0" max="120"
                                   value="{{ $pengaturan->toleransi }}"
                                   class="w-full rounded-xl border border-gray-300 px-4 py-2.5 pr-16 text-sm text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition @error('toleransi') border-red-400 @enderror" />
                            <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-sm text-gray-400">menit</span>
                        </div>
                        @error('toleransi')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400">Karyawan dianggap terlambat jika melewati jam masuk + toleransi (0 = tidak ada toleransi)</p>
                    </div>
                </div>
            </div>

            {{-- Preview Ringkasan --}}
            <div class="mt-5 rounded-2xl border border-indigo-100 bg-indigo-50 px-6 py-5">
                <h4 class="mb-3 text-xs font-semibold uppercase tracking-wider text-indigo-600">Ringkasan Pengaturan Saat Ini</h4>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="rounded-xl bg-white px-4 py-3 shadow-sm">
                        <p class="text-xs text-gray-400 mb-1">Jam Masuk</p>
                        <p class="text-lg font-bold text-indigo-600">{{ \Carbon\Carbon::parse($pengaturan->jam_masuk)->format('H:i') }}</p>
                    </div>
                    <div class="rounded-xl bg-white px-4 py-3 shadow-sm">
                        <p class="text-xs text-gray-400 mb-1">Jam Pulang</p>
                        <p class="text-lg font-bold text-red-500">{{ \Carbon\Carbon::parse($pengaturan->jam_pulang)->format('H:i') }}</p>
                    </div>
                    <div class="rounded-xl bg-white px-4 py-3 shadow-sm">
                        <p class="text-xs text-gray-400 mb-1">Toleransi</p>
                        <p class="text-lg font-bold text-amber-500">{{ $pengaturan->toleransi }} mnt</p>
                    </div>
                </div>
                <p class="mt-3 text-xs text-indigo-500">
                    Karyawan dianggap terlambat jika presensi masuk setelah
                    <strong>{{ \Carbon\Carbon::parse($pengaturan->jam_masuk)->addMinutes($pengaturan->toleransi)->format('H:i') }}</strong>
                </p>
            </div>

            {{-- Submit --}}
            <div class="mt-5 flex justify-end">
                <button type="submit"
                        class="flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <i class="ri-save-line"></i>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
