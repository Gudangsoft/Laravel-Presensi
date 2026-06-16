<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Administrasi Presensi</h2>
                <p class="text-sm text-gray-500 mt-0.5">Kelola pengajuan izin dan sakit karyawan</p>
            </div>
        </div>
    </x-slot>

    <div class="container mx-auto px-5 pt-5 pb-10 space-y-5">

        {{-- Filter Panel --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-2 mb-4">
                <i class="ri-filter-3-line text-indigo-600 text-lg"></i>
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Filter Data</h3>
            </div>
            <form action="{{ route("admin.administrasi-presensi") }}" method="get" enctype="multipart/form-data">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    {{-- Nama Karyawan --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Nama Karyawan</label>
                        <input type="text" name="karyawan" placeholder="Cari nama..." value="{{ request()->karyawan }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" />
                    </div>
                    {{-- Departemen --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Departemen</label>
                        <select name="departemen"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition bg-white">
                            <option value="0">Semua Departemen</option>
                            @foreach ($departemen as $item)
                                <option value="{{ $item->id }}" {{ request()->departemen == $item->id ? "selected" : "" }}>{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Tanggal Awal --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Tanggal Awal</label>
                        <input type="date" name="tanggal_awal"
                            value="{{ request()->tanggal_awal ? request()->tanggal_awal : \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" />
                    </div>
                    {{-- Tanggal Akhir --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir"
                            value="{{ request()->tanggal_akhir ? request()->tanggal_akhir : \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d') }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" />
                    </div>
                    {{-- Status --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Jenis Pengajuan</label>
                        <select name="status"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition bg-white">
                            <option value="0">Semua Status</option>
                            <option value="I" {{ request()->status == 'I' ? "selected" : "" }}>Izin</option>
                            <option value="S" {{ request()->status == 'S' ? "selected" : "" }}>Sakit</option>
                        </select>
                    </div>
                    {{-- Status Approved --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Status Persetujuan</label>
                        <select name="status_approved"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition bg-white">
                            <option value="0">Semua Aksi</option>
                            <option value="1" {{ request()->status_approved == 1 ? "selected" : "" }}>Pending</option>
                            <option value="2" {{ request()->status_approved == 2 ? "selected" : "" }}>Diterima</option>
                            <option value="3" {{ request()->status_approved == 3 ? "selected" : "" }}>Ditolak</option>
                        </select>
                    </div>
                    {{-- Buttons --}}
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl px-4 py-2.5 text-sm font-medium flex items-center justify-center gap-2 transition">
                            <i class="ri-search-2-line text-base"></i>
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('admin.administrasi-presensi') }}"
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl px-4 py-2.5 text-sm font-medium flex items-center justify-center gap-2 transition">
                            <i class="ri-refresh-line text-base"></i>
                            <span>Reset</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Bulk Action Bar (hidden by default) --}}
        <div id="bulk-bar" class="hidden sticky top-4 z-10 flex items-center gap-3 rounded-2xl border border-indigo-200 bg-indigo-50 px-5 py-3 shadow-md">
            <div class="flex items-center gap-2 flex-1">
                <i class="ri-checkbox-multiple-line text-indigo-600 text-lg"></i>
                <span id="bulk-count" class="text-sm font-semibold text-indigo-700">0 dipilih</span>
            </div>
            <button onclick="doBulk('terima')"
                    class="flex items-center gap-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-4 py-2 text-xs font-semibold text-white transition">
                <i class="ri-checkbox-circle-line"></i> Terima Semua
            </button>
            <button onclick="doBulk('tolak')"
                    class="flex items-center gap-1.5 rounded-xl bg-red-500 hover:bg-red-600 px-4 py-2 text-xs font-semibold text-white transition">
                <i class="ri-close-circle-line"></i> Tolak Semua
            </button>
            <button onclick="clearSelection()"
                    class="flex h-8 w-8 items-center justify-center rounded-lg text-indigo-400 hover:bg-indigo-100 transition">
                <i class="ri-close-line text-base"></i>
            </button>
        </div>

        {{-- Table Panel --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ri-file-list-3-line text-indigo-600 text-lg"></i>
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Daftar Pengajuan</h3>
                </div>
                <span class="text-xs text-gray-400">{{ $pengajuan->total() }} data ditemukan</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-4 py-3 w-10">
                                <input type="checkbox" id="select-all" title="Pilih semua pending"
                                       class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer"
                                       onchange="toggleSelectAll(this)">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Karyawan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Departemen</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Keterangan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($pengajuan as $value => $item)
                            <tr class="hover:bg-gray-50/60 transition-colors" data-id="{{ $item->id }}" data-status="{{ $item->status_approved }}">
                                {{-- Checkbox (only pending) --}}
                                <td class="px-4 py-4">
                                    @if ($item->status_approved == 1)
                                        <input type="checkbox" value="{{ $item->id }}"
                                               class="row-check w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer"
                                               onchange="updateBulkBar()">
                                    @endif
                                </td>
                                {{-- No --}}
                                <td class="px-6 py-4 text-sm font-semibold text-gray-400">
                                    {{ $pengajuan->firstItem() + $value }}
                                </td>
                                {{-- Karyawan --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                            <span class="text-indigo-600 font-semibold text-sm">
                                                {{ strtoupper(substr($item->nama_karyawan, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-800">{{ $item->nama_karyawan }}</div>
                                        </div>
                                    </div>
                                </td>
                                {{-- Departemen --}}
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $item->nama_departemen }}</td>
                                {{-- Tanggal --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-700 font-medium">
                                        {{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-0.5">
                                        {{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('l') }}
                                    </div>
                                </td>
                                {{-- Jenis --}}
                                <td class="px-6 py-4">
                                    @if ($item->status == 'I')
                                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-700">
                                            <i class="ri-calendar-check-line text-xs"></i> Izin
                                        </span>
                                    @elseif ($item->status == 'S')
                                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium bg-red-100 text-red-700">
                                            <i class="ri-heart-pulse-line text-xs"></i> Sakit
                                        </span>
                                    @endif
                                </td>
                                {{-- Keterangan --}}
                                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                                    <span class="line-clamp-2">{{ $item->keterangan ?: '-' }}</span>
                                </td>
                                {{-- Status Approved --}}
                                <td class="px-6 py-4">
                                    @if ($item->status_approved == 1)
                                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium bg-amber-100 text-amber-700">
                                            <i class="ri-time-line text-xs"></i> Pending
                                        </span>
                                    @elseif ($item->status_approved == 2)
                                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium bg-emerald-100 text-emerald-700">
                                            <i class="ri-checkbox-circle-line text-xs"></i> Disetujui
                                        </span>
                                    @elseif ($item->status_approved == 3)
                                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium bg-red-100 text-red-700">
                                            <i class="ri-close-circle-line text-xs"></i> Ditolak
                                        </span>
                                    @endif
                                </td>
                                {{-- Aksi --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1.5">
                                        @if ($item->status_approved == 1)
                                            <button type="button" title="Terima"
                                                onclick="return terima_button('{{ $item->id }}', '{{ $item->nama_karyawan }}', '{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d-m-Y') }}', 'terima')"
                                                class="w-8 h-8 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 hover:text-emerald-700 flex items-center justify-center transition">
                                                <i class="ri-checkbox-circle-line text-base"></i>
                                            </button>
                                            <button type="button" title="Tolak"
                                                onclick="return tolak_button('{{ $item->id }}', '{{ $item->nama_karyawan }}', '{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d-m-Y') }}', 'tolak')"
                                                class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-500 hover:text-red-600 flex items-center justify-center transition">
                                                <i class="ri-close-circle-line text-base"></i>
                                            </button>
                                        @elseif ($item->status_approved == 2)
                                            <button type="button" title="Batalkan"
                                                onclick="return batal_button('{{ $item->id }}', '{{ $item->nama_karyawan }}', '{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d-m-Y') }}', 'batal')"
                                                class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-700 flex items-center justify-center transition">
                                                <i class="ri-arrow-go-back-line text-base"></i>
                                            </button>
                                        @elseif ($item->status_approved == 3)
                                            <button type="button" title="Batalkan"
                                                onclick="return batal_button('{{ $item->id }}', '{{ $item->nama_karyawan }}', '{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d-m-Y') }}', 'batal')"
                                                class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-700 flex items-center justify-center transition">
                                                <i class="ri-arrow-go-back-line text-base"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center">
                                            <i class="ri-file-list-3-line text-2xl text-gray-400"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-500">Tidak ada data pengajuan</p>
                                        <p class="text-xs text-gray-400">Coba ubah filter pencarian Anda</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($pengajuan->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $pengajuan->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        // ── Bulk Action ──────────────────────────────────────────────────────
        function getChecked() {
            return [...document.querySelectorAll('.row-check:checked')].map(el => el.value);
        }

        function updateBulkBar() {
            const ids   = getChecked();
            const bar   = document.getElementById('bulk-bar');
            const count = document.getElementById('bulk-count');
            if (ids.length > 0) {
                bar.classList.remove('hidden');
                count.textContent = ids.length + ' dipilih';
            } else {
                bar.classList.add('hidden');
            }
        }

        function toggleSelectAll(cb) {
            document.querySelectorAll('.row-check').forEach(el => { el.checked = cb.checked; });
            updateBulkBar();
        }

        function clearSelection() {
            document.querySelectorAll('.row-check').forEach(el => { el.checked = false; });
            const sa = document.getElementById('select-all');
            if (sa) sa.checked = false;
            updateBulkBar();
        }

        function doBulk(ajuan) {
            const ids = getChecked();
            if (!ids.length) return;

            const label = ajuan === 'terima' ? 'menerima' : 'menolak';
            Swal.fire({
                title: 'Konfirmasi',
                html: '<p>Apakah Anda yakin ingin <b>' + label + '</b> <b>' + ids.length + '</b> pengajuan yang dipilih?</p>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: ajuan === 'terima' ? '#10b981' : '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: ajuan === 'terima' ? 'Ya, Terima' : 'Ya, Tolak',
                cancelButtonText: 'Batal',
            }).then(result => {
                if (!result.isConfirmed) return;
                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.administrasi-presensi.bulk") }}',
                    data: { _token: '{{ csrf_token() }}', ids: ids, ajuan: ajuan },
                    success: function(res) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: res.message,
                            icon: 'success',
                            confirmButtonColor: '#4f46e5',
                        }).then(() => location.reload());
                    },
                    error: function(res) {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: res.responseJSON?.message ?? 'Terjadi kesalahan.' });
                    }
                });
            });
        }

        @if (session()->has("success"))
            Swal.fire({
                title: 'Berhasil',
                text: '{{ session("success") }}',
                icon: 'success',
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'OK',
            });
        @endif

        @if (session()->has("error"))
            Swal.fire({
                title: 'Gagal',
                text: '{{ session("error") }}',
                icon: 'error',
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'OK',
            });
        @endif

        function terima_button(id, karyawan, tanggal, ajuan) {
            Swal.fire({
                title: 'Pengajuan Presensi Diterima',
                html: "<p>Apakah Anda menerima pengajuan presensi?</p>" +
                    "<div class='divider'></div>" +
                    "<div class='flex flex-col'>" +
                    "<b>Karyawan: " + karyawan + "</b>" +
                    "<b>Tanggal Pengajuan: " + tanggal + "</b>" +
                    "</div>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Terima',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ route("admin.administrasi-presensi.persetujuan") }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id,
                            "ajuan": ajuan
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: response.message,
                                icon: 'success',
                                confirmButtonColor: '#4f46e5',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        },
                        error: function(response) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.responseJSON.message
                            })
                        }
                    });
                }
            })
        }

        function tolak_button(id, karyawan, tanggal, ajuan) {
            Swal.fire({
                title: 'Pengajuan Presensi Ditolak',
                html: "<p>Apakah Anda menolak pengajuan presensi?</p>" +
                    "<div class='divider'></div>" +
                    "<div class='flex flex-col'>" +
                    "<b>Karyawan: " + karyawan + "</b>" +
                    "<b>Tanggal Pengajuan: " + tanggal + "</b>" +
                    "</div>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Tolak',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ route("admin.administrasi-presensi.persetujuan") }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id,
                            "ajuan": ajuan
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: response.message,
                                icon: 'success',
                                confirmButtonColor: '#4f46e5',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        },
                        error: function(response) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.responseJSON.message
                            })
                        }
                    });
                }
            })
        }

        function batal_button(id, karyawan, tanggal, ajuan) {
            Swal.fire({
                title: 'Pengajuan Presensi Dibatalkan',
                html: "<p>Apakah Anda membatalkan pengajuan presensi?</p>" +
                    "<div class='divider'></div>" +
                    "<div class='flex flex-col'>" +
                    "<b>Karyawan: " + karyawan + "</b>" +
                    "<b>Tanggal Pengajuan: " + tanggal + "</b>" +
                    "</div>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Batalkan',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ route("admin.administrasi-presensi.persetujuan") }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id,
                            "ajuan": ajuan
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: response.message,
                                icon: 'success',
                                confirmButtonColor: '#4f46e5',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        },
                        error: function(response) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.responseJSON.message
                            })
                        }
                    });
                }
            })
        }
    </script>
</x-app-layout>
