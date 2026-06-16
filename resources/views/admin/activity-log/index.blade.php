<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Log Aktivitas</h2>
                <p class="text-sm text-gray-500 mt-0.5">Riwayat semua aksi CRUD oleh admin</p>
            </div>
            <form action="{{ route('admin.activity-log.clear') }}" method="POST"
                  onsubmit="return confirm('Hapus log lebih dari 90 hari?')">
                @csrf
                <button type="submit"
                        class="flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 hover:bg-red-100 px-4 py-2.5 text-sm font-medium text-red-600 transition">
                    <i class="ri-delete-bin-line text-base"></i> Bersihkan Log Lama
                </button>
            </form>
        </div>
    </x-slot>

    <div class="container mx-auto px-5 pt-5 pb-10 space-y-5">

        @if (session('success'))
            <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3">
                <i class="ri-checkbox-circle-line text-emerald-500 text-lg flex-shrink-0"></i>
                <p class="text-sm text-emerald-700">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Filter --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <form method="get" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Cari</label>
                    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Nama admin / deskripsi..."
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Jenis Aksi</label>
                    <select name="aksi" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        <option value="">Semua Aksi</option>
                        @foreach ($actions as $aksi)
                            <option value="{{ $aksi }}" {{ request('aksi') === $aksi ? 'selected' : '' }}>
                                {{ str_replace('_', ' ', ucfirst($aksi)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                            class="flex-1 flex items-center justify-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 px-4 py-2.5 text-sm font-medium text-white transition">
                        <i class="ri-search-2-line"></i> Filter
                    </button>
                    <a href="{{ route('admin.activity-log') }}"
                       class="flex-1 flex items-center justify-center gap-2 rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                        <i class="ri-refresh-line"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ri-history-line text-indigo-600 text-lg"></i>
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Riwayat Aktivitas</h3>
                </div>
                <span class="text-xs text-gray-400">{{ $logs->total() }} entri</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Admin</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($logs as $log)
                            @php
                                $actionColors = [
                                    'tambah' => 'bg-emerald-100 text-emerald-700',
                                    'ubah'   => 'bg-amber-100 text-amber-700',
                                    'hapus'  => 'bg-red-100 text-red-700',
                                    'persetujuan' => 'bg-blue-100 text-blue-700',
                                    'bulk'   => 'bg-purple-100 text-purple-700',
                                ];
                                $colorKey = collect($actionColors)->keys()
                                    ->first(fn($k) => str_starts_with($log->action, $k));
                                $color = $actionColors[$colorKey] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <tr class="hover:bg-gray-50/60 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700">{{ $log->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $log->created_at->format('H:i:s') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                            <span class="text-indigo-600 font-semibold text-xs">{{ strtoupper(substr($log->user_name, 0, 1)) }}</span>
                                        </div>
                                        <span class="text-sm font-medium text-gray-800">{{ $log->user_name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $color }}">
                                        {{ str_replace('_', ' ', $log->action) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                                    <span class="line-clamp-2">{{ $log->description }}</span>
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-gray-400">{{ $log->ip_address ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center">
                                            <i class="ri-history-line text-2xl text-gray-400"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-500">Belum ada log aktivitas</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($logs->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">{{ $logs->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
