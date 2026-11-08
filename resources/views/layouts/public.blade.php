<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
    <head>
        @include('partials.head', ['title' => $title ?? 'PKU-CSIRT — Pemerintah Kota Pekanbaru'])
    </head>
    <body
        class="min-h-full bg-app-bg text-app-text antialiased selection:bg-primary-soft selection:text-primary-content flex flex-col"
        x-data="{ mobileMenuOpen: false }"
        @keydown.escape.window="mobileMenuOpen = false"
    >
        <!-- Aksesibilitas: Lewati ke konten utama -->
        <a
            href="#main-content"
            class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:px-4 focus:py-2.5 focus:bg-primary focus:text-primary-foreground focus:font-semibold focus:rounded-lg focus:shadow-lg focus:outline-hidden focus:ring-2 focus:ring-amber-400"
        >
            {{ __('Lewati ke konten utama') }}
        </a>

        <!-- Pita Identitas Resmi Pemerintah Kota Pekanbaru (Hijau Zamrud & Aksen Lancang Kuning) -->
        <div class="bg-[#0d4a3a] text-emerald-50 border-b border-emerald-800/60 text-xs">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-2 flex flex-wrap items-center justify-between gap-2">
                <div class="flex items-center gap-2.5">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-400/15 px-2.5 py-0.5 text-[11px] font-semibold text-amber-300 border border-amber-400/30">
                        <span class="size-1.5 rounded-full bg-amber-400"></span>
                        Kota Bertuah • Bumi Lancang Kuning
                    </span>
                    <span class="hidden md:inline text-emerald-100/90">
                        Dinas Komunikasi, Informatika, Statistik, dan Persandian Kota Pekanbaru
                    </span>
                </div>

                <div class="flex items-center gap-4 text-[11px] font-medium">
                    <a
                        href="mailto:csirt@pekanbaru.go.id"
                        class="inline-flex items-center gap-1 text-emerald-100 hover:text-amber-300 transition-colors cursor-pointer"
                    >
                        <flux:icon.envelope class="size-3.5 text-amber-400" />
                        <span>csirt@pekanbaru.go.id</span>
                    </a>
                    <span class="hidden sm:inline text-emerald-400/50">|</span>
                    <a
                        href="{{ route('public.rfc2350') }}"
                        wire:navigate
                        class="hidden sm:inline-flex items-center gap-1 text-amber-300 hover:text-amber-200 font-semibold transition-colors cursor-pointer"
                    >
                        <flux:icon.document-check class="size-3.5" />
                        <span>Dokumen RFC-2350 v2.0</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sticky Header Utama Portal Publik PKU-CSIRT -->
        <header
            class="sticky top-0 z-40 w-full border-b border-app-border bg-app-surface/95 backdrop-blur-md shadow-2xs"
            role="banner"
        >
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between gap-4">
                <!-- Logo Kembar: Pemko Pekanbaru & PKU-CSIRT -->
                <a
                    href="{{ route('home') }}"
                    wire:navigate
                    class="group flex items-center gap-3 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary rounded-lg py-1 cursor-pointer"
                >
                    <div class="flex items-center gap-2 shrink-0">
                        <img
                            src="{{ asset('images/brand/logo-pemko.png') }}"
                            alt="Lambang Pemerintah Kota Pekanbaru"
                            class="h-10 w-auto object-contain"
                            width="40"
                            height="40"
                        />
                        <div class="h-7 w-px bg-app-border"></div>
                        <img
                            src="{{ asset('images/brand/logo-csirt.png') }}"
                            alt="Logo PKU-CSIRT"
                            class="h-10 w-auto object-contain"
                            width="40"
                            height="40"
                        />
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-center gap-1.5">
                            <span class="text-base font-extrabold tracking-tight text-app-text group-hover:text-primary transition-colors">
                                PKU-CSIRT
                            </span>
                            <span class="hidden xl:inline-block rounded bg-amber-500/15 px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-amber-700 dark:text-amber-300 border border-amber-500/30">
                                Resmi
                            </span>
                        </div>
                        <span class="text-xs font-medium text-app-text-muted">
                            Pemerintah Kota Pekanbaru
                        </span>
                    </div>
                </a>

                <!-- Navigasi Desktop -->
                <nav class="hidden lg:flex items-center gap-1" aria-label="Navigasi utama publik">
                    @php
                        $publicNav = [
                            ['route' => 'home', 'label' => 'Beranda', 'active' => request()->routeIs('home')],
                            ['route' => 'public.articles', 'label' => 'Berita & Advisori', 'active' => request()->routeIs('public.articles*') || request()->routeIs('public.article-detail*')],
                            ['route' => 'public.hall-of-fame', 'label' => 'Hall of Fame', 'active' => request()->routeIs('public.hall-of-fame')],
                            ['route' => 'public.rfc2350', 'label' => 'RFC-2350', 'active' => request()->routeIs('public.rfc2350')],
                        ];
                    @endphp

                    @foreach ($publicNav as $nav)
                        <a
                            href="{{ route($nav['route']) }}"
                            wire:navigate
                            aria-current="{{ $nav['active'] ? 'page' : null }}"
                            class="relative px-3.5 py-2 rounded-lg text-sm font-semibold transition-colors cursor-pointer focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary {{ $nav['active'] ? 'bg-primary-soft text-primary-content' : 'text-app-text-secondary hover:bg-app-surface-muted hover:text-app-text' }}"
                        >
                            {{ $nav['label'] }}
                        </a>
                    @endforeach

                    <a
                        href="{{ route('home') }}#layanan-csirt"
                        class="px-3.5 py-2 rounded-lg text-sm font-semibold text-app-text-secondary hover:bg-app-surface-muted hover:text-app-text transition-colors cursor-pointer focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                    >
                        Layanan
                    </a>
                    <a
                        href="{{ route('home') }}#berita-advisori"
                        class="px-3.5 py-2 rounded-lg text-sm font-semibold text-app-text-secondary hover:bg-app-surface-muted hover:text-app-text transition-colors cursor-pointer focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                    >
                        Advisori
                    </a>
                </nav>

                <!-- Aksi Kanan: Theme Switcher Tunggal + Tombol Lapor + Portal Staff -->
                <div class="flex items-center gap-2.5 shrink-0">
                    <!-- Single Instance Theme Toggle -->
                    <x-theme-toggle size="sm" />

                    <a
                        href="{{ route('public.incident-report') }}"
                        wire:navigate
                        class="hidden sm:inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-primary-dark focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-colors cursor-pointer"
                    >
                        <flux:icon.shield-exclamation class="size-4 text-amber-300" />
                        <span>Lapor Insiden</span>
                    </a>

                    @auth
                        <a
                            href="{{ route('dashboard') }}"
                            wire:navigate
                            class="inline-flex items-center gap-1.5 rounded-lg border border-app-border bg-app-surface-muted px-3.5 py-2 text-xs font-semibold text-app-text hover:border-primary/40 transition-colors cursor-pointer focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                        >
                            <flux:icon.squares-2x2 class="size-4 text-primary" />
                            <span class="hidden md:inline">Dashboard</span>
                        </a>
                    @else
                        @if (Route::has('login'))
                            <a
                                href="{{ route('login') }}"
                                wire:navigate
                                class="hidden md:inline-flex items-center gap-1.5 rounded-lg border border-app-border bg-app-surface px-3.5 py-2 text-xs font-semibold text-app-text-secondary hover:bg-app-surface-muted hover:text-app-text transition-colors cursor-pointer focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                            >
                                <flux:icon.lock-closed class="size-3.5 text-app-text-muted" />
                                <span>Portal Tim</span>
                            </a>
                        @endif
                    @endauth

                    <!-- Tombol Hamburger Mobile -->
                    <button
                        type="button"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        x-bind:aria-expanded="mobileMenuOpen ? 'true' : 'false'"
                        aria-controls="public-mobile-menu"
                        aria-label="Buka atau tutup menu navigasi"
                        class="lg:hidden inline-flex size-10 items-center justify-center rounded-lg border border-app-border text-app-text-secondary hover:bg-app-surface-muted hover:text-app-text focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer"
                    >
                        <template x-if="!mobileMenuOpen">
                            <flux:icon.bars-3 class="size-5" />
                        </template>
                        <template x-if="mobileMenuOpen">
                            <flux:icon.x-mark class="size-5" />
                        </template>
                    </button>
                </div>
            </div>

            <!-- Menu Navigasi Mobile -->
            <div
                id="public-mobile-menu"
                x-show="mobileMenuOpen"
                x-cloak
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="lg:hidden border-t border-app-border bg-app-surface px-4 pt-3 pb-5 space-y-2 shadow-lg"
            >
                @foreach ($publicNav as $nav)
                    <a
                        href="{{ route($nav['route']) }}"
                        wire:navigate
                        @click="mobileMenuOpen = false"
                        class="flex items-center justify-between rounded-lg px-3.5 py-2.5 text-sm font-semibold {{ $nav['active'] ? 'bg-primary-soft text-primary-content' : 'text-app-text-secondary hover:bg-app-surface-muted hover:text-app-text' }}"
                    >
                        <span>{{ $nav['label'] }}</span>
                        @if ($nav['active'])
                            <span class="size-2 rounded-full bg-amber-500"></span>
                        @endif
                    </a>
                @endforeach

                <a
                    href="{{ route('home') }}#layanan-csirt"
                    @click="mobileMenuOpen = false"
                    class="block rounded-lg px-3.5 py-2.5 text-sm font-semibold text-app-text-secondary hover:bg-app-surface-muted hover:text-app-text"
                >
                    Layanan CSIRT
                </a>
                <a
                    href="{{ route('home') }}#berita-advisori"
                    @click="mobileMenuOpen = false"
                    class="block rounded-lg px-3.5 py-2.5 text-sm font-semibold text-app-text-secondary hover:bg-app-surface-muted hover:text-app-text"
                >
                    Berita & Advisori
                </a>

                <div class="pt-3 border-t border-app-border flex flex-col gap-2">
                    <a
                        href="{{ route('public.incident-report') }}"
                        wire:navigate
                        @click="mobileMenuOpen = false"
                        class="flex items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-xs"
                    >
                        <flux:icon.shield-exclamation class="size-4 text-amber-300" />
                        <span>Laporkan Insiden Siber</span>
                    </a>
                    @guest
                        @if (Route::has('login'))
                            <a
                                href="{{ route('login') }}"
                                wire:navigate
                                class="flex items-center justify-center gap-2 rounded-lg border border-app-border bg-app-surface-muted px-4 py-2.5 text-sm font-semibold text-app-text"
                            >
                                <flux:icon.lock-closed class="size-4" />
                                <span>Login Portal Tim CSIRT</span>
                            </a>
                        @endif
                    @endguest
                </div>
            </div>
        </header>

        <!-- Konten Utama -->
        <main id="main-content" class="flex-1 w-full pb-6" role="main" tabindex="-1">
            {{ $slot }}
        </main>

        <!-- Ornamen Geometris Pucuk Rebung Melayu Pekanbaru (Pembatas Footer Transparan) -->
        <div class="w-full overflow-hidden bg-transparent leading-none -mt-5 relative z-10 pointer-events-none" aria-hidden="true">
            <svg class="block w-full h-5 text-[#0a382c]" preserveAspectRatio="none" viewBox="0 0 1200 24" fill="currentColor">
                <path d="M0 24L30 4L60 24L90 4L120 24L150 4L180 24L210 4L240 24L270 4L300 24L330 4L360 24L390 4L420 24L450 4L480 24L510 4L540 24L570 4L600 24L630 4L660 24L690 4L720 24L750 4L780 24L810 4L840 24L870 4L900 24L930 4L960 24L990 4L1020 24L1050 4L1080 24L1110 4L1140 24L1170 4L1200 24V24H0Z" />
            </svg>
        </div>

        <!-- Footer Resmi PKU-CSIRT Pemerintah Kota Pekanbaru -->
        <footer class="bg-[#0a382c] text-emerald-50" role="contentinfo">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-12">
                    <!-- Kolom 1: Identitas & Semboyan Melayu Pekanbaru -->
                    <div class="lg:col-span-5 space-y-4">
                        <div class="flex items-center gap-3">
                            <img
                                src="{{ asset('images/brand/logo-pemko.png') }}"
                                alt="Logo Pemerintah Kota Pekanbaru"
                                class="h-12 w-auto object-contain bg-white/95 p-1.5 rounded-lg"
                            />
                            <img
                                src="{{ asset('images/brand/logo-csirt.png') }}"
                                alt="Logo PKU-CSIRT"
                                class="h-12 w-auto object-contain bg-white/95 p-1.5 rounded-lg"
                            />
                            <div>
                                <div class="text-lg font-extrabold tracking-tight text-white">PKU-CSIRT</div>
                                <div class="text-xs text-amber-300 font-semibold">Pemerintah Kota Pekanbaru</div>
                            </div>
                        </div>

                        <p class="text-sm text-emerald-100/85 leading-relaxed">
                            Computer Security Incident Response Team (CSIRT) Pemerintah Kota Pekanbaru bertugas melaksanakan pengamanan siber, penanganan insiden, dan pelindungan infrastruktur Sistem Pemerintahan Berbasis Elektronik (SPBE).
                        </p>

                        <blockquote class="rounded-xl border border-amber-400/30 bg-emerald-950/60 p-3.5 text-xs italic text-amber-200">
                            “Adat dijunjung, marwah terpelihara, siber terjaga, Pekanbaru berjaya.”
                        </blockquote>
                    </div>

                    <!-- Kolom 2: Navigasi Layanan Wajib -->
                    <div class="lg:col-span-3 space-y-3">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-amber-300">
                            Layanan & Kepatuhan
                        </h2>
                        <ul class="space-y-2.5 text-sm">
                            <li>
                                <a href="{{ route('public.articles') }}" wire:navigate class="text-emerald-100 hover:text-amber-300 transition-colors inline-flex items-center gap-2">
                                    <flux:icon.newspaper class="size-4 text-amber-400" />
                                    <span>Berita &amp; Advisori Siber</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('public.incident-report') }}" wire:navigate class="text-emerald-100 hover:text-amber-300 transition-colors inline-flex items-center gap-2">
                                    <flux:icon.shield-exclamation class="size-4 text-amber-400" />
                                    <span>Melaporkan Insiden Siber</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('public.hall-of-fame') }}" wire:navigate class="text-emerald-100 hover:text-amber-300 transition-colors inline-flex items-center gap-2">
                                    <flux:icon.trophy class="size-4 text-amber-400" />
                                    <span>Hall of Fame Peneliti</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('public.rfc2350') }}" wire:navigate class="text-emerald-100 hover:text-amber-300 transition-colors inline-flex items-center gap-2">
                                    <flux:icon.document-text class="size-4 text-amber-400" />
                                    <span>Dokumen Standar RFC-2350</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ asset('docs/Formulir-Aduan-Insiden-Siber-PKU-CSIRT-v.2.docx') }}" download class="text-emerald-100 hover:text-amber-300 transition-colors inline-flex items-center gap-2">
                                    <flux:icon.arrow-down-tray class="size-4 text-amber-400" />
                                    <span>Formulir Aduan (.DOCX)</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Kolom 3: Kontak & Lokasi Tenayan Raya -->
                    <div class="lg:col-span-4 space-y-3">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-amber-300">
                            Sekretariat PKU-CSIRT
                        </h2>
                        <div class="space-y-2 text-sm text-emerald-100/90">
                            <p class="font-semibold text-white">
                                Dinas Komunikasi, Informatika, Statistik, dan Persandian Kota Pekanbaru
                            </p>
                            <p class="text-xs leading-relaxed text-emerald-100/80">
                                Kompleks Perkantoran Walikota Pekanbaru (Tenayan Raya), Gedung Utama Lt. 3, Jl. Abdul Rahman Hamid, Kec. Tenayan Raya, Kota Pekanbaru, Riau 28285
                            </p>
                            <p class="pt-1">
                                <a href="mailto:csirt@pekanbaru.go.id" class="inline-flex items-center gap-2 font-semibold text-amber-300 hover:underline">
                                    <flux:icon.envelope class="size-4" />
                                    <span>csirt@pekanbaru.go.id</span>
                                </a>
                            </p>
                        </div>

                        <div class="pt-2 flex flex-wrap gap-2">
                            <a
                                href="https://www.pekanbaru.go.id"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-700 bg-emerald-900/60 px-3 py-1.5 text-xs font-semibold text-emerald-100 hover:border-amber-400 hover:text-amber-300 transition-colors"
                            >
                                <flux:icon.globe-alt class="size-3.5" />
                                <span>pekanbaru.go.id</span>
                            </a>
                            <a
                                href="https://instagram.com/csirt_pekanbaru"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-700 bg-emerald-900/60 px-3 py-1.5 text-xs font-semibold text-emerald-100 hover:border-amber-400 hover:text-amber-300 transition-colors"
                            >
                                <span>Instagram</span>
                            </a>
                            <a
                                href="https://youtube.com/@diskominfopekanbaru"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-700 bg-emerald-900/60 px-3 py-1.5 text-xs font-semibold text-emerald-100 hover:border-amber-400 hover:text-amber-300 transition-colors"
                            >
                                <span>YouTube Diskominfo</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mt-10 pt-6 border-t border-emerald-800/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-emerald-200/80">
                    <p>&copy; {{ date('Y') }} PKU-CSIRT • Pemerintah Kota Pekanbaru. Seluruh hak cipta dilindungi.</p>
                    <p>Zona Waktu Operasional: WIB (UTC+07:00 • Asia/Jakarta)</p>
                </div>
            </div>
        </footer>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
        @livewireScripts
    </body>
</html>

