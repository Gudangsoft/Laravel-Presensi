const CACHE = 'presensi-v1';

const PRECACHE = [
    '/',
    '/karyawan/dashboard',
    '/karyawan/presensi',
    '/karyawan/presensi/history',
    '/karyawan/profile',
];

const CACHE_ASSETS = [
    /\.css$/,
    /\.js$/,
    /fonts\./,
    /remixicon/,
    /leaflet/,
];

// Install — pre-cache shell pages
self.addEventListener('install', e => {
    e.waitUntil(
        caches.open(CACHE).then(c => c.addAll(PRECACHE)).then(() => self.skipWaiting())
    );
});

// Activate — clean old caches
self.addEventListener('activate', e => {
    e.waitUntil(
        caches.keys().then(keys =>
            Promise.all(keys.filter(k => k !== CACHE).map(k => caches.delete(k)))
        ).then(() => self.clients.claim())
    );
});

// Fetch strategy
self.addEventListener('fetch', e => {
    const { request } = e;
    const url = new URL(request.url);

    // Skip non-GET and cross-origin
    if (request.method !== 'GET' || url.origin !== location.origin) return;

    // Assets → cache first
    if (CACHE_ASSETS.some(r => r.test(url.href))) {
        e.respondWith(
            caches.match(request).then(cached => {
                if (cached) return cached;
                return fetch(request).then(res => {
                    const clone = res.clone();
                    caches.open(CACHE).then(c => c.put(request, clone));
                    return res;
                });
            })
        );
        return;
    }

    // Navigation → network first, fall back to cache
    if (request.mode === 'navigate') {
        e.respondWith(
            fetch(request)
                .then(res => {
                    const clone = res.clone();
                    caches.open(CACHE).then(c => c.put(request, clone));
                    return res;
                })
                .catch(() => caches.match(request).then(cached => {
                    if (cached) return cached;
                    // Generic offline page
                    return new Response(`
                        <!DOCTYPE html><html lang="id"><head>
                        <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
                        <title>Offline — Presensi</title>
                        <style>
                            *{margin:0;padding:0;box-sizing:border-box}
                            body{font-family:system-ui,sans-serif;background:#f1f5f9;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1rem}
                            .card{background:white;border-radius:24px;padding:2rem;text-align:center;max-width:320px;width:100%;box-shadow:0 4px 24px rgba(0,0,0,.08)}
                            .icon{width:72px;height:72px;background:#eef2ff;border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:2rem}
                            h1{font-size:1.1rem;font-weight:700;color:#1e293b;margin-bottom:.5rem}
                            p{font-size:.875rem;color:#64748b;line-height:1.6;margin-bottom:1.5rem}
                            button{background:#4f46e5;color:white;border:none;border-radius:12px;padding:.75rem 1.5rem;font-size:.875rem;font-weight:600;cursor:pointer;width:100%}
                        </style></head>
                        <body>
                            <div class="card">
                                <div class="icon">📡</div>
                                <h1>Tidak Ada Koneksi</h1>
                                <p>Halaman ini tidak tersedia offline. Periksa koneksi internet Anda dan coba lagi.</p>
                                <button onclick="location.reload()">Coba Lagi</button>
                            </div>
                        </body></html>
                    `, { headers: { 'Content-Type': 'text/html' } });
                }))
        );
    }
});
