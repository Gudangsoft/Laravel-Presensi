@extends("dashboard.layouts.main")

@section("container")
<div class="flex min-h-[60vh] items-center justify-center">
    <div class="w-full max-w-sm">

        @if ($success)
            {{-- ── Success ── --}}
            <div class="rounded-3xl overflow-hidden shadow-xl">
                <div class="p-8 text-center text-white"
                     style="background:linear-gradient(135deg,#10b981,#059669);">
                    <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-2xl bg-white/20 backdrop-blur-sm">
                        <i class="ri-checkbox-circle-fill text-4xl"></i>
                    </div>
                    <h1 class="text-xl font-black">{{ $title }}</h1>
                    <p class="mt-1 text-sm opacity-90">{{ $message }}</p>
                </div>
                <div class="bg-white p-5 space-y-3">
                    <div class="flex items-center justify-between rounded-xl bg-gray-50 px-4 py-3">
                        <span class="text-xs font-medium text-gray-500">Nama</span>
                        <span class="text-sm font-bold text-gray-800">{{ $nama }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-gray-50 px-4 py-3">
                        <span class="text-xs font-medium text-gray-500">Jenis</span>
                        <span class="text-sm font-bold text-gray-800 capitalize">{{ $jenis }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-gray-50 px-4 py-3">
                        <span class="text-xs font-medium text-gray-500">Jam</span>
                        <span class="text-sm font-bold text-indigo-600 font-mono">{{ $jam }} WIB</span>
                    </div>
                    <a href="{{ route('karyawan.dashboard') }}"
                       class="mt-2 flex w-full items-center justify-center gap-2 rounded-xl py-3 text-sm font-bold text-white transition active:scale-95"
                       style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                        <i class="ri-home-3-line"></i> Ke Dashboard
                    </a>
                </div>
            </div>

        @else
            {{-- ── Error ── --}}
            <div class="rounded-3xl overflow-hidden shadow-xl">
                <div class="p-8 text-center text-white"
                     style="background:linear-gradient(135deg,#ef4444,#dc2626);">
                    <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-2xl bg-white/20 backdrop-blur-sm">
                        <i class="ri-error-warning-fill text-4xl"></i>
                    </div>
                    <h1 class="text-xl font-black">{{ $title }}</h1>
                    <p class="mt-1 text-sm opacity-90">{{ $message }}</p>
                </div>
                <div class="bg-white p-5">
                    <a href="{{ route('karyawan.dashboard') }}"
                       class="flex w-full items-center justify-center gap-2 rounded-xl py-3 text-sm font-bold text-white transition active:scale-95"
                       style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                        <i class="ri-home-3-line"></i> Ke Dashboard
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
