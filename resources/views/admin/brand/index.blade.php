<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pengaturan Brand</h2>
            <p class="mt-0.5 text-sm text-gray-500">Kelola nama aplikasi, logo, favicon, dan identitas visual</p>
        </div>
    </x-slot>

    <div class="container mx-auto max-w-3xl px-5 pt-6 pb-10">

        @if (session('success'))
            <div class="mb-5 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                <i class="ri-checkbox-circle-fill text-lg flex-shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.brand.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- ── Identitas Aplikasi ── --}}
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                <div class="flex items-center gap-3 border-b border-gray-100 px-6 py-4">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-100">
                        <i class="ri-quill-pen-line text-indigo-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">Identitas Aplikasi</h3>
                        <p class="text-xs text-gray-400">Nama dan tagline yang tampil di seluruh halaman</p>
                    </div>
                </div>
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Nama Aplikasi <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_aplikasi"
                               value="{{ old('nama_aplikasi', $brand->nama_aplikasi) }}"
                               placeholder="contoh: Presensi App"
                               class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition @error('nama_aplikasi') border-red-400 @enderror" />
                        @error('nama_aplikasi') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        <p class="mt-1 text-xs text-gray-400">Tampil di sidebar, judul halaman, dan halaman login</p>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Tagline / Deskripsi Singkat</label>
                        <input type="text" name="tagline"
                               value="{{ old('tagline', $brand->tagline) }}"
                               placeholder="contoh: Sistem Manajemen Kehadiran"
                               class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                        <p class="mt-1 text-xs text-gray-400">Tampil di bawah nama aplikasi pada halaman login</p>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Teks Footer</label>
                        <input type="text" name="footer_text"
                               value="{{ old('footer_text', $brand->footer_text) }}"
                               placeholder="contoh: Nama Perusahaan Anda"
                               class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                        <p class="mt-1 text-xs text-gray-400">Tampil sebagai: © {{ date('Y') }} <em>teks footer</em></p>
                    </div>
                </div>
            </div>

            {{-- ── Logo ── --}}
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                <div class="flex items-center gap-3 border-b border-gray-100 px-6 py-4">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-violet-100">
                        <i class="ri-image-line text-violet-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">Logo</h3>
                        <p class="text-xs text-gray-400">Tampil di sidebar dan halaman login. Format: PNG, JPG, SVG, WebP. Max 2MB.</p>
                    </div>
                </div>
                <div class="px-6 py-5">
                    <div class="flex flex-col sm:flex-row items-start gap-6">
                        {{-- Preview --}}
                        <div class="flex-shrink-0">
                            <p class="mb-2 text-xs font-medium text-gray-500 uppercase tracking-wide">Preview</p>
                            <div class="flex h-24 w-24 items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 overflow-hidden" id="logo-preview-wrapper">
                                @if ($brand->logo)
                                    <img id="logo-preview" src="{{ $brand->logoUrl() }}" alt="Logo" class="h-full w-full object-contain p-1" />
                                @else
                                    <div id="logo-placeholder" class="flex h-full w-full items-center justify-center bg-indigo-100 rounded-2xl">
                                        <i class="ri-fingerprint-line text-4xl text-indigo-600"></i>
                                    </div>
                                    <img id="logo-preview" src="" alt="Logo" class="hidden h-full w-full object-contain p-1" />
                                @endif
                            </div>
                        </div>
                        {{-- Upload --}}
                        <div class="flex-1 space-y-3">
                            <input type="file" name="logo" id="logo-input" accept="image/png,image/jpeg,image/jpg,image/svg+xml,image/webp"
                                   onchange="previewFile('logo-input','logo-preview','logo-placeholder')"
                                   class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-700
                                          file:mr-3 file:rounded-lg file:border-0 file:bg-violet-50 file:px-3 file:py-1.5
                                          file:text-xs file:font-medium file:text-violet-600 hover:file:bg-violet-100 transition" />
                            @error('logo') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                            @if ($brand->logo)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="hapus_logo" value="1" class="rounded border-gray-300 text-red-500 focus:ring-red-500" />
                                    <span class="text-xs text-red-500 font-medium">Hapus logo saat ini</span>
                                </label>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Favicon ── --}}
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                <div class="flex items-center gap-3 border-b border-gray-100 px-6 py-4">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100">
                        <i class="ri-bookmark-line text-amber-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">Favicon</h3>
                        <p class="text-xs text-gray-400">Ikon kecil di tab browser. Format: PNG, JPG, ICO, SVG. Max 512KB.</p>
                    </div>
                </div>
                <div class="px-6 py-5">
                    <div class="flex flex-col sm:flex-row items-start gap-6">
                        {{-- Preview --}}
                        <div class="flex-shrink-0">
                            <p class="mb-2 text-xs font-medium text-gray-500 uppercase tracking-wide">Preview</p>
                            <div class="flex h-16 w-16 items-center justify-center rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 overflow-hidden">
                                <img id="favicon-preview"
                                     src="{{ $brand->faviconUrl() }}"
                                     alt="Favicon"
                                     class="h-8 w-8 object-contain" />
                            </div>
                        </div>
                        {{-- Upload --}}
                        <div class="flex-1 space-y-3">
                            <input type="file" name="favicon" id="favicon-input" accept="image/png,image/jpeg,image/ico,image/svg+xml"
                                   onchange="previewFile('favicon-input','favicon-preview',null)"
                                   class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-700
                                          file:mr-3 file:rounded-lg file:border-0 file:bg-amber-50 file:px-3 file:py-1.5
                                          file:text-xs file:font-medium file:text-amber-600 hover:file:bg-amber-100 transition" />
                            @error('favicon') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                            @if ($brand->favicon)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="hapus_favicon" value="1" class="rounded border-gray-300 text-red-500 focus:ring-red-500" />
                                    <span class="text-xs text-red-500 font-medium">Hapus favicon saat ini</span>
                                </label>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Submit ── --}}
            <div class="flex justify-end">
                <button type="submit"
                        class="flex items-center gap-2 rounded-xl bg-indigo-600 px-7 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <i class="ri-save-line"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <script>
        function previewFile(inputId, previewId, placeholderId) {
            const file    = document.getElementById(inputId).files[0];
            const preview = document.getElementById(previewId);
            if (!file) return;
            const reader  = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (placeholderId) {
                    const ph = document.getElementById(placeholderId);
                    if (ph) ph.classList.add('hidden');
                }
            };
            reader.readAsDataURL(file);
        }
    </script>
</x-app-layout>
