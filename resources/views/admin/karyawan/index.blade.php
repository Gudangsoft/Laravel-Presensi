<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800">Data Karyawan</h2>
                <div class="flex items-center gap-2">
                    {{-- Export --}}
                    <button onclick="document.getElementById('export_modal').classList.remove('hidden')"
                            class="flex items-center gap-1.5 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-medium text-emerald-700 hover:bg-emerald-100 transition-colors">
                        <i class="ri-file-excel-2-line text-base"></i>
                        <span class="hidden sm:inline">Export</span>
                    </button>
                    {{-- Import --}}
                    <button onclick="document.getElementById('import_modal').classList.remove('hidden')"
                            class="flex items-center gap-1.5 rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-medium text-blue-700 hover:bg-blue-100 transition-colors">
                        <i class="ri-upload-2-line text-base"></i>
                        <span class="hidden sm:inline">Import</span>
                    </button>
                    {{-- Tambah --}}
                    <button onclick="document.getElementById('create_modal').classList.remove('hidden')"
                            class="flex items-center gap-1.5 rounded-xl bg-indigo-600 px-3 py-2 text-xs font-medium text-white hover:bg-indigo-700 transition-colors">
                        <i class="ri-user-add-line text-base"></i>
                        <span class="hidden sm:inline">Tambah</span>
                    </button>
                </div>
            </div>
            <div class="flex flex-1 items-center gap-3">
                <form action="{{ route('admin.karyawan') }}" method="get" class="flex flex-1 items-center gap-2 sm:max-w-xl">
                    <div class="relative flex-1">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <i class="ri-search-line text-base"></i>
                        </span>
                        <input type="text" name="nama_karyawan" placeholder="Cari nama atau departemen..."
                               value="{{ request()->nama_karyawan }}"
                               class="w-full rounded-xl border border-gray-300 py-2 pl-9 pr-4 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    </div>
                    <select name="kode_departemen"
                            class="rounded-xl border border-gray-300 py-2 pl-3 pr-8 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Departemen</option>
                        @foreach ($departemen as $item)
                            <option value="{{ $item->kode }}" @if ($item->kode == request()->kode_departemen) selected @endif>{{ $item->nama }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="flex items-center gap-1.5 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
                        <i class="ri-search-2-line"></i>
                        <span class="hidden sm:inline">Cari</span>
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="container mx-auto px-5 pt-5 pb-10">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-10">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Karyawan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Departemen</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jabatan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Telepon</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($karyawan as $value => $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-gray-500">
                                    {{ $karyawan->firstItem() + $value }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $item->foto ? asset('storage/unggah/karyawan/' . $item->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($item->nama_lengkap) . '&background=6366f1&color=fff&size=40' }}"
                                             alt="{{ $item->nama_lengkap }}"
                                             class="h-10 w-10 rounded-full object-cover flex-shrink-0 ring-2 ring-gray-100"
                                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($item->nama_lengkap) }}&background=6366f1&color=fff&size=40'" />
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">{{ $item->nama_lengkap }}</p>
                                            <p class="text-xs text-gray-400">{{ $item->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium text-indigo-700">
                                        {{ $item->departemen->kode }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item->jabatan }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item->telepon }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick="return edit_button('{{ $item->id }}')"
                                                class="inline-flex items-center justify-center rounded-lg border border-indigo-200 bg-indigo-50 p-1.5 text-indigo-600 hover:bg-indigo-100 hover:border-indigo-300 transition-colors" title="Edit">
                                            <i class="ri-pencil-line text-sm"></i>
                                        </button>
                                        <button onclick="resetPassword('{{ $item->id }}', '{{ addslashes($item->nama_lengkap) }}')"
                                                class="inline-flex items-center justify-center rounded-lg border border-amber-200 bg-amber-50 p-1.5 text-amber-600 hover:bg-amber-100 hover:border-amber-300 transition-colors" title="Reset Password">
                                            <i class="ri-lock-password-line text-sm"></i>
                                        </button>
                                        <button onclick="return delete_button('{{ $item->id }}', '{{ $item->nama_lengkap }}')"
                                                class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 p-1.5 text-red-500 hover:bg-red-100 hover:border-red-300 transition-colors" title="Hapus">
                                            <i class="ri-delete-bin-line text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2 text-gray-400">
                                        <i class="ri-user-search-line text-4xl"></i>
                                        <p class="text-sm">Tidak ada data karyawan ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($karyawan->hasPages())
                <div class="border-t border-gray-100 px-4 py-3">
                    {{ $karyawan->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- ======================== Modal Create ======================== --}}
    <div id="create_modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="document.getElementById('create_modal').classList.add('hidden')"></div>
        <div class="relative z-10 w-full max-w-lg rounded-2xl bg-white shadow-2xl flex flex-col max-h-[90vh]">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 flex-shrink-0">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-100">
                        <i class="ri-user-add-line text-indigo-600"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-800">Tambah Karyawan</h3>
                </div>
                <button onclick="document.getElementById('create_modal').classList.add('hidden')"
                        class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 transition-colors">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </div>
            <div class="overflow-y-auto flex-1 px-6 py-4">
                <form id="form_create" action="{{ route('admin.karyawan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        {{-- Departemen --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Departemen <span class="text-red-500">*</span></label>
                            <select name="departemen_id"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                                <option disabled selected>Pilih Departemen!</option>
                                @foreach ($departemen as $item)
                                    <option value="{{ $item->id }}" @if ($item->id == old('departemen_id')) selected @endif>{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            @error('departemen_id')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Nama Lengkap --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_lengkap" placeholder="Masukkan nama lengkap"
                                   value="{{ old('nama_lengkap') }}" required
                                   class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                            @error('nama_lengkap')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Jabatan --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Jabatan <span class="text-red-500">*</span></label>
                            <input type="text" name="jabatan" placeholder="Masukkan jabatan"
                                   value="{{ old('jabatan') }}" required
                                   class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                            @error('jabatan')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Telepon --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Telepon <span class="text-red-500">*</span></label>
                            <input type="text" name="telepon" placeholder="Masukkan nomor telepon"
                                   value="{{ old('telepon') }}" required
                                   class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                            @error('telepon')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Email --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" placeholder="Masukkan email"
                                   value="{{ old('email') }}" required
                                   class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                            @error('email')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Password --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password" placeholder="Masukkan password" required
                                   class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                            @error('password')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Foto --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Foto</label>
                            @error('foto') <p class="mb-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            <input type="file" name="foto" id="foto" accept="image/*" onchange="previewImage()"
                                   class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-700 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-1 file:text-xs file:font-medium file:text-indigo-600 hover:file:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                            <img class="img-preview mt-3 hidden max-h-36 rounded-xl object-cover border border-gray-200" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex items-center justify-between border-t border-gray-100 px-6 py-4 flex-shrink-0">
                <button type="button" onclick="document.getElementById('form_create').reset(); document.querySelector('.img-preview').classList.add('hidden');"
                        class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">Reset</button>
                <div class="flex gap-2">
                    <button onclick="document.getElementById('create_modal').classList.add('hidden')"
                            class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" form="form_create"
                            class="rounded-xl bg-indigo-600 px-5 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ======================== Modal Edit ======================== --}}
    <div id="edit_modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="document.getElementById('edit_modal').classList.add('hidden')"></div>
        <div class="relative z-10 w-full max-w-lg rounded-2xl bg-white shadow-2xl flex flex-col max-h-[90vh]">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 flex-shrink-0">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100">
                        <i class="ri-pencil-line text-amber-600"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-800">Ubah Data Karyawan</h3>
                </div>
                <button onclick="document.getElementById('edit_modal').classList.add('hidden')"
                        class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 transition-colors">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </div>
            <div class="overflow-y-auto flex-1 px-6 py-4">
                <form id="form_edit" action="{{ route('admin.karyawan.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" />
                    <div class="space-y-4">
                        {{-- Departemen --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Departemen <span class="text-red-500">*</span></label>
                            <select name="departemen_id" id="departemen_id"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            </select>
                            @error('departemen_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        {{-- Nama Lengkap --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required
                                   class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                            @error('nama_lengkap') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        {{-- Jabatan --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Jabatan <span class="text-red-500">*</span></label>
                            <input type="text" name="jabatan" placeholder="Jabatan" required
                                   class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                            @error('jabatan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        {{-- Telepon --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Telepon <span class="text-red-500">*</span></label>
                            <input type="text" name="telepon" placeholder="Telepon" required
                                   class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                            @error('telepon') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        {{-- Email --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" placeholder="Email" required
                                   class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                            @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        {{-- Password --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                Password Baru
                                <span class="text-xs font-normal text-gray-400 ml-1">(kosongkan jika tidak ingin mengubah)</span>
                            </label>
                            <input type="password" name="password" placeholder="Masukkan password baru"
                                   class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                            @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        {{-- Foto --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Foto</label>
                            @error('foto') <p class="mb-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            <input type="file" name="foto" id="foto_edit" accept="image/*" onchange="previewImageEdit()"
                                   class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-700 file:mr-3 file:rounded-lg file:border-0 file:bg-amber-50 file:px-3 file:py-1 file:text-xs file:font-medium file:text-amber-600 hover:file:bg-amber-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                            <img class="foto-edit-preview mt-3 hidden max-h-36 rounded-xl object-cover border border-gray-200" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex items-center justify-end gap-2 border-t border-gray-100 px-6 py-4 flex-shrink-0">
                <button onclick="document.getElementById('edit_modal').classList.add('hidden')"
                        class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" form="form_edit"
                        class="rounded-xl bg-amber-500 px-5 py-2 text-sm font-medium text-white hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 transition-colors">Perbarui</button>
            </div>
        </div>
    </div>

    {{-- ======================== Modal Import ======================== --}}
    <div id="import_modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="document.getElementById('import_modal').classList.add('hidden')"></div>
        <div class="relative z-10 w-full max-w-md rounded-2xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100">
                        <i class="ri-upload-2-line text-blue-600"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-800">Import Karyawan</h3>
                </div>
                <button onclick="document.getElementById('import_modal').classList.add('hidden')"
                        class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 transition-colors">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </div>
            <div class="px-6 py-5">
                {{-- Info --}}
                <div class="mb-4 flex items-start gap-3 rounded-xl bg-blue-50 border border-blue-100 px-4 py-3">
                    <i class="ri-information-line mt-0.5 flex-shrink-0 text-blue-500"></i>
                    <div class="text-sm text-blue-700">
                        <p class="font-medium">Format file: <span class="font-semibold">.xlsx / .xls / .csv</span></p>
                        <p class="mt-0.5 text-xs text-blue-500">Kolom: kode_departemen, nama_lengkap, jabatan, telepon, email, password</p>
                    </div>
                </div>
                <form id="form_import" action="{{ route('admin.karyawan.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Pilih File Excel</label>
                            <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                                   class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-700
                                          file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-1.5
                                          file:text-xs file:font-medium file:text-blue-600 hover:file:bg-blue-100
                                          focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex items-center justify-between border-t border-gray-100 px-6 py-4">
                <a href="{{ route('admin.karyawan.template') }}"
                   class="flex items-center gap-1.5 text-sm text-emerald-600 hover:text-emerald-700 font-medium transition-colors">
                    <i class="ri-download-2-line"></i>
                    Download Template
                </a>
                <div class="flex gap-2">
                    <button onclick="document.getElementById('import_modal').classList.add('hidden')"
                            class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" form="form_import"
                            class="rounded-xl bg-blue-600 px-5 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="ri-upload-2-line mr-1"></i>Import
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ======================== Modal Export ======================== --}}
    <div id="export_modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="document.getElementById('export_modal').classList.add('hidden')"></div>
        <div class="relative z-10 w-full max-w-sm rounded-2xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100">
                        <i class="ri-file-excel-2-line text-sm text-emerald-600"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-800">Export Karyawan</h3>
                </div>
                <button onclick="document.getElementById('export_modal').classList.add('hidden')"
                        class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 transition-colors">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </div>
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Filter Departemen</label>
                    <select id="export_dept"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition bg-white">
                        <option value="">Semua Departemen</option>
                        @foreach ($departemen as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <p class="text-xs text-gray-400">Biarkan kosong untuk mengexport semua departemen.</p>
            </div>
            <div class="flex items-center justify-end gap-2 border-t border-gray-100 px-6 py-4">
                <button onclick="document.getElementById('export_modal').classList.add('hidden')"
                        class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">Batal</button>
                <button onclick="doExport()"
                        class="rounded-xl bg-emerald-600 px-5 py-2 text-sm font-medium text-white hover:bg-emerald-700 transition-colors">
                    <i class="ri-download-2-line mr-1"></i>Download
                </button>
            </div>
        </div>
    </div>

    <script>
        function doExport() {
            const deptId = document.getElementById('export_dept').value;
            const url    = '{{ route('admin.karyawan.export') }}' + (deptId ? '?departemen_id=' + deptId : '');
            window.location.href = url;
            document.getElementById('export_modal').classList.add('hidden');
        }

        function previewImage() {
            const image = document.querySelector('#foto');
            const imgPreview = document.querySelector('.img-preview');
            imgPreview.classList.remove('hidden');
            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0]);
            oFReader.onload = function(e) { imgPreview.src = e.target.result; }
        }

        function previewImageEdit() {
            const image = document.querySelector('#foto_edit');
            const imgPreview = document.querySelector('.foto-edit-preview');
            imgPreview.classList.remove('hidden');
            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0]);
            oFReader.onload = function(e) { imgPreview.src = e.target.result; }
        }

        @if (session()->has('success'))
            Swal.fire({ title: 'Berhasil', text: '{{ session('success') }}', icon: 'success', confirmButtonColor: '#4f46e5', confirmButtonText: 'OK' });
        @endif
        @if (session()->has('error') && session()->has('import_errors'))
            const importErrors = @json(session('import_errors'));
            const errHtml = '<div style="text-align:left;max-height:260px;overflow-y:auto;padding:4px 0">' +
                importErrors.map(e => `<div style="display:flex;gap:8px;margin-bottom:6px;font-size:13px;color:#374151"><span style="color:#ef4444;flex-shrink:0">●</span><span>${e}</span></div>`).join('') +
                '</div>';
            Swal.fire({
                title: 'Detail Error Import',
                html: errHtml,
                icon: 'warning',
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'Tutup',
                footer: '<span style="font-size:12px;color:#6b7280">{{ session('error') }}</span>',
            });
        @elseif (session()->has('error'))
            Swal.fire({ title: 'Gagal', text: '{{ session('error') }}', icon: 'error', confirmButtonColor: '#4f46e5', confirmButtonText: 'OK' });
        @endif

        function edit_button(id) {
            document.getElementById('edit_modal').classList.remove('hidden');
            $("select[id='departemen_id']").children().remove();

            $.ajax({
                type: "get",
                url: "{{ route('admin.karyawan.edit') }}",
                data: { "_token": "{{ csrf_token() }}", "id": id },
                success: function(data) {
                    $("input[name='id']").val(data.id);
                    $("input[name='nama_lengkap']").val(data.nama_lengkap);
                    $("input[name='jabatan']").val(data.jabatan);
                    $("input[name='telepon']").val(data.telepon);
                    $("input[name='email']").val(data.email);

                    const departemen = @json($departemen);
                    let options = '<option disabled>Pilih Departemen!</option>';
                    departemen.forEach(item => {
                        const isSelected = item.id == data.departemen_id ? 'selected' : '';
                        options += `<option value="${item.id}" ${isSelected}>${item.nama}</option>`;
                    });
                    $("select[id='departemen_id']").html(options);

                    if (data.foto) {
                        $(".foto-edit-preview").attr("src", `{{ asset('storage/unggah/karyawan/') }}/${data.foto}`);
                        $(".foto-edit-preview").removeClass('hidden');
                    } else {
                        $(".foto-edit-preview").addClass('hidden');
                    }
                }
            });
        }

        function resetPassword(id, nama) {
            Swal.fire({
                title: 'Reset Password',
                html: `<p class="text-sm text-gray-500 mb-4">Karyawan: <b>${nama}</b></p>
                       <input id="swal-pwd" type="password" placeholder="Password baru (min. 8 karakter)"
                              class="swal2-input" style="font-size:14px;">
                       <input id="swal-pwd-confirm" type="password" placeholder="Konfirmasi password baru"
                              class="swal2-input" style="font-size:14px;">`,
                confirmButtonText: 'Reset Password',
                confirmButtonColor: '#d97706',
                cancelButtonText: 'Batal',
                showCancelButton: true,
                preConfirm: () => {
                    const pwd = document.getElementById('swal-pwd').value;
                    const confirm = document.getElementById('swal-pwd-confirm').value;
                    if (!pwd || pwd.length < 8) {
                        Swal.showValidationMessage('Password minimal 8 karakter');
                        return false;
                    }
                    if (pwd !== confirm) {
                        Swal.showValidationMessage('Konfirmasi password tidak cocok');
                        return false;
                    }
                    return { pwd, confirm };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('admin.karyawan.reset-password') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            new_password: result.value.pwd,
                            new_password_confirmation: result.value.confirm,
                        },
                        success: (r) => Swal.fire({ icon: 'success', title: 'Berhasil', text: r.message, confirmButtonColor: '#4f46e5' }),
                        error: (r) => Swal.fire({ icon: 'error', title: 'Gagal', text: r.responseJSON?.message ?? 'Terjadi kesalahan.' }),
                    });
                }
            });
        }

        function delete_button(id, nama) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "<p>Data yang dihapus tidak dapat dipulihkan kembali!</p><hr class='my-2 border-gray-200'><b>Karyawan: " + nama + "</b>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ route('admin.karyawan.delete') }}",
                        data: { "_token": "{{ csrf_token() }}", "id": id },
                        success: function(response) {
                            Swal.fire({ title: 'Berhasil', text: response.message, icon: 'success', confirmButtonColor: '#4f46e5', confirmButtonText: 'OK' })
                                .then((r) => { if (r.isConfirmed) location.reload(); });
                        },
                        error: function(response) {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: response.responseJSON.message });
                        }
                    });
                }
            });
        }
    </script>
</x-app-layout>
