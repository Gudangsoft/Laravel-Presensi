<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Backup Database</h2>
                <p class="text-sm text-gray-500 mt-0.5">Kelola backup otomatis dan manual</p>
            </div>
            <button onclick="runBackup()" id="btn-backup"
                    class="flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold text-white transition active:scale-95"
                    style="background:linear-gradient(135deg,#10b981,#059669);">
                <i class="ri-database-2-line"></i> Backup Sekarang
            </button>
        </div>
    </x-slot>

    <div class="container mx-auto px-5 pt-5 pb-10">

        {{-- Info Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                        <i class="ri-file-list-3-line text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-800">{{ count($files) }}</p>
                        <p class="text-xs text-gray-400">Total File Backup</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#10b981,#059669);">
                        <i class="ri-hard-drive-2-line text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-800">{{ $totalSize }}</p>
                        <p class="text-xs text-gray-400">Total Ukuran</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#f59e0b,#d97706);">
                        <i class="ri-time-line text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-800">{{ $files[0]['time'] ?? '—' }}</p>
                        <p class="text-xs text-gray-400">Backup Terakhir</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Schedule Info --}}
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3.5 mb-6">
            <i class="ri-calendar-check-line text-emerald-600 text-lg"></i>
            <p class="text-sm text-emerald-800">
                <strong>Backup otomatis</strong> berjalan setiap hari pukul <strong>02:00 WIB</strong> via Laravel Scheduler.
                Maksimal <strong>7 file</strong> tersimpan (file lama otomatis dihapus).
            </p>
        </div>

        {{-- File List --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">File Backup</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse ($files as $f)
                    <div class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50/60 transition-colors">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#eff6ff;">
                            <i class="ri-database-2-line text-blue-500"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $f['name'] }}</p>
                            <div class="flex items-center gap-3 mt-0.5">
                                <span class="text-xs text-gray-400">{{ $f['size'] }}</span>
                                <span class="text-xs text-gray-400">{{ $f['time'] }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <a href="{{ route('admin.backup.download', $f['name']) }}"
                               class="flex items-center gap-1.5 rounded-lg border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-100 transition">
                                <i class="ri-download-line"></i> Download
                            </a>
                            <button onclick="deleteBackup('{{ $f['name'] }}')"
                                    class="flex items-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-100 transition">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="py-16 text-center text-gray-400">
                        <i class="ri-database-2-line text-4xl mb-2 block"></i>
                        <p class="text-sm">Belum ada file backup</p>
                        <p class="text-xs mt-1">Klik "Backup Sekarang" untuk membuat backup pertama</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function runBackup() {
            const btn = document.getElementById('btn-backup');
            btn.disabled = true;
            btn.innerHTML = '<i class="ri-loader-4-line animate-spin"></i> Memproses...';

            fetch('{{ route('admin.backup.run') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Backup Berhasil',
                        text: data.message,
                        confirmButtonColor: '#4f46e5',
                    }).then(() => location.reload());
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(err => {
                Swal.fire({ icon: 'error', title: 'Gagal', text: err.message });
                btn.disabled = false;
                btn.innerHTML = '<i class="ri-database-2-line"></i> Backup Sekarang';
            });
        }

        function deleteBackup(name) {
            Swal.fire({
                title: 'Hapus File Backup?',
                text: name,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
            }).then(r => {
                if (!r.isConfirmed) return;
                fetch('{{ route('admin.backup.delete') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ filename: name }),
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) location.reload();
                    else Swal.fire({ icon: 'error', title: 'Gagal', text: data.message });
                });
            });
        }
    </script>
</x-app-layout>
