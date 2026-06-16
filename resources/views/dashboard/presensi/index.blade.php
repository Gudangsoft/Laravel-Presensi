@extends("dashboard.layouts.main")

@section("css")
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <style>
        #webcam-capture video,
        #webcam-capture canvas {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover;
            display: block;
        }
        #webcam-capture {
            width: 100% !important;
        }
        .leaflet-container {
            font-family: inherit;
        }
    </style>
@endsection

@section("js")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // ── Webcam: responsive width ──────────────────────────────────────
        const camWrap = document.getElementById('webcam-container');
        const camW    = camWrap ? camWrap.clientWidth : 480;
        const camH    = Math.min(Math.round(camW * 0.75), 400); // 4:3, max 400px

        Webcam.set({
            width: camW,
            height: camH,
            image_format: 'jpeg',
            jpeg_quality: 90,
            force_flash: false,
            flip_horiz: false,
        });
        Webcam.attach('#webcam-capture');

        // ── Geolocation + Map ─────────────────────────────────────────────
        let image    = null;
        let lokasi   = document.getElementById('lokasi');
        let leafMap  = null;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallBack, {
                enableHighAccuracy: true,
                timeout: 10000,
            });
        }

        function successCallback(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            lokasi.value = lat + ',' + lng;

            // Show map first so Leaflet can read container size
            document.getElementById('map-loading').classList.add('hidden');
            const mapEl = document.getElementById('map');
            mapEl.classList.remove('hidden');

            leafMap = L.map('map').setView([lat, lng], 17);

            // Force re-center after layout reflow (fixes mobile sizing issue)
            setTimeout(() => {
                leafMap.invalidateSize();
                leafMap.setView([lat, lng], 17);
            }, 300);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap &copy; CARTO',
                subdomains: 'abcd'
            }).addTo(leafMap);

            // User marker
            const userIcon = L.divIcon({
                className: '',
                html: `<div style="width:14px;height:14px;background:#4f46e5;border:3px solid white;border-radius:50%;box-shadow:0 2px 8px rgba(0,0,0,0.3)"></div>`,
                iconSize: [14, 14],
                iconAnchor: [7, 7],
            });
            L.marker([lat, lng], { icon: userIcon }).addTo(leafMap)
             .bindPopup('<b>Posisi Anda</b>').openPopup();

            // Office radius circles
            @foreach ($lokasiKantors as $lok)
            L.circle([{{ $lok->latitude }}, {{ $lok->longitude }}], {
                color: '#ef4444',
                fillColor: '#ef4444',
                fillOpacity: 0.12,
                weight: 2,
                radius: {{ $lok->radius }}
            }).addTo(leafMap).bindPopup('<b>{{ addslashes($lok->kota) }}</b><br>Radius: {{ $lok->radius }} m');
            @endforeach

            // Re-center when device orientation changes
            window.addEventListener('orientationchange', () => {
                setTimeout(() => { leafMap.invalidateSize(); leafMap.setView([lat, lng], 17); }, 400);
            });

            // Show location badge
            document.getElementById('loc-badge').classList.remove('hidden');
            document.getElementById('loc-text').textContent = lat.toFixed(5) + ', ' + lng.toFixed(5);
        }

        function errorCallBack() {
            document.getElementById('map-loading').innerHTML =
                '<div class="flex flex-col items-center gap-2 text-red-400">' +
                '<i class="ri-map-pin-off-line text-3xl"></i>' +
                '<p class="text-sm font-medium">Izin lokasi ditolak atau tidak tersedia</p></div>';
        }

        // ── Take Presensi ─────────────────────────────────────────────────
        const btn = document.getElementById('take-presensi');
        if (btn) {
            btn.addEventListener('click', function () {
                if (!lokasi.value) {
                    Swal.fire({ icon: 'warning', title: 'Lokasi belum terdeteksi', text: 'Tunggu hingga lokasi Anda terdeteksi.' });
                    return;
                }
                btn.disabled = true;
                btn.innerHTML = '<i class="ri-loader-4-line animate-spin text-lg"></i> Memproses...';

                Webcam.snap(function (uri) {
                    image = uri;
                    $.ajax({
                        type: 'POST',
                        url: '{{ route("karyawan.presensi.store") }}',
                        data: {
                            _token: '{{ csrf_token() }}',
                            image: image,
                            lokasi: lokasi.value,
                            jenis: $("input[name='presensi']").val(),
                        },
                        success: function (res) {
                            if (res.status == 200) {
                                if (res.jenis_presensi == 'masuk') {
                                    document.getElementById('notifikasi_presensi_masuk').play();
                                } else {
                                    document.getElementById('notifikasi_presensi_keluar').play();
                                }
                                Swal.fire({ icon: 'success', title: 'Presensi', text: res.message, confirmButtonText: 'OK' });
                                setTimeout(() => { location.href = '{{ route("karyawan.dashboard") }}'; }, 3000);
                            } else {
                                if (res.jenis_error == 'radius') {
                                    document.getElementById('notifikasi_presensi_gagal_radius').play();
                                }
                                Swal.fire({ icon: 'error', title: 'Presensi Gagal', text: res.message, confirmButtonText: 'OK' });
                                btn.disabled = false;
                                btn.innerHTML = originalLabel;
                            }
                        },
                        error: function () {
                            Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan, coba lagi.' });
                            btn.disabled = false;
                            btn.innerHTML = originalLabel;
                        }
                    });
                });
            });

            const originalLabel = btn.innerHTML;
        }
    </script>
@endsection

@section("container")
    {{-- Audio --}}
    <audio id="notifikasi_presensi_masuk"><source src="{{ asset('audio/notifikasi_presensi_masuk.mp3') }}" type="audio/mpeg"></audio>
    <audio id="notifikasi_presensi_keluar"><source src="{{ asset('audio/notifikasi_presensi_keluar.mp3') }}" type="audio/mpeg"></audio>
    <audio id="notifikasi_presensi_gagal_radius"><source src="{{ asset('audio/notifikasi_presensi_gagal_radius.mp3') }}" type="audio/mpeg"></audio>

    <input type="text" name="lokasi" id="lokasi" hidden>

    <div class="space-y-4">

        {{-- ── Status Card ── --}}
        <div class="grid grid-cols-2 gap-3">
            <div class="rounded-2xl bg-white border border-gray-100 shadow-sm px-4 py-3">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 mb-1">Masuk</p>
                @if ($presensiKaryawan && $presensiKaryawan->jam_masuk)
                    <p class="text-lg font-bold text-indigo-600">{{ date('H:i', strtotime($presensiKaryawan->jam_masuk)) }}</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">WIB</p>
                @else
                    <p class="text-lg font-bold text-gray-300">—</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">Belum presensi</p>
                @endif
            </div>
            <div class="rounded-2xl bg-white border border-gray-100 shadow-sm px-4 py-3">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 mb-1">Pulang</p>
                @if ($presensiKaryawan && $presensiKaryawan->jam_keluar)
                    <p class="text-lg font-bold text-emerald-600">{{ date('H:i', strtotime($presensiKaryawan->jam_keluar)) }}</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">WIB</p>
                @else
                    <p class="text-lg font-bold text-gray-300">—</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">Belum pulang</p>
                @endif
            </div>
        </div>

        {{-- ── Camera Card ── --}}
        <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">

            {{-- Header --}}
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-100">
                        <i class="ri-camera-2-line text-indigo-600"></i>
                    </div>
                    <span class="text-sm font-semibold text-gray-800">Foto Selfie</span>
                </div>
                @if ($presensiKaryawan == null)
                    <span class="inline-flex items-center gap-1 rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-semibold text-indigo-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                        Presensi Masuk
                    </span>
                @elseif ($presensiKaryawan->jam_keluar == null)
                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                        Presensi Keluar
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-500">
                        <i class="ri-check-line text-xs"></i>
                        Selesai
                    </span>
                @endif
            </div>

            {{-- Webcam Area --}}
            <div id="webcam-container" class="relative bg-gray-900 w-full">
                @if ($presensiKaryawan == null || $presensiKaryawan->jam_keluar == null)
                    <div id="webcam-capture" class="w-full"></div>
                @else
                    {{-- Presensi sudah selesai, tampilkan foto terakhir --}}
                    <div class="flex flex-col items-center justify-center py-12 gap-3 text-gray-400">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100">
                            <i class="ri-shield-check-line text-3xl text-emerald-500"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-600">Presensi hari ini selesai</p>
                        <p class="text-xs text-gray-400">Masuk {{ date('H:i', strtotime($presensiKaryawan->jam_masuk)) }} — Keluar {{ date('H:i', strtotime($presensiKaryawan->jam_keluar)) }}</p>
                    </div>
                @endif
            </div>

            {{-- Action Button --}}
            <div class="px-4 pb-4 pt-3">
                @if ($presensiKaryawan == null)
                    <input type="hidden" name="presensi" value="masuk">
                    <button id="take-presensi"
                            class="w-full flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 py-3.5 text-sm font-semibold text-white shadow-md shadow-indigo-200 hover:from-indigo-700 hover:to-violet-700 active:scale-95 transition-all duration-200">
                        <i class="ri-fingerprint-line text-xl"></i>
                        Presensi Masuk
                    </button>
                @elseif ($presensiKaryawan->jam_keluar == null)
                    <input type="hidden" name="presensi" value="keluar">
                    <button id="take-presensi"
                            class="w-full flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 py-3.5 text-sm font-semibold text-white shadow-md shadow-amber-200 hover:from-amber-600 hover:to-orange-600 active:scale-95 transition-all duration-200">
                        <i class="ri-logout-circle-r-line text-xl"></i>
                        Presensi Keluar
                    </button>
                @else
                    <button disabled
                            class="w-full flex items-center justify-center gap-2 rounded-xl bg-gray-100 py-3.5 text-sm font-semibold text-gray-400 cursor-not-allowed">
                        <i class="ri-checkbox-circle-line text-xl"></i>
                        Presensi Selesai
                    </button>
                @endif
            </div>
        </div>

        {{-- ── Map Card ── --}}
        <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-rose-100">
                        <i class="ri-map-pin-2-line text-rose-600"></i>
                    </div>
                    <span class="text-sm font-semibold text-gray-800">Lokasi Anda</span>
                </div>
                <span id="loc-badge" class="hidden max-w-[140px] truncate rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-1 text-[10px] font-medium text-emerald-700">
                    <i class="ri-crosshair-2-line mr-1"></i><span id="loc-text"></span>
                </span>
            </div>

            {{-- Map loading state --}}
            <div id="map-loading" class="flex h-56 items-center justify-center gap-2 text-gray-400 bg-gray-50">
                <i class="ri-loader-4-line animate-spin text-2xl text-indigo-500"></i>
                <span class="text-sm">Mendeteksi lokasi...</span>
            </div>

            {{-- Map --}}
            <div id="map" class="hidden h-56 w-full"></div>

            {{-- Office info --}}
            @if(count($lokasiKantors) > 0)
                <div class="border-t border-gray-100 px-4 py-2.5 flex flex-wrap gap-2">
                    @foreach($lokasiKantors as $lok)
                        <span class="inline-flex items-center gap-1 rounded-full bg-red-50 border border-red-200 px-2.5 py-1 text-[11px] font-medium text-red-600">
                            <span class="h-2 w-2 rounded-full bg-red-400"></span>
                            {{ $lok->kota }} ({{ $lok->radius }}m)
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
@endsection
