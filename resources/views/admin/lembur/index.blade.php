<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800">Manajemen Lembur</h2>
            <form method="GET" action="{{ route('admin.lembur') }}" class="flex items-center gap-2">
                <select name="status" onchange="this.form.submit()"
                    class="rounded-xl border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="">Semua Status</option>
                    <option value="0" @selected(request('status')==='0')>Menunggu</option>
                    <option value="1" @selected(request('status')==='1')>Disetujui</option>
                    <option value="2" @selected(request('status')==='2')>Ditolak</option>
                </select>
            </form>
        </div>
    </x-slot>

    <div class="container mx-auto px-5 pt-5 pb-10">

        @if(session('success'))
            <div class="mb-4 flex items-center gap-2 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
                <i class="ri-checkbox-circle-line"></i> {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Karyawan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Jam</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Keterangan</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($lembur as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $item->karyawan->foto ? asset('storage/unggah/karyawan/'.$item->karyawan->foto) : 'https://ui-avatars.com/api/?name='.urlencode($item->karyawan->nama_lengkap).'&background=6366f1&color=fff&size=32' }}"
                                         class="w-8 h-8 rounded-full object-cover" alt="">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">{{ $item->karyawan->nama_lengkap }}</p>
                                        <p class="text-xs text-gray-400">{{ $item->karyawan->departemen->nama ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('D MMM Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ substr($item->jam_mulai,0,5) }} – {{ substr($item->jam_selesai,0,5) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 max-w-xs">
                                <p class="line-clamp-2">{{ $item->keterangan }}</p>
                                @if($item->catatan_admin)
                                    <p class="text-xs text-indigo-500 italic mt-0.5">{{ $item->catatan_admin }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold
                                    bg-{{ $item->statusColor() }}-100 text-{{ $item->statusColor() }}-700">
                                    {{ $item->statusLabel() }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($item->status == 0)
                                <div class="flex items-center justify-center gap-2">
                                    <form method="POST" action="{{ route('admin.lembur.approve') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <button type="submit" class="rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 px-3 py-1.5 text-xs font-semibold hover:bg-emerald-100 transition-colors">
                                            <i class="ri-check-line"></i> Setujui
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.lembur.reject') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <button type="submit" class="rounded-lg bg-red-50 border border-red-200 text-red-700 px-3 py-1.5 text-xs font-semibold hover:bg-red-100 transition-colors">
                                            <i class="ri-close-line"></i> Tolak
                                        </button>
                                    </form>
                                </div>
                                @else
                                <p class="text-center text-xs text-gray-300">—</p>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                                <i class="ri-inbox-line text-4xl block mb-2"></i>
                                Tidak ada pengajuan lembur
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($lembur->hasPages())
            <div class="border-t border-gray-100 px-4 py-3">
                {{ $lembur->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
