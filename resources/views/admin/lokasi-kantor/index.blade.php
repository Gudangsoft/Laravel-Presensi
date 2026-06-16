<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __("Lokasi Kantor") }}
            </h2>
            <label class="btn btn-primary btn-sm" for="create_modal">Tambah Data</label>
        </div>
    </x-slot>

    <div class="container mx-auto px-5 pt-5">
        <div class="w-full overflow-x-auto rounded-md bg-slate-200 px-10">
            <table class="table mb-4 w-full border-collapse items-center border-gray-200 align-top dark:border-white/40">
                <thead class="text-sm text-gray-800 dark:text-gray-300">
                    <tr>
                        <th></th>
                        <th>Kota</th>
                        <th>Alamat</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Radius (m)</th>
                        <th>Is Used?</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lokasiKantor as $value => $item)
                        <tr class="hover">
                            <td class="font-bold">{{ $lokasiKantor->firstItem() + $value }}</td>
                            <td class="text-slate-500">{{ $item->kota }}</td>
                            <td class="text-slate-500">{{ $item->alamat }}</td>
                            <td class="text-slate-500">{{ $item->latitude }}</td>
                            <td class="text-slate-500">{{ $item->longitude }}</td>
                            <td class="text-slate-500">{{ $item->radius }}</td>
                            <td>
                                @if ($item->is_used)
                                    <i class="ri-checkbox-circle-fill text-lg text-success"></i>
                                @else
                                    <i class="ri-close-circle-fill text-lg text-error"></i>
                                @endif
                            </td>
                            <td>
                                <label class="btn btn-warning btn-sm" for="edit_modal" onclick="return edit_button('{{ $item->id }}')">
                                    <i class="ri-pencil-fill"></i>
                                </label>
                                <label class="btn btn-error btn-sm" onclick="return delete_button('{{ $item->id }}', '{{ $item->kota }}')">
                                    <i class="ri-delete-bin-line"></i>
                                </label>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mx-3 mb-5">{{ $lokasiKantor->links() }}</div>
        </div>
    </div>

    {{-- ── Modal Create ── --}}
    <input type="checkbox" id="create_modal" class="modal-toggle" />
    <div class="modal" role="dialog">
        <div class="modal-box max-w-2xl">
            <div class="mb-4 flex justify-between">
                <h3 class="text-lg font-bold">Tambah {{ $title }}</h3>
                <label for="create_modal" class="cursor-pointer"><i class="ri-close-large-fill"></i></label>
            </div>

            <form action="{{ route('admin.lokasi-kantor.store') }}" method="POST">
                @csrf

                {{-- Gunakan Lokasi Saya --}}
                <button type="button" onclick="useMyLocation('create')"
                    class="btn btn-success btn-sm w-full mb-3">
                    <i class="ri-focus-3-line"></i> Gunakan Lokasi Saya Sekarang
                </button>

                {{-- Embed Google Maps --}}
                <div class="mb-3 rounded-xl border border-blue-200 bg-blue-50 p-3">
                    <p class="mb-1 text-xs font-semibold text-blue-700">
                        <i class="ri-map-2-line"></i>
                        Atau tempel kode embed dari Google Maps:
                        Buka <b>Google Maps</b> → cari lokasi → klik <b>Bagikan</b> → tab <b>Sematkan peta</b> → salin kode iframe
                    </p>
                    <div class="flex gap-2">
                        <textarea id="create-embed-code" rows="2"
                            placeholder='Tempel kode <iframe src="https://www.google.com/maps/embed?pb=..." ...></iframe> di sini'
                            class="textarea textarea-bordered w-full text-xs"></textarea>
                        <button type="button" onclick="applyEmbed('create')"
                            class="btn btn-primary btn-sm self-end whitespace-nowrap">
                            <i class="ri-check-line"></i> Terapkan
                        </button>
                    </div>
                    <p id="create-embed-error" class="mt-1 hidden text-xs text-red-500"></p>
                </div>

                {{-- iframe preview --}}
                <div id="create-map-wrap" class="mb-3 hidden">
                    <iframe id="create-iframe"
                        style="width:100%;height:280px;border-radius:10px;border:0;"
                        allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>

                <label class="form-control w-full">
                    <div class="label"><span class="label-text font-semibold">Kota<span class="text-red-500">*</span></span></div>
                    <input type="text" name="kota" placeholder="Kota" class="input input-bordered w-full text-blue-700" value="{{ old('kota') }}" required />
                    @error('kota')<div class="label"><span class="label-text-alt text-sm text-error">{{ $message }}</span></div>@enderror
                </label>

                <label class="form-control w-full">
                    <div class="label"><span class="label-text font-semibold">Alamat<span class="text-red-500">*</span></span></div>
                    <textarea name="alamat" placeholder="Alamat" class="textarea textarea-bordered w-full text-blue-700">{{ old('alamat') }}</textarea>
                    @error('alamat')<div class="label"><span class="label-text-alt text-sm text-error">{{ $message }}</span></div>@enderror
                </label>

                <div class="flex gap-2">
                    <label class="form-control w-full">
                        <div class="label"><span class="label-text font-semibold">Latitude<span class="text-red-500">*</span></span></div>
                        <input type="text" name="latitude" id="create-lat" placeholder="Otomatis dari embed"
                            class="input input-bordered w-full text-blue-700" value="{{ old('latitude') }}" required />
                        @error('latitude')<div class="label"><span class="label-text-alt text-sm text-error">{{ $message }}</span></div>@enderror
                    </label>
                    <label class="form-control w-full">
                        <div class="label"><span class="label-text font-semibold">Longitude<span class="text-red-500">*</span></span></div>
                        <input type="text" name="longitude" id="create-lng" placeholder="Otomatis dari embed"
                            class="input input-bordered w-full text-blue-700" value="{{ old('longitude') }}" required />
                        @error('longitude')<div class="label"><span class="label-text-alt text-sm text-error">{{ $message }}</span></div>@enderror
                    </label>
                </div>

                <label class="form-control w-full">
                    <div class="label"><span class="label-text font-semibold">Radius (meter)<span class="text-red-500">*</span></span></div>
                    <input type="number" min="1" name="radius" placeholder="Contoh: 50"
                        class="input input-bordered w-full text-blue-700" value="{{ old('radius', 50) }}" required />
                    @error('radius')<div class="label"><span class="label-text-alt text-sm text-error">{{ $message }}</span></div>@enderror
                </label>

                <div>
                    <div class="label"><span class="label-text font-semibold">Is Used?<span class="text-red-500">*</span></span></div>
                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <span class="label-text">Iya</span>
                            <input type="radio" name="is_used" value='1' class="radio checked:bg-red-500" />
                        </label>
                    </div>
                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <span class="label-text">Tidak</span>
                            <input type="radio" name="is_used" value='0' class="radio checked:bg-blue-500" checked />
                        </label>
                    </div>
                    @error('is_used')<div class="label"><span class="label-text-alt text-sm text-error">{{ $message }}</span></div>@enderror
                </div>

                <button type="submit" class="btn btn-success mt-3 w-full text-white">Simpan</button>
            </form>
        </div>
    </div>

    {{-- ── Modal Edit ── --}}
    <input type="checkbox" id="edit_modal" class="modal-toggle" />
    <div class="modal" role="dialog">
        <div class="modal-box max-w-2xl">
            <div class="mb-4 flex justify-between">
                <h3 class="text-lg font-bold">Ubah {{ $title }}</h3>
                <label for="edit_modal" class="cursor-pointer"><i class="ri-close-large-fill"></i></label>
            </div>

            <form action="{{ route('admin.lokasi-kantor.update') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="edit-id">

                {{-- iframe lokasi saat ini --}}
                <div id="edit-map-wrap" class="mb-3 hidden">
                    <p class="mb-1 text-xs text-gray-500"><i class="ri-map-pin-line"></i> Lokasi saat ini</p>
                    <iframe id="edit-iframe"
                        style="width:100%;height:280px;border-radius:10px;border:0;"
                        allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>

                {{-- Gunakan Lokasi Saya --}}
                <button type="button" onclick="useMyLocation('edit')"
                    class="btn btn-success btn-sm w-full mb-3">
                    <i class="ri-focus-3-line"></i> Gunakan Lokasi Saya Sekarang
                </button>

                {{-- Ubah lokasi via embed baru --}}
                <div class="mb-3">
                    <button type="button" onclick="toggleEmbedPanel()"
                        class="flex w-full items-center justify-between rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-700">
                        <span><i class="ri-map-2-line mr-1"></i> Ubah Lokasi (tempel kode embed baru)</span>
                        <i class="ri-arrow-down-s-line text-lg" id="edit-toggle-icon"></i>
                    </button>
                    <div id="edit-embed-panel" class="hidden rounded-b-xl border border-t-0 border-blue-200 bg-blue-50 px-4 pb-3 pt-2">
                        <p class="mb-2 text-xs text-blue-600">
                            Buka <b>Google Maps</b> → cari lokasi → <b>Bagikan</b> → tab <b>Sematkan peta</b> → salin kode iframe
                        </p>
                        <div class="flex gap-2">
                            <textarea id="edit-embed-code" rows="2"
                                placeholder='Tempel kode <iframe ...></iframe> di sini'
                                class="textarea textarea-bordered w-full text-xs"></textarea>
                            <button type="button" onclick="applyEmbed('edit')"
                                class="btn btn-primary btn-sm self-end whitespace-nowrap">
                                <i class="ri-check-line"></i> Terapkan
                            </button>
                        </div>
                        <p id="edit-embed-error" class="mt-1 hidden text-xs text-red-500"></p>
                    </div>
                </div>

                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text font-semibold">Kota<span class="text-red-500">*</span></span>
                        <span class="label-text-alt" id="loading_edit1"></span>
                    </div>
                    <input type="text" name="kota" id="edit-kota" placeholder="Kota" class="input input-bordered w-full text-blue-700" required />
                    @error('kota')<div class="label"><span class="label-text-alt text-sm text-error">{{ $message }}</span></div>@enderror
                </label>

                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text font-semibold">Alamat<span class="text-red-500">*</span></span>
                        <span class="label-text-alt" id="loading_edit2"></span>
                    </div>
                    <textarea name="alamat" id="edit-alamat" placeholder="Alamat" class="textarea textarea-bordered w-full text-blue-700"></textarea>
                    @error('alamat')<div class="label"><span class="label-text-alt text-sm text-error">{{ $message }}</span></div>@enderror
                </label>

                <div class="flex gap-2">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text font-semibold">Latitude<span class="text-red-500">*</span></span>
                            <span class="label-text-alt" id="loading_edit3"></span>
                        </div>
                        <input type="text" name="latitude" id="edit-lat" class="input input-bordered w-full text-blue-700" required />
                        @error('latitude')<div class="label"><span class="label-text-alt text-sm text-error">{{ $message }}</span></div>@enderror
                    </label>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text font-semibold">Longitude<span class="text-red-500">*</span></span>
                            <span class="label-text-alt" id="loading_edit4"></span>
                        </div>
                        <input type="text" name="longitude" id="edit-lng" class="input input-bordered w-full text-blue-700" required />
                        @error('longitude')<div class="label"><span class="label-text-alt text-sm text-error">{{ $message }}</span></div>@enderror
                    </label>
                </div>

                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text font-semibold">Radius (meter)<span class="text-red-500">*</span></span>
                        <span class="label-text-alt" id="loading_edit5"></span>
                    </div>
                    <input type="number" min="1" name="radius" id="edit-radius" class="input input-bordered w-full text-blue-700" required />
                    @error('radius')<div class="label"><span class="label-text-alt text-sm text-error">{{ $message }}</span></div>@enderror
                </label>

                <div>
                    <div class="label">
                        <span class="label-text font-semibold">Is Used?<span class="text-red-500">*</span></span>
                        <span class="label-text-alt" id="loading_edit6"></span>
                    </div>
                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <span class="label-text">Iya</span>
                            <input type="radio" name="is_used" id="edit-is-used-yes" value='1' class="radio checked:bg-red-500" />
                        </label>
                    </div>
                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <span class="label-text">Tidak</span>
                            <input type="radio" name="is_used" id="edit-is-used-no" value='0' class="radio checked:bg-blue-500" />
                        </label>
                    </div>
                    @error('is_used')<div class="label"><span class="label-text-alt text-sm text-error">{{ $message }}</span></div>@enderror
                </div>

                <button type="submit" class="btn btn-warning mt-3 w-full text-slate-700">Perbarui</button>
            </form>
        </div>
    </div>

    <script>
        @if (session()->has('success'))
            Swal.fire({ title: 'Berhasil', text: '{{ session("success") }}', icon: 'success', confirmButtonColor: '#6419E6', confirmButtonText: 'OK' });
        @endif
        @if (session()->has('error'))
            Swal.fire({ title: 'Gagal', text: '{{ session("error") }}', icon: 'error', confirmButtonColor: '#6419E6', confirmButtonText: 'OK' });
        @endif

        // Gunakan GPS saat ini sebagai koordinat kantor
        function useMyLocation(type) {
            if (!navigator.geolocation) {
                Swal.fire('Error', 'Browser tidak mendukung geolocation.', 'error');
                return;
            }
            Swal.fire({
                title: 'Mengambil lokasi...',
                text: 'Pastikan izin lokasi browser sudah diaktifkan.',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            navigator.geolocation.getCurrentPosition(
                function (pos) {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    document.getElementById(type + '-lat').value = lat.toFixed(10);
                    document.getElementById(type + '-lng').value = lng.toFixed(10);

                    const iframe = document.getElementById(type + '-iframe');
                    iframe.src = `https://maps.google.com/maps?q=${lat},${lng}&z=17&output=embed&hl=id`;
                    document.getElementById(type + '-map-wrap').classList.remove('hidden');

                    Swal.fire({
                        title: 'Lokasi Ditemukan',
                        html: `Lat: <b>${lat.toFixed(6)}</b><br>Lng: <b>${lng.toFixed(6)}</b>`,
                        icon: 'success',
                        confirmButtonColor: '#6419E6',
                        confirmButtonText: 'OK'
                    });
                },
                function () {
                    Swal.fire('Gagal', 'Tidak dapat mengambil lokasi. Aktifkan izin lokasi di browser.', 'error');
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        }

        // Toggle panel embed edit
        function toggleEmbedPanel() {
            const panel = document.getElementById('edit-embed-panel');
            const icon  = document.getElementById('edit-toggle-icon');
            panel.classList.toggle('hidden');
            icon.classList.toggle('ri-arrow-down-s-line');
            icon.classList.toggle('ri-arrow-up-s-line');
        }

        // Ekstrak lat/lng dari kode embed Google Maps lalu tampilkan iframe
        function applyEmbed(type) {
            const code  = document.getElementById(type + '-embed-code').value.trim();
            const errEl = document.getElementById(type + '-embed-error');
            errEl.classList.add('hidden');

            // Ambil src dari tag <iframe> atau langsung URL
            let src = code;
            const srcMatch = code.match(/src=["']([^"']+)["']/);
            if (srcMatch) src = srcMatch[1];

            // Ekstrak koordinat dari parameter pb di URL embed Google Maps
            const lngMatch = src.match(/!2d([-\d.]+)/);
            const latMatch = src.match(/!3d([-\d.]+)/);

            if (!latMatch || !lngMatch) {
                errEl.textContent = 'Kode embed tidak valid. Pastikan menyalin dari Google Maps → Bagikan → Sematkan peta.';
                errEl.classList.remove('hidden');
                return;
            }

            const lat = parseFloat(latMatch[1]);
            const lng = parseFloat(lngMatch[1]);

            document.getElementById(type + '-lat').value = lat.toFixed(10);
            document.getElementById(type + '-lng').value = lng.toFixed(10);

            const iframe = document.getElementById(type + '-iframe');
            iframe.src = src;
            document.getElementById(type + '-map-wrap').classList.remove('hidden');
        }

        // Edit: load data via AJAX
        function edit_button(id) {
            const loading = `<span class="loading loading-dots loading-md text-purple-600"></span>`;
            ['loading_edit1','loading_edit2','loading_edit3','loading_edit4','loading_edit5','loading_edit6']
                .forEach(el => document.getElementById(el).innerHTML = loading);

            $.ajax({
                type: 'get',
                url: '{{ route("admin.lokasi-kantor.edit") }}',
                data: { '_token': '{{ csrf_token() }}', 'id': id },
                success: function (data) {
                    document.getElementById('edit-id').value     = data.id;
                    document.getElementById('edit-kota').value   = data.kota;
                    document.getElementById('edit-alamat').value = data.alamat;
                    document.getElementById('edit-lat').value    = data.latitude;
                    document.getElementById('edit-lng').value    = data.longitude;
                    document.getElementById('edit-radius').value = data.radius;
                    document.getElementById(data.is_used ? 'edit-is-used-yes' : 'edit-is-used-no').checked = true;

                    // Tampilkan iframe lokasi saat ini dari koordinat DB
                    const iframe = document.getElementById('edit-iframe');
                    iframe.src = `https://maps.google.com/maps?q=${data.latitude},${data.longitude}&z=17&output=embed&hl=id`;
                    document.getElementById('edit-map-wrap').classList.remove('hidden');

                    ['loading_edit1','loading_edit2','loading_edit3','loading_edit4','loading_edit5','loading_edit6']
                        .forEach(el => document.getElementById(el).innerHTML = '');
                }
            });
        }

        function delete_button(id, nama) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "<p>Data yang dihapus tidak dapat dipulihkan kembali!</p><div class='divider'></div><b>Kota: " + nama + "</b>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6419E6',
                cancelButtonColor: '#F87272',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'post',
                        url: '{{ route("admin.lokasi-kantor.delete") }}',
                        data: { '_token': '{{ csrf_token() }}', 'id': id },
                        success: function (response) {
                            Swal.fire({ title: 'Berhasil', text: response.message, icon: 'success', confirmButtonColor: '#6419E6', confirmButtonText: 'OK' })
                                .then(r => { if (r.isConfirmed) location.reload(); });
                        },
                        error: function (response) {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: response.responseJSON.message });
                        }
                    });
                }
            });
        }
    </script>
</x-app-layout>
