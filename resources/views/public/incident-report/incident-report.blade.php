<div>
    @php
        $profile = $this->profile;
    @endphp

    <!-- Header Banner Halaman Melaporkan Insiden -->
    <section class="relative overflow-hidden bg-linear-to-br from-[#072c22] via-[#0e4f3d] to-[#157a5f] text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 lg:py-14">
            <div class="max-w-3xl space-y-4">
                <div class="inline-flex items-center gap-2 rounded-full border border-amber-400/40 bg-emerald-950/60 px-3.5 py-1 text-xs font-semibold text-amber-300">
                    <flux:icon.shield-exclamation class="size-4 text-amber-400" />
                    <span>Kanal Resmi Pelaporan Insiden & Celah Keamanan (TLP:AMBER)</span>
                </div>

                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white">
                    Melaporkan Insiden Siber &amp; Kerentanan
                </h1>

                <p class="text-sm sm:text-base text-emerald-100/90 leading-relaxed">
                    Laporkan indikasi serangan siber, penyalahgunaan aset digital, atau temuan celah keamanan pada ruang lingkup <code class="rounded bg-emerald-950/80 px-1.5 py-0.5 text-amber-300 font-mono text-xs">*.pekanbaru.go.id</code>. Setiap laporan yang valid akan mendapatkan nomor tiket resmi dan apresiasi pada Hall of Fame PKU-CSIRT.
                </p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Kolom Kiri (Utama): Formulir Pelaporan Interaktif atau Bukti Tiket -->
            <div class="lg:col-span-8">
                @if ($submittedTicket !== null)
                    @php
                        $subject = rawurlencode('[' . $submittedTicket['ticket_number'] . '] Laporan ' . $submittedTicket['category'] . ' - PKU-CSIRT');
                        $bodyText = "Yth. Tim Tanggap Insiden Siber (PKU-CSIRT) Kota Pekanbaru,\n\n"
                            . "Berikut rincian tiket laporan keamanan siber yang telah saya registrasikan melalui portal resmi:\n\n"
                            . "- Nomor Tiket: " . $submittedTicket['ticket_number'] . "\n"
                            . "- Waktu Registrasi: " . $submittedTicket['created_at'] . " WIB\n"
                            . "- Nama Pelapor: " . $submittedTicket['reporter_name'] . " (" . $submittedTicket['reporter_org'] . ")\n"
                            . "- Kategori: " . $submittedTicket['category'] . "\n"
                            . "- Sistem/URL Terdampak: " . $submittedTicket['affected_system'] . "\n"
                            . "- Tingkat Keparahan: " . $submittedTicket['severity'] . "\n\n"
                            . "Ringkasan Teknis / Kronologi:\n" . $submittedTicket['description'] . "\n\n"
                            . "(Silakan lampirkan tangkapan layar, log, atau dokumen Formulir-Aduan-Insiden-Siber-PKU-CSIRT-v.2.docx pada email ini bila diperlukan).\n\n"
                            . "Hormat saya,\n" . $submittedTicket['reporter_name'];
                        $bodyEncoded = rawurlencode($bodyText);
                        $mailtoHref = 'mailto:' . $profile['email'] . '?subject=' . $subject . '&body=' . $bodyEncoded;
                        $gmailHref = 'https://mail.google.com/mail/?view=cm&fs=1&to=' . rawurlencode((string) $profile['email']) . '&su=' . $subject . '&body=' . $bodyEncoded;
                    @endphp

                    <div class="rounded-2xl border-2 border-success bg-app-surface p-6 sm:p-8 shadow-md space-y-6">
                        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-app-border pb-5">
                            <div class="flex items-center gap-3">
                                <div class="flex size-12 items-center justify-center rounded-xl bg-success-soft text-success">
                                    <flux:icon.check-badge class="size-7" />
                                </div>
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-wider text-success">
                                        Laporan Berhasil Diregistrasi
                                    </span>
                                    <h2 class="text-xl font-extrabold text-app-text">
                                        Tiket #{{ $submittedTicket['ticket_number'] }}
                                    </h2>
                                </div>
                            </div>

                            <span class="rounded-full bg-amber-500/15 px-3 py-1 text-xs font-bold text-amber-700 dark:text-amber-300 border border-amber-500/30">
                                Status: Menunggu Triase Awal
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 rounded-xl bg-app-surface-muted p-4 text-xs sm:text-sm">
                            <div>
                                <p class="text-xs text-app-text-muted">Pelapor / Peneliti</p>
                                <p class="font-bold text-app-text">{{ $submittedTicket['reporter_name'] }} ({{ $submittedTicket['reporter_org'] }})</p>
                            </div>
                            <div>
                                <p class="text-xs text-app-text-muted">Pos-el Koordinasi</p>
                                <p class="font-bold text-app-text">{{ $submittedTicket['reporter_email'] }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-app-text-muted">Kategori Laporan</p>
                                <p class="font-bold text-app-text">{{ $submittedTicket['category'] }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-app-text-muted">Sistem / Subdomain Terdampak</p>
                                <p class="font-mono font-bold text-primary break-all">{{ $submittedTicket['affected_system'] }}</p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <p class="text-xs font-bold uppercase tracking-wider text-app-text-muted">
                                Langkah Lanjutan (Opsional untuk Lampiran Bukti/PoC)
                            </p>
                            <p class="text-xs sm:text-sm text-app-text-secondary leading-relaxed">
                                Laporan Anda telah tercatat pada sistem antrean triase PKU-CSIRT. Apabila Anda memiliki lampiran bukti berupa tangkapan layar, video PoC, atau dokumen <code class="font-mono text-xs">.docx</code> yang telah diisi, silakan kirimkan menggunakan salah satu tombol di bawah ini (subjek dan nomor tiket sudah terisi otomatis):
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <a
                                href="{{ $mailtoHref }}"
                                class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-xs font-bold text-white hover:bg-primary-dark transition-colors cursor-pointer"
                            >
                                <flux:icon.envelope class="size-4 text-amber-300" />
                                <span>Kirim via Aplikasi Email</span>
                            </a>

                            <a
                                href="{{ $gmailHref }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-2 rounded-xl border border-app-border bg-app-surface-muted px-4 py-2.5 text-xs font-bold text-app-text hover:border-primary/50 transition-colors cursor-pointer"
                            >
                                <flux:icon.paper-airplane class="size-4 text-primary" />
                                <span>Buka di Gmail Web</span>
                            </a>

                            <a
                                href="{{ asset('docs/Formulir-Aduan-Insiden-Siber-PKU-CSIRT-v.2.docx') }}"
                                download="Formulir-Aduan-Insiden-Siber-PKU-CSIRT-v.2.docx"
                                class="inline-flex items-center gap-2 rounded-xl border border-app-border bg-app-surface px-4 py-2.5 text-xs font-semibold text-app-text-secondary hover:text-app-text transition-colors cursor-pointer"
                            >
                                <flux:icon.arrow-down-tray class="size-4 text-primary" />
                                <span>Unduh Formulir .DOCX</span>
                            </a>

                            <button
                                type="button"
                                wire:click="resetForm"
                                class="ms-auto inline-flex items-center gap-1.5 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-app-text-muted hover:text-app-text transition-colors cursor-pointer"
                            >
                                <flux:icon.plus class="size-4" />
                                <span>Buat Laporan Baru</span>
                            </button>
                        </div>
                    </div>
                @else
                    <div class="rounded-2xl border border-app-border bg-app-surface p-6 sm:p-8 shadow-xs">
                        <div class="border-b border-app-border pb-5 mb-6 space-y-1">
                            <h2 class="text-xl font-extrabold text-app-text">
                                Formulir Registrasi Tiket Insiden & Kerentanan
                            </h2>
                            <p class="text-xs sm:text-sm text-app-text-secondary">
                                Lengkapi rincian teknis di bawah ini untuk mendapatkan Nomor Tiket Resmi PKU-CSIRT secara instan.
                            </p>
                        </div>

                        <form wire:submit="submitReport" class="space-y-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <flux:field>
                                    <flux:label>Nama Pelapor / Handle Peneliti *</flux:label>
                                    <flux:input
                                        wire:model="reporter_name"
                                        placeholder="Contoh: Aditya Kurniawan"
                                        required
                                    />
                                    <flux:error name="reporter_name" />
                                </flux:field>

                                <flux:field>
                                    <flux:label>Alamat Pos-el Aktif *</flux:label>
                                    <flux:input
                                        type="email"
                                        wire:model="reporter_email"
                                        placeholder="nama@domain.id"
                                        required
                                    />
                                    <flux:error name="reporter_email" />
                                </flux:field>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <flux:field>
                                    <flux:label>Instansi / Afiliasi Pelapor</flux:label>
                                    <flux:input
                                        wire:model="reporter_org"
                                        placeholder="OPD Pemko Pekanbaru / Peneliti Independen"
                                    />
                                    <flux:error name="reporter_org" />
                                </flux:field>

                                <flux:field>
                                    <flux:label>Kategori Laporan *</flux:label>
                                    <select
                                        wire:model="category"
                                        aria-label="Kategori Laporan"
                                        class="w-full rounded-lg border border-app-border bg-app-surface px-3.5 py-2 text-sm text-app-text focus:border-primary focus:outline-hidden focus:ring-2 focus:ring-primary/20"
                                    >
                                        <option value="Kerentanan Aplikasi (Responsible Disclosure)">Kerentanan Aplikasi (Responsible Disclosure)</option>
                                        <option value="Web Defacement / Perubahan Tampilan">Web Defacement / Perubahan Tampilan</option>
                                        <option value="Phishing & Social Engineering">Phishing & Social Engineering</option>
                                        <option value="Malware / Ransomware / Webshell">Malware / Ransomware / Webshell</option>
                                        <option value="Indikasi Kebocoran Data (Data Breach)">Indikasi Kebocoran Data (Data Breach)</option>
                                        <option value="Anomali Trafik / DDoS">Anomali Trafik / DDoS</option>
                                    </select>
                                    <flux:error name="category" />
                                </flux:field>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                                <div class="sm:col-span-2">
                                    <flux:field>
                                        <flux:label>URL Subdomain / Sistem Terdampak *</flux:label>
                                        <flux:input
                                            wire:model="affected_system"
                                            placeholder="https://layanan.pekanbaru.go.id/..."
                                            required
                                        />
                                        <flux:error name="affected_system" />
                                    </flux:field>
                                </div>

                                <div>
                                    <flux:field>
                                        <flux:label>Tingkat Keparahan *</flux:label>
                                        <select
                                            wire:model="severity"
                                            aria-label="Tingkat Keparahan"
                                            class="w-full rounded-lg border border-app-border bg-app-surface px-3.5 py-2 text-sm text-app-text focus:border-primary focus:outline-hidden focus:ring-2 focus:ring-primary/20"
                                        >
                                            <option value="Kritis">Kritis (CVSS 9.0–10.0)</option>
                                            <option value="Tinggi">Tinggi (CVSS 7.0–8.9)</option>
                                            <option value="Sedang">Sedang (CVSS 4.0–6.9)</option>
                                            <option value="Rendah">Rendah (CVSS 0.1–3.9)</option>
                                        </select>
                                        <flux:error name="severity" />
                                    </flux:field>
                                </div>
                            </div>

                            <flux:field>
                                <flux:label>Kronologi Insiden / Langkah Reproduksi (Proof of Concept) *</flux:label>
                                <flux:textarea
                                    wire:model="description"
                                    rows="5"
                                    placeholder="uraikan waktu temuan, parameter/endpoint yang terdampak, langkah-langkah reproduksi, serta rekomendasi perbaikan..."
                                    required
                                />
                                <flux:error name="description" />
                            </flux:field>

                            <div class="rounded-xl border border-app-border bg-app-surface-muted p-4 space-y-2">
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        wire:model="agreed_disclosure"
                                        class="mt-1 size-4 rounded border-app-border text-primary focus:ring-primary"
                                    />
                                    <span class="text-xs text-app-text-secondary leading-relaxed">
                                        Saya menyatakan bahwa laporan ini disampaikan dengan itikad baik sesuai prinsip <strong>Responsible Disclosure</strong> PKU-CSIRT Kota Pekanbaru, tanpa melakukan perusakan layanan, pengubahan data, maupun publikasi sepihak sebelum celah keamanan selesai dimitigasi.
                                    </span>
                                </label>
                                <flux:error name="agreed_disclosure" />
                            </div>

                            <div class="pt-2 flex flex-wrap items-center justify-between gap-4">
                                <p class="text-xs text-app-text-muted">
                                    * Wajib diisi. Data pelapor dilindungi sesuai kebijakan privasi RFC-2350.
                                </p>

                                <flux:button variant="primary" type="submit" icon="paper-airplane">
                                    Kirim Laporan & Terbitkan Tiket
                                </flux:button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Kolom Kanan: Unduh Dokumen Resmi .DOCX & Panduan Alur Pelaporan -->
            <aside class="lg:col-span-4 space-y-6">
                <!-- Kartu Unduh Formulir Resmi .DOCX -->
                <div class="rounded-2xl border-2 border-amber-400/60 bg-app-surface p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-amber-500/15 text-amber-600 dark:text-amber-400">
                            <flux:icon.document-arrow-down class="size-6" />
                        </div>
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">
                                Dokumen Standar PKU-CSIRT
                            </span>
                            <h3 class="text-base font-bold text-app-text">
                                Formulir Aduan Resmi (.DOCX)
                            </h3>
                        </div>
                    </div>

                    <p class="text-xs text-app-text-secondary leading-relaxed">
                        Bagi Perangkat Daerah (OPD) atau pelapor yang memerlukan dokumen berita acara aduan resmi dalam format Microsoft Word, unduh template di bawah ini dan kirimkan ke <strong>{{ $profile['email'] }}</strong>.
                    </p>

                    <div class="space-y-2.5 pt-1">
                        <a
                            href="{{ asset('docs/Formulir-Aduan-Insiden-Siber-PKU-CSIRT-v.2.docx') }}"
                            download="Formulir-Aduan-Insiden-Siber-PKU-CSIRT-v.2.docx"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-amber-400 px-4 py-2.5 text-xs font-extrabold text-emerald-950 hover:bg-amber-300 transition-colors cursor-pointer"
                        >
                            <flux:icon.arrow-down-tray class="size-4" />
                            <span>Unduh Formulir Aduan v2 (.DOCX)</span>
                        </a>

                        <a
                            href="{{ $profile['gmail_compose_url'] }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-app-border bg-app-surface-muted px-4 py-2.5 text-xs font-bold text-app-text hover:border-primary/40 transition-colors cursor-pointer"
                        >
                            <flux:icon.envelope class="size-4 text-primary" />
                            <span>Kirim Email Langsung ke CSIRT</span>
                        </a>
                    </div>
                </div>

                <!-- Alur Penanganan Insiden Siber -->
                <div class="rounded-2xl border border-app-border bg-app-surface p-6 shadow-2xs space-y-4">
                    <h3 class="text-sm font-extrabold uppercase tracking-wider text-app-text">
                        Alur Penanganan Laporan (SLA)
                    </h3>

                    <ol class="space-y-4 text-xs">
                        <li class="flex items-start gap-3">
                            <span class="inline-flex size-6 shrink-0 items-center justify-center rounded-full bg-primary text-white font-bold">1</span>
                            <div>
                                <p class="font-bold text-app-text">Registrasi & Penerbitan Tiket</p>
                                <p class="text-app-text-secondary mt-0.5">Pelapor menerima kode tiket unik <code class="font-mono">PKU-CSIRT-YYYYMMDD-XXXX</code> sebagai bukti lapor.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="inline-flex size-6 shrink-0 items-center justify-center rounded-full bg-primary text-white font-bold">2</span>
                            <div>
                                <p class="font-bold text-app-text">Triase & Verifikasi Teknis (&lt; 2 Jam)</p>
                                <p class="text-app-text-secondary mt-0.5">Tim Sandiman & Analis PKU-CSIRT mereproduksi temuan dan menilai tingkat dampak CVSS.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="inline-flex size-6 shrink-0 items-center justify-center rounded-full bg-primary text-white font-bold">3</span>
                            <div>
                                <p class="font-bold text-app-text">Isolasi & Remediasi Bersama OPD</p>
                                <p class="text-app-text-secondary mt-0.5">Penambalan celah keamanan (patching) dan pemulihan layanan pada sistem terdampak.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="inline-flex size-6 shrink-0 items-center justify-center rounded-full bg-amber-400 text-emerald-950 font-extrabold">4</span>
                            <div>
                                <p class="font-bold text-app-text">Verifikasi Akhir & Hall of Fame</p>
                                <p class="text-app-text-secondary mt-0.5">Peneliti keamanan yang melaporkan celah valid diabadikan pada halaman Hall of Fame Kota Pekanbaru.</p>
                            </div>
                        </li>
                    </ol>
                </div>
            </aside>
        </div>
    </section>
</div>
