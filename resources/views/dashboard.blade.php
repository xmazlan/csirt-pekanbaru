<x-layouts::app :title="__('Dashboard')">
    @php
        $service = app(\App\Services\CsirtDataService::class);
        $incidents = $service->incidentReports();
        $hof = $service->hallOfFameData();
        $articles = $service->articles();
        $openIncidents = count(array_filter($incidents, static fn (array $r): bool => ($r['status'] ?? '') === 'baru'));
        $processingIncidents = count(array_filter($incidents, static fn (array $r): bool => ($r['status'] ?? '') === 'diproses'));
    @endphp

    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <!-- Banner Identitas Operasional PKU-CSIRT Kota Pekanbaru -->
        <div class="rounded-2xl border border-amber-400/40 bg-linear-to-r from-[#072c22] via-[#0e4f3d] to-[#157a5f] p-6 text-white shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-1.5">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-400/20 px-3 py-0.5 text-xs font-bold text-amber-300 border border-amber-400/30">
                        Pusat Kendali PKU-CSIRT • Kota Bertuah
                    </span>
                    <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight text-white">
                        Dashboard Operasional Keamanan Siber Pekanbaru
                    </h1>
                    <p class="text-xs sm:text-sm text-emerald-100/90">
                        Dinas Komunikasi, Informatika, Statistik, dan Persandian Kota Pekanbaru — Kompleks Perkantoran Tenayan Raya
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <a
                        href="{{ route('dashboard.incidents') }}"
                        wire:navigate
                        class="inline-flex items-center gap-2 rounded-xl bg-amber-400 px-4 py-2.5 text-xs font-extrabold text-emerald-950 hover:bg-amber-300 transition-colors"
                    >
                        <flux:icon.shield-exclamation class="size-4" />
                        <span>Triase Insiden</span>
                    </a>
                    <a
                        href="{{ route('dashboard.hof') }}"
                        wire:navigate
                        class="inline-flex items-center gap-2 rounded-xl border border-amber-300/40 bg-emerald-900/70 px-4 py-2.5 text-xs font-bold text-white hover:bg-emerald-800 transition-colors"
                    >
                        <flux:icon.trophy class="size-4 text-amber-300" />
                        <span>Kelola Hall of Fame</span>
                    </a>
                    <a
                        href="{{ route('dashboard.articles') }}"
                        wire:navigate
                        class="inline-flex items-center gap-2 rounded-xl border border-amber-300/40 bg-emerald-900/70 px-4 py-2.5 text-xs font-bold text-white hover:bg-emerald-800 transition-colors"
                    >
                        <flux:icon.newspaper class="size-4 text-amber-300" />
                        <span>Kelola Berita</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Ringkasan Indikator Utama -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
            <div class="rounded-xl border border-app-border bg-app-surface p-5 shadow-2xs space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-app-text-muted">Tiket Insiden Baru</span>
                    <flux:icon.shield-exclamation class="size-5 text-danger" />
                </div>
                <p class="text-2xl font-extrabold text-app-text">{{ $openIncidents }} Tiket</p>
                <p class="text-xs text-app-text-secondary">{{ $processingIncidents }} dalam penanganan</p>
            </div>

            <div class="rounded-xl border border-app-border bg-app-surface p-5 shadow-2xs space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-app-text-muted">Total Laporan</span>
                    <flux:icon.inbox-stack class="size-5 text-primary" />
                </div>
                <p class="text-2xl font-extrabold text-app-text">{{ count($incidents) }} Laporan</p>
                <p class="text-xs text-app-text-secondary">Kanal Web &amp; Pos-el</p>
            </div>

            <div class="rounded-xl border border-app-border bg-app-surface p-5 shadow-2xs space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-app-text-muted">Hall of Fame</span>
                    <flux:icon.trophy class="size-5 text-amber-500" />
                </div>
                <p class="text-2xl font-extrabold text-app-text">{{ $hof['stats']['total_researchers'] ?? 0 }} Peneliti</p>
                <p class="text-xs text-app-text-secondary">{{ $hof['stats']['total_entries'] ?? 0 }} celah dimitigasi</p>
            </div>

            <div class="rounded-xl border border-app-border bg-app-surface p-5 shadow-2xs space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-app-text-muted">Publikasi Berita</span>
                    <flux:icon.newspaper class="size-5 text-emerald-600 dark:text-emerald-400" />
                </div>
                <p class="text-2xl font-extrabold text-app-text">{{ count($articles) }} Artikel</p>
                <p class="text-xs text-primary font-semibold">
                    <a href="{{ route('dashboard.articles') }}" wire:navigate class="hover:underline">Kelola &rarr;</a>
                </p>
            </div>

            <div class="rounded-xl border border-app-border bg-app-surface p-5 shadow-2xs space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-app-text-muted">Status RFC-2350</span>
                    <flux:icon.document-check class="size-5 text-info" />
                </div>
                <p class="text-2xl font-extrabold text-app-text">Versi 2.0</p>
                <p class="text-xs text-success font-semibold">Publik &amp; Terverifikasi</p>
            </div>
        </div>

        <!-- Tabel Tiket Insiden Terbaru & Pintasan Cepat -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-8 rounded-2xl border border-app-border bg-app-surface p-5 shadow-2xs space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-extrabold text-app-text">Antrean Laporan Insiden Terbaru</h2>
                        <p class="text-xs text-app-text-secondary">Tiket aduan siber &amp; kerentanan dari konstituen maupun peneliti publik</p>
                    </div>
                    <a
                        href="{{ route('dashboard.incidents') }}"
                        wire:navigate
                        class="text-xs font-bold text-primary hover:underline"
                    >
                        Kelola Semua &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <thead class="border-b border-app-border text-xs font-bold uppercase text-app-text-muted">
                            <tr>
                                <th class="py-2.5 pe-3">No. Tiket</th>
                                <th class="py-2.5 px-3">Kategori &amp; Sistem</th>
                                <th class="py-2.5 px-3">Pelapor</th>
                                <th class="py-2.5 ps-3 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-app-border">
                            @foreach (array_slice($incidents, 0, 5) as $item)
                                <tr>
                                    <td class="py-3 pe-3 font-mono text-xs font-bold text-primary">
                                        {{ $item['ticket_number'] }}
                                    </td>
                                    <td class="py-3 px-3">
                                        <p class="font-bold text-app-text">{{ $item['category'] }}</p>
                                        <p class="font-mono text-xs text-app-text-muted">{{ $item['affected_system'] }}</p>
                                    </td>
                                    <td class="py-3 px-3 text-xs text-app-text-secondary">
                                        {{ $item['reporter_name'] }}
                                    </td>
                                    <td class="py-3 ps-3 text-right">
                                        <span class="inline-flex rounded-full bg-primary-soft px-2.5 py-0.5 text-[11px] font-bold uppercase text-primary-content">
                                            {{ $item['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="lg:col-span-4 rounded-2xl border border-app-border bg-app-surface p-5 shadow-2xs space-y-4">
                <div>
                    <h2 class="text-base font-extrabold text-app-text">Akses Cepat Layanan Wajib</h2>
                    <p class="text-xs text-app-text-secondary">Pintasan ke halaman publik &amp; dokumen resmi</p>
                </div>

                <div class="space-y-2.5 text-xs font-semibold">
                    <a
                        href="{{ route('public.incident-report') }}"
                        wire:navigate
                        class="flex items-center justify-between rounded-xl border border-app-border bg-app-surface-muted p-3.5 text-app-text hover:border-primary/50 transition-colors"
                    >
                        <span class="flex items-center gap-2.5">
                            <flux:icon.shield-exclamation class="size-4 text-danger" />
                            <span>Halaman Melaporkan Insiden</span>
                        </span>
                        <flux:icon.arrow-right class="size-4 text-app-text-muted" />
                    </a>

                    <a
                        href="{{ route('public.hall-of-fame') }}"
                        wire:navigate
                        class="flex items-center justify-between rounded-xl border border-app-border bg-app-surface-muted p-3.5 text-app-text hover:border-primary/50 transition-colors"
                    >
                        <span class="flex items-center gap-2.5">
                            <flux:icon.trophy class="size-4 text-amber-500" />
                            <span>Halaman Publik Hall of Fame</span>
                        </span>
                        <flux:icon.arrow-right class="size-4 text-app-text-muted" />
                    </a>

                    <a
                        href="{{ route('public.rfc2350') }}"
                        wire:navigate
                        class="flex items-center justify-between rounded-xl border border-app-border bg-app-surface-muted p-3.5 text-app-text hover:border-primary/50 transition-colors"
                    >
                        <span class="flex items-center gap-2.5">
                            <flux:icon.document-text class="size-4 text-info" />
                            <span>Halaman Kepatuhan RFC-2350</span>
                        </span>
                        <flux:icon.arrow-right class="size-4 text-app-text-muted" />
                    </a>

                    <a
                        href="{{ route('dashboard.articles') }}"
                        wire:navigate
                        class="flex items-center justify-between rounded-xl border border-app-border bg-app-surface-muted p-3.5 text-app-text hover:border-primary/50 transition-colors"
                    >
                        <span class="flex items-center gap-2.5">
                            <flux:icon.newspaper class="size-4 text-emerald-600 dark:text-emerald-400" />
                            <span>Kelola Publikasi &amp; Berita</span>
                        </span>
                        <flux:icon.arrow-right class="size-4 text-app-text-muted" />
                    </a>

                    <a
                        href="{{ route('dashboard.media') }}"
                        wire:navigate
                        class="flex items-center justify-between rounded-xl border border-app-border bg-app-surface-muted p-3.5 text-app-text hover:border-primary/50 transition-colors"
                    >
                        <span class="flex items-center gap-2.5">
                            <flux:icon.photo class="size-4 text-emerald-600 dark:text-emerald-400" />
                            <span>Pustaka Media &amp; Gambar</span>
                        </span>
                        <flux:icon.arrow-right class="size-4 text-app-text-muted" />
                    </a>

                    <a
                        href="{{ route('home') }}"
                        wire:navigate
                        class="flex items-center justify-between rounded-xl border border-app-border bg-app-surface-muted p-3.5 text-app-text hover:border-primary/50 transition-colors"
                    >
                        <span class="flex items-center gap-2.5">
                            <flux:icon.globe-alt class="size-4 text-primary" />
                            <span>Portal Beranda Publik PKU-CSIRT</span>
                        </span>
                        <flux:icon.arrow-right class="size-4 text-app-text-muted" />
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
