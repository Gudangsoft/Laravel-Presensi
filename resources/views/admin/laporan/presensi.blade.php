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

                <form id="formKaryawan" action="{{ route("admin.laporan.presensi.karyawan") }}" method="post" target="_blank" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Bulan</label>
                            <input
                                type="month"
                                name="bulan"
                                id="bulanKaryawan"
                                value="{{ Carbon\Carbon::now()->format("Y-m") }}"
                                required
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Karyawan</label>
                            <select
                                name="karyawan"
                                id="karyawanSelect"
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
                            <button type="button"
                                onclick="previewKaryawan()"
                                class="flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl px-4 py-2.5 text-sm font-medium transition">
                                <i class="ri-eye-line text-base"></i> View
                            </button>
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

                <form id="formSemua" action="{{ route("admin.laporan.presensi.semua-karyawan") }}" method="post" target="_blank" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Bulan</label>
                            <input
                                type="month"
                                name="bulan"
                                id="bulanSemua"
                                value="{{ Carbon\Carbon::now()->format("Y-m") }}"
                                required
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                            />
                        </div>
                        <div class="flex gap-2">
                            <button type="button"
                                onclick="previewSemua()"
                                class="flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl px-4 py-2.5 text-sm font-medium transition">
                                <i class="ri-eye-line text-base"></i> View
                            </button>
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

    {{-- ── MODAL PREVIEW ─────────────────────────────────────────────── --}}
    <div id="modalPreview" class="fixed inset-0 z-50 hidden">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>

        {{-- Modal Box --}}
        <div class="relative z-10 flex h-full items-center justify-center p-4">
            <div class="w-full max-w-4xl max-h-[90vh] bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden">

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 flex-shrink-0">
                    <div>
                        <h3 id="modalTitle" class="text-base font-bold text-gray-800">Preview Rekap Presensi</h3>
                        <p id="modalSubtitle" class="text-xs text-gray-400 mt-0.5"></p>
                    </div>
                    <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition">
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>

                {{-- Loading --}}
                <div id="modalLoading" class="flex flex-col items-center justify-center py-20 gap-3">
                    <div class="w-8 h-8 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin"></div>
                    <p class="text-sm text-gray-400">Memuat data...</p>
                </div>

                {{-- Error --}}
                <div id="modalError" class="hidden flex-col items-center justify-center py-20 gap-3 text-red-500">
                    <i class="ri-error-warning-line text-4xl"></i>
                    <p id="modalErrorMsg" class="text-sm">Terjadi kesalahan.</p>
                </div>

                {{-- Content --}}
                <div id="modalContent" class="hidden flex-col flex-1 overflow-hidden">

                    {{-- Info Bar --}}
                    <div id="modalInfoBar" class="flex flex-wrap gap-3 px-6 py-3 bg-gray-50 border-b border-gray-100 text-xs text-gray-600 flex-shrink-0"></div>

                    {{-- Table --}}
                    <div class="flex-1 overflow-auto">
                        <table class="w-full border-collapse text-sm">
                            <thead id="modalThead" class="bg-indigo-50 text-xs text-indigo-700 sticky top-0">
                            </thead>
                            <tbody id="modalTbody" class="divide-y divide-gray-100">
                            </tbody>
                        </table>
                    </div>

                    {{-- Footer --}}
                    <div id="modalFooter" class="flex items-center justify-between px-6 py-3 border-t border-gray-100 bg-gray-50 text-xs text-gray-500 flex-shrink-0"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const urlKaryawan = '{{ route('admin.laporan.presensi.karyawan.view') }}';
        const urlSemua    = '{{ route('admin.laporan.presensi.semua-karyawan.view') }}';

        function openModal() {
            document.getElementById('modalPreview').classList.remove('hidden');
            document.getElementById('modalLoading').classList.remove('hidden');
            document.getElementById('modalContent').classList.add('hidden');
            document.getElementById('modalError').classList.add('hidden');
        }

        function closeModal() {
            document.getElementById('modalPreview').classList.add('hidden');
        }

        function showError(msg) {
            document.getElementById('modalLoading').classList.add('hidden');
            document.getElementById('modalError').classList.remove('hidden');
            document.getElementById('modalError').classList.add('flex');
            document.getElementById('modalErrorMsg').textContent = msg;
        }

        function showContent() {
            document.getElementById('modalLoading').classList.add('hidden');
            document.getElementById('modalContent').classList.remove('hidden');
            document.getElementById('modalContent').classList.add('flex');
        }

        function previewKaryawan() {
            const bulan    = document.getElementById('bulanKaryawan').value;
            const karyawan = document.getElementById('karyawanSelect').value;
            if (!bulan || !karyawan) {
                alert('Pilih bulan dan karyawan terlebih dahulu.');
                return;
            }
            openModal();
            fetch(`${urlKaryawan}?bulan=${bulan}&karyawan=${karyawan}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(res => {
                if (res.error) { showError(res.error); return; }

                document.getElementById('modalTitle').textContent    = 'Rekap Presensi — ' + res.karyawan.nama;
                document.getElementById('modalSubtitle').textContent = res.bulan;

                document.getElementById('modalInfoBar').innerHTML = `
                    <span><strong>Jabatan:</strong> ${res.karyawan.jabatan}</span>
                    <span class="text-gray-300">|</span>
                    <span><strong>Departemen:</strong> ${res.karyawan.departemen}</span>
                    <span class="text-gray-300">|</span>
                    <span><strong>Email:</strong> ${res.karyawan.email}</span>
                    <span class="ml-auto font-semibold text-indigo-600">Total Hadir: ${res.total} hari</span>
                `;

                document.getElementById('modalThead').innerHTML = `
                    <tr>
                        <th class="px-4 py-2.5 text-left font-semibold">#</th>
                        <th class="px-4 py-2.5 text-left font-semibold">Tanggal</th>
                        <th class="px-4 py-2.5 text-left font-semibold">Hari</th>
                        <th class="px-4 py-2.5 text-left font-semibold">Jam Masuk</th>
                        <th class="px-4 py-2.5 text-left font-semibold">Jam Keluar</th>
                        <th class="px-4 py-2.5 text-left font-semibold">Durasi</th>
                        <th class="px-4 py-2.5 text-left font-semibold">Status</th>
                    </tr>
                `;

                const tbody = res.data.map((r, i) => `
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-2.5 text-gray-400 text-xs">${i + 1}</td>
                        <td class="px-4 py-2.5 font-medium text-gray-800">${r.tanggal}</td>
                        <td class="px-4 py-2.5 text-gray-500">${r.hari}</td>
                        <td class="px-4 py-2.5 font-mono text-gray-800">${r.jam_masuk}</td>
                        <td class="px-4 py-2.5 font-mono text-gray-500">${r.jam_keluar}</td>
                        <td class="px-4 py-2.5 text-gray-600">${r.durasi}</td>
                        <td class="px-4 py-2.5">
                            ${r.status === 'Terlambat'
                                ? '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700"><i class="ri-time-line"></i> Terlambat</span>'
                                : '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700"><i class="ri-check-line"></i> Tepat Waktu</span>'
                            }
                        </td>
                    </tr>
                `).join('');

                document.getElementById('modalTbody').innerHTML = tbody || '<tr><td colspan="7" class="text-center py-10 text-gray-400">Tidak ada data presensi pada bulan ini.</td></tr>';
                document.getElementById('modalFooter').innerHTML = `<span>Menampilkan ${res.data.length} dari ${res.total} data</span>`;
                showContent();
            })
            .catch(() => showError('Gagal memuat data. Coba lagi.'));
        }

        function previewSemua() {
            const bulan = document.getElementById('bulanSemua').value;
            if (!bulan) {
                alert('Pilih bulan terlebih dahulu.');
                return;
            }
            openModal();
            fetch(`${urlSemua}?bulan=${bulan}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(res => {
                if (res.error) { showError(res.error); return; }

                document.getElementById('modalTitle').textContent    = 'Rekap Presensi Semua Karyawan';
                document.getElementById('modalSubtitle').textContent = res.bulan;

                document.getElementById('modalInfoBar').innerHTML = `
                    <span>Total karyawan yang hadir: <strong class="text-indigo-600">${res.total} orang</strong></span>
                `;

                document.getElementById('modalThead').innerHTML = `
                    <tr>
                        <th class="px-4 py-2.5 text-left font-semibold">#</th>
                        <th class="px-4 py-2.5 text-left font-semibold">Nama Karyawan</th>
                        <th class="px-4 py-2.5 text-left font-semibold">Jabatan</th>
                        <th class="px-4 py-2.5 text-left font-semibold">Departemen</th>
                        <th class="px-4 py-2.5 text-center font-semibold">Total Hadir</th>
                        <th class="px-4 py-2.5 text-center font-semibold">Terlambat</th>
                    </tr>
                `;

                const tbody = res.data.map((r, i) => `
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-2.5 text-gray-400 text-xs">${i + 1}</td>
                        <td class="px-4 py-2.5 font-medium text-gray-800">${r.nama}</td>
                        <td class="px-4 py-2.5 text-gray-500">${r.jabatan}</td>
                        <td class="px-4 py-2.5 text-gray-500">${r.departemen}</td>
                        <td class="px-4 py-2.5 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 font-bold text-sm">${r.total_hadir}</span>
                        </td>
                        <td class="px-4 py-2.5 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full ${r.total_terlambat > 0 ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-400'} font-bold text-sm">${r.total_terlambat}</span>
                        </td>
                    </tr>
                `).join('');

                document.getElementById('modalTbody').innerHTML = tbody || '<tr><td colspan="6" class="text-center py-10 text-gray-400">Tidak ada data presensi pada bulan ini.</td></tr>';
                document.getElementById('modalFooter').innerHTML = `<span>Menampilkan ${res.data.length} karyawan</span>`;
                showContent();
            })
            .catch(() => showError('Gagal memuat data. Coba lagi.'));
        }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
    </script>
</x-app-layout>
