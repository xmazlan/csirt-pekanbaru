<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>
    {{ filled($title ?? null) ? $title.' - '.config('app.name', 'Laravel') : config('app.name', 'Laravel') }}
</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

@fonts

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance

<script>
    // Deteksi awal state rail sidebar untuk mencegah cumulative layout shift (CLS)
    try {
        if (localStorage.getItem('flux-sidebar-collapsed-desktop') === 'true' && window.innerWidth >= 1024) {
            document.documentElement.classList.add('sidebar-collapsed-init');
        }
    } catch (e) {}
</script>
