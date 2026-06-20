<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800">Monitoring Keterlambatan</h2>
            <form method="GET" action="{{ route('admin.monitoring.keterlambatan') }}" class="flex items-center gap-2">
                <select name="bulan" class="rounded-xl border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" @selected($m == $bulan)>
                            {{ \Carbon\Carbon::create(null,$m)->isoFormat('MMMM') }}
                        </option>
                    @endforeach
                </select>
                <select name="tahun" class="rounded-xl border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    @foreach(range(now()->year, now()->year-2) as $y)
                        <option value="{{ $y }}" @selected($y == $tahun)>{{ $y }}</option>
                    @endforeach
                </select>
                <button type="submit" class="rounded-xl bg-indigo-600 text-white px-4 py-2 text-sm font-semibold hover:bg-indigo-700 transition-colors">
                    Tampilkan
                </button>
            </form>
        </div>
    </x-slot>

    <div class="container mx-auto px-5 pt-5 pb-10">

        {{-- Summary --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
                <p class="text-3xl font-black text-red-600">{{ $data->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">Karyawan Pernah Terlambat</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
                <p class="text-3xl font-black text-orange-500">{{ $data->sum('total_terlambat') }}</p>
                <p class="text-xs text-gray-500 mt-1">Total Kejadian Terlambat</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
                <p class="text-3xl font-black text-indigo-600">{{ \Carbon\Carbon::create($tahun, $bulan)->isoFormat('MMM Y') }}</p>
                <p class="text-xs text-gray-500 mt-1">Periode</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Karyawan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Departemen</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Hadir</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Terlambat</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Detail Keterlambatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($data as $i => $row)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-400">{{ $i + 1 }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $row->foto ? asset('storage/unggah/karyawan/'.$row->foto) : 'https://ui-avatars.com/api/?name='.urlencode($row->nama_lengkap).'&background=6366f1&color=fff&size=32' }}"
                                         class="w-8 h-8 rounded-full object-cover" alt="">
                                    <span class="text-sm font-semibold text-gray-800">{{ $row->nama_lengkap }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $row->departemen }}</td>
                            <td class="px-4 py-3 text-center text-sm font-semibold text-gray-700">{{ $row->total_hadir }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold
                                    {{ $row->total_terlambat >= 5 ? 'bg-red-100 text-red-700' : ($row->total_terlambat >= 3 ? 'bg-orange-100 text-orange-700' : 'bg-amber-100 text-amber-700') }}">
                                    {{ $row->total_terlambat }}x
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500 max-w-xs">{{ $row->detail_terlambat }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                                <i class="ri-checkbox-circle-line text-4xl text-emerald-400 block mb-2"></i>
                                Tidak ada keterlambatan pada periode ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
