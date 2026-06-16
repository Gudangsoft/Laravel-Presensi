<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">QR Presensi</h2>
                <p class="text-sm text-gray-500 mt-0.5">Generate QR Code untuk presensi masuk/keluar tanpa selfie</p>
            </div>
        </div>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

    <div class="container mx-auto px-5 pt-5 pb-10">
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            {{-- ── Generate Panel ── --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center">
                        <i class="ri-qr-code-line text-indigo-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Generate QR Code</h3>
                        <p class="text-xs text-gray-400">QR berlaku sesuai durasi yang dipilih</p>
                    </div>
                </div>

                {{-- Form --}}
                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jenis Presensi</label>
                        <select id="qr-jenis"
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                            <option value="masuk">Masuk</option>
                            <option value="keluar">Keluar</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Durasi Berlaku</label>
                        <select id="qr-durasi"
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                            <option value="5">5 menit</option>
                            <option value="10" selected>10 menit</option>
                            <option value="15">15 menit</option>
                            <option value="30">30 menit</option>
                        </select>
                    </div>
                </div>
                <button onclick="generateQr()" id="btn-generate"
                        class="w-full flex items-center justify-center gap-2 rounded-xl py-3 text-sm font-semibold text-white transition active:scale-95"
                        style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                    <i class="ri-qr-code-line text-base"></i> Generate QR Code
                </button>

                {{-- QR Display --}}
                <div id="qr-panel" class="hidden mt-6 text-center">
                    <div class="inline-block rounded-2xl border-4 border-indigo-100 p-3 bg-white shadow-inner">
                        <canvas id="qr-canvas"></canvas>
                    </div>

                    {{-- Info --}}
                    <div class="mt-4 space-y-2">
                        <div id="qr-jenis-badge" class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-sm font-bold text-white"
                             style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                            <i class="ri-login-circle-line"></i> <span id="qr-jenis-label">Masuk</span>
                        </div>

                        {{-- Countdown --}}
                        <div class="flex items-center justify-center gap-2">
                            <i class="ri-timer-line text-gray-400 text-sm"></i>
                            <span class="text-sm text-gray-600">Berlaku: </span>
                            <span id="countdown" class="text-sm font-bold font-mono text-indigo-600">--:--</span>
                        </div>

                        {{-- URL --}}
                        <div class="bg-gray-50 rounded-xl px-4 py-2 text-xs font-mono text-gray-500 break-all" id="qr-url-text"></div>
                    </div>

                    <div class="flex gap-2 mt-4">
                        <button onclick="printQr()" class="flex-1 flex items-center justify-center gap-1.5 rounded-xl border border-gray-300 px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                            <i class="ri-printer-line"></i> Print
                        </button>
                        <button onclick="invalidateQr()" id="btn-invalidate"
                                class="flex-1 flex items-center justify-center gap-1.5 rounded-xl border border-red-200 bg-red-50 px-3 py-2.5 text-sm font-medium text-red-600 hover:bg-red-100 transition">
                            <i class="ri-close-circle-line"></i> Nonaktifkan
                        </button>
                    </div>
                </div>
            </div>

            {{-- ── Active/Recent Sessions ── --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="ri-history-line text-indigo-600 text-lg"></i>
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Riwayat QR</h3>
                    </div>
                    <span class="text-xs text-gray-400">{{ $sessions->total() }} sesi</span>
                </div>
                <div class="divide-y divide-gray-50 max-h-[520px] overflow-y-auto">
                    @forelse ($sessions as $s)
                        @php
                            $state = $s->isUsed() ? 'used' : ($s->isExpired() ? 'expired' : 'active');
                            $badge = match($state) {
                                'active'  => 'bg-emerald-100 text-emerald-700',
                                'used'    => 'bg-blue-100 text-blue-700',
                                'expired' => 'bg-gray-100 text-gray-500',
                            };
                            $label = match($state) {
                                'active'  => 'Aktif',
                                'used'    => 'Digunakan',
                                'expired' => 'Kedaluwarsa',
                            };
                        @endphp
                        <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-gray-50/60 transition-colors">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0
                                {{ $s->jenis === 'masuk' ? 'bg-emerald-100' : 'bg-amber-100' }}">
                                <i class="ri-{{ $s->jenis === 'masuk' ? 'login-circle' : 'logout-circle' }}-line
                                   text-sm {{ $s->jenis === 'masuk' ? 'text-emerald-600' : 'text-amber-600' }}"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold text-gray-800 capitalize">{{ $s->jenis }}</span>
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold {{ $badge }}">{{ $label }}</span>
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $s->created_at->format('d M Y H:i') }}
                                    @if ($s->isUsed() && $s->karyawan)
                                        &bull; <span class="text-blue-500">{{ $s->karyawan->nama_lengkap }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-xs text-gray-400 text-right flex-shrink-0">
                                <div>exp: {{ $s->expires_at->format('H:i') }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="py-16 text-center text-gray-400">
                            <i class="ri-qr-code-line text-4xl mb-2 block"></i>
                            <p class="text-sm">Belum ada sesi QR</p>
                        </div>
                    @endforelse
                </div>
                @if ($sessions->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">{{ $sessions->links() }}</div>
                @endif
            </div>
        </div>
    </div>

    <script>
        let currentSessionId = null;
        let countdownTimer   = null;

        function generateQr() {
            const btn    = document.getElementById('btn-generate');
            const jenis  = document.getElementById('qr-jenis').value;
            const durasi = document.getElementById('qr-durasi').value;

            btn.innerHTML = '<i class="ri-loader-4-line animate-spin"></i> Generating...';
            btn.disabled  = true;

            fetch('{{ route('admin.qr-presensi.generate') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ jenis, durasi })
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) throw new Error(data.message);

                currentSessionId = null; // will be set after page refresh on invalidate
                document.getElementById('qr-panel').classList.remove('hidden');
                document.getElementById('qr-jenis-label').textContent = jenis === 'masuk' ? 'Presensi Masuk' : 'Presensi Keluar';
                document.getElementById('qr-url-text').textContent = data.scan_url;

                // Render QR
                const canvas = document.getElementById('qr-canvas');
                QRCode.toCanvas(canvas, data.scan_url, { width: 240, margin: 1, color: { dark: '#1e1b4b' } });

                // Store session id for invalidate
                window._qrToken = data.token;
                window._qrExpiresAt = data.expires_at;

                startCountdown(data.expires_at);

                btn.innerHTML = '<i class="ri-qr-code-line text-base"></i> Generate QR Code';
                btn.disabled  = false;

                // Reload list section after 1s
                setTimeout(() => location.reload(), 1500);
            })
            .catch(err => {
                Swal.fire({ icon: 'error', title: 'Gagal', text: err.message });
                btn.innerHTML = '<i class="ri-qr-code-line text-base"></i> Generate QR Code';
                btn.disabled  = false;
            });
        }

        function startCountdown(expiresMs) {
            clearInterval(countdownTimer);
            countdownTimer = setInterval(() => {
                const remaining = Math.max(0, expiresMs - Date.now());
                const m = String(Math.floor(remaining / 60000)).padStart(2, '0');
                const s = String(Math.floor((remaining % 60000) / 1000)).padStart(2, '0');
                const el = document.getElementById('countdown');
                el.textContent = `${m}:${s}`;
                if (remaining === 0) {
                    clearInterval(countdownTimer);
                    el.textContent = 'Kedaluwarsa';
                    el.classList.replace('text-indigo-600', 'text-red-500');
                }
            }, 1000);
        }

        function invalidateQr() {
            if (!window._qrToken) return;
            Swal.fire({
                title: 'Nonaktifkan QR?',
                text: 'QR Code akan langsung tidak berlaku.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Nonaktifkan',
                cancelButtonText: 'Batal',
            }).then(r => {
                if (!r.isConfirmed) return;
                fetch('{{ route('admin.qr-presensi.invalidate') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ token: window._qrToken })
                }).then(() => location.reload());
            });
        }

        function printQr() {
            const canvas = document.getElementById('qr-canvas');
            const url    = canvas.toDataURL();
            const jenis  = document.getElementById('qr-jenis-label').textContent;
            const w      = window.open('', '_blank', 'width=400,height=500');
            w.document.write(`
                <html><head><title>QR Presensi</title>
                <style>body{font-family:sans-serif;text-align:center;padding:2rem}
                img{border:4px solid #e0e7ff;border-radius:16px;padding:8px}
                h2{color:#1e1b4b}p{color:#6b7280;font-size:14px}</style></head>
                <body><h2>QR ${jenis}</h2><img src="${url}" /><p>${document.getElementById('qr-url-text').textContent}</p>
                <script>window.print();window.close()<\/script></body></html>
            `);
        }

        // If active QR exists on page load, show it
        @if ($activeQr)
            document.getElementById('qr-panel').classList.remove('hidden');
            document.getElementById('qr-jenis-label').textContent = '{{ $activeQr->jenis === 'masuk' ? 'Presensi Masuk' : 'Presensi Keluar' }}';
            document.getElementById('qr-url-text').textContent = '{{ $activeQr->scanUrl() }}';
            window._qrToken = '{{ $activeQr->token }}';
            QRCode.toCanvas(document.getElementById('qr-canvas'), '{{ $activeQr->scanUrl() }}', { width: 240, margin: 1, color: { dark: '#1e1b4b' } });
            startCountdown({{ $activeQr->expires_at->timestamp * 1000 }});
        @endif
    </script>
</x-app-layout>
