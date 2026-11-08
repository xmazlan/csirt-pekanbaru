<div class="space-y-6">
    @php
        $counts = $this->counts;
    @endphp

    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-primary-soft px-2.5 py-0.5 text-xs font-bold text-primary-content">
                    <span class="size-1.5 rounded-full bg-primary"></span>
                    Media &amp; Penyimpanan Lokal
                </span>
            </div>
            <h1 class="mt-1 text-xl sm:text-2xl font-extrabold tracking-tight text-app-text">
                Manajemen Media &amp; Aset Digital
            </h1>
            <p class="text-xs sm:text-sm text-app-text-secondary">
                Pusat repositori gambar dan infografis mandiri untuk berita dan dokumentasi giat PKU-CSIRT Pekanbaru.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <button
                type="button"
                wire:click="openUpload"
                class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-primary-dark transition-colors cursor-pointer"
            >
                <flux:icon.arrow-up-tray class="size-4" />
                <span>Unggah Media Baru</span>
            </button>
        </div>
    </div>

    <!-- Statistik Media -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded-xl border border-app-border bg-app-surface p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-app-text-muted">Total Berkas Media</p>
            <p class="mt-1 text-2xl font-extrabold text-app-text">{{ $counts['total'] }}</p>
            <p class="mt-0.5 text-[11px] text-app-text-muted">Tersedia di pustaka aplikasi</p>
        </div>

        <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700 dark:text-emerald-300">Unggahan Tim Lokal</p>
            <p class="mt-1 text-2xl font-extrabold text-emerald-700 dark:text-emerald-300">{{ $counts['upload'] }}</p>
            <p class="mt-0.5 text-[11px] text-emerald-600/80 dark:text-emerald-400/80">Tersimpan di storage mandiri</p>
        </div>

        <div class="rounded-xl border border-amber-500/30 bg-amber-500/10 p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-amber-700 dark:text-amber-300">Koleksi Bawaan CSIRT</p>
            <p class="mt-1 text-2xl font-extrabold text-amber-700 dark:text-amber-300">{{ $counts['system'] }}</p>
            <p class="mt-0.5 text-[11px] text-amber-600/80 dark:text-amber-400/80">Aset ilustrasi dan logo resmi</p>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-xl border border-app-border bg-app-surface p-4">
        <div class="flex flex-wrap items-center gap-1.5" role="group" aria-label="Filter sumber media">
            @foreach (['' => 'Semua Media', 'upload' => 'Unggahan Tim', 'system' => 'Koleksi Bawaan'] as $key => $label)
                <button
                    type="button"
                    wire:click="setSourceFilter('{{ $key }}')"
                    class="rounded-lg px-3 py-1.5 text-xs font-bold transition-colors cursor-pointer {{ $sourceFilter === $key ? 'bg-primary text-white shadow-xs' : 'bg-app-surface-muted text-app-text-secondary hover:text-app-text' }}"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <div class="w-full sm:w-72">
            <input
                type="search"
                wire:model.live.debounce.250ms="search"
                aria-label="Cari berkas media"
                placeholder="Cari nama berkas, path..."
                class="w-full rounded-lg border border-app-border bg-app-surface-muted px-3 py-1.5 text-xs text-app-text focus:border-primary focus:outline-hidden"
            />
        </div>
    </div>

    <!-- Modal Form Unggah Media -->
    @if ($showUploadModal)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs overflow-y-auto"
            role="dialog"
            aria-modal="true"
            aria-labelledby="upload-modal-title"
        >
            <div class="relative w-full max-w-lg my-8 rounded-2xl border border-app-border bg-app-surface shadow-2xl overflow-hidden flex flex-col">
                <div class="flex items-center justify-between border-b border-app-border px-6 py-4 bg-app-surface-muted">
                    <div>
                        <h2 id="upload-modal-title" class="text-base font-extrabold text-app-text">
                            Unggah Media Gambar Baru
                        </h2>
                        <p class="text-xs text-app-text-secondary">
                            Berkas akan disimpan langsung di media storage lokal PKU-CSIRT.
                        </p>
                    </div>

                    <button
                        type="button"
                        wire:click="closeUpload"
                        class="rounded-lg p-1.5 text-app-text-muted hover:bg-app-surface hover:text-app-text transition-colors cursor-pointer"
                    >
                        <flux:icon.x-mark class="size-5" />
                    </button>
                </div>

                <form wire:submit="upload" class="p-6 space-y-4">
                    <!-- Dropzone Berkas -->
                    <div class="space-y-1">
                        <label for="media-file-input" class="text-xs font-bold uppercase tracking-wider text-app-text">
                            Pilih Berkas Gambar <span class="text-status-danger">*</span>
                        </label>

                        <div class="rounded-xl border-2 border-dashed border-app-border p-6 text-center hover:border-primary transition-colors bg-app-surface-muted/50">
                            <input
                                id="media-file-input"
                                type="file"
                                wire:model="file"
                                accept="image/jpeg,image/png,image/webp,image/svg+xml"
                                class="sr-only"
                            />
                            <label for="media-file-input" class="cursor-pointer flex flex-col items-center gap-2">
                                <div class="flex size-12 items-center justify-center rounded-full bg-primary-soft text-primary">
                                    <flux:icon.photo class="size-6" />
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-primary hover:underline">Klik untuk memilih gambar</span>
                                    <span class="text-xs text-app-text-muted"> atau seret ke sini</span>
                                </div>
                                <p class="text-[11px] text-app-text-muted">
                                    Format: JPG, PNG, WEBP, atau SVG (Maks. 5 MB)
                                </p>
                            </label>
                        </div>

                        <!-- Pratinjau Sementara -->
                        @if ($file && ! $errors->has('file') && method_exists($file, 'isPreviewable') && $file->isPreviewable())
                            <div class="mt-3 p-3 rounded-xl border border-app-border bg-app-surface flex items-center gap-3">
                                <div class="relative size-16 shrink-0 overflow-hidden rounded-lg border border-app-border">
                                    <img
                                        src="{{ $file->temporaryUrl() }}"
                                        alt="Pratinjau Unggahan"
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-app-text truncate">{{ $file->getClientOriginalName() }}</p>
                                    <p class="text-[11px] text-app-text-muted">Siap diunggah ke server</p>
                                </div>
                            </div>
                        @elseif ($file && ! $errors->has('file'))
                            <div class="mt-3 p-3 rounded-xl border border-app-border bg-app-surface flex items-center gap-3">
                                <div class="flex size-16 items-center justify-center shrink-0 rounded-lg border border-app-border bg-app-surface-muted text-app-text-muted">
                                    <flux:icon.document class="size-8" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-app-text truncate">{{ method_exists($file, 'getClientOriginalName') ? $file->getClientOriginalName() : 'Berkas' }}</p>
                                    <p class="text-[11px] text-app-text-muted">Siap diproses sistem</p>
                                </div>
                            </div>
                        @endif

                        @error('file')
                            <p class="text-xs font-semibold text-status-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Label / Nama Media -->
                    <div class="space-y-1">
                        <label for="media-label-input" class="text-xs font-bold uppercase tracking-wider text-app-text">
                            Label / Keterangan Media (Opsional)
                        </label>
                        <input
                            id="media-label-input"
                            type="text"
                            wire:model="label"
                            placeholder="Contoh: Dokumentasi Workshop Sandiman 2026"
                            class="w-full rounded-lg border border-app-border bg-app-surface px-3 py-2 text-xs sm:text-sm text-app-text focus:border-primary focus:outline-hidden"
                        />
                        @error('label')
                            <p class="text-xs font-semibold text-status-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-3 border-t border-app-border flex items-center justify-end gap-2.5">
                        <button
                            type="button"
                            wire:click="closeUpload"
                            class="rounded-xl border border-app-border bg-app-surface px-4 py-2 text-xs font-bold text-app-text hover:bg-app-surface-muted transition-colors cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-2 text-xs font-bold text-white shadow-xs hover:bg-primary-dark transition-colors cursor-pointer"
                        >
                            <span wire:loading.remove>Unggah Sekarang</span>
                            <span wire:loading>Mengunggah...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Konfirmasi Hapus Media -->
    @if ($pendingDeletePath !== null)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
            role="alertdialog"
            aria-modal="true"
            aria-labelledby="delete-media-title"
        >
            <div class="relative w-full max-w-md rounded-2xl border border-app-border bg-app-surface p-6 shadow-2xl space-y-4">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-full bg-status-danger-soft text-status-danger shrink-0">
                        <flux:icon.trash class="size-5" />
                    </div>
                    <div>
                        <h2 id="delete-media-title" class="text-base font-extrabold text-app-text">
                            Hapus Berkas Media?
                        </h2>
                        <p class="text-xs text-app-text-muted">Berkas akan dihapus permanen dari storage lokal.</p>
                    </div>
                </div>

                <p class="text-xs sm:text-sm text-app-text-secondary">
                    Apakah Anda yakin ingin menghapus berkas <span class="font-bold text-app-text">"{{ $pendingDeleteName }}"</span>?
                </p>

                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <button
                        type="button"
                        wire:click="cancelDelete"
                        class="rounded-xl border border-app-border bg-app-surface px-4 py-2 text-xs font-bold text-app-text hover:bg-app-surface-muted transition-colors cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="button"
                        wire:click="deleteConfirmed"
                        class="rounded-xl bg-status-danger px-4 py-2 text-xs font-bold text-white shadow-xs hover:opacity-90 transition-opacity cursor-pointer"
                    >
                        Ya, Hapus Media
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Pratinjau Gambar Penuh -->
    @if ($previewUrl !== null)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
            role="dialog"
            aria-modal="true"
            aria-labelledby="preview-modal-title"
        >
            <div class="relative max-w-4xl w-full rounded-2xl border border-app-border bg-app-surface overflow-hidden shadow-2xl">
                <div class="flex items-center justify-between border-b border-app-border px-4 py-3 bg-app-surface-muted">
                    <p id="preview-modal-title" class="text-xs font-bold text-app-text truncate">{{ $previewTitle }}</p>
                    <button
                        type="button"
                        wire:click="closePreview"
                        class="rounded-lg p-1 text-app-text-muted hover:text-app-text transition-colors cursor-pointer"
                    >
                        <flux:icon.x-mark class="size-5" />
                    </button>
                </div>
                <div class="p-4 flex items-center justify-center bg-black/40 max-h-[75vh] overflow-hidden">
                    <img
                        src="{{ $previewUrl }}"
                        alt="{{ $previewTitle }}"
                        class="max-h-[70vh] w-auto object-contain rounded-lg shadow-lg"
                    />
                </div>
            </div>
        </div>
    @endif

    <!-- Galeri Grid Media -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @forelse ($this->mediaItems as $media)
            @php
                $isUpload = $media['source'] === 'upload';
            @endphp
            <div
                wire:key="media-item-{{ $media['id'] }}"
                x-data="{
                    copied: false,
                    copyPath() {
                        navigator.clipboard.writeText('{{ $media['path'] }}');
                        this.copied = true;
                        setTimeout(() => { this.copied = false }, 2000);
                    }
                }"
                class="group relative flex flex-col overflow-hidden rounded-2xl border border-app-border bg-app-surface shadow-2xs hover:border-primary/50 transition-all duration-200"
            >
                <!-- Thumbnail -->
                <div class="relative aspect-16/10 w-full overflow-hidden bg-app-surface-muted">
                    <img
                        src="{{ $media['url'] }}"
                        alt="{{ $media['name'] }}"
                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                        loading="lazy"
                    />
                    <div class="absolute top-2 left-2 flex items-center gap-1">
                        @if ($isUpload)
                            <span class="rounded-md bg-emerald-950/80 px-2 py-0.5 text-[10px] font-bold text-amber-300 backdrop-blur-xs">
                                Unggahan Tim
                            </span>
                        @else
                            <span class="rounded-md bg-zinc-900/80 px-2 py-0.5 text-[10px] font-bold text-zinc-200 backdrop-blur-xs">
                                Koleksi Bawaan
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Info Berkas -->
                <div class="p-3.5 flex flex-1 flex-col justify-between space-y-2">
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-app-text truncate" title="{{ $media['name'] }}">
                            {{ $media['name'] }}
                        </p>
                        <p class="font-mono text-[10px] text-app-text-muted truncate">
                            {{ $media['path'] }}
                        </p>
                        <div class="flex items-center justify-between text-[10px] text-app-text-muted pt-1">
                            <span>{{ $media['size'] }}</span>
                            <span>{{ \Illuminate\Support\Carbon::parse($media['created_at'])->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="pt-2 border-t border-app-border flex items-center justify-between gap-1.5">
                        <button
                            type="button"
                            @click="copyPath"
                            title="Salin Path Berkas"
                            class="inline-flex items-center gap-1 rounded-lg border border-app-border bg-app-surface-muted px-2 py-1 text-[11px] font-semibold text-app-text hover:border-primary/50 transition-colors cursor-pointer"
                        >
                            <flux:icon.link class="size-3 text-primary" />
                            <span x-text="copied ? 'Tersalin!' : 'Salin Path'"></span>
                        </button>

                        <div class="flex items-center gap-1">
                            <button
                                type="button"
                                wire:click="preview('{{ $media['url'] }}', '{{ addslashes($media['name']) }}')"
                                title="Pratinjau Penuh"
                                class="rounded-lg p-1 text-app-text-muted hover:bg-app-surface-muted hover:text-primary transition-colors cursor-pointer"
                            >
                                <flux:icon.eye class="size-4" />
                            </button>

                            @if ($isUpload)
                                <button
                                    type="button"
                                    wire:click="confirmDelete('{{ $media['path'] }}', '{{ addslashes($media['name']) }}')"
                                    title="Hapus Berkas Media"
                                    class="rounded-lg p-1 text-app-text-muted hover:bg-status-danger-soft hover:text-status-danger transition-colors cursor-pointer"
                                >
                                    <flux:icon.trash class="size-4" />
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-2xl border border-app-border bg-app-surface p-12 text-center shadow-2xs">
                <div class="mx-auto max-w-sm space-y-3">
                    <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-app-surface-muted text-app-text-muted">
                        <flux:icon.photo class="size-6" />
                    </div>
                    <p class="text-sm font-bold text-app-text">Tidak ada berkas media ditemukan</p>
                    <p class="text-xs text-app-text-secondary">
                        Coba sesuaikan kata kunci pencarian atau unggah gambar baru ke storage lokal.
                    </p>
                    <button
                        type="button"
                        wire:click="openUpload"
                        class="mt-2 inline-flex items-center gap-1.5 rounded-lg bg-primary px-3 py-1.5 text-xs font-bold text-white shadow-xs"
                    >
                        <flux:icon.arrow-up-tray class="size-3.5" />
                        <span>Unggah Gambar Pertama</span>
                    </button>
                </div>
            </div>
        @endforelse
    </div>
</div>
