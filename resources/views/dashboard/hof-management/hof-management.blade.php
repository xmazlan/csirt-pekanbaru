<div class="space-y-8">
    @php
        $hof = $this->hofData;
        $entries = $hof['entries'] ?? [];
        $leaderboard = $hof['leaderboard'] ?? [];
    @endphp

    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight text-app-text">
                Manajemen Hall of Fame Security Researcher
            </h1>
            <p class="text-xs sm:text-sm text-app-text-secondary">
                Tambahkan atau kelola daftar peneliti keamanan yang telah melaporkan celah keamanan valid pada ekosistem <code class="font-mono text-xs text-primary">*.pekanbaru.go.id</code>.
            </p>
        </div>

        <a
            href="{{ route('public.hall-of-fame') }}"
            wire:navigate
            class="inline-flex items-center gap-2 rounded-xl border border-app-border bg-app-surface px-4 py-2 text-xs font-bold text-app-text hover:border-primary/50 transition-colors"
        >
            <flux:icon.trophy class="size-4 text-amber-500" />
            <span>Buka Hall of Fame Publik</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Formulir Tambah Entri Hall of Fame -->
        <div class="lg:col-span-5 rounded-2xl border border-app-border bg-app-surface p-6 shadow-2xs space-y-5">
            <div class="border-b border-app-border pb-4">
                <h2 class="text-base font-extrabold text-app-text">
                    Tambah Apresiasi Peneliti Baru
                </h2>
                <p class="text-xs text-app-text-secondary">
                    Peringkat leaderboard peneliti akan dihitung otomatis berdasarkan jumlah laporan dan skor CVSS.
                </p>
            </div>

            <form wire:submit="addEntry" class="space-y-4">
                <flux:field>
                    <flux:label>Nama Peneliti Keamanan *</flux:label>
                    <flux:input wire:model="name" placeholder="Contoh: William / Aditya Kurniawan" required />
                    <flux:error name="name" />
                </flux:field>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Tanggal Pelaporan *</flux:label>
                        <flux:input type="date" wire:model="date" required />
                        <flux:error name="date" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Skor CVSS v3 (0.1 - 10.0) *</flux:label>
                        <flux:input type="number" step="0.1" min="0.1" max="10.0" wire:model="cvss" required />
                        <flux:error name="cvss" />
                    </flux:field>
                </div>

                <flux:field>
                    <flux:label>Judul Kerentanan (Vulnerability) *</flux:label>
                    <flux:input wire:model="vuln_name" placeholder="Contoh: Broken Access Control & IDOR" required />
                    <flux:error name="vuln_name" />
                </flux:field>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>URL Subdomain Terdampak *</flux:label>
                        <flux:input wire:model="vuln_web" placeholder="https://layanan.pekanbaru.go.id" required />
                        <flux:error name="vuln_web" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Tautan Profil / Apresiasi *</flux:label>
                        <flux:input wire:model="url" placeholder="https://pekanbaru.go.id" required />
                        <flux:error name="url" />
                    </flux:field>
                </div>

                <flux:field>
                    <flux:label>Ringkasan Remediasi *</flux:label>
                    <flux:textarea
                        wire:model="description"
                        rows="3"
                        placeholder="Jelaskan secara singkat temuan dan perbaikan yang telah diterapkan..."
                        required
                    />
                    <flux:error name="description" />
                </flux:field>

                <div class="pt-2">
                    <flux:button variant="primary" type="submit" icon="plus" class="w-full">
                        Simpan ke Hall of Fame
                    </flux:button>
                </div>
            </form>
        </div>

        <!-- Daftar Entri Hall of Fame Saat Ini -->
        <div class="lg:col-span-7 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-extrabold text-app-text">
                    Daftar Entri Terpublikasi ({{ count($entries) }})
                </h2>
                <span class="text-xs text-app-text-muted">
                    {{ count($leaderboard) }} Peneliti di Leaderboard
                </span>
            </div>

            <div class="space-y-3">
                @forelse ($entries as $entry)
                    <div
                        wire:key="admin-hof-{{ $entry['id'] }}"
                        class="rounded-xl border border-app-border bg-app-surface p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4"
                    >
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-bold text-sm text-app-text">{{ $entry['name'] }}</span>
                                <span class="rounded bg-primary-soft px-2 py-0.5 text-[11px] font-bold text-primary-content">
                                    CVSS {{ $entry['cvss'] }} ({{ $entry['severity'] }})
                                </span>
                                <span class="text-xs text-app-text-muted">{{ $entry['date'] }}</span>
                            </div>
                            <p class="text-xs font-semibold text-app-text-secondary">
                                {{ $entry['vuln_name'] }} — <span class="font-mono text-primary">{{ $entry['vuln_web'] }}</span>
                            </p>
                            <p class="text-xs text-app-text-muted line-clamp-2">
                                {{ $entry['description'] }}
                            </p>
                        </div>

                        <div class="shrink-0">
                            <flux:button
                                variant="danger"
                                size="sm"
                                icon="trash"
                                wire:click="confirmDelete({{ $entry['id'] }}, '{{ addslashes((string) $entry['name']) }}')"
                            >
                                Hapus
                            </flux:button>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-app-border bg-app-surface p-8 text-center text-sm text-app-text-muted">
                        Belum ada entri Hall of Fame.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Penghapusan Flux UI (Tanpa confirm() Browser Native) -->
    <flux:modal name="confirm-delete-hof" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Hapus Entri Hall of Fame?</flux:heading>
                <flux:subheading>
                    Apakah Anda yakin ingin menghapus entri apresiasi untuk peneliti <strong>{{ $pendingDeleteName }}</strong>? Tindakan ini akan memperbarui perhitungan peringkat leaderboard.
                </flux:subheading>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" wire:click="deleteConfirmed">
                    Ya, Hapus Entri
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
