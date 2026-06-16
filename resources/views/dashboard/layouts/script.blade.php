{{-- Service Worker (Offline Mode) --}}
<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js').catch(() => {});
    }
    // Offline banner
    function setOfflineBanner(offline) {
        const b = document.getElementById('offline-banner');
        if (!b) return;
        if (offline) {
            b.classList.remove('hidden');
            document.body.style.paddingTop = '40px';
        } else {
            b.classList.add('hidden');
            document.body.style.paddingTop = '';
        }
    }
    setOfflineBanner(!navigator.onLine);
    window.addEventListener('offline', () => setOfflineBanner(true));
    window.addEventListener('online',  () => setOfflineBanner(false));
</script>

{{-- Sidebar mobile toggle --}}
<script src="{{ asset("js/sidenav-burger.js") }}"></script>

{{-- Alpine dark mode init --}}
<script src="{{ asset('js/init-alpine.js') }}"></script>

{{-- ChartJS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- JQuery --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

{{-- DataTables --}}
<script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

{{-- Sweetalert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
