@extends("dashboard.layouts.main")

@section("js")
    {{-- Cropper.js --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        let cropper = null;

        function openCropper(input) {
            if (!input.files || !input.files[0]) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('cropperImg');
                img.src = e.target.result;
                document.getElementById('cropModal').style.display = 'flex';
                if (cropper) { cropper.destroy(); cropper = null; }
                cropper = new Cropper(img, {
                    aspectRatio: 1,
                    viewMode: 1,
                    autoCropArea: 0.85,
                    movable: true,
                    zoomable: true,
                });
            };
            reader.readAsDataURL(input.files[0]);
        }

        function applyCrop() {
            if (!cropper) return;
            const canvas = cropper.getCroppedCanvas({ width: 400, height: 400 });
            const base64 = canvas.toDataURL('image/jpeg', 0.85);
            document.getElementById('foto_cropped').value = base64;
            document.getElementById('avatarPreview').src = base64;
            closeCropModal();
        }

        function closeCropModal() {
            document.getElementById('cropModal').style.display = 'none';
            if (cropper) { cropper.destroy(); cropper = null; }
            document.getElementById('fotoInput').value = '';
        }
    </script>
@endsection

@section("container")
    <div class="space-y-6">

        {{-- ===== HEADER CARD ===== --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 via-indigo-500 to-violet-600 p-6 shadow-lg">
            <div class="pointer-events-none absolute -right-8 -top-8 h-40 w-40 rounded-full bg-white/10"></div>
            <div class="pointer-events-none absolute -bottom-6 right-20 h-24 w-24 rounded-full bg-white/10"></div>
            <div class="flex items-center gap-5">
                @if ($karyawan->foto)
                    <img src="{{ asset('storage/unggah/karyawan/' . $karyawan->foto) }}"
                         class="h-20 w-20 rounded-2xl object-cover ring-4 ring-white/30 shadow-lg" />
                @else
                    <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-white/20 text-4xl text-white ring-4 ring-white/30 shadow-lg">
                        <i class="ri-user-fill"></i>
                    </div>
                @endif
                <div>
                    <h2 class="text-2xl font-bold text-white">{{ $karyawan->nama_lengkap }}</h2>
                    <p class="mt-1 text-sm text-indigo-200">{{ $karyawan->jabatan }}</p>
                    <span class="mt-2 inline-flex items-center gap-1.5 rounded-full bg-white/20 px-3 py-1 text-xs font-semibold text-white">
                        <i class="ri-mail-line"></i>
                        {{ $karyawan->email }}
                    </span>
                </div>
            </div>
        </div>

        {{-- ===== FLASH MESSAGES ===== --}}
        @if (session('success'))
            <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                <i class="ri-checkbox-circle-fill text-lg flex-shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <i class="ri-error-warning-fill text-lg flex-shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if (session('password_success'))
            <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                <i class="ri-checkbox-circle-fill text-lg flex-shrink-0"></i>
                <span>{{ session('password_success') }}</span>
            </div>
        @endif
        @if (session('password_error'))
            <div class="flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <i class="ri-error-warning-fill text-lg flex-shrink-0"></i>
                <span>{{ session('password_error') }}</span>
            </div>
        @endif

        {{-- ===== EDIT FORM ===== --}}
        <form action="{{ route('karyawan.profile.update') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="foto_cropped" id="foto_cropped" />

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                {{-- Left: Form fields --}}
                <div class="lg:col-span-2">
                    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                        <h3 class="mb-5 text-sm font-semibold uppercase tracking-wide text-gray-500">Informasi Pribadi</h3>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700">Email</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="ri-mail-line text-gray-400"></i>
                                    </span>
                                    <input type="email" id="email" name="email"
                                           value="{{ old('email', $karyawan->email) }}"
                                           class="w-full rounded-xl border border-gray-300 py-2.5 pl-9 pr-4 text-sm text-gray-800 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors @error('email') border-red-400 @enderror" />
                                </div>
                                @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="nama_lengkap" class="mb-1.5 block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="ri-user-line text-gray-400"></i>
                                    </span>
                                    <input type="text" id="nama_lengkap" name="nama_lengkap"
                                           value="{{ $karyawan->nama_lengkap }}"
                                           class="w-full rounded-xl border border-gray-300 py-2.5 pl-9 pr-4 text-sm text-gray-800 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors @error('nama_lengkap') border-red-400 @enderror" />
                                </div>
                                @error('nama_lengkap')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="telepon" class="mb-1.5 block text-sm font-medium text-gray-700">Telepon</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="ri-phone-line text-gray-400"></i>
                                    </span>
                                    <input type="text" id="telepon" name="telepon"
                                           value="{{ $karyawan->telepon }}"
                                           class="w-full rounded-xl border border-gray-300 py-2.5 pl-9 pr-4 text-sm text-gray-800 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors @error('telepon') border-red-400 @enderror" />
                                </div>
                                @error('telepon')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit"
                                    class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md hover:shadow-indigo-300 hover:-translate-y-0.5 transition-all duration-200">
                                <i class="ri-save-line"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Right: Photo upload with cropper --}}
                <div class="lg:col-span-1">
                    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                        <h3 class="mb-5 text-sm font-semibold uppercase tracking-wide text-gray-500">Foto Profil</h3>
                        <div class="flex flex-col items-center gap-4">
                            @php $fotoSrc = $karyawan->foto
                                ? asset('storage/unggah/karyawan/' . $karyawan->foto)
                                : asset('img/carousel-3.jpg'); @endphp
                            <img id="avatarPreview"
                                 src="{{ $fotoSrc }}"
                                 class="h-32 w-32 rounded-2xl object-cover ring-4 ring-indigo-100 shadow" />

                            <label class="w-full cursor-pointer">
                                <div class="flex items-center justify-center gap-2 rounded-xl border-2 border-dashed border-indigo-200 bg-indigo-50 px-4 py-3 text-sm font-medium text-indigo-600 hover:bg-indigo-100 transition-colors">
                                    <i class="ri-crop-line text-lg"></i>
                                    <span>Pilih & Crop Foto</span>
                                </div>
                                <input type="file" name="foto" id="fotoInput" class="hidden" accept="image/*"
                                       onchange="openCropper(this)" />
                            </label>
                            <p class="text-xs text-gray-400 text-center">Foto akan di-crop otomatis menjadi 1:1</p>
                        </div>
                    </div>
                </div>

            </div>
        </form>

        {{-- ===== GANTI PASSWORD ===== --}}
        <form action="{{ route('karyawan.profile.password') }}" method="post">
            @csrf
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <h3 class="mb-1 text-sm font-semibold uppercase tracking-wide text-gray-500">Ganti Password</h3>
                <p class="mb-5 text-xs text-gray-400">Masukkan password lama terlebih dahulu untuk memperbarui password.</p>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Password Lama</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="ri-lock-password-line text-gray-400"></i>
                            </span>
                            <input type="password" name="current_password"
                                   placeholder="Password saat ini"
                                   class="w-full rounded-xl border border-gray-300 py-2.5 pl-9 pr-4 text-sm text-gray-800 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors @error('current_password') border-red-400 @enderror" />
                        </div>
                        @error('current_password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Password Baru</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="ri-lock-unlock-line text-gray-400"></i>
                            </span>
                            <input type="password" name="new_password"
                                   placeholder="Min. 6 karakter"
                                   class="w-full rounded-xl border border-gray-300 py-2.5 pl-9 pr-4 text-sm text-gray-800 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors @error('new_password') border-red-400 @enderror" />
                        </div>
                        @error('new_password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="ri-lock-fill text-gray-400"></i>
                            </span>
                            <input type="password" name="new_password_confirmation"
                                   placeholder="Ulangi password baru"
                                   class="w-full rounded-xl border border-gray-300 py-2.5 pl-9 pr-4 text-sm text-gray-800 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors" />
                        </div>
                    </div>
                </div>

                <div class="mt-5 flex justify-end">
                    <button type="submit"
                            class="flex items-center gap-2 rounded-xl px-6 py-2.5 text-sm font-semibold text-white shadow-md hover:-translate-y-0.5 transition-all duration-200"
                            style="background:linear-gradient(135deg,#dc2626,#be185d);">
                        <i class="ri-key-2-line"></i>
                        Ganti Password
                    </button>
                </div>
            </div>
        </form>

    </div>

    {{-- ===== CROPPER MODAL ===== --}}
    <div id="cropModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" style="display:none;">
        <div class="w-full max-w-sm rounded-2xl bg-white shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h4 class="text-sm font-bold text-gray-800">Crop Foto Profil</h4>
                <button onclick="closeCropModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="ri-close-line text-gray-500"></i>
                </button>
            </div>
            <div class="p-4" style="max-height:340px;overflow:hidden;">
                <img id="cropperImg" src="" alt="" style="max-width:100%;display:block;" />
            </div>
            <div class="flex justify-end gap-3 px-5 py-4 border-t border-gray-100">
                <button onclick="closeCropModal()"
                        class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">Batal</button>
                <button onclick="applyCrop()"
                        class="rounded-xl px-5 py-2 text-sm font-semibold text-white transition hover:-translate-y-0.5"
                        style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                    <i class="ri-crop-line mr-1"></i> Terapkan
                </button>
            </div>
        </div>
    </div>

@endsection
