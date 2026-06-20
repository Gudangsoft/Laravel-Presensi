@extends("dashboard.layouts.main")

@section("container")
<div class="max-w-2xl mx-auto">

    <div class="flex items-center gap-3 mb-5">
        <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-teal-400 to-cyan-600 flex items-center justify-center shadow">
            <i class="ri-contacts-book-2-line text-white text-xl"></i>
        </div>
        <div>
            <h1 class="text-lg font-bold text-gray-800">Direktori Karyawan</h1>
            <p class="text-xs text-gray-400">Kontak seluruh rekan kerja</p>
        </div>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('karyawan.direktori') }}" class="flex gap-2 mb-5">
        <div class="relative flex-1">
            <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau departemen..."
                class="w-full rounded-xl border border-gray-300 pl-9 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none">
        </div>
        <button type="submit" class="rounded-xl bg-teal-500 text-white px-4 py-2.5 text-sm font-semibold hover:bg-teal-600 transition-colors">
            Cari
        </button>
    </form>

    <div class="space-y-3">
        @forelse($karyawan as $item)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
            <img src="{{ $item->foto ? asset('storage/unggah/karyawan/'.$item->foto) : 'https://ui-avatars.com/api/?name='.urlencode($item->nama_lengkap).'&background=6366f1&color=fff&size=48' }}"
                 class="w-12 h-12 rounded-xl object-cover flex-shrink-0" alt="">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-gray-800">{{ $item->nama_lengkap }}</p>
                <p class="text-xs text-gray-500">{{ $item->jabatan ?: '–' }}</p>
                <span class="inline-flex items-center mt-1 rounded-full bg-indigo-50 px-2 py-0.5 text-[10px] font-semibold text-indigo-700">
                    {{ $item->departemen->kode }}
                </span>
            </div>
            <div class="flex flex-col items-end gap-1.5 flex-shrink-0">
                @if($item->telepon)
                <a href="tel:{{ $item->telepon }}"
                   class="flex items-center gap-1.5 text-xs text-emerald-600 font-medium hover:underline">
                    <i class="ri-phone-line"></i> {{ $item->telepon }}
                </a>
                @endif
                <a href="mailto:{{ $item->email }}"
                   class="flex items-center gap-1.5 text-xs text-gray-400 hover:text-indigo-600 transition-colors">
                    <i class="ri-mail-line"></i>
                    <span class="max-w-[120px] truncate">{{ $item->email }}</span>
                </a>
            </div>
        </div>
        @empty
        <div class="text-center py-16 text-gray-300">
            <i class="ri-user-search-line text-5xl block mb-2"></i>
            <p class="text-sm">Tidak ada karyawan ditemukan</p>
        </div>
        @endforelse
    </div>

    @if($karyawan->hasPages())
    <div class="mt-4">{{ $karyawan->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
