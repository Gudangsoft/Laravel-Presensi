<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Koreksi Presensi</h2>
                <p class="text-sm text-gray-500 mt-0.5">Tinjau pengajuan koreksi / lupa absen dari karyawan</p>
            </div>
        </div>
    </x-slot>

    <div class="container mx-auto px-5 pt-5 pb-10">

        {{-- Filters --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
            <form method="GET" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[160px]">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Cari Karyawan</label>
                    <div class="relative">
                        <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Nama karyawan..."
                               class="w-full border border-gray-300 rounded-xl pl-9 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                </div>
                <div class="min-w-[140px]">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                        <option value="">Semua</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Disetujui</option>
                        <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <button type="submit"
                        class="flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold text-white transition"
                        style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                    <i class="ri-filter-line"></i> Filter
                </button>
                @if (request('cari') || request('status') !== null && request('status') !== '')
                    <a href="{{ route('admin.koreksi-presensi') }}"
                       class="flex items-center gap-1.5 rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                        <i class="ri-close-line"></i> Reset
                    </a>
                @endif
            </form>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3.5 mb-6">
                <i class="ri-checkbox-circle-line text-emerald-500"></i>
                <p class="text-sm text-emerald-800">{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="flex items-center gap-2 rounded-2xl border border-red-200 bg-red-50 px-5 py-3.5 mb-6">
                <i class="ri-error-warning-line text-red-500"></i>
                <p class="text-sm text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Karyawan</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jam Masuk</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jam Keluar</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Keterangan</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Diajukan</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($koreksis as $k)
                            @php
                                $badgeCls = match($k->status) {
                                    1 => 'bg-emerald-100 text-emerald-700',
                                    2 => 'bg-red-100 text-red-700',
                                    default => 'bg-amber-100 text-amber-700',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50/60 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                            <span class="text-xs font-bold text-indigo-600">
                                                {{ strtoupper(substr($k->karyawan->nama_lengkap ?? '?', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-800">{{ $k->karyawan->nama_lengkap ?? '-' }}</div>
                                            <div class="text-xs text-gray-400">{{ $k->karyawan->departemen->nama_departemen ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($k->tanggal)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-5 py-4 text-sm font-mono text-gray-700">
                                    {{ $k->jam_masuk ?? '<span class="text-gray-300">—</span>' }}
                                </td>
                                <td class="px-5 py-4 text-sm font-mono text-gray-700">
                                    {{ $k->jam_keluar ?? '<span class="text-gray-300">—</span>' }}
                                </td>
                                <td class="px-5 py-4 text-xs text-gray-600 max-w-[200px]">
                                    <span class="line-clamp-2" title="{{ $k->keterangan }}">{{ $k->keterangan }}</span>
                                </td>
                                <td class="px-5 py-4 text-xs text-gray-400">
                                    {{ $k->created_at->format('d M H:i') }}
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-bold {{ $badgeCls }}">
                                        {{ $k->statusLabel() }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    @if ($k->status == 0)
                                        <div class="flex items-center justify-end gap-2">
                                            <button onclick="doApprove({{ $k->id }})"
                                                    class="flex items-center gap-1 rounded-lg bg-emerald-50 border border-emerald-200 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 transition">
                                                <i class="ri-check-line"></i> Setujui
                                            </button>
                                            <button onclick="doReject({{ $k->id }})"
                                                    class="flex items-center gap-1 rounded-lg bg-red-50 border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-100 transition">
                                                <i class="ri-close-line"></i> Tolak
                                            </button>
                                        </div>
                                    @else
                                        @if ($k->catatan_admin)
                                            <span class="text-xs text-gray-400 italic" title="{{ $k->catatan_admin }}">
                                                {{ Str::limit($k->catatan_admin, 30) }}
                                            </span>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-16 text-center text-gray-400">
                                    <i class="ri-edit-line text-4xl mb-2 block"></i>
                                    <p class="text-sm">Tidak ada pengajuan koreksi</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($koreksis->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">{{ $koreksis->links() }}</div>
            @endif
        </div>
    </div>

    {{-- Reject Modal --}}
    <div id="reject-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">
            <h3 class="text-base font-bold text-gray-800 mb-1">Tolak Koreksi</h3>
            <p class="text-sm text-gray-500 mb-4">Isi catatan penolakan (opsional)</p>
            <textarea id="catatan-admin" rows="3" placeholder="Alasan penolakan..."
                      class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-400 outline-none transition resize-none"></textarea>
            <div class="flex gap-3 mt-4">
                <button onclick="closeRejectModal()"
                        class="flex-1 rounded-xl border border-gray-300 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                    Batal
                </button>
                <button onclick="submitReject()"
                        class="flex-1 rounded-xl py-2.5 text-sm font-bold text-white transition"
                        style="background:linear-gradient(135deg,#ef4444,#dc2626);">
                    Konfirmasi Tolak
                </button>
            </div>
        </div>
    </div>

    <script>
        let rejectId = null;

        function doApprove(id) {
            Swal.fire({
                title: 'Setujui Koreksi?',
                text: 'Data presensi akan diperbarui sesuai pengajuan.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Ya, Setujui',
                cancelButtonText: 'Batal',
            }).then(r => {
                if (!r.isConfirmed) return;
                submitForm('{{ route('admin.koreksi-presensi.approve') }}', { id });
            });
        }

        function doReject(id) {
            rejectId = id;
            document.getElementById('catatan-admin').value = '';
            document.getElementById('reject-modal').classList.replace('hidden', 'flex');
        }

        function closeRejectModal() {
            document.getElementById('reject-modal').classList.replace('flex', 'hidden');
            rejectId = null;
        }

        function submitReject() {
            const catatan = document.getElementById('catatan-admin').value.trim();
            submitForm('{{ route('admin.koreksi-presensi.reject') }}', { id: rejectId, catatan_admin: catatan });
        }

        function submitForm(action, data) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = action;
            form.innerHTML = `<input name="_token" value="{{ csrf_token() }}">`;
            Object.entries(data).forEach(([k, v]) => {
                const el = document.createElement('input');
                el.name = k; el.value = v;
                form.appendChild(el);
            });
            document.body.appendChild(form);
            form.submit();
        }

        // Close modal on backdrop click
        document.getElementById('reject-modal').addEventListener('click', function(e) {
            if (e.target === this) closeRejectModal();
        });
    </script>
</x-app-layout>
