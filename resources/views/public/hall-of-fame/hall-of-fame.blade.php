<div>
    @php
        $profile = $this->profile;
        $data = $this->hofData;
        $leaderboard = $data['leaderboard'] ?? [];
        $entries = $data['entries'] ?? [];
        $years = $data['years'] ?? [];
        $stats = $data['stats'] ?? ['total_entries' => 0, 'total_researchers' => 0, 'critical_high_count' => 0];
        $pantunLines = $profile['hof_pantun'] ?? [];
    @endphp

    <!-- Header Banner Hall of Fame Kota Pekanbaru -->
    <section class="relative overflow-hidden bg-linear-to-br from-[#072c22] via-[#0e4f3d] to-[#157a5f] text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-7 space-y-4">
                    <div class="inline-flex items-center gap-2 rounded-full border border-amber-400/40 bg-emerald-950/60 px-3.5 py-1 text-xs font-semibold text-amber-300">
                        <flux:icon.trophy class="size-4 text-amber-400" />
                        <span>Apresiasi Kehormatan Peneliti Keamanan Siber</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white">
                        Hall of Fame <span class="text-amber-300">PKU-CSIRT</span>
                    </h1>

                    <p class="text-sm sm:text-base text-emerald-100/90 leading-relaxed">
                        Daftar kehormatan para peneliti keamanan informasi (<em>Security Researcher</em>) yang telah berkontribusi melaporkan celah keamanan secara bertanggung jawab pada ruang lingkup <code class="rounded bg-emerald-950/80 px-1.5 py-0.5 text-amber-300 font-mono text-xs">*.pekanbaru.go.id</code>.
                    </p>

                    <div class="pt-2 flex flex-wrap gap-3">
                        <a
                            href="{{ route('public.incident-report') }}"
                            wire:navigate
                            class="inline-flex items-center gap-2 rounded-xl bg-amber-400 px-4 py-2.5 text-xs font-extrabold text-emerald-950 hover:bg-amber-300 transition-colors cursor-pointer"
                        >
                            <flux:icon.shield-exclamation class="size-4" />
                            <span>Kirim Laporan Kerentanan Baru</span>
                        </a>
                        @auth
                            <a
                                href="{{ route('dashboard.hof') }}"
                                wire:navigate
                                class="inline-flex items-center gap-2 rounded-xl border border-amber-300/40 bg-emerald-900/70 px-4 py-2.5 text-xs font-bold text-white hover:bg-emerald-800 transition-colors cursor-pointer"
                            >
                                <flux:icon.cog-6-tooth class="size-4 text-amber-300" />
                                <span>Kelola Hall of Fame (Admin)</span>
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Bingkai Pantun Penghargaan Melayu Pekanbaru -->
                <div class="lg:col-span-5">
                    <div class="rounded-2xl border-2 border-amber-400/60 bg-emerald-950/75 p-6 shadow-xl backdrop-blur-xs space-y-3">
                        <div class="flex items-center justify-between border-b border-amber-400/25 pb-2.5">
                            <span class="text-xs font-extrabold uppercase tracking-wider text-amber-300">
                                Sekapur Sirih Penghargaan
                            </span>
                            <span class="text-[11px] font-semibold text-emerald-200">
                                Kota Bertuah
                            </span>
                        </div>
                        <div class="space-y-1.5 text-xs sm:text-sm italic text-emerald-50 leading-relaxed">
                            @foreach ($pantunLines as $line)
                                <p>{{ $line }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 space-y-12">
        <!-- Ringkasan Statistik Hall of Fame -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-app-border bg-app-surface p-5 flex items-center gap-4 shadow-2xs">
                <div class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-amber-500/15 text-amber-600 dark:text-amber-400">
                    <flux:icon.users class="size-6" />
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-app-text-muted">Peneliti Terdaftar</p>
                    <p class="text-2xl font-extrabold text-app-text">{{ $stats['total_researchers'] }} Kontributor</p>
                </div>
            </div>

            <div class="rounded-2xl border border-app-border bg-app-surface p-5 flex items-center gap-4 shadow-2xs">
                <div class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-primary-soft text-primary">
                    <flux:icon.shield-check class="size-6" />
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-app-text-muted">Total Temuan Dimitigasi</p>
                    <p class="text-2xl font-extrabold text-app-text">{{ $stats['total_entries'] }} Laporan Valid</p>
                </div>
            </div>

            <div class="rounded-2xl border border-app-border bg-app-surface p-5 flex items-center gap-4 shadow-2xs">
                <div class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-danger-soft text-danger">
                    <flux:icon.bolt class="size-6" />
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-app-text-muted">Temuan Kritis & Tinggi</p>
                    <p class="text-2xl font-extrabold text-app-text">{{ $stats['critical_high_count'] }} Kerentanan</p>
                </div>
            </div>
        </div>

        <!-- Papan Peringkat (Leaderboard) Peneliti Keamanan Siber -->
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2">
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-app-text">
                        Leaderboard Peneliti Keamanan Siber
                    </h2>
                    <p class="text-xs sm:text-sm text-app-text-secondary">
                        Diurutkan berdasarkan jumlah laporan valid terbanyak, skor CVSS tertinggi, dan kontribusi terbaru.
                    </p>
                </div>
                @if ($yearFilter !== '')
                    <span class="inline-flex items-center rounded-full bg-amber-500/15 px-3 py-1 text-xs font-bold text-amber-700 dark:text-amber-300">
                        Periode Tahun {{ $yearFilter }}
                    </span>
                @endif
            </div>

            <div class="overflow-hidden rounded-2xl border border-app-border bg-app-surface shadow-2xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-app-border bg-app-surface-muted text-xs font-bold uppercase tracking-wider text-app-text-secondary">
                            <tr>
                                <th scope="col" class="px-5 py-3.5 w-20">Peringkat</th>
                                <th scope="col" class="px-5 py-3.5">Nama Security Researcher</th>
                                <th scope="col" class="px-5 py-3.5 text-center">Jumlah Temuan Valid</th>
                                <th scope="col" class="px-5 py-3.5 text-center">Skor CVSS Tertinggi</th>
                                <th scope="col" class="px-5 py-3.5 text-right">Kontribusi Terakhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-app-border">
                            @forelse ($leaderboard as $person)
                                <tr wire:key="leaderboard-row-{{ $person['position'] }}" class="hover:bg-app-surface-muted/60 transition-colors">
                                    <td class="px-5 py-4">
                                        @if ($person['position'] === 1)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-400 px-2.5 py-1 text-xs font-extrabold text-emerald-950 shadow-2xs">
                                                <span>#1</span>
                                            </span>
                                        @elseif ($person['position'] <= 3)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-primary-soft px-2.5 py-1 text-xs font-bold text-primary-content">
                                                <span>#{{ $person['position'] }}</span>
                                            </span>
                                        @else
                                            <span class="px-2 text-xs font-bold text-app-text-muted">
                                                #{{ $person['position'] }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 font-bold text-app-text">
                                        <div class="flex items-center gap-2">
                                            <span>{{ $person['name'] }}</span>
                                            @if (! empty($person['url']))
                                                <a
                                                    href="{{ $person['url'] }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    aria-label="Profil {{ $person['name'] }}"
                                                    class="text-primary hover:underline inline-flex items-center"
                                                >
                                                    <flux:icon.arrow-top-right-on-square class="size-3.5" />
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="inline-flex items-center rounded-lg bg-primary-soft px-2.5 py-1 text-xs font-extrabold text-primary-content">
                                            {{ $person['total'] }} Laporan
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-center font-mono font-bold text-app-text">
                                        {{ number_format((float) $person['highest_cvss'], 1) }}
                                    </td>
                                    <td class="px-5 py-4 text-right text-xs text-app-text-secondary">
                                        {{ $person['latest_formatted'] ?: '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-8 text-center text-sm text-app-text-muted">
                                        Belum ada data peringkat peneliti untuk filter yang dipilih.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Filter & Daftar Riwayat Temuan Kerentanan -->
        <div class="space-y-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 rounded-2xl border border-app-border bg-app-surface p-5 shadow-2xs">
                <div>
                    <h2 class="text-lg font-extrabold text-app-text">
                        Daftar Riwayat Temuan & Remediasi Kerentanan
                    </h2>
                    <p class="text-xs text-app-text-secondary">
                        Menampilkan {{ count($entries) }} laporan kerentanan yang telah diverifikasi dan ditutup.
                    </p>
                </div>

                <!-- Kontrol Filter Interaktif -->
                <div class="flex flex-wrap items-center gap-3">
                    <div>
                        <input
                            type="search"
                            wire:model.live.debounce.250ms="search"
                            aria-label="Cari peneliti atau kerentanan"
                            placeholder="Cari nama, celah, subdomain..."
                            class="w-full sm:w-56 rounded-lg border border-app-border bg-app-surface-muted px-3 py-2 text-xs text-app-text focus:border-primary focus:outline-hidden"
                        />
                    </div>

                    <div>
                        <select
                            wire:model.live="yearFilter"
                            aria-label="Filter Tahun"
                            class="rounded-lg border border-app-border bg-app-surface-muted px-3 py-2 text-xs font-semibold text-app-text focus:border-primary focus:outline-hidden"
                        >
                            <option value="">Semua Tahun</option>
                            @foreach ($years as $year)
                                <option value="{{ $year }}">Tahun {{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select
                            wire:model.live="severityFilter"
                            aria-label="Filter Tingkat Keparahan"
                            class="rounded-lg border border-app-border bg-app-surface-muted px-3 py-2 text-xs font-semibold text-app-text focus:border-primary focus:outline-hidden"
                        >
                            <option value="">Semua Tingkat CVSS</option>
                            <option value="Kritis">Kritis (9.0 - 10.0)</option>
                            <option value="Tinggi">Tinggi (7.0 - 8.9)</option>
                            <option value="Sedang">Sedang (4.0 - 6.9)</option>
                        </select>
                    </div>

                    @if ($yearFilter !== '' || $severityFilter !== '' || $search !== '')
                        <button
                            type="button"
                            wire:click="resetFilters"
                            class="rounded-lg border border-app-border px-3 py-2 text-xs font-semibold text-app-text-secondary hover:text-app-text cursor-pointer"
                        >
                            Reset
                        </button>
                    @endif
                </div>
            </div>

            <!-- Kartu-kartu Rincian Temuan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @forelse ($entries as $entry)
                    @php
                        $severity = (string) ($entry['severity'] ?? 'Tinggi');
                        $badgeClasses = match ($severity) {
                            'Kritis' => 'bg-danger-soft text-danger-content border-danger/30',
                            'Tinggi' => 'bg-warning-soft text-warning-content border-warning/30',
                            default => 'bg-info-soft text-info-content border-info/30',
                        };
                    @endphp

                    <article
                        wire:key="hof-entry-{{ $entry['id'] }}"
                        class="rounded-2xl border border-app-border bg-app-surface p-6 shadow-2xs hover:border-primary/40 transition-colors flex flex-col justify-between gap-4"
                    >
                        <div class="space-y-3">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex size-8 items-center justify-center rounded-lg bg-primary-soft text-xs font-extrabold text-primary-content">
                                        {{ mb_strtoupper(mb_substr((string) $entry['name'], 0, 2)) }}
                                    </span>
                                    <div>
                                        <h3 class="text-sm font-extrabold text-app-text">
                                            {{ $entry['name'] }}
                                        </h3>
                                        <p class="text-[11px] text-app-text-muted">
                                            Dilaporkan: {{ $entry['date'] }}
                                        </p>
                                    </div>
                                </div>

                                <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-extrabold {{ $badgeClasses }}">
                                    <span>CVSS {{ $entry['cvss'] }}</span>
                                    <span>•</span>
                                    <span>{{ $severity }}</span>
                                </span>
                            </div>

                            <div class="space-y-1 pt-1">
                                <h4 class="text-base font-bold text-app-text">
                                    {{ $entry['vuln_name'] }}
                                </h4>
                                <p class="text-xs font-mono font-semibold text-primary break-all">
                                    Subdomain: {{ $entry['vuln_web'] }}
                                </p>
                            </div>

                            <p class="text-xs sm:text-sm text-app-text-secondary leading-relaxed">
                                {{ $entry['description'] }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-app-border flex items-center justify-between text-xs">
                            <span class="inline-flex items-center gap-1.5 font-semibold text-success">
                                <flux:icon.check-circle class="size-4" />
                                <span>Dimitigasi & Terverifikasi</span>
                            </span>

                            @if (! empty($entry['url']))
                                <a
                                    href="{{ $entry['url'] }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center gap-1 font-semibold text-primary hover:underline"
                                >
                                    <span>Tautan Apresiasi</span>
                                    <flux:icon.arrow-top-right-on-square class="size-3.5" />
                                </a>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-2xl border border-app-border bg-app-surface p-10 text-center space-y-3">
                        <p class="text-base font-bold text-app-text">Tidak ada entri Hall of Fame yang cocok dengan filter pencarian.</p>
                        <button
                            type="button"
                            wire:click="resetFilters"
                            class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-xs font-bold text-white cursor-pointer"
                        >
                            Tampilkan Semua Entri
                        </button>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
