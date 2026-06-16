<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Hari Libur</h2>
                <p class="text-sm text-gray-500 mt-0.5">Kelola kalender hari libur nasional & perusahaan</p>
            </div>
            <button onclick="document.getElementById('modal_tambah').classList.remove('hidden')"
                    class="flex items-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 px-4 py-2.5 text-sm font-semibold text-white transition">
                <i class="ri-add-line text-base"></i> Tambah Hari Libur
            </button>
        </div>
    </x-slot>

    <div class="container mx-auto px-5 pt-5 pb-10 space-y-5">

        {{-- Flash --}}
        @if (session('success'))
            <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3">
                <i class="ri-checkbox-circle-line text-emerald-500 text-lg flex-shrink-0"></i>
                <p class="text-sm text-emerald-700">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Filter Tahun --}}
        <form method="get" class="flex items-center gap-3">
            <select name="tahun" onchange="this.form.submit()"
                    class="rounded-xl border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                @foreach ($tahunList as $t)
                    <option value="{{ $t }}" {{ request('tahun', date('Y')) == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
            <span class="text-xs text-gray-400">Semua hari libur berulang selalu ditampilkan</span>
        </form>

        {{-- Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ri-calendar-event-line text-indigo-600 text-lg"></i>
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Daftar Hari Libur</h3>
                </div>
                <span class="text-xs text-gray-400">{{ $hariLibur->total() }} entri</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipe</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($hariLibur as $i => $item)
                            <tr class="hover:bg-gray-50/60 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-400">{{ $hariLibur->firstItem() + $i }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0
                                            {{ $item->is_recurring ? 'bg-amber-100' : 'bg-indigo-100' }}">
                                            <i class="ri-calendar-event-fill text-sm {{ $item->is_recurring ? 'text-amber-600' : 'text-indigo-600' }}"></i>
                                        </div>
                                        <span class="font-semibold text-gray-800 text-sm">{{ $item->nama }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-700">
                                        {{ $item->tanggal->translatedFormat('d F') }}
                                        @if (!$item->is_recurring)
                                            {{ $item->tanggal->format('Y') }}
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-400">{{ $item->tanggal->translatedFormat('l') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($item->is_recurring)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-700">
                                            <i class="ri-repeat-line text-xs"></i> Berulang Tahunan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-700">
                                            <i class="ri-calendar-check-line text-xs"></i> Tanggal Tetap
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button onclick="editItem({{ $item->id }}, '{{ addslashes($item->nama) }}', '{{ $item->tanggal->format('Y-m-d') }}', {{ $item->is_recurring ? 'true' : 'false' }})"
                                                class="w-8 h-8 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-600 flex items-center justify-center transition">
                                            <i class="ri-edit-line text-sm"></i>
                                        </button>
                                        <button onclick="hapusItem({{ $item->id }}, '{{ addslashes($item->nama) }}')"
                                                class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center transition">
                                            <i class="ri-delete-bin-line text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center">
                                            <i class="ri-calendar-event-line text-2xl text-gray-400"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-500">Belum ada hari libur</p>
                                        <p class="text-xs text-gray-400">Klik "Tambah Hari Libur" untuk menambahkan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($hariLibur->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">{{ $hariLibur->links() }}</div>
            @endif
        </div>
    </div>

    {{-- ── Modal Tambah ── --}}
    <div id="modal_tambah" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="document.getElementById('modal_tambah').classList.add('hidden')"></div>
        <div class="relative z-10 w-full max-w-md rounded-2xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-100">
                        <i class="ri-calendar-add-line text-sm text-indigo-600"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-800">Tambah Hari Libur</h3>
                </div>
                <button onclick="document.getElementById('modal_tambah').classList.add('hidden')"
                        class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 transition-colors">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </div>
            <form action="{{ route('admin.hari-libur.store') }}" method="POST">
                @csrf
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Nama Hari Libur</label>
                        <input type="text" name="nama" required placeholder="cth. Hari Raya Idul Fitri"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Tanggal</label>
                        <input type="date" name="tanggal" required
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_recurring" value="1"
                               class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Berulang setiap tahun</p>
                            <p class="text-xs text-gray-400">Centang untuk hari libur nasional yang tanggalnya tetap tiap tahun</p>
                        </div>
                    </label>
                </div>
                <div class="flex items-center justify-end gap-2 border-t border-gray-100 px-6 py-4">
                    <button type="button" onclick="document.getElementById('modal_tambah').classList.add('hidden')"
                            class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit"
                            class="rounded-xl bg-indigo-600 hover:bg-indigo-700 px-5 py-2 text-sm font-medium text-white transition-colors">
                        <i class="ri-save-line mr-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Modal Edit ── --}}
    <div id="modal_edit" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="document.getElementById('modal_edit').classList.add('hidden')"></div>
        <div class="relative z-10 w-full max-w-md rounded-2xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100">
                        <i class="ri-edit-line text-sm text-amber-600"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-800">Edit Hari Libur</h3>
                </div>
                <button onclick="document.getElementById('modal_edit').classList.add('hidden')"
                        class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 transition-colors">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </div>
            <form action="{{ route('admin.hari-libur.update') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="edit_id">
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Nama Hari Libur</label>
                        <input type="text" name="nama" id="edit_nama" required
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Tanggal</label>
                        <input type="date" name="tanggal" id="edit_tanggal" required
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_recurring" id="edit_recurring" value="1"
                               class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Berulang setiap tahun</p>
                        </div>
                    </label>
                </div>
                <div class="flex items-center justify-end gap-2 border-t border-gray-100 px-6 py-4">
                    <button type="button" onclick="document.getElementById('modal_edit').classList.add('hidden')"
                            class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit"
                            class="rounded-xl bg-amber-500 hover:bg-amber-600 px-5 py-2 text-sm font-medium text-white transition-colors">
                        <i class="ri-save-line mr-1"></i>Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editItem(id, nama, tanggal, isRecurring) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_tanggal').value = tanggal;
            document.getElementById('edit_recurring').checked = isRecurring;
            document.getElementById('modal_edit').classList.remove('hidden');
        }

        function hapusItem(id, nama) {
            Swal.fire({
                title: 'Hapus Hari Libur?',
                html: '<p>Hapus <b>' + nama + '</b> dari daftar hari libur?</p>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
            }).then(result => {
                if (!result.isConfirmed) return;
                $.post('{{ route('admin.hari-libur.delete') }}', {
                    _token: '{{ csrf_token() }}',
                    id: id
                }, function(res) {
                    if (res.success) location.reload();
                    else Swal.fire({ icon: 'error', title: 'Gagal', text: res.message });
                });
            });
        }
    </script>
</x-app-layout>
