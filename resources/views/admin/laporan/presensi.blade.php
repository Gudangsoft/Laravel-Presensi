<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __("Laporan Presensi") }}
            </h2>
        </div>
    </x-slot>

    <div class="container mx-auto px-5 pt-6 pb-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Card 1: Laporan Per Karyawan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center">
                        <i class="ri-user-line text-2xl text-indigo-600"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-800">Laporan Per Karyawan</h3>
                        <p class="text-sm text-gray-500">Export laporan presensi satu karyawan</p>
                    </div>
                </div>

                <form action="{{ route("admin.laporan.presensi.karyawan") }}" method="post" target="_blank" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Bulan</label>
                            <input
                                type="month"
                                name="bulan"
                                value="{{ Carbon\Carbon::now()->format("Y-m") }}"
                                required
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Karyawan</label>
                            <select
                                name="karyawan"
                                required
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition bg-white"
                            >
                                <option disabled selected value="">-- Pilih karyawan --</option>
                                @foreach ($karyawan as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit"
                                class="flex-1 flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl px-4 py-2.5 text-sm font-medium transition">
                                <i class="ri-file-pdf-line text-base"></i> PDF
                            </button>
                            <button type="submit"
                                formaction="{{ route('admin.laporan.presensi.karyawan.excel') }}"
                                class="flex-1 flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl px-4 py-2.5 text-sm font-medium transition">
                                <i class="ri-file-excel-line text-base"></i> Excel
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Card 2: Laporan Semua Karyawan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center">
                        <i class="ri-team-line text-2xl text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-800">Laporan Semua Karyawan</h3>
                        <p class="text-sm text-gray-500">Export laporan presensi seluruh karyawan</p>
                    </div>
                </div>

                <form action="{{ route("admin.laporan.presensi.semua-karyawan") }}" method="post" target="_blank" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Bulan</label>
                            <input
                                type="month"
                                name="bulan"
                                value="{{ Carbon\Carbon::now()->format("Y-m") }}"
                                required
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                            />
                        </div>
                        <div class="flex gap-2">
                            <button type="submit"
                                class="flex-1 flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl px-4 py-2.5 text-sm font-medium transition">
                                <i class="ri-file-pdf-line text-base"></i> PDF
                            </button>
                            <button type="submit"
                                formaction="{{ route('admin.laporan.presensi.semua-karyawan.excel') }}"
                                class="flex-1 flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl px-4 py-2.5 text-sm font-medium transition">
                                <i class="ri-file-excel-line text-base"></i> Excel
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
