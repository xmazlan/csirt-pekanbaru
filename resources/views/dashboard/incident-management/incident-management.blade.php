<div class="space-y-6">
    @php
        $counts = $this->counts;
    @endphp

    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight text-app-text">
                Manajemen Tiket Insiden &amp; Kerentanan Siber
            </h1>
            <p class="text-xs sm:text-sm text-app-text-secondary">
                Pantau, triase, dan perbarui status penanganan laporan insiden yang masuk ke PKU-CSIRT Kota Pekanbaru.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a
                href="{{ route('public.incident-report') }}"
                wire:navigate
                class="inline-flex items-center gap-2 rounded-xl border border-app-border bg-app-surface px-4 py-2 text-xs font-bold text-app-text hover:border-primary/50 transition-colors"
            >
                <flux:icon.arrow-top-right-on-square class="size-4 text-primary" />
                <span>Lihat Formulir Publik</span>
            </a>
        </div>
    </div>

    <!-- Ringkasan Status Tiket -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-xl border border-app-border bg-app-surface p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-app-text-muted">Total Tiket</p>
            <p class="mt-1 text-2xl font-extrabold text-app-text">{{ $counts['total'] }}</p>
        </div>
        <div class="rounded-xl border border-danger/30 bg-danger-soft/40 p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-danger-content">Menunggu Triase (Baru)</p>
            <p class="mt-1 text-2xl font-extrabold text-danger-content">{{ $counts['baru'] }}</p>
        </div>
        <div class="rounded-xl border border-warning/30 bg-warning-soft/40 p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-warning-content">Sedang Diproses</p>
            <p class="mt-1 text-2xl font-extrabold text-warning-content">{{ $counts['diproses'] }}</p>
        </div>
        <div class="rounded-xl border border-success/30 bg-success-soft/40 p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-success-content">Selesai Dimitigasi</p>
            <p class="mt-1 text-2xl font-extrabold text-success-content">{{ $counts['selesai'] }}</p>
        </div>
    </div>

    <!-- Bar Filter & Pencarian -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-xl border border-app-border bg-app-surface p-4">
        <div class="flex flex-wrap items-center gap-1.5" role="group" aria-label="Filter status tiket">
            @foreach (['' => 'Semua Tiket', 'baru' => 'Baru', 'diproses' => 'Diproses', 'selesai' => 'Selesai'] as $key => $label)
                <button
                    type="button"
                    wire:click="setStatusFilter('{{ $key }}')"
                    class="rounded-lg px-3 py-1.5 text-xs font-bold transition-colors cursor-pointer {{ $statusFilter === $key ? 'bg-primary text-white' : 'bg-app-surface-muted text-app-text-secondary hover:text-app-text' }}"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <div class="w-full sm:w-64">
            <input
                type="search"
                wire:model.live.debounce.250ms="search"
                aria-label="Cari tiket insiden"
                placeholder="Cari nomor tiket, pelapor, URL..."
                class="w-full rounded-lg border border-app-border bg-app-surface-muted px-3 py-1.5 text-xs text-app-text focus:border-primary focus:outline-hidden"
            />
        </div>
    </div>

    <!-- Daftar Tiket Laporan Insiden -->
    <div class="space-y-4">
        @forelse ($this->reports as $report)
            @php
                $statusBadge = match ($report['status']) {
                    'baru' => 'bg-danger-soft text-danger-content border-danger/30',
                    'diproses' => 'bg-warning-soft text-warning-content border-warning/30',
                    default => 'bg-success-soft text-success-content border-success/30',
                };
            @endphp

            <div
                wire:key="incident-row-{{ $report['id'] }}"
                class="rounded-xl border border-app-border bg-app-surface p-5 shadow-2xs space-y-4"
            >
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div class="space-y-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="font-mono text-xs font-extrabold text-primary">
                                {{ $report['ticket_number'] }}
                            </span>
                            <span class="rounded-full border px-2.5 py-0.5 text-[11px] font-bold uppercase {{ $statusBadge }}">
                                {{ $report['status'] }}
                            </span>
                            <span class="rounded bg-app-surface-muted px-2 py-0.5 text-[11px] font-semibold text-app-text-secondary">
                                Keparahan: {{ $report['severity'] }}
                            </span>
                        </div>
                        <h2 class="text-base font-bold text-app-text">
                            {{ $report['category'] }} — <span class="font-mono text-sm text-primary">{{ $report['affected_system'] }}</span>
                        </h2>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <span class="text-xs text-app-text-muted me-1">Ubah Status:</span>
                        @foreach (['baru' => 'Baru', 'diproses' => 'Diproses', 'selesai' => 'Selesai'] as $stKey => $stLabel)
                            <button
                                type="button"
                                wire:click="updateStatus({{ $report['id'] }}, '{{ $stKey }}')"
                                class="rounded-lg px-2.5 py-1 text-xs font-bold transition-colors cursor-pointer {{ $report['status'] === $stKey ? 'bg-primary text-white' : 'border border-app-border bg-app-surface-muted text-app-text-secondary hover:text-app-text' }}"
                            >
                                {{ $stLabel }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <p class="text-xs sm:text-sm text-app-text-secondary leading-relaxed">
                    {{ $report['description'] }}
                </p>

                <div class="flex flex-wrap items-center justify-between gap-2 border-t border-app-border pt-3 text-xs text-app-text-muted">
                    <div>
                        Pelapor: <strong class="text-app-text">{{ $report['reporter_name'] }}</strong> ({{ $report['reporter_org'] }}) • {{ $report['reporter_email'] }}
                    </div>
                    <div>
                        Waktu Lapor: {{ $report['created_at'] }} WIB
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-app-border bg-app-surface p-8 text-center text-sm text-app-text-muted">
                Tidak ada tiket laporan insiden yang sesuai dengan filter.
            </div>
        @endforelse
    </div>
</div>
