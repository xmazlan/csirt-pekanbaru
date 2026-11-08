<div class="space-y-12">
    @php
        $article = $this->article;
        $readingTime = max(1, (int) ceil(str_word_count(strip_tags((string) ($article['content'] ?? $article['excerpt'] ?? ''))) / 180));
        $paragraphs = array_filter(array_map('trim', explode("\n", (string) ($article['content'] ?? $article['excerpt'] ?? ''))), static fn (string $p): bool => $p !== '');
        $currentUrl = url()->current();
        $shareText = urlencode((string) ($article['title'] ?? '').' — PKU-CSIRT Kota Pekanbaru');
    @endphp

    <!-- HERO HEADER ARTIKEL -->
    <section class="bg-gradient-to-b from-[#072c22] via-[#0b3d30] to-[#0d4a3a] text-white py-12 px-4 sm:px-6 lg:px-8 border-b border-emerald-800/60">
        <div class="mx-auto max-w-4xl space-y-4">
            <!-- Breadcrumb Navigation -->
            <nav class="flex items-center gap-2 text-xs text-emerald-200/80 flex-wrap" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" wire:navigate class="hover:text-amber-300 transition-colors">Beranda</a>
                <flux:icon.chevron-right class="size-3 text-emerald-400/60" />
                <a href="{{ route('public.articles') }}" wire:navigate class="hover:text-amber-300 transition-colors">Berita &amp; Advisori</a>
                <flux:icon.chevron-right class="size-3 text-emerald-400/60" />
                <span class="text-amber-300 font-semibold truncate max-w-xs sm:max-w-md" aria-current="page">
                    {{ $article['title'] }}
                </span>
            </nav>

            <!-- Metadata Chips -->
            <div class="flex flex-wrap items-center gap-2 pt-2">
                <span class="rounded-md bg-amber-400/20 px-3 py-1 text-xs font-bold text-amber-300 border border-amber-400/30">
                    {{ $article['category'] }}
                </span>
                <span class="text-emerald-300/60">•</span>
                <span class="text-xs text-emerald-100 flex items-center gap-1.5">
                    <flux:icon.calendar class="size-3.5 text-amber-400" />
                    {{ \Illuminate\Support\Carbon::parse($article['published_at'])->translatedFormat('d F Y') }}
                </span>
                <span class="text-emerald-300/60">•</span>
                <span class="text-xs text-emerald-100 flex items-center gap-1.5">
                    <flux:icon.clock class="size-3.5 text-amber-400" />
                    {{ $readingTime }} menit baca
                </span>
            </div>

            <!-- Judul Artikel -->
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white leading-tight">
                {{ $article['title'] }}
            </h1>

            <!-- Penulis & Instansi -->
            <div class="pt-2 flex items-center gap-3">
                <div class="flex size-9 items-center justify-center rounded-full bg-emerald-800/80 text-amber-300 border border-emerald-700 font-bold text-xs">
                    CSIRT
                </div>
                <div class="text-xs">
                    <p class="font-bold text-white">{{ $article['author'] ?? 'Diskominfotiksan Pekanbaru' }}</p>
                    <p class="text-emerald-200/80">Pemerintah Kota Pekanbaru</p>
                </div>
            </div>
        </div>
    </section>

    <!-- KONTEN UTAMA ARTIKEL -->
    <article class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Gambar Utama / Hero Thumbnail -->
        <div class="overflow-hidden rounded-2xl border border-app-border bg-app-surface shadow-md">
            <div class="relative aspect-16/9 w-full overflow-hidden bg-app-surface-muted">
                <img
                    src="{{ asset(ltrim((string) $article['thumbnail'], '/')) }}"
                    alt="{{ $article['title'] }}"
                    class="h-full w-full object-cover"
                />
            </div>
            <div class="p-3 bg-app-surface-muted text-center text-xs text-app-text-muted italic border-t border-app-border">
                Dokumentasi &amp; Infografis Resmi — Tim Tanggap Insiden Siber PKU-CSIRT Kota Pekanbaru
            </div>
        </div>

        <!-- Paragraf Isi Artikel -->
        <div class="prose max-w-none text-app-text space-y-5 text-sm sm:text-base leading-relaxed">
            @foreach ($paragraphs as $p)
                <p class="text-app-text leading-relaxed">
                    {{ $p }}
                </p>
            @endforeach
        </div>

        <!-- Kotak Peringatan & Advisori Resmi PKU-CSIRT -->
        <div class="rounded-2xl border border-amber-500/40 bg-amber-500/10 p-5 sm:p-6 space-y-3">
            <div class="flex items-center gap-2.5">
                <flux:icon.shield-exclamation class="size-5 text-amber-600 dark:text-amber-400 shrink-0" />
                <h2 class="text-sm sm:text-base font-extrabold text-amber-800 dark:text-amber-300">
                    Kesiapsiagaan &amp; Pelaporan Insiden Siber
                </h2>
            </div>
            <p class="text-xs sm:text-sm text-app-text-secondary leading-relaxed">
                Apabila Organisasi Perangkat Daerah (OPD) atau masyarakat menemukan indikasi serangan siber, celah keamanan, atau insiden terkait aset digital Pemerintah Kota Pekanbaru, mohon segera sampaikan laporan aduan secara resmi kepada tim teknis kami.
            </p>
            <div class="pt-2 flex flex-wrap items-center gap-3">
                <a
                    href="{{ route('public.incident-report') }}"
                    wire:navigate
                    class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-primary-dark transition-colors cursor-pointer"
                >
                    <flux:icon.shield-exclamation class="size-4" />
                    <span>Laporkan Insiden ke PKU-CSIRT</span>
                </a>
                <a
                    href="mailto:csirt@pekanbaru.go.id"
                    class="inline-flex items-center gap-2 rounded-xl border border-app-border bg-app-surface px-4 py-2 text-xs font-bold text-app-text hover:bg-app-surface-muted transition-colors cursor-pointer"
                >
                    <flux:icon.envelope class="size-4 text-primary" />
                    <span>csirt@pekanbaru.go.id</span>
                </a>
            </div>
        </div>

        <!-- Seksi Bagikan & Interaksi -->
        <div
            x-data="{
                copied: false,
                copyLink() {
                    navigator.clipboard.writeText('{{ $currentUrl }}');
                    this.copied = true;
                    setTimeout(() => { this.copied = false }, 2500);
                }
            }"
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 py-5 border-y border-app-border"
        >
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-app-text-muted">Bagikan Artikel:</span>
                <div class="flex items-center gap-1.5">
                    <!-- WhatsApp -->
                    <a
                        href="https://api.whatsapp.com/send?text={{ $shareText }}%20{{ urlencode($currentUrl) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        title="Bagikan ke WhatsApp"
                        class="flex size-8 items-center justify-center rounded-lg border border-app-border text-app-text-secondary hover:bg-app-surface-muted hover:text-emerald-600 transition-colors"
                    >
                        <flux:icon.chat-bubble-left-ellipsis class="size-4" />
                    </a>

                    <!-- Telegram -->
                    <a
                        href="https://t.me/share/url?url={{ urlencode($currentUrl) }}&text={{ $shareText }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        title="Bagikan ke Telegram"
                        class="flex size-8 items-center justify-center rounded-lg border border-app-border text-app-text-secondary hover:bg-app-surface-muted hover:text-blue-500 transition-colors"
                    >
                        <flux:icon.paper-airplane class="size-4" />
                    </a>

                    <!-- X / Twitter -->
                    <a
                        href="https://twitter.com/intent/tweet?text={{ $shareText }}&url={{ urlencode($currentUrl) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        title="Bagikan ke X"
                        class="flex size-8 items-center justify-center rounded-lg border border-app-border text-app-text-secondary hover:bg-app-surface-muted hover:text-app-text transition-colors"
                    >
                        <flux:icon.share class="size-4" />
                    </a>

                    <!-- Salin Tautan -->
                    <button
                        type="button"
                        @click="copyLink"
                        title="Salin tautan artikel"
                        class="flex size-8 items-center justify-center rounded-lg border border-app-border text-app-text-secondary hover:bg-app-surface-muted hover:text-primary transition-colors cursor-pointer"
                    >
                        <flux:icon.link class="size-4" />
                    </button>
                    <span x-show="copied" x-cloak class="text-xs font-bold text-emerald-600 dark:text-emerald-400 animate-pulse ps-1">
                        Tautan tersalin!
                    </span>
                </div>
            </div>

            <div>
                <a
                    href="{{ route('public.articles') }}"
                    wire:navigate
                    class="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:underline"
                >
                    <flux:icon.arrow-left class="size-3.5" />
                    <span>Kembali ke Arsip Berita</span>
                </a>
            </div>
        </div>

        <!-- Rekomendasi Berita & Advisori Terkait -->
        @if (count($this->relatedArticles) > 0)
            <div class="pt-4 space-y-5">
                <div class="space-y-1">
                    <span class="text-xs font-bold uppercase tracking-wider text-app-text-muted">Eksplorasi Informasi</span>
                    <h2 class="text-xl font-extrabold text-app-text">Berita &amp; Advisori Terkait</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($this->relatedArticles as $rel)
                        <article class="group flex flex-col overflow-hidden rounded-xl border border-app-border bg-app-surface shadow-2xs hover:border-primary/40 transition-colors">
                            <a
                                href="{{ route('public.article-detail', ['id' => $rel['id']]) }}"
                                wire:navigate
                                class="relative aspect-16/10 w-full overflow-hidden bg-app-surface-muted block"
                            >
                                <img
                                    src="{{ asset(ltrim((string) $rel['thumbnail'], '/')) }}"
                                    alt="{{ $rel['title'] }}"
                                    class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    loading="lazy"
                                />
                                <div class="absolute top-2 left-2">
                                    <span class="rounded bg-emerald-950/85 px-2 py-0.5 text-[10px] font-bold text-amber-300">
                                        {{ $rel['category'] }}
                                    </span>
                                </div>
                            </a>

                            <div class="p-4 flex flex-1 flex-col justify-between space-y-2">
                                <div class="space-y-1">
                                    <p class="text-[10px] text-app-text-muted">
                                        {{ \Illuminate\Support\Carbon::parse($rel['published_at'])->translatedFormat('d M Y') }}
                                    </p>
                                    <h3 class="text-xs font-bold text-app-text group-hover:text-primary transition-colors line-clamp-2">
                                        <a href="{{ route('public.article-detail', ['id' => $rel['id']]) }}" wire:navigate>
                                            {{ $rel['title'] }}
                                        </a>
                                    </h3>
                                </div>

                                <a
                                    href="{{ route('public.article-detail', ['id' => $rel['id']]) }}"
                                    wire:navigate
                                    class="inline-flex items-center gap-1 text-[11px] font-bold text-primary hover:underline pt-2 border-t border-app-border"
                                >
                                    <span>Baca</span>
                                    <flux:icon.arrow-right class="size-3" />
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif
    </article>
</div>

