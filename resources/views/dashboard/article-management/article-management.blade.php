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
                    Portal Informasi &amp; Literasi Siber
                </span>
            </div>
            <h1 class="mt-1 text-xl sm:text-2xl font-extrabold tracking-tight text-app-text">
                Manajemen Publikasi &amp; Berita Kegiatan
            </h1>
            <p class="text-xs sm:text-sm text-app-text-secondary">
                Kelola siaran berita, advisori keamanan siber, dan dokumentasi kegiatan resmi PKU-CSIRT Kota Pekanbaru.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a
                href="{{ route('dashboard.media') }}"
                wire:navigate
                class="inline-flex items-center gap-2 rounded-xl border border-app-border bg-app-surface px-3.5 py-2 text-xs font-bold text-app-text hover:border-primary/50 transition-colors"
            >
                <flux:icon.photo class="size-4 text-emerald-600 dark:text-emerald-400" />
                <span>Pustaka Media</span>
            </a>

            <a
                href="{{ route('public.articles') }}"
                wire:navigate
                class="inline-flex items-center gap-2 rounded-xl border border-app-border bg-app-surface px-3.5 py-2 text-xs font-bold text-app-text hover:border-primary/50 transition-colors"
            >
                <flux:icon.arrow-top-right-on-square class="size-4 text-primary" />
                <span>Lihat Arsip Publik</span>
            </a>

            <button
                type="button"
                wire:click="startCreate"
                class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-primary-dark transition-colors cursor-pointer"
            >
                <flux:icon.plus class="size-4" />
                <span>Tambah Publikasi Baru</span>
            </button>
        </div>
    </div>

    <!-- Ringkasan Statistik Editorial -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded-xl border border-app-border bg-app-surface p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-app-text-muted">Total Publikasi</p>
            <p class="mt-1 text-2xl font-extrabold text-app-text">{{ $counts['total'] }}</p>
            <p class="mt-0.5 text-[11px] text-app-text-muted">Artikel terdaftar di sistem</p>
        </div>

        <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700 dark:text-emerald-300">Diterbitkan (Tayang)</p>
            <p class="mt-1 text-2xl font-extrabold text-emerald-700 dark:text-emerald-300">{{ $counts['published'] }}</p>
            <p class="mt-0.5 text-[11px] text-emerald-600/80 dark:text-emerald-400/80">Aktif dapat dibaca masyarakat</p>
        </div>

        <div class="rounded-xl border border-amber-500/30 bg-amber-500/10 p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-amber-700 dark:text-amber-300">Draf Internal</p>
            <p class="mt-1 text-2xl font-extrabold text-amber-700 dark:text-amber-300">{{ $counts['draft'] }}</p>
            <p class="mt-0.5 text-[11px] text-amber-600/80 dark:text-amber-400/80">Hanya terlihat oleh tim internal</p>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 rounded-xl border border-app-border bg-app-surface p-4">
        <!-- Kategori Chips -->
        <div class="flex flex-wrap items-center gap-1.5" role="group" aria-label="Filter kategori">
            <button
                type="button"
                wire:click="setCategoryFilter('')"
                class="rounded-lg px-3 py-1.5 text-xs font-bold transition-colors cursor-pointer {{ $categoryFilter === '' ? 'bg-primary text-white shadow-xs' : 'bg-app-surface-muted text-app-text-secondary hover:text-app-text' }}"
            >
                Semua Kategori
            </button>
            @foreach ($this->categories as $cat)
                <button
                    type="button"
                    wire:click="setCategoryFilter('{{ $cat }}')"
                    class="rounded-lg px-3 py-1.5 text-xs font-bold transition-colors cursor-pointer {{ $categoryFilter === $cat ? 'bg-primary text-white shadow-xs' : 'bg-app-surface-muted text-app-text-secondary hover:text-app-text' }}"
                >
                    {{ $cat }}
                </button>
            @endforeach
        </div>

        <!-- Filter Status & Pencarian -->
        <div class="flex flex-col sm:flex-row items-center gap-2">
            <div class="flex items-center gap-1">
                @foreach (['' => 'Semua Status', 'published' => 'Diterbitkan', 'draft' => 'Draf'] as $sKey => $sLabel)
                    <button
                        type="button"
                        wire:click="setStatusFilter('{{ $sKey }}')"
                        class="rounded-lg px-2.5 py-1 text-[11px] font-semibold transition-colors cursor-pointer {{ $statusFilter === $sKey ? 'bg-zinc-800 text-white dark:bg-zinc-200 dark:text-zinc-900' : 'bg-app-surface-muted text-app-text-muted hover:text-app-text' }}"
                    >
                        {{ $sLabel }}
                    </button>
                @endforeach
            </div>

            <div class="w-full sm:w-64">
                <input
                    type="search"
                    wire:model.live.debounce.250ms="search"
                    aria-label="Cari artikel berita atau advisori"
                    placeholder="Cari judul, konten, kategori..."
                    class="w-full rounded-lg border border-app-border bg-app-surface-muted px-3 py-1.5 text-xs text-app-text focus:border-primary focus:outline-hidden"
                />
            </div>
        </div>
    </div>

    <!-- Modal Form Tambah / Edit Artikel -->
    @if ($showForm)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs overflow-y-auto"
            role="dialog"
            aria-modal="true"
            aria-labelledby="article-modal-title"
        >
            <div class="relative w-full max-w-3xl my-8 rounded-2xl border border-app-border bg-app-surface shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b border-app-border px-6 py-4 bg-app-surface-muted">
                    <div>
                        <h2 id="article-modal-title" class="text-base sm:text-lg font-extrabold text-app-text">
                            {{ $editingId ? 'Edit Publikasi Berita / Advisori' : 'Tambah Publikasi Berita / Advisori Baru' }}
                        </h2>
                        <p class="text-xs text-app-text-secondary">
                            Lengkapi rincian siaran berita dan advisori siber untuk portal PKU-CSIRT Pekanbaru.
                        </p>
                    </div>

                    <button
                        type="button"
                        wire:click="cancelForm"
                        aria-label="Tutup form"
                        class="rounded-lg p-1.5 text-app-text-muted hover:bg-app-surface hover:text-app-text transition-colors cursor-pointer"
                    >
                        <flux:icon.x-mark class="size-5" />
                    </button>
                </div>

                <!-- Modal Body Scrollable -->
                <form wire:submit="save" class="flex-1 overflow-y-auto p-6 space-y-5">
                    <!-- Judul Publikasi -->
                    <div class="space-y-1">
                        <label for="article-title" class="text-xs font-bold uppercase tracking-wider text-app-text">
                            Judul Publikasi / Berita <span class="text-status-danger">*</span>
                        </label>
                        <input
                            id="article-title"
                            type="text"
                            wire:model="title"
                            placeholder="Contoh: Pemko Pekanbaru Perkuat Sistem Ketahanan Siber Terpadu"
                            class="w-full rounded-lg border border-app-border bg-app-surface px-3 py-2 text-sm text-app-text placeholder:text-app-text-muted focus:border-primary focus:outline-hidden"
                        />
                        @error('title')
                            <p class="text-xs font-semibold text-status-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori & Status Editorial -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="space-y-1">
                            <label for="article-category" class="text-xs font-bold uppercase tracking-wider text-app-text">
                                Kategori <span class="text-status-danger">*</span>
                            </label>
                            <select
                                id="article-category"
                                wire:model="category"
                                class="w-full rounded-lg border border-app-border bg-app-surface px-3 py-2 text-xs sm:text-sm text-app-text focus:border-primary focus:outline-hidden"
                            >
                                <option value="Keamanan Siber">Keamanan Siber</option>
                                <option value="Advisori Insiden">Advisori Insiden</option>
                                <option value="Edukasi &amp; Literasi">Edukasi &amp; Literasi</option>
                                <option value="Peringatan Publik">Peringatan Publik</option>
                            </select>
                            @error('category')
                                <p class="text-xs font-semibold text-status-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="article-status" class="text-xs font-bold uppercase tracking-wider text-app-text">
                                Status Editorial <span class="text-status-danger">*</span>
                            </label>
                            <select
                                id="article-status"
                                wire:model="status"
                                class="w-full rounded-lg border border-app-border bg-app-surface px-3 py-2 text-xs sm:text-sm text-app-text focus:border-primary focus:outline-hidden"
                            >
                                <option value="published">Diterbitkan (Tayang di Publik)</option>
                                <option value="draft">Draf (Disimpan Internal)</option>
                            </select>
                            @error('status')
                                <p class="text-xs font-semibold text-status-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="article-published-at" class="text-xs font-bold uppercase tracking-wider text-app-text">
                                Tanggal Terbit <span class="text-status-danger">*</span>
                            </label>
                            <input
                                id="article-published-at"
                                type="date"
                                wire:model="published_at"
                                class="w-full rounded-lg border border-app-border bg-app-surface px-3 py-2 text-xs sm:text-sm text-app-text focus:border-primary focus:outline-hidden"
                            />
                            @error('published_at')
                                <p class="text-xs font-semibold text-status-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Penulis / Instansi -->
                    <div class="space-y-1">
                        <label for="article-author" class="text-xs font-bold uppercase tracking-wider text-app-text">
                            Penerbit / Penulis <span class="text-status-danger">*</span>
                        </label>
                        <input
                            id="article-author"
                            type="text"
                            wire:model="author"
                            placeholder="Contoh: Diskominfotiksan Pekanbaru / Tim PKU-CSIRT"
                            class="w-full rounded-lg border border-app-border bg-app-surface px-3 py-2 text-xs sm:text-sm text-app-text focus:border-primary focus:outline-hidden"
                        />
                        @error('author')
                            <p class="text-xs font-semibold text-status-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pengelolaan Gambar Thumbnail Mandiri -->
                    <div class="space-y-3 rounded-2xl border border-app-border bg-app-surface-muted/40 p-4">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold uppercase tracking-wider text-app-text">
                                Gambar Thumbnail Artikel <span class="text-status-danger">*</span>
                            </label>

                            <button
                                type="button"
                                wire:click="openMediaPicker"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-app-border bg-app-surface px-2.5 py-1 text-xs font-semibold text-app-text hover:border-primary/50 transition-colors cursor-pointer"
                            >
                                <flux:icon.photo class="size-3.5 text-primary" />
                                <span>Pilih dari Pustaka Media</span>
                            </button>
                        </div>

                        <!-- Opsi 1: Unggah Langsung Berkas Baru -->
                        <div class="space-y-1">
                            <label for="article-thumbnail-file" class="text-[11px] font-semibold text-app-text-secondary">
                                Opsi A: Unggah Gambar Baru dari Perangkat (disimpan ke storage lokal)
                            </label>
                            <input
                                id="article-thumbnail-file"
                                type="file"
                                wire:model="thumbnailFile"
                                accept="image/jpeg,image/png,image/webp,image/svg+xml"
                                class="block w-full text-xs text-app-text file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:bg-primary-dark cursor-pointer"
                            />
                            @error('thumbnailFile')
                                <p class="text-xs font-semibold text-status-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pratinjau Thumbnail Terpilih -->
                        <div class="pt-2 flex items-center gap-4">
                            <div class="relative size-20 shrink-0 overflow-hidden rounded-xl border border-app-border bg-app-surface">
                                @if ($thumbnailFile && ! $errors->has('thumbnailFile') && method_exists($thumbnailFile, 'isPreviewable') && $thumbnailFile->isPreviewable())
                                    <img
                                        src="{{ $thumbnailFile->temporaryUrl() }}"
                                        alt="Pratinjau Thumbnail Baru"
                                        class="h-full w-full object-cover"
                                    />
                                @else
                                    <img
                                        src="{{ asset(ltrim($custom_thumbnail ?: $thumbnail, '/')) }}"
                                        alt="Pratinjau Thumbnail"
                                        class="h-full w-full object-cover"
                                    />
                                @endif
                            </div>
                            <div class="flex-1 min-w-0 space-y-1 text-xs">
                                <p class="font-bold text-app-text truncate">
                                    {{ $thumbnailFile ? 'Berkas baru siap diunggah: ' . $thumbnailFile->getClientOriginalName() : ($custom_thumbnail ?: $thumbnail) }}
                                </p>
                                <p class="text-[11px] text-app-text-muted">
                                    {{ $thumbnailFile ? 'Akan otomatis disimpan ke storage/media saat formulir disimpan.' : 'Gambar ini akan tampil sebagai banner artikel publik.' }}
                                </p>
                            </div>
                        </div>

                        <!-- Opsi 2: Katalog Cepat Thumbnail Resmi CSIRT -->
                        <div class="pt-2 space-y-1.5">
                            <span class="text-[11px] font-semibold text-app-text-secondary">
                                Opsi B: Atau pilih salah satu ilustrasi resmi CSIRT:
                            </span>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                @foreach ($this->availableThumbnails as $tItem)
                                    @php
                                        $isSelected = $thumbnail === $tItem['path'] && trim($custom_thumbnail) === '' && ! $thumbnailFile;
                                    @endphp
                                    <button
                                        type="button"
                                        wire:click="selectThumbnail('{{ $tItem['path'] }}')"
                                        class="group relative flex flex-col overflow-hidden rounded-xl border text-start transition-all cursor-pointer {{ $isSelected ? 'border-primary ring-2 ring-primary/40 bg-primary-soft/30' : 'border-app-border bg-app-surface hover:border-primary/40' }}"
                                    >
                                        <div class="relative aspect-16/10 w-full overflow-hidden bg-app-surface-muted">
                                            <img
                                                src="{{ asset(ltrim($tItem['path'], '/')) }}"
                                                alt="{{ $tItem['label'] }}"
                                                class="h-full w-full object-cover"
                                            />
                                        </div>
                                        <span class="p-1.5 text-[10px] font-semibold text-app-text truncate">
                                            {{ $tItem['label'] }}
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Isi Artikel / Materi Advisori -->
                    <div class="space-y-1">
                        <label for="article-content" class="text-xs font-bold uppercase tracking-wider text-app-text">
                            Isi Berita / Materi Advisori Keamanan Siber <span class="text-status-danger">*</span>
                        </label>
                        <textarea
                            id="article-content"
                            rows="7"
                            wire:model="content"
                            placeholder="Tuliskan isi berita kegiatan, buletin peringatan dini, analisis teknis, atau panduan mitigasi selengkapnya..."
                            class="w-full rounded-lg border border-app-border bg-app-surface px-3 py-2 text-xs sm:text-sm text-app-text placeholder:text-app-text-muted focus:border-primary focus:outline-hidden"
                        ></textarea>
                        <p class="text-[11px] text-app-text-muted">Gunakan baris baru antar-paragraf untuk format bacaan yang nyaman.</p>
                        @error('content')
                            <p class="text-xs font-semibold text-status-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Modal Actions -->
                    <div class="pt-4 border-t border-app-border flex items-center justify-end gap-3">
                        <button
                            type="button"
                            wire:click="cancelForm"
                            class="rounded-xl border border-app-border bg-app-surface px-4 py-2 text-xs font-bold text-app-text hover:bg-app-surface-muted transition-colors cursor-pointer"
                        >
                            Batal
                        </button>

                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-2 text-xs font-bold text-white shadow-xs hover:bg-primary-dark transition-colors cursor-pointer"
                        >
                            <span wire:loading.remove>{{ $editingId ? 'Simpan Perubahan' : 'Terbitkan Publikasi' }}</span>
                            <span wire:loading>Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Pemilih Pustaka Media (Media Picker) -->
    @if ($showMediaPicker)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-xs overflow-y-auto"
            role="dialog"
            aria-modal="true"
            aria-labelledby="media-picker-title"
        >
            <div class="relative w-full max-w-3xl my-8 rounded-2xl border border-app-border bg-app-surface shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
                <div class="flex items-center justify-between border-b border-app-border px-5 py-3.5 bg-app-surface-muted">
                    <div>
                        <h3 id="media-picker-title" class="text-sm font-extrabold text-app-text">
                            Pilih Gambar dari Pustaka Media
                        </h3>
                        <p class="text-[11px] text-app-text-secondary">
                            Klik salah satu media untuk menetapkannya sebagai thumbnail artikel.
                        </p>
                    </div>

                    <button
                        type="button"
                        wire:click="closeMediaPicker"
                        class="rounded-lg p-1 text-app-text-muted hover:text-app-text transition-colors cursor-pointer"
                    >
                        <flux:icon.x-mark class="size-5" />
                    </button>
                </div>

                <!-- Input Pencarian Media -->
                <div class="p-3 border-b border-app-border bg-app-surface">
                    <input
                        type="search"
                        wire:model.live.debounce.200ms="mediaPickerSearch"
                        placeholder="Cari media di pustaka..."
                        class="w-full rounded-lg border border-app-border bg-app-surface-muted px-3 py-1.5 text-xs text-app-text focus:border-primary focus:outline-hidden"
                    />
                </div>

                <!-- Galeri Media Picker -->
                <div class="flex-1 overflow-y-auto p-4 grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @forelse ($this->libraryMediaItems as $m)
                        <button
                            type="button"
                            wire:click="selectMediaFromLibrary('{{ $m['path'] }}')"
                            class="group relative flex flex-col overflow-hidden rounded-xl border border-app-border bg-app-surface hover:border-primary text-start transition-all cursor-pointer"
                        >
                            <div class="relative aspect-16/10 w-full overflow-hidden bg-app-surface-muted">
                                <img
                                    src="{{ $m['url'] }}"
                                    alt="{{ $m['name'] }}"
                                    class="h-full w-full object-cover transition-transform group-hover:scale-105"
                                />
                            </div>
                            <div class="p-2 space-y-0.5">
                                <p class="text-[11px] font-bold text-app-text truncate">{{ $m['name'] }}</p>
                                <p class="text-[10px] text-app-text-muted truncate">{{ $m['size'] }}</p>
                            </div>
                        </button>
                    @empty
                        <div class="col-span-full py-8 text-center text-xs text-app-text-muted">
                            Tidak ada berkas media yang cocok.
                        </div>
                    @endforelse
                </div>

                <div class="p-3 border-t border-app-border flex justify-end bg-app-surface-muted">
                    <button
                        type="button"
                        wire:click="closeMediaPicker"
                        class="rounded-lg border border-app-border bg-app-surface px-3 py-1.5 text-xs font-semibold text-app-text"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Konfirmasi Hapus Artikel -->
    @if ($pendingDeleteId !== null)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
            role="alertdialog"
            aria-modal="true"
            aria-labelledby="delete-modal-title"
        >
            <div class="relative w-full max-w-md rounded-2xl border border-app-border bg-app-surface p-6 shadow-2xl space-y-4">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-full bg-status-danger-soft text-status-danger shrink-0">
                        <flux:icon.trash class="size-5" />
                    </div>
                    <div>
                        <h2 id="delete-modal-title" class="text-base font-extrabold text-app-text">
                            Hapus Publikasi Berita?
                        </h2>
                        <p class="text-xs text-app-text-muted">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                </div>

                <p class="text-xs sm:text-sm text-app-text-secondary">
                    Apakah Anda yakin ingin menghapus artikel <span class="font-bold text-app-text">"{{ $pendingDeleteTitle }}"</span> dari sistem PKU-CSIRT?
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
                        Ya, Hapus Sekarang
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Tabel Daftar Publikasi Berita -->
    <div class="rounded-2xl border border-app-border bg-app-surface overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="border-b border-app-border bg-app-surface-muted text-[11px] font-bold uppercase tracking-wider text-app-text-muted">
                    <tr>
                        <th class="py-3 px-4">Artikel &amp; Thumbnail</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Tanggal Terbit</th>
                        <th class="py-3 px-4 text-center">Status Editorial</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-app-border">
                    @forelse ($this->articles as $article)
                        @php
                            $isPublished = in_array((string) ($article['status'] ?? 'published'), ['published', 'selesai'], true);
                        @endphp
                        <tr class="hover:bg-app-surface-muted/50 transition-colors">
                            <td class="py-3.5 px-4 min-w-[280px]">
                                <div class="flex items-start gap-3">
                                    <div class="relative size-14 shrink-0 overflow-hidden rounded-lg border border-app-border bg-app-surface-muted">
                                        <img
                                            src="{{ asset(ltrim((string) $article['thumbnail'], '/')) }}"
                                            alt="{{ $article['title'] }}"
                                            class="h-full w-full object-cover"
                                            loading="lazy"
                                        />
                                    </div>
                                    <div class="space-y-1 min-w-0">
                                        <a
                                            href="{{ route('public.article-detail', ['id' => $article['id']]) }}"
                                            wire:navigate
                                            class="font-bold text-app-text hover:text-primary transition-colors line-clamp-1"
                                        >
                                            {{ $article['title'] }}
                                        </a>
                                        <p class="text-[11px] text-app-text-secondary line-clamp-2">
                                            {{ $article['excerpt'] ?? Str::limit(strip_tags((string) ($article['content'] ?? '')), 120) }}
                                        </p>
                                        <p class="text-[10px] text-app-text-muted">
                                            Oleh: {{ $article['author'] ?? 'Diskominfotiksan Pekanbaru' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-md bg-primary-soft px-2.5 py-1 text-[11px] font-bold text-primary-content">
                                    {{ $article['category'] }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 whitespace-nowrap text-xs text-app-text-secondary">
                                {{ \Illuminate\Support\Carbon::parse($article['published_at'])->translatedFormat('d F Y') }}
                            </td>

                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if ($isPublished)
                                    <span class="inline-flex rounded-full border border-emerald-500/30 bg-emerald-500/10 px-2.5 py-0.5 text-[10px] font-extrabold uppercase text-emerald-700 dark:text-emerald-300">
                                        Diterbitkan
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full border border-zinc-500/30 bg-zinc-500/10 px-2.5 py-0.5 text-[10px] font-extrabold uppercase text-zinc-600 dark:text-zinc-400">
                                        Draf
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a
                                        href="{{ route('public.article-detail', ['id' => $article['id']]) }}"
                                        wire:navigate
                                        title="Pratinjau di Portal Publik"
                                        class="rounded-lg p-1.5 text-app-text-muted hover:bg-app-surface-muted hover:text-primary transition-colors"
                                    >
                                        <flux:icon.arrow-top-right-on-square class="size-4" />
                                    </a>

                                    <button
                                        type="button"
                                        wire:click="startEdit({{ $article['id'] }})"
                                        title="Edit Artikel"
                                        class="rounded-lg p-1.5 text-app-text-muted hover:bg-app-surface-muted hover:text-app-text transition-colors cursor-pointer"
                                    >
                                        <flux:icon.pencil-square class="size-4" />
                                    </button>

                                    <button
                                        type="button"
                                        wire:click="confirmDelete({{ $article['id'] }}, '{{ addslashes((string) $article['title']) }}')"
                                        title="Hapus Artikel"
                                        class="rounded-lg p-1.5 text-app-text-muted hover:bg-status-danger-soft hover:text-status-danger transition-colors cursor-pointer"
                                    >
                                        <flux:icon.trash class="size-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="mx-auto max-w-sm space-y-2">
                                    <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-app-surface-muted text-app-text-muted">
                                        <flux:icon.newspaper class="size-6" />
                                    </div>
                                    <p class="text-sm font-bold text-app-text">Tidak ada artikel yang cocok</p>
                                    <p class="text-xs text-app-text-secondary">
                                        Coba sesuaikan kata kunci pencarian atau ubah filter kategori/status di atas.
                                    </p>
                                    <button
                                        type="button"
                                        wire:click="startCreate"
                                        class="mt-2 inline-flex items-center gap-1.5 rounded-lg bg-primary px-3 py-1.5 text-xs font-bold text-white shadow-xs"
                                    >
                                        <flux:icon.plus class="size-3.5" />
                                        <span>Buat Artikel Baru</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
