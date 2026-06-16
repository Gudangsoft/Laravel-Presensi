<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Notifikasi</h2>
                <p class="text-sm text-gray-500 mt-0.5">Semua notifikasi sistem</p>
            </div>
            @if ($notifikasis->total() > 0)
                <form method="POST" action="{{ route('admin.notifikasi.baca-semua') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-2 rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                        <i class="ri-check-double-line"></i> Tandai Semua Dibaca
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="container mx-auto px-5 pt-5 pb-10 max-w-3xl">

        @forelse ($notifikasis as $n)
            @php
                $icon = match($n->type) {
                    'success' => 'ri-checkbox-circle-fill',
                    'warning' => 'ri-alert-fill',
                    'error'   => 'ri-error-warning-fill',
                    default   => 'ri-information-fill',
                };
                $iconColor = match($n->type) {
                    'success' => '#10b981',
                    'warning' => '#f59e0b',
                    'error'   => '#ef4444',
                    default   => '#6366f1',
                };
                $bg = match($n->type) {
                    'success' => '#f0fdf4',
                    'warning' => '#fffbeb',
                    'error'   => '#fef2f2',
                    default   => '#eef2ff',
                };
            @endphp
            <div class="flex gap-4 rounded-2xl mb-3 border p-4 transition-colors {{ $n->read_at ? 'bg-white border-gray-100' : 'bg-indigo-50 border-indigo-200' }}">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl"
                     style="background:{{ $bg }};">
                    <i class="{{ $icon }} text-xl" style="color:{{ $iconColor }};"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <p class="text-sm font-semibold text-gray-800 {{ $n->read_at ? '' : 'font-bold' }}">
                                {{ $n->title }}
                            </p>
                            <p class="text-sm text-gray-600 mt-0.5">{{ $n->message }}</p>
                        </div>
                        @if (!$n->read_at)
                            <span class="inline-block h-2 w-2 flex-shrink-0 rounded-full bg-indigo-500 mt-1.5"></span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="text-xs text-gray-400">{{ $n->created_at->diffForHumans() }}</span>
                        @if ($n->url)
                            <a href="{{ $n->url }}" class="text-xs text-indigo-600 font-medium hover:underline">
                                Lihat Detail <i class="ri-arrow-right-s-line"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-gray-100 bg-white p-16 text-center shadow-sm">
                <div class="mx-auto mb-3 flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-50">
                    <i class="ri-notification-3-line text-2xl text-indigo-400"></i>
                </div>
                <p class="text-sm font-semibold text-gray-600">Tidak ada notifikasi</p>
                <p class="mt-1 text-xs text-gray-400">Notifikasi akan muncul saat ada aktivitas</p>
            </div>
        @endforelse

        @if ($notifikasis->hasPages())
            <div class="mt-4">{{ $notifikasis->links() }}</div>
        @endif
    </div>
</x-app-layout>
