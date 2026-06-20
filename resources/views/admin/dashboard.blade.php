<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Dashboard</h2>
                <p class="text-sm text-gray-500" id="tanggal-header"></p>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right">
                    <p class="text-2xl font-bold text-indigo-600" id="jam-header">--:--:--</p>
                    <p class="text-xs text-gray-400" id="salam-header"></p>
                </div>
            </div>
        </div>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="px-5 py-5 space-y-5">

        {{-- ── Stat Cards ── --}}
        <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-6">

            {{-- Total Karyawan --}}
            <div class="col-span-2 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 p-5 text-white shadow-lg md:col-span-1 xl:col-span-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium opacity-80">Total Karyawan</p>
                        <p class="mt-1 text-4xl font-bold">{{ $totalKaryawan }}</p>
                    </div>
                    <div class="rounded-full bg-white/20 p-3">
                        <i class="ri-team-fill text-2xl"></i>
                    </div>
                </div>
                <p class="mt-3 text-xs opacity-70">
                    <i class="ri-bar-chart-fill"></i> {{ $rekapBulanIni }} presensi bulan ini
                </p>
            </div>

            {{-- Hadir --}}
            <div class="rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 p-5 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium opacity-80">Hadir Hari Ini</p>
                        <p class="mt-1 text-4xl font-bold" data-stat="hadir">{{ $rekapPresensi->jml_kehadiran ?? 0 }}</p>
                    </div>
                    <div class="rounded-full bg-white/20 p-3">
                        <i class="ri-user-follow-fill text-2xl"></i>
                    </div>
                </div>
                <div class="mt-3">
                    @php $pctHadir = $totalKaryawan > 0 ? round(($rekapPresensi->jml_kehadiran ?? 0) / $totalKaryawan * 100) : 0 @endphp
                    <div class="h-1.5 w-full rounded-full bg-white/30">
                        <div class="h-1.5 rounded-full bg-white" data-stat="pct_bar" style="width:{{ $pctHadir }}%"></div>
                    </div>
                    <p class="mt-1 text-xs opacity-70" data-stat="pct_hadir">{{ $pctHadir }}% dari total karyawan</p>
                </div>
            </div>

            {{-- Tidak Hadir --}}
            <div class="rounded-2xl bg-gradient-to-br from-slate-400 to-gray-600 p-5 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium opacity-80">Tidak Hadir</p>
                        <p class="mt-1 text-4xl font-bold" data-stat="tidak_hadir">{{ $totalTidakHadir }}</p>
                    </div>
                    <div class="rounded-full bg-white/20 p-3">
                        <i class="ri-user-unfollow-fill text-2xl"></i>
                    </div>
                </div>
                <p class="mt-3 text-xs opacity-70">
                    <i class="ri-information-line"></i> Belum ada keterangan
                </p>
            </div>

            {{-- Terlambat --}}
            <div class="rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 p-5 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium opacity-80">Terlambat</p>
                        <p class="mt-1 text-4xl font-bold" data-stat="terlambat">{{ $rekapPresensi->jml_terlambat ?? 0 }}</p>
                    </div>
                    <div class="rounded-full bg-white/20 p-3">
                        <i class="ri-time-fill text-2xl"></i>
                    </div>
                </div>
                <p class="mt-3 text-xs opacity-70">
                    <i class="ri-alarm-warning-line"></i> Masuk setelah {{ \Carbon\Carbon::parse($pengaturan->jam_masuk)->format('H:i') }}
                </p>
            </div>

            {{-- Sakit --}}
            <div class="rounded-2xl bg-gradient-to-br from-red-400 to-rose-600 p-5 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium opacity-80">Sakit</p>
<p class="mt-1 text-4xl font-bold" data-stat="sakit">{{ $rekapPengajuanPresensi->jml_sakit ?? 0 }}</p>
                    </div>
                    <div class="rounded-full bg-white/20 p-3">
                        <i class="ri-heart-pulse-fill text-2xl"></i>
                    </div>
                </div>
                <p class="mt-3 text-xs opacity-70">
                    <i class="ri-hospital-line"></i> Pengajuan disetujui hari ini
                </p>
            </div>

            {{-- Izin --}}
            <div class="rounded-2xl bg-gradient-to-br from-blue-400 to-cyan-600 p-5 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium opacity-80">Izin</p>
                        <p class="mt-1 text-4xl font-bold" data-stat="izin">{{ $rekapPengajuanPresensi->jml_izin ?? 0 }}</p>
                    </div>
                    <div class="rounded-full bg-white/20 p-3">
                        <i class="ri-file-list-3-fill text-2xl"></i>
                    </div>
                </div>
                <p class="mt-3 text-xs opacity-70">
                    <i class="ri-clipboard-line"></i> Pengajuan disetujui hari ini
                </p>
            </div>
        </div>

        {{-- ── Ulang Tahun Hari Ini ── --}}
        @if($ultahHariIni->isNotEmpty())
        <div class="rounded-2xl bg-gradient-to-r from-pink-500 via-rose-500 to-red-500 p-4 text-white shadow-lg">
            <div class="flex items-center gap-3 flex-wrap">
                <div class="text-3xl">🎂</div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold uppercase tracking-widest opacity-80">Ulang Tahun Hari Ini</p>
                    <div class="mt-1 flex flex-wrap gap-2">
                        @foreach($ultahHariIni as $k)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/20 px-3 py-1 text-sm font-semibold backdrop-blur-sm">
                            @if($k->foto)
                                <img src="{{ asset('storage/unggah/karyawan/' . $k->foto) }}" class="h-5 w-5 rounded-full object-cover">
                            @else
                                <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-white/30 text-xs font-bold">{{ strtoupper(substr($k->nama_lengkap,0,1)) }}</span>
                            @endif
                            {{ $k->nama_lengkap }}
                            <span class="opacity-75 text-xs">- {{ $k->jabatan }}</span>
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ── Charts ── --}}
        <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">

            {{-- Grafik kehadiran --}}
            <div class="xl:col-span-2 rounded-2xl bg-white p-5 shadow-lg">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-800">Grafik Kehadiran</h3>
                        <p id="chart-subtitle" class="text-xs text-gray-400">7 hari terakhir</p>
                    </div>
                    <div class="flex items-center gap-1 rounded-xl bg-gray-100 p-1">
                        <button onclick="switchPeriod('7days', '7 hari terakhir')" id="btn-7days"
                                class="chart-period-btn rounded-lg px-3 py-1 text-xs font-semibold transition bg-white text-indigo-600 shadow-sm">
                            7 Hari
                        </button>
                        <button onclick="switchPeriod('4weeks', '4 minggu terakhir')" id="btn-4weeks"
                                class="chart-period-btn rounded-lg px-3 py-1 text-xs font-semibold transition text-gray-500 hover:text-gray-700">
                            4 Minggu
                        </button>
                        <button onclick="switchPeriod('12months', '12 bulan terakhir')" id="btn-12months"
                                class="chart-period-btn rounded-lg px-3 py-1 text-xs font-semibold transition text-gray-500 hover:text-gray-700">
                            12 Bulan
                        </button>
                    </div>
                </div>
                <canvas id="chartKehadiran" height="100"></canvas>
            </div>

            {{-- Donut hari ini --}}
            <div class="rounded-2xl bg-white p-5 shadow-lg">
                <div class="mb-4">
                    <h3 class="font-bold text-gray-800">Status Hari Ini</h3>
                    <p class="text-xs text-gray-400">Komposisi kehadiran</p>
                </div>
                <canvas id="chartDonut" height="180"></canvas>
                <div class="mt-4 space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="flex items-center gap-2"><span class="inline-block h-3 w-3 rounded-full bg-emerald-400"></span> Hadir</span>
                        <span class="font-bold" data-leg="hadir">{{ $rekapPresensi->jml_kehadiran ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="flex items-center gap-2"><span class="inline-block h-3 w-3 rounded-full bg-gray-400"></span> Tidak Hadir</span>
                        <span class="font-bold" data-leg="tidak_hadir">{{ $totalTidakHadir }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="flex items-center gap-2"><span class="inline-block h-3 w-3 rounded-full bg-amber-400"></span> Terlambat</span>
                        <span class="font-bold" data-leg="terlambat">{{ $rekapPresensi->jml_terlambat ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="flex items-center gap-2"><span class="inline-block h-3 w-3 rounded-full bg-red-400"></span> Sakit</span>
                        <span class="font-bold" data-leg="sakit">{{ $rekapPengajuanPresensi->jml_sakit ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="flex items-center gap-2"><span class="inline-block h-3 w-3 rounded-full bg-blue-400"></span> Izin</span>
                        <span class="font-bold" data-leg="izin">{{ $rekapPengajuanPresensi->jml_izin ?? 0 }}</span>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100 text-center">
                    <span id="last-refreshed" class="text-[10px] text-gray-400 font-mono"></span>
                </div>
            </div>
        </div>

        {{-- ── Log Aktivitas + Tabel Presensi ── --}}
        <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">

        {{-- Log Aktivitas --}}
        <div class="xl:col-span-1 rounded-2xl bg-white shadow-lg flex flex-col">
            <div class="flex items-center justify-between border-b px-5 py-4">
                <div>
                    <h3 class="font-bold text-gray-800">Log Aktivitas</h3>
                    <p class="text-xs text-gray-400">10 aktivitas terbaru</p>
                </div>
                <a href="{{ route('admin.activity-log') }}"
                   class="flex items-center gap-1 rounded-xl border border-indigo-200 px-3 py-1.5 text-xs font-medium text-indigo-600 hover:bg-indigo-50 transition-colors">
                    Lihat Semua <i class="ri-arrow-right-line"></i>
                </a>
            </div>
            <div class="flex-1 overflow-y-auto px-4 py-3" style="max-height:480px">
                @forelse ($activityLogs as $log)
                    @php
                        $action = strtolower($log->action ?? '');
                        [$iconClass, $bgClass, $textClass] = match(true) {
                            str_contains($action, 'login')          => ['ri-login-circle-line',  'bg-emerald-100', 'text-emerald-600'],
                            str_contains($action, 'logout')         => ['ri-logout-circle-line', 'bg-gray-100',    'text-gray-500'],
                            str_contains($action, 'delete')
                            || str_contains($action, 'hapus')       => ['ri-delete-bin-line',    'bg-red-100',     'text-red-500'],
                            str_contains($action, 'create')
                            || str_contains($action, 'tambah')
                            || str_contains($action, 'add')         => ['ri-add-circle-line',    'bg-blue-100',    'text-blue-600'],
                            str_contains($action, 'update')
                            || str_contains($action, 'edit')
                            || str_contains($action, 'ubah')        => ['ri-edit-line',           'bg-amber-100',   'text-amber-600'],
                            str_contains($action, 'reset')
                            || str_contains($action, 'password')    => ['ri-lock-line',           'bg-violet-100',  'text-violet-600'],
                            str_contains($action, 'export')
                            || str_contains($action, 'download')    => ['ri-download-line',       'bg-teal-100',    'text-teal-600'],
                            str_contains($action, 'approve')
                            || str_contains($action, 'setuju')      => ['ri-check-double-line',   'bg-emerald-100', 'text-emerald-600'],
                            str_contains($action, 'reject')
                            || str_contains($action, 'tolak')       => ['ri-close-circle-line',   'bg-red-100',     'text-red-500'],
                            default                                 => ['ri-information-line',    'bg-indigo-100',  'text-indigo-600'],
                        };
                    @endphp
                    <div class="flex items-start gap-3 py-3 border-b border-gray-50 last:border-0">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full {{ $bgClass }} flex items-center justify-center mt-0.5">
                            <i class="{{ $iconClass }} text-sm {{ $textClass }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-800 truncate">{{ $log->user_name ?? 'System' }}</p>
                            <p class="text-xs text-gray-500 leading-relaxed line-clamp-2 mt-0.5">{{ $log->description }}</p>
                            <p class="text-[10px] text-gray-400 mt-1 flex items-center gap-1">
                                <i class="ri-time-line"></i>
                                {{ $log->created_at?->diffForHumans() ?? '-' }}
                                @if($log->ip_address)
                                    <span class="ml-1 px-1.5 py-0.5 bg-gray-100 rounded text-gray-400 font-mono">{{ $log->ip_address }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                        <i class="ri-history-line text-3xl mb-2"></i>
                        <p class="text-sm">Belum ada aktivitas tercatat</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Tabel Presensi Hari Ini --}}
        <div class="xl:col-span-2 rounded-2xl bg-white shadow-lg">
            <div class="flex items-center justify-between border-b px-6 py-4">
                <div>
                    <h3 class="font-bold text-gray-800">Presensi Hari Ini</h3>
                    <p class="text-xs text-gray-400">Daftar karyawan yang sudah presensi masuk</p>
                </div>
                <a href="{{ route('admin.monitoring-presensi') }}"
                    class="flex items-center gap-1.5 rounded-xl border border-indigo-200 px-3 py-1.5 text-xs font-medium text-indigo-600 hover:bg-indigo-50 transition-colors">
                    Lihat Semua <i class="ri-arrow-right-line"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-50 text-xs text-gray-500">
                        <tr>
                            <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3">Karyawan</th>
                            <th class="px-6 py-3">Jabatan</th>
                            <th class="px-6 py-3">Jam Masuk</th>
                            <th class="px-6 py-3">Jam Keluar</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($karyawanHadir as $i => $k)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-3 text-gray-500">{{ $i + 1 }}</td>
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        @if ($k->foto)
                                            <img src="{{ asset('storage/unggah/karyawan/' . $k->foto) }}"
                                                class="h-9 w-9 rounded-full object-cover ring-2 ring-indigo-100"
                                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($k->nama_lengkap) }}&background=6366f1&color=fff'">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($k->nama_lengkap) }}&background=6366f1&color=fff"
                                                class="h-9 w-9 rounded-full object-cover">
                                        @endif
                                        <span class="font-medium text-gray-800">{{ $k->nama_lengkap }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-gray-500">{{ $k->jabatan }}</td>
                                <td class="px-6 py-3 font-mono font-semibold text-gray-700">{{ $k->jam_masuk ?? '-' }}</td>
                                <td class="px-6 py-3 font-mono text-gray-500">{{ $k->jam_keluar ?? '-' }}</td>
                                <td class="px-6 py-3">
                                    @if ($k->jam_masuk && $k->jam_masuk > $pengaturan->jam_masuk)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-700">
                                            <i class="ri-time-line"></i> Terlambat
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-700">
                                            <i class="ri-check-line"></i> Tepat Waktu
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center">
                                    <div class="flex flex-col items-center gap-2 text-gray-400">
                                        <i class="ri-user-search-line text-4xl"></i>
                                        <p class="text-sm">Belum ada karyawan yang presensi hari ini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        </div>{{-- end grid log+presensi --}}

    </div>

    <script>
        // ── Jam & Salam ──────────────────────────────────────────
        const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        const hari  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

        function updateClock() {
            const now = new Date();
            const h = String(now.getHours()).padStart(2,'0');
            const m = String(now.getMinutes()).padStart(2,'0');
            const s = String(now.getSeconds()).padStart(2,'0');
            document.getElementById('jam-header').textContent = `${h}:${m}:${s}`;

            const jam = now.getHours();
            let salam = jam < 11 ? 'Selamat Pagi' : jam < 15 ? 'Selamat Siang' : jam < 18 ? 'Selamat Sore' : 'Selamat Malam';
            document.getElementById('salam-header').textContent = salam;

            const tglEl = document.getElementById('tanggal-header');
            if (tglEl) {
                tglEl.textContent = `${hari[now.getDay()]}, ${now.getDate()} ${bulan[now.getMonth()]} ${now.getFullYear()}`;
            }
        }
        updateClock();
        setInterval(updateClock, 1000);

        // ── Grafik Kehadiran (Bar) — period-switchable ──────────
        const labels7 = @json($labels7Hari);
        const data7   = @json($data7Hari);

        const barChart = new Chart(document.getElementById('chartKehadiran'), {
            type: 'bar',
            data: {
                labels: labels7,
                datasets: [{
                    label: 'Karyawan Hadir',
                    data: data7,
                    backgroundColor: 'rgba(99, 102, 241, 0.15)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                animation: { duration: 400 },
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.y} karyawan hadir` } }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 }, grid: { color: '#f3f4f6' } },
                    x: { grid: { display: false } }
                }
            }
        });

        function switchPeriod(period, subtitle) {
            document.querySelectorAll('.chart-period-btn').forEach(b => {
                b.classList.remove('bg-white', 'text-indigo-600', 'shadow-sm');
                b.classList.add('text-gray-500');
            });
            const active = document.getElementById('btn-' + period);
            active.classList.add('bg-white', 'text-indigo-600', 'shadow-sm');
            active.classList.remove('text-gray-500');
            document.getElementById('chart-subtitle').textContent = subtitle;

            fetch('{{ route('admin.dashboard.chart') }}?period=' + period)
                .then(r => r.json())
                .then(({ labels, data }) => {
                    barChart.data.labels   = labels;
                    barChart.data.datasets[0].data = data;
                    barChart.update();
                });
        }

        // ── Donut Status Hari Ini ────────────────────────────────
        const donutChart = new Chart(document.getElementById('chartDonut'), {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Tidak Hadir', 'Terlambat', 'Sakit', 'Izin'],
                datasets: [{
                    data: [
                        {{ $rekapPresensi->jml_kehadiran ?? 0 }},
                        {{ $totalTidakHadir }},
                        {{ $rekapPresensi->jml_terlambat ?? 0 }},
                        {{ $rekapPengajuanPresensi->jml_sakit ?? 0 }},
                        {{ $rekapPengajuanPresensi->jml_izin ?? 0 }},
                    ],
                    backgroundColor: ['#34d399','#9ca3af','#fbbf24','#f87171','#60a5fa'],
                    borderWidth: 0,
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                cutout: '70%',
                animation: { duration: 400 },
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed} orang` } }
                }
            }
        });

        // ── Real-time polling (60 s) ─────────────────────────────
        const statIds = {
            hadir:        document.querySelector('[data-stat="hadir"]'),
            tidak_hadir:  document.querySelector('[data-stat="tidak_hadir"]'),
            terlambat:    document.querySelector('[data-stat="terlambat"]'),
            sakit:        document.querySelector('[data-stat="sakit"]'),
            izin:         document.querySelector('[data-stat="izin"]'),
            pct_hadir:    document.querySelector('[data-stat="pct_hadir"]'),
            pct_bar:      document.querySelector('[data-stat="pct_bar"]'),
        };

        function refreshStats() {
            fetch('{{ route('admin.dashboard.stats') }}')
                .then(r => r.json())
                .then(d => {
                    if (statIds.hadir)       statIds.hadir.textContent = d.hadir;
                    if (statIds.tidak_hadir) statIds.tidak_hadir.textContent = d.tidak_hadir;
                    if (statIds.terlambat)   statIds.terlambat.textContent = d.terlambat;
                    if (statIds.sakit)       statIds.sakit.textContent = d.sakit;
                    if (statIds.izin)        statIds.izin.textContent = d.izin;
                    if (statIds.pct_hadir)   statIds.pct_hadir.textContent = d.pct_hadir + '% dari total karyawan';
                    if (statIds.pct_bar)     statIds.pct_bar.style.width = d.pct_hadir + '%';

                    // update donut
                    donutChart.data.datasets[0].data = [d.hadir, d.tidak_hadir, d.terlambat, d.sakit, d.izin];
                    donutChart.update();

                    // update legend values
                    ['hadir','tidak_hadir','terlambat','sakit','izin'].forEach(k => {
                        const leg = document.querySelector('[data-leg="' + k + '"]');
                        if (leg) leg.textContent = d[k];
                    });
                })
                .catch(() => {});
        }
        setInterval(refreshStats, 60000);

        // ── Last-refreshed badge ─────────────────────────────────
        const refreshBadge = document.getElementById('last-refreshed');
        function tickRefreshBadge() {
            if (refreshBadge) {
                const now = new Date();
                refreshBadge.textContent = 'Diperbarui ' +
                    String(now.getHours()).padStart(2,'0') + ':' +
                    String(now.getMinutes()).padStart(2,'0') + ':' +
                    String(now.getSeconds()).padStart(2,'0');
            }
        }
        tickRefreshBadge();
        setInterval(tickRefreshBadge, 1000);
    </script>
</x-app-layout>
