<div class="space-y-12">
    <!-- HERO HEADER BERITA & ADVISORI -->
    <section class="relative overflow-hidden bg-gradient-to-b from-[#072c22] via-[#0b3d30] to-[#0d4a3a] text-white py-16 px-4 sm:px-6 lg:px-8 border-b border-emerald-800/60">
        <!-- Aksen Pola Latar Belakang -->
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#f59e0b_1px,transparent_1px)] [background-size:20px_20px] pointer-events-none" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-7xl space-y-4">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-emerald-200/80" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" wire:navigate class="hover:text-amber-300 transition-colors">Beranda</a>
                <flux:icon.chevron-right class="size-3 text-emerald-400/60" />
                <span class="text-amber-300 font-semibold" aria-current="page">Berita &amp; Advisori</span>
            </nav>

            <div class="max-w-3xl space-y-3">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-400/15 px-3 py-1 text-xs font-bold text-amber-300 border border-amber-400/30">
                    <span class="size-1.5 rounded-full bg-amber-400"></span>
                    Publikasi Resmi PKU-CSIRT • Kota Bertuah
                </span>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white">
                    Berita Kegiatan &amp; Advisori Keamanan Siber
                </h1>
                <p class="text-sm sm:text-base text-emerald-100/90 leading-relaxed">
                    Pusat siaran pers resmi, peringatan dini insiden, panduan remediasi celah keamanan, dan dokumentasi kegiatan peningkatan kapasitas siber di lingkungan Pemerintah Kota Pekanbaru.
                </p>
            </div>
        </div>
    </section>

    <!-- FILTER & PENCARIAN -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 rounded-2xl border border-app-border bg-app-surface p-4 shadow-2xs">
            <!-- Filter Kategori -->
            <div class="flex flex-wrap items-center gap-1.5" role="group" aria-label="Filter kategori berita">
                <button
                    type="button"
                    wire:click="setCategory('')"
                    class="rounded-xl px-3.5 py-2 text-xs font-bold transition-colors cursor-pointer {{ $category === '' ? 'bg-primary text-white shadow-xs' : 'bg-app-surface-muted text-app-text-secondary hover:text-app-text' }}"
                >
                    Semua Kategori
                </button>
                @foreach ($this->categories as $cat)
                    <button
                        type="button"
                        wire:click="setCategory('{{ $cat }}')"
                        class="rounded-xl px-3.5 py-2 text-xs font-bold transition-colors cursor-pointer {{ $category === $cat ? 'bg-primary text-white shadow-xs' : 'bg-app-surface-muted text-app-text-secondary hover:text-app-text' }}"
                    >
                        {{ $cat }}
                    </button>
                @endforeach
            </div>

            <!-- Input Pencarian -->
            <div class="w-full md:w-72">
                <div class="relative">
                    <input
                        type="search"
                        wire:model.live.debounce.250ms="search"
                        aria-label="Cari artikel berita atau advisori"
                        placeholder="Cari judul, kata kunci..."
                        class="w-full rounded-xl border border-app-border bg-app-surface-muted pe-8 ps-3 py-2 text-xs text-app-text focus:border-primary focus:outline-hidden"
                    />
                    <div class="pointer-events-none absolute inset-y-0 end-0 flex items-center pe-3 text-app-text-muted">
                        <flux:icon.magnifying-glass class="size-4" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- GRID ARTIKEL BERITA & ADVISORI -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-16">
        @if (count($this->articles) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($this->articles as $article)
                    @php
                        $readingTime = max(1, (int) ceil(str_word_count(strip_tags((string) ($article['content'] ?? $article['excerpt'] ?? ''))) / 180));
                    @endphp
                    <article
                        wire:key="article-card-{{ $article['id'] }}"
                        class="group flex flex-col overflow-hidden rounded-2xl border border-app-border bg-app-surface shadow-2xs hover:border-primary/50 transition-all duration-200 hover:-translate-y-1"
                    >
                        <!-- Thumbnail dengan Badge Kategori -->
                        <a
                            href="{{ route('public.article-detail', ['id' => $article['id']]) }}"
                            wire:navigate
                            class="relative aspect-16/10 w-full overflow-hidden bg-app-surface-muted block"
                        >
                            <img
                                src="{{ asset(ltrim((string) $article['thumbnail'], '/')) }}"
                                alt="{{ $article['title'] }}"
                                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                loading="lazy"
                            />
                            <div class="absolute top-3 left-3 flex items-center gap-1.5">
                                <span class="rounded-md bg-emerald-950/85 px-2.5 py-1 text-[11px] font-bold text-amber-300 backdrop-blur-xs shadow-xs">
                                    {{ $article['category'] }}
                                </span>
                            </div>
                        </a>

                        <!-- Rincian Artikel -->
                        <div class="flex flex-1 flex-col justify-between p-6 space-y-4">
                            <div class="space-y-2.5">
                                <div class="flex items-center justify-between text-[11px] text-app-text-muted">
                                    <span class="inline-flex items-center gap-1.5">
                                        <flux:icon.calendar class="size-3.5 text-app-text-muted" />
                                        {{ \Illuminate\Support\Carbon::parse($article['published_at'])->translatedFormat('d F Y') }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 text-[11px] font-medium text-app-text-muted">
                                        <flux:icon.clock class="size-3.5" />
                                        {{ $readingTime }} mnt baca
                                    </span>
                                </div>

                                <h2 class="text-base sm:text-lg font-bold text-app-text leading-snug group-hover:text-primary transition-colors">
                                    <a href="{{ route('public.article-detail', ['id' => $article['id']]) }}" wire:navigate>
                                        {{ $article['title'] }}
                                    </a>
                                </h2>

                                <p class="text-xs sm:text-sm text-app-text-secondary line-clamp-3 leading-relaxed">
                                    {{ $article['excerpt'] ?? Str::limit(strip_tags((string) ($article['content'] ?? '')), 150) }}
                                </p>
                            </div>

                            <!-- Footer Kartu -->
                            <div class="pt-4 border-t border-app-border flex items-center justify-between text-xs">
                                <span class="font-medium text-app-text-muted truncate max-w-[170px]">
                                    {{ $article['author'] ?? 'Diskominfotiksan' }}
                                </span>

                                <a
                                    href="{{ route('public.article-detail', ['id' => $article['id']]) }}"
                                    wire:navigate
                                    class="inline-flex items-center gap-1 font-bold text-primary hover:underline"
                                >
                                    <span>Baca</span>
                                    <flux:icon.arrow-right class="size-3.5" />
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <!-- Keadaan Kosong -->
            <div class="rounded-2xl border border-app-border bg-app-surface p-12 text-center shadow-2xs">
                <div class="mx-auto max-w-md space-y-3">
                    <div class="mx-auto flex size-14 items-center justify-center rounded-full bg-primary-soft text-primary">
                        <flux:icon.newspaper class="size-7" />
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-app-text">Tidak Ada Publikasi Ditemukan</h3>
                    <p class="text-xs sm:text-sm text-app-text-secondary leading-relaxed">
                        Tidak ada artikel berita atau buletin advisori yang cocok dengan kriteria pencarian atau kategori yang Anda pilih saat ini.
                    </p>
                    <div class="pt-2">
                        <button
                            type="button"
                            wire:click="resetFilter"
                            class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-primary-dark transition-colors cursor-pointer"
                        >
                            <flux:icon.arrow-path class="size-4" />
                            <span>Tampilkan Semua Publikasi</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </section>
</div>

