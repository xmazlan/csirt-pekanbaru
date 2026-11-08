# CSIRT Pekanbaru

Repositori aplikasi Computer Security Incident Response Team (CSIRT) Pemerintah Kota Pekanbaru, dikembangkan menggunakan Laravel Livewire Starter Kit dengan Flux UI. Repositori ini menyediakan fondasi autentikasi Fortify, manajemen pengaturan akun pengguna (profil, kata sandi, passkey, otentikasi dua faktor), dashboard, dan tata letak shell terpadu yang modern.

## Stack dan Versi Terkunci

- **Backend**: PHP ^8.3, Laravel Framework 13.40.0, Livewire 4.4.8, Livewire Flux 2.21.0, Livewire Blaze 1.0.21, Laravel Fortify 1.41.0.
- **Frontend**: Tailwind CSS 4.3.4 (menggunakan plugin `@tailwindcss/vite`), Vite Plus 1.4.0 (`vite-plus`), dan Alpine.js (bawaan Livewire).
- **Database**: MySQL (dikonfigurasi pada `.env` dengan database `kominfo_csirt_v2`, host `127.0.0.1:3306`).
- **Pengujian & Kualitas Kode**: Pest 5.3.5, Larastan 3.9, Laravel Pint 1.27.

Versi lengkap dan terkunci tersedia pada `composer.lock` dan `package-lock.json`.

## Fitur dan Arsitektur UI Terpadu

Repositori mengadopsi paket standar UI Starter Kit sesuai panduan bersama:
1. **Unified App Shell Layout**:
   - Header sticky terpadu *full-width* dengan slot brand, navigasi, theme toggle tunggal, notifikasi, dan profil pengguna.
   - Rail sidebar desktop responsif (lebar 64px saat collapsed, 256px saat expanded) dengan transisi mulus dan penyimpanan status di `localStorage`.
   - Tooltip Popover API native untuk rail sidebar yang bebas terpotong clipping (`overflow: hidden`).
   - Drawer mobile dengan *backdrop blur*, *focus trap*, dan penguncian scroll body.
   - Script inisialisasi di `resources/views/partials/head.blade.php` untuk mencegah *Cumulative Layout Shift* (CLS) saat memuat status sidebar.
2. **Animated Theme Toggle**:
   - Transisi ekspansi sirkular (*circular clip-path expansion*) memanfaatkan View Transitions API dengan fallback instan pada browser tanpa dukungan.
   - Sinkronisasi instan lintas tab (*cross-tab storage sync*).
   - *Navigation Guard* anti-flicker pada transisi navigasi SPA Livewire (`livewire:navigating` dan `livewire:navigated`).
   - Tepat satu instance pemilih tema per tampilan aktif (ditempatkan konsisten di header).
3. **Standarisasi Halaman Galat (Error Pages)**:
   - Layout terpadu `resources/views/layouts/error.blade.php` dengan kartu galat terpusat `resources/views/components/error-page.blade.php`.
   - Mengakomodasi kode status HTTP: `401`, `403`, `404`, `419`, `429`, `500`, dan `503`.
4. **Stacked Toast Notifications**:
   - Grup toast tunggal global terisolasi dengan direktif `@persist('toast')` di layout utama dan layout galat.
   - Kompatibel penuh dengan pemanggilan feedback server-side `Flux::toast()`.
5. **Prinsip UI/UX & Konfirmasi Modal**:
   - Menghindari dialog browser native (`alert()`, `confirm()`, `wire:confirm`) dan menggantikannya dengan modal konfirmasi interaktif Flux UI.
   - Penempatan pesan galat dekat kontrol input terkait.

## Fitur Layanan Utama CSIRT Pekanbaru (Aktif)

Selain fondasi autentikasi dan shell UI terpadu, aplikasi menyediakan portal publik beridentitas Kota Pekanbaru ("Kota Bertuah" — Bumi Lancang Kuning, dengan palet Hijau Zamrud Melayu `#157a5f`, aksen Kuning Emas Lancang Kuning, dan ornamen geometris Pucuk Rebung) beserta modul manajemen internal:

1. **Portal Beranda Publik (`/`, komponen `public::home`)**:
   - Hero dengan semboyan Melayu *"Adat dijunjung, marwah terpelihara, siber terjaga, Pekanbaru berjaya"* dan slider interaktif dokumentasi giat Sandiman/CSIRT yang menghormati `prefers-reduced-motion`.
   - Katalog Layanan Reaktif, Proaktif, dan Peningkatan Kapasitas beserta target SLA.
   - Publikasi Berita & Advisori Keamanan Siber dengan filter kategori interaktif Livewire.
   - Informasi Sekretariat Diskominfotiksan Kota Pekanbaru (Kompleks Perkantoran Tenayan Raya) beserta peta tersemat.
2. **Melaporkan Insiden & Kerentanan Siber (`/public/lapor-insiden`, komponen `public::incident-report`)**:
   - Formulir registrasi tiket insiden dan *Responsible Disclosure* untuk ruang lingkup `*.pekanbaru.go.id` dengan penerbitan Nomor Tiket otomatis (`PKU-CSIRT-YYYYMMDD-XXXX`).
   - Integrasi pengiriman pos-el terformat (`mailto:` dan Gmail Web Compose) serta unduhan langsung dokumen resmi `Formulir-Aduan-Insiden-Siber-PKU-CSIRT-v.2.docx` tanpa redirect paksa.
3. **Hall of Fame Security Researcher (`/public/hall-of-fame`, komponen `public::hall-of-fame`)**:
   - Sekapur sirih pantun penghargaan Melayu Pekanbaru dan Papan Peringkat (*Leaderboard*) peneliti keamanan berdasarkan jumlah laporan valid, skor CVSS v3 tertinggi, dan tanggal kontribusi terbaru.
   - Filter interaktif berdasarkan tahun, tingkat keparahan CVSS (`Kritis`, `Tinggi`, `Sedang`), dan pencarian kata kunci.
4. **Kepatuhan Standar IETF RFC-2350 (`/public/rfc2350` & redirect `/RFC-2350`, komponen `public::rfc2350`)**:
   - Ringkasan terstruktur Pasal 1 hingga Pasal 6 standar RFC-2350 PKU-CSIRT v2.0.
   - Penampil dokumen PDF interaktif tersemat dan tombol unduh berkas resmi `RFC-2350 - PKU-CSIRT v.2.pdf`.
5. **Publikasi Berita & Advisori Keamanan Siber (`/public/berita`, `/public/berita/{id}`, redirect `/public/publikasi`)**:
   - Arsip publik berita & buletin siber (`public::articles`) dengan filter kategori, pencarian kata kunci instan, estimasi waktu baca, serta kartu responsif tanpa label/badge progress operasional yang tidak relevan bagi publikasi umum.
   - Halaman detail artikel (`public::article-detail`) dengan banner hero, tipografi terstruktur, kotak peringatan resmi PKU-CSIRT, aksi bagikan interaktif (WhatsApp, Telegram, X, salin tautan dengan umpan balik clipboard), dan rekomendasi artikel terkait.
6. **Manajemen Internal Dashboard (`/dashboard`, `/dashboard/insiden`, `/dashboard/hall-of-fame`, `/dashboard/berita`, `/dashboard/media`)**:
   - Ringkasan metrik operasional terpadu di `/dashboard` (tiket insiden, laporan masuk, peneliti Hall of Fame, publikasi berita, pustaka media, dan RFC-2350).
   - Triase dan pembaruan status tiket laporan insiden (`baru`, `diproses`, `selesai`) di `/dashboard/insiden` (`dashboard::incident-management`).
   - Penambahan dan penghapusan entri Hall of Fame dengan modal konfirmasi di `/dashboard/hall-of-fame` (`dashboard::hof-management`).
   - Manajemen publikasi dan berita kegiatan di `/dashboard/berita` (redirect `/dashboard/publikasi`, komponen `dashboard::article-management`) dengan siklus editorial murni (`published` vs `draft`), pembuatan dan pembaruan artikel, opsi unggah berkas cover mandiri, pemilih aset dari pustaka media, serta modal konfirmasi penghapusan aman.
   - Pustaka Media & Aset Digital di `/dashboard/media` (komponen `dashboard::media-management`) untuk mengunggah, mempratinjau, menyalin path, dan mengelola berkas media lokal mandiri (`storage/app/public/media/`) agar portal tidak bergantung pada tautan URL gambar eksternal yang rentan putus.

## Struktur Direktori Utama

- `config/livewire.php`: Konfigurasi Livewire 4 dengan generator default Multi-File Component (`mfc`, `emoji: false`) serta namespace komponen `dashboard` (`resources/views/dashboard`) dan `public` (`resources/views/public`) di samping `layouts` dan `pages`.
- `app/Services/CsirtDataService.php`: Pengelola data terpusat (*Single Source of Truth*) untuk profil PKU-CSIRT, katalog layanan, artikel advisori, Hall of Fame & leaderboard, pelaporan insiden siber, serta struktur dokumen RFC-2350 (mendukung pembacaan tabel database bila tersedia maupun penyimpanan runtime berbasis Cache).
- `app/Livewire/Settings/`: Komponen class-based Livewire bawaan starter kit untuk pengelolaan akun, profil, tema tampilan, dan keamanan pengguna.
- `app/Actions/Fortify/`: Aksi autentikasi, registrasi, dan reset kata sandi pengguna.
- `resources/css/app.css`: Variabel warna semantik tema (`--color-app-*`, `--color-primary*`, `--color-status-*`), aturan rail desktop, dan gaya tooltip Popover.
- `resources/js/`:
  - `resources/js/theme-toggle.js`: Controller Alpine `themeToggle` (View Transitions clip-path, cross-tab sync).
  - `resources/js/ui/layout-controller.js`: Controller Alpine `appLayout` (rail collapse toggle, Popover API tooltips, mobile drawer).
  - `resources/js/ui/navigation-transitions.js`: Guard anti-flicker transisi navigasi Livewire.
  - `resources/js/app.js`: Inisialisasi dan pendaftaran ekstensi UI Alpine.
- `resources/views/`: Layout aplikasi, tampilan autentikasi Fortify, dan komponen antarmuka:
  - `resources/views/layouts/app.blade.php`: Shell layout utama dashboard dengan header full-width, rail desktop, mobile drawer, dan persistent toast.
  - `resources/views/layouts/public.blade.php`: Layout portal publik PKU-CSIRT beridentitas Kota Pekanbaru dengan pita informasi resmi, navigasi responsif, theme switcher tunggal, ornamen Pucuk Rebung, dan persistent toast.
  - `resources/views/layouts/auth/`: Layout halaman autentikasi (login, register, two-factor).
  - `resources/views/layouts/error.blade.php`: Layout terpadu halaman galat sistem/HTTP.
  - `resources/views/public/`: Komponen Multi-File Livewire halaman publik (`home`, `incident-report`, `hall-of-fame`, `rfc2350`, `articles`, `article-detail`).
  - `resources/views/dashboard/`: Komponen Multi-File Livewire halaman manajemen internal (`incident-management`, `hof-management`, `article-management`, `media-management`).
  - `resources/views/components/`: Komponen antarmuka (`app-logo`, `theme-toggle`, `csirt-navigation`, `error-page`).
  - `resources/views/errors/`: Template galat HTTP standar (`401.blade.php` s.d. `503.blade.php`).
  - `resources/views/dashboard.blade.php`: Halaman ringkasan operasional dashboard CSIRT.
- `public/`: Aset statis publik aplikasi:
  - `public/images/brand/`: Logo resmi PKU-CSIRT (`logo-csirt.png`) dan Pemerintah Kota Pekanbaru (`logo-pemko.png`).
  - `public/images/profile-csirt.png`: Foto profil lembaga CSIRT.
  - `public/docs/`: Dokumen resmi kepatuhan RFC-2350 dan formulir aduan insiden siber.
  - `public/sandiman/`: Aset media aktif (hero slider dan thumbnail artikel) untuk kompatibilitas data dan seeder.
  - `public/storage/`: Tautan simbolik (*symbolic link*) ke `storage/app/public/media/` untuk berkas unggahan mandiri via `php artisan storage:link`.
- `routes/`: Definisi rute aplikasi (`web.php`, `settings.php`, `console.php`).
- `docs/`: Dokumentasi pendukung proyek. Lihat [Dokumentasi Proyek](docs/README.md).

## Panduan Pengembangan

### Prasyarat

- PHP 8.3 atau lebih baru dengan ekstensi yang dibutuhkan Laravel (pdo_mysql, mbstring, curl, dll.).
- Node.js (v20+) dan npm.
- Database server MySQL yang sedang berjalan.

### Perintah Rutin

- Menjalankan server pengembangan:
  ```bash
  composer run dev
  ```
  *(Menjalankan server Laravel, antrean worker, dan Vite concurrently)*
- Membangun aset frontend:
  ```bash
  npm run build
  ```
- Pemeriksaan gaya penulisan kode (Pint):
  ```bash
  composer run lint:check
  ```
- Perbaikan otomatis gaya penulisan kode (Pint):
  ```bash
  composer run lint
  ```
- Pemeriksaan tipe statis (Larastan / PHPStan):
  ```bash
  composer run types:check
  ```
- Menjalankan pengujian (Pest):
  ```bash
  composer test
  ```
- Menjalankan pemeriksaan integrasi berkelanjutan penuh (lint, tipe, dan test):
  ```bash
  composer run ci:check
  ```

> [!WARNING]
> Jangan menjalankan skrip `composer setup` pada database yang sudah berisi data riil, karena skrip tersebut memicu perintah `php artisan migrate --force` otomatis.

## Panduan Agent

Aturan bersama untuk coding agent (termasuk prinsip Karpathy, identitas visual, larangan tema satanik/okultisme, rekomendasi URL, penanganan data, dan panduan reusable) tercantum lengkap di [AGENTS.md](AGENTS.md). Berkas pengarah `CODEX.md`, `CLAUDE.md`, dan `GEMINI.md` merujuk ke dokumen tersebut.
