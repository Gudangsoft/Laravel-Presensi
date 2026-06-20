@extends("dashboard.layouts.main")

@section("container")
<div class="max-w-2xl mx-auto">

    <div class="flex items-center gap-3 mb-5">
        <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-orange-400 to-amber-600 flex items-center justify-center shadow">
            <i class="ri-time-line text-white text-xl"></i>
        </div>
        <div>
            <h1 class="text-lg font-bold text-gray-800">Pengajuan Lembur</h1>
            <p class="text-xs text-gray-400">Ajukan & pantau status lembur kamu</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 flex items-center gap-2 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
            <i class="ri-checkbox-circle-line"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Form Ajukan --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-5">
        <h2 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
            <i class="ri-add-circle-line text-orange-500"></i> Ajukan Lembur Baru
        </h2>
        <form method="POST" action="{{ route('karyawan.lembur.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Lembur</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal') }}" required
                        class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none @error('tanggal') border-red-400 @enderror">
                    @error('tanggal')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div></div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Jam Mulai</label>
                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai') }}" required
                        class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Jam Selesai</label>
                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai') }}" required
                        class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none">
                    @error('jam_selesai')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Keterangan / Alasan</label>
                <textarea name="keterangan" rows="3" required
                    class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none resize-none @error('keterangan') border-red-400 @enderror"
                    placeholder="Jelaskan alasan atau pekerjaan yang dilembur...">{{ old('keterangan') }}</textarea>
                @error('keterangan')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <button type="submit"
                class="w-full rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 text-sm transition-colors">
                <i class="ri-send-plane-line mr-1"></i> Kirim Pengajuan
            </button>
        </form>
    </div>

    {{-- Riwayat --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-bold text-gray-700">Riwayat Pengajuan</h2>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($lembur as $item)
            <div class="px-5 py-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800">
                            {{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('D MMM Y') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ substr($item->jam_mulai,0,5) }} – {{ substr($item->jam_selesai,0,5) }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $item->keterangan }}</p>
                        @if($item->catatan_admin)
                            <p class="text-xs text-indigo-600 mt-1 italic">Catatan: {{ $item->catatan_admin }}</p>
                        @endif
                    </div>
                    <span class="flex-shrink-0 inline-flex items-center rounded-full px-2.5 py-1 text-[10px] font-bold
                        bg-{{ $item->statusColor() }}-100 text-{{ $item->statusColor() }}-700">
                        {{ $item->statusLabel() }}
                    </span>
                </div>
            </div>
            @empty
            <div class="px-5 py-10 text-center text-gray-300">
                <i class="ri-inbox-line text-4xl block mb-2"></i>
                <p class="text-sm">Belum ada pengajuan lembur</p>
            </div>
            @endforelse
        </div>
        @if($lembur->hasPages())
        <div class="border-t border-gray-100 px-5 py-3">
            {{ $lembur->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
