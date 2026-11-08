<div>
    @php
        $profile = $this->profile;
        $sections = $this->sections;
        $pdfUrl = asset('docs/RFC-2350 - PKU-CSIRT v.2.pdf');
    @endphp

    <!-- Header Banner RFC-2350 -->
    <section class="relative overflow-hidden bg-linear-to-br from-[#072c22] via-[#0e4f3d] to-[#157a5f] text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                <div class="max-w-3xl space-y-4">
                    <div class="inline-flex items-center gap-2 rounded-full border border-amber-400/40 bg-emerald-950/60 px-3.5 py-1 text-xs font-semibold text-amber-300">
                        <flux:icon.document-check class="size-4 text-amber-400" />
                        <span>Standar Internasional IETF RFC-2350 • Versi 2.0</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white">
                        Dokumen Kepatuhan <span class="text-amber-300">RFC-2350</span> PKU-CSIRT
                    </h1>

                    <p class="text-sm sm:text-base text-emerald-100/90 leading-relaxed">
                        Dokumen ini mendeskripsikan profil resmi, otoritas, konstituen, saluran komunikasi, kebijakan klasifikasi insiden, serta standar layanan Computer Security Incident Response Team (CSIRT) Pemerintah Kota Pekanbaru sesuai kerangka kerja IETF RFC-2350.
                    </p>

                    <div class="inline-flex flex-wrap items-center gap-2 rounded-xl border border-amber-300/30 bg-emerald-950/55 px-3.5 py-2 text-xs text-emerald-100">
                        <span class="font-semibold text-amber-300">Poin 1.3 Lokasi Dokumen:</span>
                        <a
                            href="{{ route('public.rfc2350.pdf') }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="font-mono font-bold text-white underline decoration-amber-400 hover:text-amber-300 transition-colors"
                        >
                            https://CSIRT.pekanbaru.go.id/rfc2350.pdf
                        </a>
                    </div>
                </div>

                <!-- Tombol Aksi Dokumen -->
                <div class="flex flex-col sm:flex-row lg:flex-col gap-3 shrink-0">
                    <a
                        href="{{ $pdfUrl }}"
                        download="RFC-2350-PKU-CSIRT-v2.pdf"
                        class="inline-flex items-center justify-center gap-2.5 rounded-xl bg-amber-400 px-5 py-3 text-xs sm:text-sm font-extrabold text-emerald-950 shadow-md hover:bg-amber-300 transition-colors cursor-pointer"
                    >
                        <flux:icon.arrow-down-tray class="size-5" />
                        <span>Unduh Dokumen PDF Resmi</span>
                    </a>

                    <a
                        href="{{ $pdfUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-amber-300/50 bg-emerald-900/70 px-5 py-3 text-xs sm:text-sm font-bold text-white hover:bg-emerald-800 transition-colors cursor-pointer"
                    >
                        <flux:icon.arrow-top-right-on-square class="size-4 text-amber-300" />
                        <span>Buka PDF di Tab Baru</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 space-y-12">
        <!-- Ringkasan Terstruktur Pasal 1 s.d. Pasal 6 RFC-2350 -->
        <div class="space-y-6">
            <div class="space-y-1">
                <h2 class="text-xl sm:text-2xl font-extrabold text-app-text">
                    Struktur Pokok Dokumen RFC-2350 PKU-CSIRT
                </h2>
                <p class="text-xs sm:text-sm text-app-text-secondary">
                    Ikhtisar cepat setiap bab dalam dokumen RFC-2350 untuk memudahkan verifikasi mandat, kontak darurat, dan cakupan konstituen.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($sections as $idx => $item)
                    <article
                        wire:key="rfc-section-{{ $idx }}"
                        class="rounded-2xl border border-app-border bg-app-surface p-6 shadow-2xs hover:border-primary/50 transition-colors flex flex-col justify-between gap-4"
                    >
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="rounded-md bg-primary-soft px-2.5 py-1 text-xs font-extrabold text-primary-content">
                                    {{ $item['section'] }}
                                </span>
                                <flux:icon.shield-check class="size-5 text-primary" />
                            </div>

                            <h3 class="text-base font-bold text-app-text">
                                {{ $item['title'] }}
                            </h3>

                            <p class="text-xs text-app-text-secondary leading-relaxed">
                                {{ $item['summary'] }}
                            </p>

                            <ul class="space-y-2 border-t border-app-border pt-3 text-xs text-app-text-secondary">
                                @foreach ($item['details'] as $detail)
                                    <li class="flex items-start gap-2">
                                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-amber-500"></span>
                                        <span>{{ $detail }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        <!-- Penampil Dokumen PDF Interaktif -->
        <div class="rounded-2xl border border-app-border bg-app-surface overflow-hidden shadow-md">
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-app-border bg-app-surface-muted px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-xl bg-primary-soft text-primary">
                        <flux:icon.document-text class="size-5" />
                    </div>
                    <div>
                        <h2 class="text-sm sm:text-base font-extrabold text-app-text">
                            Pratinjau Dokumen Resmi: RFC-2350 - PKU-CSIRT v.2.pdf
                        </h2>
                        <p class="text-xs text-app-text-muted">
                            Dinas Komunikasi, Informatika, Statistik, dan Persandian Kota Pekanbaru
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <a
                        href="{{ asset('docs/Formulir-Aduan-Insiden-Siber-PKU-CSIRT-v.2.docx') }}"
                        download="Formulir-Aduan-Insiden-Siber-PKU-CSIRT-v.2.docx"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-app-border bg-app-surface px-3.5 py-2 text-xs font-semibold text-app-text hover:border-primary/40 transition-colors"
                    >
                        <flux:icon.arrow-down-tray class="size-4 text-primary" />
                        <span>Formulir Aduan (.DOCX)</span>
                    </a>

                    <a
                        href="{{ $pdfUrl }}"
                        download="RFC-2350-PKU-CSIRT-v2.pdf"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-4 py-2 text-xs font-bold text-white hover:bg-primary-dark transition-colors"
                    >
                        <flux:icon.arrow-down-tray class="size-4 text-amber-300" />
                        <span>Unduh PDF</span>
                    </a>
                </div>
            </div>

            <div class="bg-neutral-900">
                <iframe
                    src="{{ $pdfUrl }}"
                    title="Dokumen Resmi RFC-2350 PKU-CSIRT Pemerintah Kota Pekanbaru"
                    class="w-full h-[78vh] min-h-[540px] border-0"
                ></iframe>
            </div>

            <div class="border-t border-app-border bg-app-surface-muted px-6 py-3 flex flex-wrap items-center justify-between gap-2 text-xs text-app-text-secondary">
                <span>Apabila peramban perangkat seluler Anda tidak menampilkan PDF secara langsung, silakan gunakan tombol unduh.</span>
                <a href="{{ $pdfUrl }}" target="_blank" rel="noopener noreferrer" class="font-bold text-primary hover:underline">
                    Buka berkas PDF langsung &rarr;
                </a>
            </div>
        </div>
    </section>
</div>
