@extends("dashboard.layouts.main")

@section("js")
    <script>
        $(document).ready(function() {
            $("#searchButton").click(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "{{ route("karyawan.izin.search") }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        bulan: $("#bulan").val(),
                        tahun: $("#tahun").val()
                    },
                    cache: false,
                    success: function(res) {
                        $("#searchPresensi").html(res);
                    }
                });
            });
        });

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
    </script>
@endsection

@section("container")
    <div class="px-4 py-6">

        {{-- Page Header --}}
        <div class="mb-6 flex flex-col gap-1">
            <h1 class="text-xl font-bold text-gray-800">Data Izin / Sakit</h1>
            <p class="text-sm text-gray-500">Kelola riwayat pengajuan izin dan sakit kamu di sini.</p>
        </div>

        {{-- Card Utama --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

            {{-- Filter + Tombol --}}
            <div class="flex flex-col md:flex-row md:items-end gap-4 mb-6">

                {{-- Filter Bulan --}}
                <div class="flex-1">
                    <label for="bulan" class="block mb-1.5 text-xs font-semibold text-gray-600 uppercase tracking-wider">Bulan</label>
                    <select name="bulan" id="bulan" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-700 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" required>
                        @foreach ($bulan as $value => $item)
                            <option value="{{ $value + 1 }}" {{ ($value + 1) == date('n') ? 'selected' : '' }}>{{ $item }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Tahun --}}
                <div class="flex-1">
                    <label for="tahun" class="block mb-1.5 text-xs font-semibold text-gray-600 uppercase tracking-wider">Tahun</label>
                    <select name="tahun" id="tahun" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-700 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" required>
                        @php
                            $firstItem = $riwayatPengajuanPresensi->first();
                            $tahunMulai = $firstItem ? date("Y", strtotime($firstItem->tanggal_pengajuan)) : date("Y");
                        @endphp
                        @for ($tahun = $tahunMulai; $tahun <= date("Y"); $tahun++)
                            <option value="{{ $tahun }}" {{ $tahun == date('Y') ? 'selected' : '' }}>{{ $tahun }}</option>
                        @endfor
                    </select>
                </div>

                {{-- Tombol Cari --}}
                <div class="flex-shrink-0">
                    <button type="button" id="searchButton" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl px-5 py-2.5 text-sm font-medium transition-colors">
                        <i class="ri-search-2-line text-base"></i>
                        Cari
                    </button>
                </div>

                {{-- Tombol Buat Pengajuan --}}
                <div class="flex-shrink-0">
                    <a href="{{ route('karyawan.izin.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl px-5 py-2.5 text-sm font-medium transition-colors">
                        <i class="ri-add-line text-base"></i>
                        Buat Pengajuan
                    </a>
                </div>

            </div>

            {{-- Tabel --}}
            <div id="searchPresensi">
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Hari</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Keterangan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($riwayatPengajuanPresensi as $value => $item)
                                <tr class="hover:bg-gray-50 transition-colors">

                                    {{-- No --}}
                                    <td class="px-4 py-3.5 text-gray-500 font-medium">
                                        {{ $riwayatPengajuanPresensi->firstItem() + $value }}
                                    </td>

                                    {{-- Tanggal --}}
                                    <td class="px-4 py-3.5 text-gray-700 font-medium">
                                        {{ date("d-m-Y", strtotime($item->tanggal_pengajuan)) }}
                                    </td>

                                    {{-- Hari --}}
                                    <td class="px-4 py-3.5 text-gray-500">
                                        {{ date("l", strtotime($item->tanggal_pengajuan)) }}
                                    </td>

                                    {{-- Jenis (Izin / Sakit) --}}
                                    <td class="px-4 py-3.5">
                                        @if ($item->status == "I")
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                                <i class="ri-calendar-check-line mr-1"></i> Izin
                                            </span>
                                        @elseif ($item->status == "S")
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                                                <i class="ri-hospital-line mr-1"></i> Sakit
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-500">
                                                -
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Keterangan --}}
                                    <td class="px-4 py-3.5 text-gray-500 max-w-xs truncate">
                                        {{ $item->keterangan ?? '-' }}
                                    </td>

                                    {{-- Status Approval --}}
                                    <td class="px-4 py-3.5">
                                        @if ($item->status_approved == 0)
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Pending
                                            </span>
                                        @elseif ($item->status_approved == 1)
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Disetujui
                                            </span>
                                        @elseif ($item->status_approved == 2)
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span> Ditolak
                                            </span>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3 text-gray-400">
                                            <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center">
                                                <i class="ri-file-list-3-line text-2xl text-gray-300"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-500">Belum ada data pengajuan</p>
                                                <p class="text-xs text-gray-400 mt-0.5">Gunakan filter di atas atau buat pengajuan baru.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($riwayatPengajuanPresensi->hasPages())
                    <div class="mt-5">
                        {{ $riwayatPengajuanPresensi->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
