<div>
    @php
        $profile = $this->profile;
        $hof = $this->hofSummary;
        $topResearchers = array_slice($hof['leaderboard'] ?? [], 0, 3);
        $recentFindings = array_slice($hof['entries'] ?? [], 0, 3);
        $slides = array_slice($this->articles, 0, 4);
    @endphp

    <!-- HERO SECTION: Identitas Kota Pekanbaru (Hijau Zamrud Melayu & Kuning Emas Lancang Kuning) -->
    <section class="relative overflow-hidden bg-linear-to-br from-[#072c22] via-[#0e4f3d] to-[#157a5f] text-white">
        <!-- Ornamen Latar Geometris Melayu (Songket / Pucuk Rebung Halus) -->
        <div
            class="pointer-events-none absolute inset-0 opacity-10"
            style="background-image: radial-gradient(#fbbf24 1px, transparent 1px), radial-gradient(#fbbf24 1px, #072c22 1px); background-size: 32px 32px; background-position: 0 0, 16px 16px;"
            aria-hidden="true"
        ></div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-14 lg:py-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                <!-- Kolom Kiri: Narasi & Identitas Kota Bertuah -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex flex-wrap items-center gap-2 rounded-full border border-amber-400/40 bg-emerald-950/60 px-3.5 py-1.5 text-xs font-semibold text-amber-300 shadow-xs">
                        <flux:icon.shield-check class="size-4 text-amber-400" />
                        <span>Tim Tanggap Insiden Siber Resmi Pemerintah Kota Pekanbaru</span>
                    </div>

                    <div class="space-y-3">
                        <p class="text-xs sm:text-sm font-bold uppercase tracking-widest text-amber-300">
                            {{ $profile['city_tagline'] }}
                        </p>
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight">
                            Menjaga Kedaulatan Siber <span class="text-amber-300">Kota Bertuah</span> Pekanbaru
                        </h1>
                        <p class="text-base sm:text-lg text-emerald-100/90 leading-relaxed max-w-2xl">
                            {{ $profile['hero_description'] }}
                        </p>
                    </div>

                    <!-- Semboyan Melayu Riau -->
                    <div class="rounded-xl border-l-4 border-amber-400 bg-emerald-950/55 px-4 py-3 backdrop-blur-xs">
                        <p class="text-xs font-semibold uppercase tracking-wider text-amber-300">
                            Semboyan Marwah Siber Melayu
                        </p>
                        <p class="mt-0.5 text-sm sm:text-base font-medium italic text-white">
                            “{{ $profile['malay_motto'] }}”
                        </p>
                    </div>

                    <!-- Tombol CTA Fitur Wajib -->
                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <a
                            href="{{ route('public.incident-report') }}"
                            wire:navigate
                            class="inline-flex items-center gap-2.5 rounded-xl bg-amber-400 px-5 py-3 text-sm font-extrabold text-emerald-950 shadow-md hover:bg-amber-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300 transition-colors cursor-pointer"
                        >
                            <flux:icon.shield-exclamation class="size-5 text-emerald-950" />
                            <span>Laporkan Insiden Siber</span>
                        </a>

                        <a
                            href="{{ route('public.hall-of-fame') }}"
                            wire:navigate
                            class="inline-flex items-center gap-2 rounded-xl border border-amber-300/50 bg-emerald-900/70 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-800/80 hover:border-amber-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300 transition-colors cursor-pointer"
                        >
                            <flux:icon.trophy class="size-5 text-amber-300" />
                            <span>Hall of Fame</span>
                        </a>

                        <a
                            href="{{ route('public.rfc2350') }}"
                            wire:navigate
                            class="inline-flex items-center gap-2 rounded-xl border border-emerald-400/30 bg-white/10 px-4 py-3 text-sm font-semibold text-emerald-50 hover:bg-white/20 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300 transition-colors cursor-pointer"
                        >
                            <flux:icon.document-text class="size-5 text-amber-300" />
                            <span>Standar RFC-2350</span>
                        </a>
                    </div>
                </div>

                <!-- Kolom Kanan: Slider Interaktif Kegiatan PKU-CSIRT -->
                <div
                    class="lg:col-span-5"
                    x-data="{
                        activeSlide: 0,
                        paused: false,
                        totalSlides: {{ count($slides) }},
                        timer: null,
                        init() {
                            const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                            if (!reduced && this.totalSlides > 1) {
                                this.startTimer();
                            }
                        },
                        startTimer() {
                            this.stopTimer();
                            this.timer = setInterval(() => {
                                if (!this.paused) {
                                    this.next();
                                }
                            }, 5500);
                        },
                        stopTimer() {
                            if (this.timer) {
                                clearInterval(this.timer);
                                this.timer = null;
                            }
                        },
                        next() {
                            this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
                        },
                        prev() {
                            this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides;
                        }
                    }"
                    @mouseenter="paused = true"
                    @mouseleave="paused = false"
                >
                    <div class="relative overflow-hidden rounded-2xl border-2 border-amber-400/50 bg-emerald-950 shadow-2xl">
                        <div class="relative aspect-16/10 w-full overflow-hidden bg-emerald-950">
                            @foreach ($slides as $index => $slide)
                                <div
                                    x-show="activeSlide === {{ $index }}"
                                    x-cloak
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 scale-105"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    class="absolute inset-0"
                                >
                                    <img
                                        src="{{ asset(ltrim((string) $slide['thumbnail'], '/')) }}"
                                        alt="{{ $slide['title'] }}"
                                        class="h-full w-full object-cover"
                                    />
                                    <div class="absolute inset-0 bg-linear-to-t from-emerald-950 via-emerald-950/50 to-transparent"></div>

                                    <div class="absolute inset-x-0 bottom-0 p-5 space-y-1.5">
                                        <span class="inline-flex items-center gap-1.5 rounded-md bg-amber-400 px-2 py-0.5 text-[11px] font-extrabold uppercase tracking-wider text-emerald-950">
                                            {{ $slide['category'] }}
                                        </span>
                                        <h2 class="text-base sm:text-lg font-bold text-white leading-snug">
                                            {{ $slide['title'] }}
                                        </h2>
                                        <p class="text-xs text-emerald-100/90 line-clamp-2">
                                            {{ $slide['excerpt'] }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Kontrol Navigasi Slider -->
                        <div class="flex items-center justify-between border-t border-emerald-800/80 bg-emerald-950/95 px-4 py-2.5">
                            <div class="flex items-center gap-1.5">
                                @foreach ($slides as $index => $slide)
                                    <button
                                        type="button"
                                        @click="activeSlide = {{ $index }}"
                                        aria-label="Tampilkan slide {{ $index + 1 }}"
                                        class="h-2 rounded-full transition-all cursor-pointer"
                                        :class="activeSlide === {{ $index }} ? 'w-6 bg-amber-400' : 'w-2 bg-emerald-700 hover:bg-emerald-500'"
                                    ></button>
                                @endforeach
                            </div>

                            <div class="flex items-center gap-1.5">
                                <button
                                    type="button"
                                    @click="prev()"
                                    aria-label="Slide sebelumnya"
                                    class="inline-flex size-7 items-center justify-center rounded-lg border border-emerald-700 text-emerald-100 hover:border-amber-400 hover:text-amber-300 transition-colors cursor-pointer"
                                >
                                    <flux:icon.chevron-left class="size-4" />
                                </button>
                                <button
                                    type="button"
                                    @click="paused = !paused"
                                    :aria-label="paused ? 'Putar otomatis slider' : 'Jeda otomatis slider'"
                                    class="inline-flex size-7 items-center justify-center rounded-lg border border-emerald-700 text-emerald-100 hover:border-amber-400 hover:text-amber-300 transition-colors cursor-pointer"
                                >
                                    <span x-show="!paused" class="text-[10px] font-bold">II</span>
                                    <span x-show="paused" x-cloak class="text-[10px] font-bold">▶</span>
                                </button>
                                <button
                                    type="button"
                                    @click="next()"
                                    aria-label="Slide berikutnya"
                                    class="inline-flex size-7 items-center justify-center rounded-lg border border-emerald-700 text-emerald-100 hover:border-amber-400 hover:text-amber-300 transition-colors cursor-pointer"
                                >
                                    <flux:icon.chevron-right class="size-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- BAR STATISTIK KETAHANAN SIBER KOTA PEKANBARU -->
    <section class="relative -mt-6 z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" aria-label="Ringkasan indikator PKU-CSIRT">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 rounded-2xl border border-app-border bg-app-surface p-5 shadow-lg">
            <div class="flex items-start gap-3.5 p-2">
                <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-primary-soft text-primary">
                    <flux:icon.globe-alt class="size-6" />
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-app-text-muted">Cakupan Konstituen</p>
                    <p class="text-lg font-extrabold text-app-text">*.pekanbaru.go.id</p>
                    <p class="text-xs text-app-text-secondary">Seluruh OPD Pemko Pekanbaru</p>
                </div>
            </div>

            <div class="flex items-start gap-3.5 p-2">
                <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-amber-500/15 text-amber-600 dark:text-amber-400">
                    <flux:icon.trophy class="size-6" />
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-app-text-muted">Hall of Fame</p>
                    <p class="text-lg font-extrabold text-app-text">{{ $hof['stats']['total_entries'] ?? 0 }} Laporan Valid</p>
                    <p class="text-xs text-app-text-secondary">{{ $hof['stats']['total_researchers'] ?? 0 }} Peneliti Keamanan</p>
                </div>
            </div>

            <div class="flex items-start gap-3.5 p-2">
                <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-info-soft text-info">
                    <flux:icon.document-check class="size-6" />
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-app-text-muted">Kepatuhan Standar</p>
                    <p class="text-lg font-extrabold text-app-text">IETF RFC-2350</p>
                    <p class="text-xs text-app-text-secondary">Dokumen Resmi Versi 2.0</p>
                </div>
            </div>

            <div class="flex items-start gap-3.5 p-2">
                <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-success-soft text-success">
                    <flux:icon.clock class="size-6" />
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-app-text-muted">Target Triase Awal</p>
                    <p class="text-lg font-extrabold text-app-text">&lt; 2 Jam Kerja</p>
                    <p class="text-xs text-app-text-secondary">Siaga Insiden Kritis 24/7</p>
                </div>
            </div>
        </div>
    </section>

    <!-- TIGA PILAR UTAMA PKU-CSIRT: Lapor Insiden, Hall of Fame, dan RFC-2350 -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16 space-y-12">
        <div class="text-center max-w-3xl mx-auto space-y-3">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-primary-soft px-3 py-1 text-xs font-bold uppercase tracking-wider text-primary-content">
                Portal Layanan Utama
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-app-text">
                Kolaborasi Publik & Transparansi Keamanan Siber Pekanbaru
            </h2>
            <p class="text-sm sm:text-base text-app-text-secondary">
                Akses langsung ke kanal pelaporan insiden terpadu, papan apresiasi peneliti keamanan siber, serta dokumen standar operasional resmi PKU-CSIRT.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kartu 1: Melaporkan Insiden Siber -->
            <div class="flex flex-col justify-between rounded-2xl border border-app-border bg-app-surface p-6 shadow-xs hover:border-primary/50 transition-colors">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex size-12 items-center justify-center rounded-xl bg-danger-soft text-danger">
                            <flux:icon.shield-exclamation class="size-6" />
                        </div>
                        <span class="rounded-full bg-danger-soft px-2.5 py-1 text-xs font-bold text-danger-content">
                            Respon Cepat
                        </span>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-app-text">
                            Melaporkan Insiden & Celah Keamanan
                        </h3>
                        <p class="text-sm text-app-text-secondary leading-relaxed">
                            Temukan indikasi peretasan, web defacement, phishing, atau celah keamanan pada domain <code class="rounded bg-app-surface-muted px-1.5 py-0.5 text-xs font-semibold text-primary">*.pekanbaru.go.id</code>? Laporkan secara aman dan dapatkan nomor tiket pelacakan instan.
                        </p>
                    </div>

                    <ul class="space-y-2 text-xs text-app-text-secondary border-t border-app-border pt-4">
                        <li class="flex items-center gap-2">
                            <flux:icon.check-circle class="size-4 text-success shrink-0" />
                            <span>Formulir interaktif dengan pembuatan Nomor Tiket otomatis</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <flux:icon.check-circle class="size-4 text-success shrink-0" />
                            <span>Unduhan langsung template resmi <strong>Formulir Aduan (.DOCX)</strong></span>
                        </li>
                        <li class="flex items-center gap-2">
                            <flux:icon.check-circle class="size-4 text-success shrink-0" />
                            <span>Integrasi pengiriman pos-el terformat ke <strong>csirt@pekanbaru.go.id</strong></span>
                        </li>
                    </ul>
                </div>

                <div class="mt-6 pt-4 border-t border-app-border flex flex-wrap items-center gap-2.5">
                    <a
                        href="{{ route('public.incident-report') }}"
                        wire:navigate
                        class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-xs font-bold text-white hover:bg-primary-dark transition-colors cursor-pointer"
                    >
                        <span>Buat Laporan Insiden</span>
                        <flux:icon.arrow-right class="size-4" />
                    </a>
                    <a
                        href="{{ asset('docs/Formulir-Aduan-Insiden-Siber-PKU-CSIRT-v.2.docx') }}"
                        download="Formulir-Aduan-Insiden-Siber-PKU-CSIRT-v.2.docx"
                        class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-app-border bg-app-surface-muted px-3.5 py-2.5 text-xs font-semibold text-app-text hover:border-primary/40 transition-colors cursor-pointer"
                        title="Unduh Formulir Aduan .DOCX"
                    >
                        <flux:icon.arrow-down-tray class="size-4 text-primary" />
                        <span>.DOCX</span>
                    </a>
                </div>
            </div>

            <!-- Kartu 2: Hall of Fame Peneliti Keamanan -->
            <div class="flex flex-col justify-between rounded-2xl border-2 border-amber-400/60 bg-app-surface p-6 shadow-md relative overflow-hidden">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex size-12 items-center justify-center rounded-xl bg-amber-500/15 text-amber-600 dark:text-amber-400">
                            <flux:icon.trophy class="size-6" />
                        </div>
                        <span class="rounded-full bg-amber-500/15 px-2.5 py-1 text-xs font-bold text-amber-700 dark:text-amber-300 border border-amber-500/30">
                            Apresiasi Kehormatan
                        </span>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-app-text">
                            Hall of Fame Security Researcher
                        </h3>
                        <p class="text-sm text-app-text-secondary leading-relaxed">
                            Penghargaan setinggi-tingginya dari Pemerintah Kota Pekanbaru kepada para peneliti keamanan siber atas kontribusi pelaporan celah keamanan secara beretika (<em>Responsible Disclosure</em>).
                        </p>
                    </div>

                    <!-- Mini Leaderboard Top 3 -->
                    <div class="space-y-2 border-t border-app-border pt-4">
                        <p class="text-xs font-bold uppercase tracking-wider text-app-text-muted">
                            Peringkat Kontributor Teratas
                        </p>
                        @foreach ($topResearchers as $researcher)
                            <div class="flex items-center justify-between rounded-lg bg-app-surface-muted px-3 py-2 text-xs">
                                <div class="flex items-center gap-2 font-semibold text-app-text">
                                    <span class="inline-flex size-5 items-center justify-center rounded-full bg-amber-400 text-[11px] font-extrabold text-emerald-950">
                                        {{ $researcher['position'] }}
                                    </span>
                                    <span>{{ $researcher['name'] }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="rounded bg-primary-soft px-2 py-0.5 font-bold text-primary-content">
                                        {{ $researcher['total'] }} Temuan
                                    </span>
                                    <span class="text-app-text-muted">CVSS {{ number_format((float) $researcher['highest_cvss'], 1) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-app-border">
                    <a
                        href="{{ route('public.hall-of-fame') }}"
                        wire:navigate
                        class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-amber-400 px-4 py-2.5 text-xs font-extrabold text-emerald-950 hover:bg-amber-300 transition-colors cursor-pointer"
                    >
                        <flux:icon.trophy class="size-4" />
                        <span>Lihat Leaderboard & Daftar Temuan</span>
                    </a>
                </div>
            </div>

            <!-- Kartu 3: Dokumen Kepatuhan RFC-2350 -->
            <div class="flex flex-col justify-between rounded-2xl border border-app-border bg-app-surface p-6 shadow-xs hover:border-primary/50 transition-colors">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex size-12 items-center justify-center rounded-xl bg-info-soft text-info">
                            <flux:icon.document-text class="size-6" />
                        </div>
                        <span class="rounded-full bg-info-soft px-2.5 py-1 text-xs font-bold text-info-content">
                            Standar IETF
                        </span>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-app-text">
                            Dokumen Kepatuhan RFC-2350
                        </h3>
                        <p class="text-sm text-app-text-secondary leading-relaxed">
                            Pedoman standar internasional IETF RFC-2350 yang memuat piagam, mandat, informasi kontak resmi, ruang lingkup konstituen, serta tata kerja layanan PKU-CSIRT.
                        </p>
                    </div>

                    <ul class="space-y-2 text-xs text-app-text-secondary border-t border-app-border pt-4">
                        <li class="flex items-center gap-2">
                            <flux:icon.check-circle class="size-4 text-info shrink-0" />
                            <span>Ringkasan terstruktur Pasal 1 hingga Pasal 6</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <flux:icon.check-circle class="size-4 text-info shrink-0" />
                            <span>Penampil dokumen PDF interaktif langsung di peramban</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <flux:icon.check-circle class="size-4 text-info shrink-0" />
                            <span>Unduhan dokumen resmi <strong>RFC-2350 PKU-CSIRT v.2.pdf</strong></span>
                        </li>
                    </ul>
                </div>

                <div class="mt-6 pt-4 border-t border-app-border flex flex-wrap items-center gap-2.5">
                    <a
                        href="{{ route('public.rfc2350') }}"
                        wire:navigate
                        class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-xs font-bold text-white hover:bg-primary-dark transition-colors cursor-pointer"
                    >
                        <span>Baca Dokumen RFC-2350</span>
                        <flux:icon.arrow-right class="size-4" />
                    </a>
                    <a
                        href="{{ asset('docs/RFC-2350 - PKU-CSIRT v.2.pdf') }}"
                        download="RFC-2350-PKU-CSIRT-v2.pdf"
                        class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-app-border bg-app-surface-muted px-3.5 py-2.5 text-xs font-semibold text-app-text hover:border-primary/40 transition-colors cursor-pointer"
                        title="Unduh PDF RFC-2350"
                    >
                        <flux:icon.arrow-down-tray class="size-4 text-primary" />
                        <span>.PDF</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- SEKSI KATALOG LAYANAN REAKTIF & PROAKTIF PKU-CSIRT -->
    <section id="layanan-csirt" class="bg-app-surface-muted/70 border-y border-app-border py-16 scroll-mt-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div class="space-y-2 max-w-2xl">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-primary-soft px-3 py-1 text-xs font-bold uppercase tracking-wider text-primary-content">
                        Katalog Layanan Resmi
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-app-text">
                        Layanan Reaktif, Proaktif & Peningkatan Kapasitas
                    </h2>
                    <p class="text-sm text-app-text-secondary">
                        Dirancang sesuai mandat Peraturan BSSN dan standar RFC-2350 untuk melindungi aset informasi Pemerintah Kota Pekanbaru.
                    </p>
                </div>

                <a
                    href="{{ route('public.rfc2350') }}"
                    wire:navigate
                    class="inline-flex items-center gap-2 text-xs font-bold text-primary hover:underline cursor-pointer"
                >
                    <span>Lihat Spesifikasi Layanan di RFC-2350</span>
                    <flux:icon.arrow-right class="size-4" />
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($this->services as $service)
                    <div class="rounded-2xl border border-app-border bg-app-surface p-6 shadow-2xs hover:border-primary/40 transition-colors flex flex-col justify-between gap-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between gap-2">
                                <span class="rounded-md bg-primary-soft px-2.5 py-1 text-[11px] font-bold text-primary-content">
                                    {{ $service['category'] }}
                                </span>
                                <span class="text-[11px] font-mono font-semibold text-app-text-muted">
                                    {{ $service['code'] }}
                                </span>
                            </div>

                            <h3 class="text-base font-bold text-app-text">
                                {{ $service['title'] }}
                            </h3>

                            <p class="text-xs sm:text-sm text-app-text-secondary leading-relaxed">
                                {{ $service['description'] }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-app-border flex items-center justify-between text-xs">
                            <span class="text-app-text-muted font-medium">Target Tingkat Layanan:</span>
                            <span class="font-bold text-primary">{{ $service['sla'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- SEKSI BERITA, KEGIATAN & ADVISORI KEAMANAN SIBER -->
    <section id="berita-advisori" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16 space-y-8 scroll-mt-20">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div class="space-y-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-500/15 px-3 py-1 text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-amber-300">
                    Publikasi & Peringatan Dini
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-app-text">
                    Berita Kegiatan &amp; Advisori Keamanan Siber
                </h2>
                <p class="text-sm text-app-text-secondary">
                    Informasi terkini mengenai langkah pengamanan siber, mitigasi ancaman, dan kegiatan literasi digital di Kota Pekanbaru.
                </p>
            </div>

            <!-- Filter Kategori Livewire & Tautan Arsip -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="flex flex-wrap items-center gap-1.5" role="group" aria-label="Filter kategori berita">
                    <button
                        type="button"
                        wire:click="setCategory('')"
                        class="rounded-lg px-3 py-1.5 text-xs font-bold transition-colors cursor-pointer {{ $categoryFilter === '' ? 'bg-primary text-white' : 'bg-app-surface-muted text-app-text-secondary hover:text-app-text' }}"
                    >
                        Semua
                    </button>
                    @foreach ($this->articleCategories as $cat)
                        <button
                            type="button"
                            wire:click="setCategory('{{ $cat }}')"
                            class="rounded-lg px-3 py-1.5 text-xs font-bold transition-colors cursor-pointer {{ $categoryFilter === $cat ? 'bg-primary text-white' : 'bg-app-surface-muted text-app-text-secondary hover:text-app-text' }}"
                        >
                            {{ $cat }}
                        </button>
                    @endforeach
                </div>

                <a
                    href="{{ route('public.articles') }}"
                    wire:navigate
                    class="inline-flex items-center gap-1 text-xs font-bold text-primary hover:underline whitespace-nowrap self-start sm:self-center"
                >
                    <span>Semua Berita</span>
                    <flux:icon.arrow-right class="size-3.5" />
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($this->filteredArticles as $article)
                <article
                    wire:key="home-article-{{ $article['id'] }}"
                    class="group flex flex-col overflow-hidden rounded-2xl border border-app-border bg-app-surface shadow-2xs hover:border-primary/40 transition-colors"
                >
                    <a
                        href="{{ route('public.article-detail', ['id' => $article['id']]) }}"
                        wire:navigate
                        class="relative aspect-16/10 w-full overflow-hidden bg-app-surface-muted block"
                    >
                        <img
                            src="{{ asset(ltrim((string) $article['thumbnail'], '/')) }}"
                            alt="{{ $article['title'] }}"
                            class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300"
                            loading="lazy"
                        />
                        <div class="absolute top-3 left-3">
                            <span class="rounded-md bg-emerald-950/85 px-2.5 py-1 text-[11px] font-bold text-amber-300 backdrop-blur-xs">
                                {{ $article['category'] }}
                            </span>
                        </div>
                    </a>

                    <div class="flex flex-1 flex-col justify-between p-5 space-y-4">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-[11px] text-app-text-muted">
                                <span class="inline-flex items-center gap-1.5">
                                    <flux:icon.calendar class="size-3.5 text-app-text-muted" />
                                    {{ \Illuminate\Support\Carbon::parse($article['published_at'])->translatedFormat('d M Y') }}
                                </span>
                                <span class="text-emerald-700 dark:text-emerald-400 font-semibold">
                                    {{ $article['category'] }}
                                </span>
                            </div>

                            <h3 class="text-sm sm:text-base font-bold text-app-text line-clamp-2 group-hover:text-primary transition-colors">
                                <a href="{{ route('public.article-detail', ['id' => $article['id']]) }}" wire:navigate>
                                    {{ $article['title'] }}
                                </a>
                            </h3>

                            <p class="text-xs text-app-text-secondary line-clamp-3 leading-relaxed">
                                {{ $article['excerpt'] }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-app-border flex items-center justify-between text-xs font-semibold text-primary">
                            <span>Diskominfotiksan</span>
                            <a
                                href="{{ route('public.article-detail', ['id' => $article['id']]) }}"
                                wire:navigate
                                class="inline-flex items-center gap-1 hover:underline font-bold"
                            >
                                <span>Baca</span>
                                <flux:icon.arrow-right class="size-3" />
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <!-- SEKSI KONTAK & LOKASI SEKRETARIAT TENAYAN RAYA -->
    <section class="bg-transparent border-t border-app-border py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-6 space-y-4">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-primary-soft px-3 py-1 text-xs font-bold uppercase tracking-wider text-primary-content">
                        Posko & Sekretariat Resmi
                    </span>
                    <h2 class="text-2xl font-extrabold tracking-tight text-app-text">
                        Pusat Operasi Keamanan Siber (PKU-CSIRT) Tenayan Raya
                    </h2>
                    <p class="text-sm text-app-text-secondary leading-relaxed">
                        Berlokasi di Kompleks Perkantoran Pemerintah Kota Pekanbaru, Tenayan Raya. Kami siap berkoordinasi dengan pengelola TI Perangkat Daerah, BSSN, dan peneliti keamanan siber.
                    </p>

                    <div class="space-y-3 pt-2 text-sm">
                        <div class="flex items-start gap-3">
                            <flux:icon.map-pin class="size-5 text-primary shrink-0 mt-0.5" />
                            <div>
                                <p class="font-bold text-app-text">Alamat Kantor</p>
                                <p class="text-xs text-app-text-secondary">{{ $profile['address'] }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <flux:icon.envelope class="size-5 text-primary shrink-0 mt-0.5" />
                            <div>
                                <p class="font-bold text-app-text">Pos-el Pelaporan Resmi</p>
                                <a href="mailto:{{ $profile['email'] }}" class="text-xs font-semibold text-primary hover:underline">
                                    {{ $profile['email'] }}
                                </a>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <flux:icon.clock class="size-5 text-primary shrink-0 mt-0.5" />
                            <div>
                                <p class="font-bold text-app-text">Jam Layanan & Siaga</p>
                                <p class="text-xs text-app-text-secondary">{{ $profile['WorkingHours'] }} ({{ $profile['timezone'] }})</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-6">
                    <div class="overflow-hidden rounded-2xl border border-app-border bg-app-surface shadow-sm aspect-16/10">
                        <iframe
                            title="Peta Lokasi Sekretariat PKU-CSIRT Kompleks Perkantoran Tenayan Raya Pekanbaru"
                            src="{{ $profile['maps_embed'] }}"
                            class="h-full w-full border-0"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                        ></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
