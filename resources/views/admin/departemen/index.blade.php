<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __("Data Departemen") }}
            </h2>
            <button onclick="document.getElementById('create_modal').classList.remove('hidden')"
                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl px-4 py-2 font-medium text-sm transition-colors duration-150">
                <i class="ri-add-line text-base"></i>
                Tambah Departemen
            </button>
        </div>
    </x-slot>

    <div class="container mx-auto px-5 pt-5">

        {{-- Search & Table Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

            {{-- Search Bar --}}
            <form action="{{ route('admin.departemen') }}" method="get" class="mb-5">
                <div class="flex w-full flex-wrap gap-2 md:flex-nowrap">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="ri-search-2-line text-base"></i>
                        </span>
                        <input
                            type="text"
                            name="cari_departemen"
                            placeholder="Cari departemen..."
                            class="w-full border border-gray-300 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                            value="{{ request()->cari_departemen }}"
                        />
                    </div>
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl px-5 py-2.5 font-medium text-sm transition-colors duration-150">
                        <i class="ri-search-2-line text-base"></i>
                        Cari
                    </button>
                </div>
            </form>

            {{-- Table --}}
            <div class="w-full overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider rounded-tl-xl w-12">No</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode Departemen</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Departemen</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider rounded-tr-xl text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($departemen as $value => $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-100">
                                <td class="px-4 py-3.5 text-gray-500 font-medium">
                                    {{ $departemen->firstItem() + $value }}
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium text-indigo-700">
                                        {{ $item->kode }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-gray-700 font-medium">{{ $item->nama }}</td>
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            onclick="return edit_button('{{ $item->id }}')"
                                            class="inline-flex items-center gap-1.5 bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors duration-150 border border-amber-200">
                                            <i class="ri-pencil-fill text-sm"></i>
                                            Edit
                                        </button>
                                        <button
                                            onclick="return delete_button('{{ $item->id }}', '{{ $item->nama }}')"
                                            class="inline-flex items-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors duration-150 border border-red-200">
                                            <i class="ri-delete-bin-line text-sm"></i>
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @if ($departemen->isEmpty())
                            <tr>
                                <td colspan="4" class="px-4 py-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <i class="ri-inbox-2-line text-4xl"></i>
                                        <span class="text-sm">Tidak ada data departemen</span>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-5 pt-4 border-t border-gray-100">
                {{ $departemen->links() }}
            </div>

        </div>
    </div>

    {{-- ===================== Modal Create ===================== --}}
    <div id="create_modal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
             onclick="document.getElementById('create_modal').classList.add('hidden')"></div>

        {{-- Modal Box --}}
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 z-10">
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="ri-building-line text-indigo-600 text-base"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-800">Tambah {{ $title }}</h3>
                </div>
                <button onclick="document.getElementById('create_modal').classList.add('hidden')"
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5">
                <form action="{{ route('admin.departemen.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Kode --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Kode <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="kode"
                            placeholder="Contoh: DEP-001"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                            value="{{ old('kode') }}"
                            required
                        />
                        @error('kode')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="ri-error-warning-line"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Nama --}}
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Nama <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="nama"
                            placeholder="Nama departemen"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                            value="{{ old('nama') }}"
                            required
                        />
                        @error('nama')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="ri-error-warning-line"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-3">
                        <button type="reset"
                            class="flex-1 border border-gray-300 text-gray-600 hover:bg-gray-50 rounded-xl px-4 py-2.5 text-sm font-medium transition-colors">
                            Reset
                        </button>
                        <button type="submit"
                            class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl px-4 py-2.5 text-sm font-medium transition-colors">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- ===================== End Modal Create ===================== --}}

    {{-- ===================== Modal Edit ===================== --}}
    <div id="edit_modal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
             onclick="document.getElementById('edit_modal').classList.add('hidden')"></div>

        {{-- Modal Box --}}
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 z-10">
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                        <i class="ri-pencil-line text-amber-600 text-base"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-800">Ubah {{ $title }}</h3>
                </div>
                <button onclick="document.getElementById('edit_modal').classList.add('hidden')"
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5">
                <form action="{{ route('admin.departemen.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="id" hidden>

                    {{-- Kode --}}
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-sm font-semibold text-gray-700">
                                Kode <span class="text-red-500">*</span>
                            </label>
                            <span id="loading_edit1" class="text-xs text-indigo-500"></span>
                        </div>
                        <input
                            type="text"
                            name="kode"
                            placeholder="Kode departemen"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                            required
                        />
                        @error('kode')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="ri-error-warning-line"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Nama --}}
                    <div class="mb-5">
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-sm font-semibold text-gray-700">
                                Nama <span class="text-red-500">*</span>
                            </label>
                            <span id="loading_edit2" class="text-xs text-indigo-500"></span>
                        </div>
                        <input
                            type="text"
                            name="nama"
                            placeholder="Nama departemen"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                            required
                        />
                        @error('nama')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="ri-error-warning-line"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-3">
                        <button type="button"
                            onclick="document.getElementById('edit_modal').classList.add('hidden')"
                            class="flex-1 border border-gray-300 text-gray-600 hover:bg-gray-50 rounded-xl px-4 py-2.5 text-sm font-medium transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 bg-amber-500 hover:bg-amber-600 text-white rounded-xl px-4 py-2.5 text-sm font-medium transition-colors">
                            Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- ===================== End Modal Edit ===================== --}}

    <script>
        @if (session()->has('success'))
            Swal.fire({
                title: 'Berhasil',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonColor: '#4F46E5',
                confirmButtonText: 'OK',
            });
        @endif

        @if (session()->has('error'))
            Swal.fire({
                title: 'Gagal',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonColor: '#4F46E5',
                confirmButtonText: 'OK',
            });
        @endif

        function edit_button(id) {
            // Buka modal edit
            document.getElementById('edit_modal').classList.remove('hidden');

            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-indigo-600"></span>`;
            $("#loading_edit1").html(loading);
            $("#loading_edit2").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('admin.departemen.edit') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function(data) {
                    let items = [];
                    $.each(data, function(key, val) {
                        items.push(val);
                    });

                    $("input[name='id']").val(items[0]);
                    $("input[name='kode']").val(items[1]);
                    $("input[name='nama']").val(items[2]);

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                }
            });
        }

        function delete_button(id, nama) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "<p>Data yang dihapus tidak dapat dipulihkan kembali!</p>" +
                    "<hr class='my-3 border-gray-200'>" +
                    "<div class='flex flex-col'>" +
                    "<b>Departemen: " + nama + "</b>" +
                    "</div>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4F46E5',
                cancelButtonColor: '#EF4444',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ route('admin.departemen.delete') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: response.message,
                                icon: 'success',
                                confirmButtonColor: '#4F46E5',
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
                            });
                        }
                    });
                }
            });
        }
    </script>
</x-app-layout>
