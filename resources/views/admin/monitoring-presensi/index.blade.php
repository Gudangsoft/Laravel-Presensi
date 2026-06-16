<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __("Monitoring Presensi") }}
            </h2>
        </div>
    </x-slot>

    <div class="container mx-auto px-5 pt-5 pb-10">

        {{-- Header + Filter --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Monitoring Presensi</h1>
                <p class="mt-0.5 text-sm text-gray-500">Pantau kehadiran karyawan secara real-time</p>
            </div>
            <form action="{{ route("admin.monitoring-presensi") }}" method="get" class="flex items-center gap-2">
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="ri-calendar-line text-gray-400"></i>
                    </span>
                    <input
                        type="date"
                        name="tanggal_presensi"
                        class="w-full rounded-xl border border-gray-300 py-2.5 pl-9 pr-4 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        value="{{ request()->tanggal_presensi ? request()->tanggal_presensi : Carbon\Carbon::now()->format('Y-m-d') }}"
                    />
                </div>
                <button type="submit" class="flex items-center gap-1.5 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                    <i class="ri-search-2-line text-base"></i>
                    Filter
                </button>
            </form>
        </div>

        {{-- Stats Summary Cards --}}
        @php
            $total     = $monitoring->total();
            $terlambat = $monitoring->getCollection()->filter(fn($item) => $item->jam_masuk > Carbon\Carbon::make($pengaturan->jam_masuk)->format('H:i:s'))->count();
            $hadir     = $total - $terlambat;
        @endphp
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
            {{-- Total --}}
            <div class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <i class="ri-team-line text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Hadir</p>
                    <p class="mt-0.5 text-2xl font-bold text-gray-800">{{ $total }}</p>
                </div>
            </div>
            {{-- Tepat Waktu --}}
            <div class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <i class="ri-checkbox-circle-line text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Tepat Waktu</p>
                    <p class="mt-0.5 text-2xl font-bold text-gray-800">{{ $hadir }}</p>
                </div>
            </div>
            {{-- Terlambat --}}
            <div class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <i class="ri-time-line text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Terlambat</p>
                    <p class="mt-0.5 text-2xl font-bold text-gray-800">{{ $terlambat }}</p>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">No</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Karyawan</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Departemen</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Jam Masuk</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Foto Masuk</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Jam Keluar</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Foto Keluar</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($monitoring as $value => $item)
                            <tr class="transition-colors hover:bg-gray-50">
                                {{-- No --}}
                                <td class="px-5 py-4 font-semibold text-gray-700">
                                    {{ $monitoring->firstItem() + $value }}
                                </td>

                                {{-- Karyawan --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-600">
                                            {{ strtoupper(substr($item->nama_karyawan, 0, 1)) }}
                                        </div>
                                        <p class="font-semibold text-gray-800">{{ $item->nama_karyawan }}</p>
                                    </div>
                                </td>

                                {{-- Departemen --}}
                                <td class="px-5 py-4 text-gray-600">{{ $item->nama_departemen }}</td>

                                {{-- Jam Masuk --}}
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1 text-gray-700">
                                        <i class="ri-login-box-line text-indigo-400"></i>
                                        {{ $item->jam_masuk }}
                                    </span>
                                </td>

                                {{-- Foto Masuk --}}
                                <td class="px-5 py-4">
                                    <button
                                        type="button"
                                        onclick="viewLokasi('lokasi_masuk', '{{ $item->id }}')"
                                        class="group relative block h-12 w-12 overflow-hidden rounded-lg border-2 border-indigo-100 transition hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        title="Lihat lokasi masuk"
                                    >
                                        <img
                                            src="{{ asset('storage/unggah/presensi/' . $item->foto_masuk) }}"
                                            alt="{{ $item->foto_masuk }}"
                                            class="h-full w-full object-cover"
                                        />
                                        <span class="absolute inset-0 flex items-center justify-center bg-indigo-600/0 transition group-hover:bg-indigo-600/40">
                                            <i class="ri-map-pin-line text-white opacity-0 transition group-hover:opacity-100"></i>
                                        </span>
                                    </button>
                                </td>

                                {{-- Jam Keluar --}}
                                <td class="px-5 py-4">
                                    @if ($item->jam_keluar)
                                        <span class="inline-flex items-center gap-1 text-gray-700">
                                            <i class="ri-logout-box-line text-red-400"></i>
                                            {{ $item->jam_keluar }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-600">
                                            <i class="ri-time-line"></i>
                                            Belum Presensi
                                        </span>
                                    @endif
                                </td>

                                {{-- Foto Keluar --}}
                                <td class="px-5 py-4">
                                    @if ($item->foto_keluar)
                                        <button
                                            type="button"
                                            onclick="viewLokasi('lokasi_keluar', '{{ $item->id }}')"
                                            class="group relative block h-12 w-12 overflow-hidden rounded-lg border-2 border-red-100 transition hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-400"
                                            title="Lihat lokasi keluar"
                                        >
                                            <img
                                                src="{{ asset('storage/unggah/presensi/' . $item->foto_keluar) }}"
                                                alt="{{ $item->foto_keluar }}"
                                                class="h-full w-full object-cover"
                                            />
                                            <span class="absolute inset-0 flex items-center justify-center bg-red-600/0 transition group-hover:bg-red-600/40">
                                                <i class="ri-map-pin-line text-white opacity-0 transition group-hover:opacity-100"></i>
                                            </span>
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </td>

                                {{-- Status Badge --}}
                                <td class="px-5 py-4">
                                    @if ($item->jam_masuk > Carbon\Carbon::make($pengaturan->jam_masuk)->format('H:i:s'))
                                        @php
                                            $masuk   = Carbon\Carbon::make($item->jam_masuk);
                                            $batas   = Carbon\Carbon::make($pengaturan->jam_masuk);
                                            $diff    = $masuk->diff($batas);
                                            $selisih = $diff->format('%h') != 0
                                                ? $diff->format('%h jam %I menit')
                                                : $diff->format('%I menit');
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700 ring-1 ring-amber-200">
                                            <i class="ri-alarm-warning-line"></i>
                                            Terlambat {{ $selisih }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-emerald-200">
                                            <i class="ri-checkbox-circle-line"></i>
                                            Tepat Waktu
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <i class="ri-inbox-line text-5xl"></i>
                                        <p class="text-sm font-medium">Tidak ada data presensi untuk tanggal ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            <div class="border-t border-gray-100 px-5 py-4">
                {{ $monitoring->links() }}
            </div>
        </div>
    </div>

    {{-- Modal View Lokasi --}}
    <div id="modal-lokasi" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4 backdrop-blur-sm">
        <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl">
            {{-- Modal Header --}}
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <div class="flex items-center gap-2">
                    <i class="ri-map-pin-2-line text-lg text-indigo-600"></i>
                    <h3 class="judul-lokasi text-base font-semibold capitalize text-gray-800"></h3>
                </div>
                <button type="button" onclick="closeModal()" class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600">
                    <i class="ri-close-large-line text-lg"></i>
                </button>
            </div>
            {{-- Modal Body --}}
            <div class="px-6 py-5">
                <div class="mb-4">
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-gray-500">Koordinat</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="ri-crosshair-2-line text-indigo-400"></i>
                        </span>
                        <input
                            type="text"
                            name="lokasi"
                            placeholder="Memuat koordinat..."
                            class="w-full rounded-xl border border-gray-300 bg-gray-50 py-2.5 pl-9 pr-4 text-sm font-mono text-blue-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            readonly
                        />
                        <span id="loading_edit1" class="absolute inset-y-0 right-3 flex items-center"></span>
                    </div>
                </div>
                <div id="lokasi-map" class="h-72 w-full overflow-hidden rounded-xl border border-gray-200"></div>
            </div>
        </div>
    </div>
    {{-- End Modal --}}

    <script>
        function openModal() {
            const modal = document.getElementById('modal-lokasi');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('modal-lokasi');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            // Remove existing map instance
            const mapDiv = document.getElementById('lokasi-map');
            mapDiv.innerHTML = '';
            if (window._leafletMap) {
                window._leafletMap.remove();
                window._leafletMap = null;
            }
        }

        // Close modal on backdrop click
        document.getElementById('modal-lokasi').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        @if (session()->has('success'))
            Swal.fire({
                title: 'Berhasil',
                text: '{{ session("success") }}',
                icon: 'success',
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'OK',
            });
        @endif

        @if (session()->has('error'))
            Swal.fire({
                title: 'Gagal',
                text: '{{ session("error") }}',
                icon: 'error',
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'OK',
            });
        @endif

        function maps(latitude, longitude) {
            if (window._leafletMap) {
                window._leafletMap.remove();
                window._leafletMap = null;
            }
            document.getElementById('lokasi-map').innerHTML = '';

            let map = L.map('lokasi-map').setView([latitude, longitude], 17);
            window._leafletMap = map;

            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                maxZoom: 19,
                subdomains: 'abcd',
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'
            }).addTo(map);

            let marker = L.marker([latitude, longitude]).addTo(map);
            marker.bindPopup('<b>Posisi Karyawan</b>').openPopup();

            L.circle([{{ $lokasiKantor->latitude }}, {{ $lokasiKantor->longitude }}], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: {{ $lokasiKantor->radius }}
            }).addTo(map);
        }

        function viewLokasi(tipe, id) {
            openModal();

            let loading = `<span class="loading loading-dots loading-sm text-indigo-600"></span>`;
            $('#loading_edit1').html(loading);
            $('input[name="lokasi"]').val('');
            $('.judul-lokasi').html(tipe.replace('_', ' '));

            $.ajax({
                type: 'post',
                url: '{{ route("admin.monitoring-presensi.lokasi") }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'tipe': tipe,
                    'id': id,
                },
                success: function(data) {
                    let items = [];
                    $.each(data, function(key, val) {
                        items.push(val);
                    });

                    $('input[name="lokasi"]').val(items[0]);
                    $('#loading_edit1').html('');

                    let lokasi = items[0].split(',');
                    maps(lokasi[0], lokasi[1]);
                }
            });
        }
    </script>
</x-app-layout>
