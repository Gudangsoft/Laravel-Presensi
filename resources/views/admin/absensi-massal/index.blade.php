@extends("admin.layouts.app")

@section("container")
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Absensi Massal</h1>
        <p class="text-sm text-gray-500 mt-1">Input presensi untuk semua karyawan dalam satu tanggal</p>
    </div>

    {{-- Flash --}}
    @if (session('success'))
        <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            <i class="ri-checkbox-circle-fill text-lg flex-shrink-0"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Filter --}}
    <form method="GET" action="{{ route('admin.absensi-massal') }}"
          class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
        <h3 class="mb-4 text-sm font-semibold text-gray-500 uppercase tracking-wider">Filter</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" max="{{ date('Y-m-d') }}"
                       class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors" />
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Departemen</label>
                <select name="departemen_id"
                        class="w-full appearance-none rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors">
                    <option value="">Semua Departemen</option>
                    @foreach ($departemen as $dep)
                        <option value="{{ $dep->id }}" {{ $departemenId == $dep->id ? 'selected' : '' }}>
                            {{ $dep->nama_departemen }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold text-white transition hover:-translate-y-0.5"
                        style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                    <i class="ri-filter-3-line"></i> Terapkan
                </button>
            </div>
        </div>
    </form>

    {{-- Info bar --}}
    <div class="flex items-center gap-3 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-700">
        <i class="ri-information-line text-lg flex-shrink-0"></i>
        <div>
            Tanggal: <strong>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</strong> &nbsp;·&nbsp;
            Total: <strong>{{ $karyawans->count() }} karyawan</strong> &nbsp;·&nbsp;
            Sudah hadir: <strong>{{ $existing->count() }}</strong>
        </div>
    </div>

    {{-- Absensi Form --}}
    @if ($karyawans->count())
        <form action="{{ route('admin.absensi-massal.store') }}" method="POST">
            @csrf
            <input type="hidden" name="tanggal" value="{{ $tanggal }}" />

            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-8">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Karyawan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-56">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">Jam Masuk</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">Jam Keluar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($karyawans as $i => $kar)
                                @php $ex = $existing->get($kar->id); @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-xs text-gray-400">{{ $i + 1 }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-gray-800">{{ $kar->nama_lengkap }}</div>
                                        <div class="text-xs text-gray-400">{{ $kar->departemen->nama_departemen ?? '-' }} · {{ $kar->jabatan }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex gap-3">
                                            <label class="flex items-center gap-1.5 cursor-pointer">
                                                <input type="radio" name="items[{{ $kar->id }}][status]" value="hadir"
                                                       {{ $ex ? 'checked' : '' }}
                                                       class="text-emerald-600 focus:ring-emerald-500" />
                                                <span class="text-xs font-medium text-emerald-700">Hadir</span>
                                            </label>
                                            <label class="flex items-center gap-1.5 cursor-pointer">
                                                <input type="radio" name="items[{{ $kar->id }}][status]" value="hapus"
                                                       {{ (!$ex) ? '' : '' }}
                                                       class="text-red-500 focus:ring-red-500" />
                                                <span class="text-xs font-medium text-red-600">Hapus</span>
                                            </label>
                                            <label class="flex items-center gap-1.5 cursor-pointer">
                                                <input type="radio" name="items[{{ $kar->id }}][status]" value="skip"
                                                       {{ !$ex ? 'checked' : '' }}
                                                       class="text-gray-400 focus:ring-gray-400" />
                                                <span class="text-xs font-medium text-gray-500">Skip</span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="time" name="items[{{ $kar->id }}][jam_masuk]"
                                               value="{{ $ex ? $ex->jam_masuk : $pengaturan->jam_masuk }}"
                                               class="w-full rounded-lg border border-gray-200 bg-gray-50 px-2 py-1.5 text-xs text-gray-700 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-300 transition-colors" />
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="time" name="items[{{ $kar->id }}][jam_keluar]"
                                               value="{{ $ex ? $ex->jam_keluar : '' }}"
                                               class="w-full rounded-lg border border-gray-200 bg-gray-50 px-2 py-1.5 text-xs text-gray-700 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-300 transition-colors" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Actions --}}
                <div class="border-t border-gray-100 flex items-center justify-between px-5 py-4 bg-gray-50">
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="setAllStatus('hadir')"
                                class="text-xs font-semibold text-emerald-600 hover:underline">Tandai Semua Hadir</button>
                        <span class="text-gray-200">|</span>
                        <button type="button" onclick="setAllStatus('skip')"
                                class="text-xs font-semibold text-gray-500 hover:underline">Reset Semua</button>
                    </div>
                    <button type="submit"
                            class="flex items-center gap-2 rounded-xl px-6 py-2.5 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5"
                            style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                        <i class="ri-save-line"></i> Simpan Absensi
                    </button>
                </div>
            </div>
        </form>
    @else
        <div class="rounded-2xl border border-gray-100 bg-white py-16 text-center shadow-sm">
            <i class="ri-user-search-line text-4xl text-gray-200 block mb-2"></i>
            <p class="text-sm font-medium text-gray-400">Tidak ada karyawan ditemukan</p>
        </div>
    @endif

</div>
@endsection

@section("js")
<script>
function setAllStatus(val) {
    document.querySelectorAll('input[type=radio][value="' + val + '"]').forEach(r => r.checked = true);
}
</script>
@endsection
