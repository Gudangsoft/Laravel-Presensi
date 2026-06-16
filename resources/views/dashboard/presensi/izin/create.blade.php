@extends("dashboard.layouts.main")

@section("container")
    <div class="flex justify-center px-4 py-6">
        <div class="w-full max-w-lg">

            {{-- Icon Header --}}
            <div class="mb-6 flex flex-col items-center">
                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-indigo-100">
                    <i class="ri-file-list-3-line text-3xl text-indigo-600"></i>
                </div>
                <h1 class="text-xl font-bold text-gray-800">Buat Pengajuan Izin / Sakit</h1>
                <p class="mt-1 text-sm text-gray-500">Isi form berikut untuk mengajukan izin atau sakit</p>
            </div>

            {{-- Card Form --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <form action="{{ route('karyawan.izin.store') }}" method="POST" enctype="multipart/form-data" id="pengajuanPresensiStore">
                    @csrf

                    {{-- Jenis Pengajuan --}}
                    <div class="mb-5">
                        <label for="status" class="mb-1.5 block text-sm font-semibold text-gray-700">
                            Jenis Pengajuan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <i class="ri-shield-check-line text-gray-400"></i>
                            </div>
                            <select
                                id="status"
                                name="status"
                                class="w-full appearance-none rounded-xl border border-gray-300 bg-white py-2.5 pl-9 pr-10 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 {{ $errors->has('status') ? 'border-red-400 focus:border-red-500 focus:ring-red-500' : '' }}"
                                required
                            >
                                <option disabled selected value="">-- Pilih Jenis Pengajuan --</option>
                                @foreach ($statusPengajuan as $item)
                                    <option value="{{ $item->value }}" {{ old('status') == $item->value ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5">
                                <i class="ri-arrow-down-s-line text-gray-400"></i>
                            </div>
                        </div>
                        @error("status")
                            <p class="mt-1.5 flex items-center gap-1 text-xs text-red-500">
                                <i class="ri-error-warning-line"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        @error("jenis_pembayaran")
                            <p class="mt-1.5 flex items-center gap-1 text-xs text-red-500">
                                <i class="ri-error-warning-line"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Tanggal Pengajuan --}}
                    <div class="mb-5">
                        <label for="tanggal_pengajuan" class="mb-1.5 block text-sm font-semibold text-gray-700">
                            Tanggal Pengajuan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <i class="ri-calendar-line text-gray-400"></i>
                            </div>
                            <input
                                type="date"
                                id="tanggal_pengajuan"
                                name="tanggal_pengajuan"
                                value="{{ old('tanggal_pengajuan') }}"
                                class="w-full rounded-xl border border-gray-300 py-2.5 pl-9 pr-4 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 {{ $errors->has('tanggal_pengajuan') ? 'border-red-400 focus:border-red-500 focus:ring-red-500' : '' }}"
                                required
                            />
                        </div>
                        @error("tanggal_pengajuan")
                            <p class="mt-1.5 flex items-center gap-1 text-xs text-red-500">
                                <i class="ri-error-warning-line"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div class="mb-6">
                        <label for="keterangan" class="mb-1.5 block text-sm font-semibold text-gray-700">
                            Keterangan
                            <span class="ml-1 font-normal text-gray-400">(opsional)</span>
                        </label>
                        <textarea
                            id="keterangan"
                            name="keterangan"
                            rows="4"
                            maxlength="500"
                            placeholder="Tuliskan keterangan izin atau sakit Anda di sini..."
                            class="w-full resize-none rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 {{ $errors->has('keterangan') ? 'border-red-400 focus:border-red-500 focus:ring-red-500' : '' }}"
                            oninput="updateCharCount(this)"
                        >{{ old('keterangan') }}</textarea>
                        <div class="mt-1 flex items-center justify-between">
                            @error("keterangan")
                                <p class="flex items-center gap-1 text-xs text-red-500">
                                    <i class="ri-error-warning-line"></i>
                                    {{ $message }}
                                </p>
                            @else
                                <span></span>
                            @enderror
                            <span id="charCount" class="text-xs text-gray-400">0 / 500</span>
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex flex-col gap-3">
                        <button
                            type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-indigo-800"
                        >
                            <i class="ri-save-line"></i>
                            Simpan Pengajuan
                        </button>
                        <a
                            href="{{ route('karyawan.izin') }}"
                            class="flex w-full items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-50 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
                        >
                            <i class="ri-arrow-left-line"></i>
                            Kembali
                        </a>
                    </div>

                </form>
            </div>

        </div>
    </div>

    <script>
        function updateCharCount(el) {
            const count = el.value.length;
            const max = el.getAttribute('maxlength') || 500;
            const counter = document.getElementById('charCount');
            if (counter) {
                counter.textContent = count + ' / ' + max;
                if (count >= max * 0.9) {
                    counter.classList.add('text-amber-500');
                    counter.classList.remove('text-gray-400');
                } else {
                    counter.classList.remove('text-amber-500');
                    counter.classList.add('text-gray-400');
                }
            }
        }

        // Init counter on page load for old() value
        document.addEventListener('DOMContentLoaded', function () {
            const textarea = document.getElementById('keterangan');
            if (textarea && textarea.value.length > 0) {
                updateCharCount(textarea);
            }
        });
    </script>
@endsection
