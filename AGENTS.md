# Aturan Bersama Coding Agent — CSIRT Pekanbaru

## Sumber aturan

`AGENTS.md` adalah satu-satunya sumber otoritatif aturan bersama untuk seluruh coding agent yang bekerja pada repositori CSIRT Pekanbaru. File pengarah agent (`CODEX.md`, `CLAUDE.md`, `GEMINI.md`) hanya merujuk ke dokumen ini tanpa menduplikasi atau menambah aturan bersama.

Instruksi eksplisit pengguna dan hierarki sistem/developer tetap berlaku. Aturan ini diselaraskan pada 6 November 2026 merujuk [Panduan Generate AGENTS.md](/Users/macprom4/Documents/Agents/guides/PANDUAN_GENERATE_AGENTS.md). Keputusan, persetujuan, dan fakta runtime proyek lain tidak berlaku otomatis di sini.

Gunakan bahasa Indonesia yang jelas, ringkas, dan praktis; panggil pengguna Mazlan secara wajar. Jelaskan hasil, manfaat, verifikasi, dan hambatan secara transparan. Kerjakan instruksi implementasi yang jelas sampai tuntas dalam batas izin yang diberikan. Tanyakan hanya ambiguitas berdampak besar atau izin yang belum tersedia; pilihan teknis rutin tidak memerlukan izin baru.

## Referensi skill bersama

Sumber skill bersama berada di `/Users/macprom4/Documents/Agents/skills/`. Baca file yang relevan sebelum bekerja:

- [karpathy-guidelines](/Users/macprom4/Documents/Agents/skills/karpathy-guidelines/SKILL.md): wajib sebelum menulis, meninjau, atau refactor kode. Periksa asumsi, utamakan solusi sederhana, batasi perubahan hanya pada bagian yang diperlukan (surgical), dan tentukan kriteria keberhasilan yang dapat diverifikasi.
- [ui-ux-pro-max](/Users/macprom4/Documents/Agents/skills/ui-ux-pro-max/SKILL.md): untuk perancangan UI/UX, tata letak, palet warna, aksesibilitas, dan interaksi. Stack serta preferensi proyek mengungguli katalog umum.
- [dark-mode-flicker](/Users/macprom4/Documents/Agents/skills/dark-mode-flicker/SKILL.md): untuk diagnosis kedipan tema (light/dark flash) atau anomali transisi navigasi. Reproduksi gejala aktual sebelum menerapkan solusi.

Gunakan rujukan langsung ke sumber bersama tersebut; jangan membuat salinan skill per proyek. Rujukan path lokal ini perlu disesuaikan bila proyek dikloning ke mesin lain. Bila file sumber tidak ditemukan, laporkan hambatan tersebut dan jangan mengklaim telah membacanya. Mengubah skill bersama memengaruhi proyek lain dan bukan bagian dari pekerjaan rutin repositori ini.

## Panduan adopsi reusable

Untuk membuat atau meregenerasi aturan agent di proyek ini atau proyek lain, gunakan panduan bersama di [Panduan Generate AGENTS.md](/Users/macprom4/Documents/Agents/guides/PANDUAN_GENERATE_AGENTS.md). Dokumen tersebut adalah panduan proses adopsi; aturan operasional yang berlaku di CSIRT Pekanbaru tercantum di `AGENTS.md` ini.

## Pemeriksaan sebelum perubahan (Prinsip Karpathy)

1. **Think Before Coding**: Periksa path, struktur direktori, lockfile, konfigurasi, pola kode existing, serta status Git dan diff (termasuk staged dan untracked). Nyatakan asumsi secara eksplisit; jika ada keraguan atau pilihan bercabang yang substantif, tanyakan sebelum menulis kode.
2. **Simplicity First**: Pilih pendekatan paling sederhana yang memecahkan masalah. Hindari fitur spekulatif, abstraksi sekali pakai, atau penambahan pustaka yang tidak diminta.
3. **Surgical Changes**: Sentuh hanya file dan baris yang wajib diubah untuk memenuhi instruksi. Pertahankan gaya penulisan, indentasi, dan komentar existing. Bersihkan hanya kode mati yang dihasilkan oleh perubahan sendiri.
4. **Goal-Driven Execution**: Tetapkan kriteria verifikasi sebelum mengeksekusi langkah teknis. Lakukan validasi mandiri hingga kriteria terpenuhi.

Sampaikan alternatif substantif beserta manfaat, risiko, fakta, dan trade-off sebelum menjalankan pekerjaan yang bergantung pada keputusan tersebut. Jangan menganggap keheningan pengguna sebagai persetujuan. Izin yang telah jelas diberikan tidak perlu diminta ulang.

## Fondasi teknis

Snapshot paket terpasang saat adopsi: Laravel Framework 13.40.0, Livewire 4.4.8, Flux 2.21.0, Blaze 1.0.21, Fortify 1.41.0, Pest 5.3.5, Larastan 3.9. Frontend menggunakan Tailwind CSS 4.3.4 (via `@tailwindcss/vite`) dan Vite Plus 1.4.0. Versi terkunci tersimpan di `composer.lock` dan `package-lock.json`; verifikasi ulang sebelum memakai API fitur tertentu.

- Pertahankan fondasi resmi Laravel Livewire Starter Kit dan Flux UI yang tersedia. Jangan memasukkan DaisyUI, Bootstrap, Spatie Permission, Volt, Jetstream, atau dependensi lain tanpa kebutuhan nyata dan persetujuan.
- Komponen Livewire starter kit existing berformat class-based (`App\Livewire\Settings\...`) dengan view Blade di `resources/views/livewire/settings/` dan view Fortify di `resources/views/livewire/auth/`. Jangan mengonversi komponen existing ke format lain di luar lingkup instruksi.
- Gunakan Artisan generator untuk membuat artefak baru; selalu periksa opsi perintah dengan `php artisan help <command>` dan gunakan nama yang deskriptif.
- Gunakan named routes, validasi input yang ketat, otorisasi pada action, dan penanganan exception yang relevan. Logika bisnis berada di server; Alpine.js hanya untuk interaksi antarmuka lokal bila diperlukan.
- Pada model Eloquent, gunakan `$guarded` untuk model baru dengan menetapkan atribut yang dilindungi secara sadar; pertahankan model existing apa adanya. Mass-assignment protection bukan otorisasi akses.
- Local Image Builder berbasis JavaScript memakai port 3010 atau lebih tinggi.
- Jangan menyimpan kredensial, token, atau informasi sensitif dalam kode sumber, dokumentasi, atau log.

## Rekomendasi struktur URL dan pemisahan lapisan

- **Halaman dashboard & manajemen**: Disarankan menggunakan prefix URL `/dashboard` beserta turunannya (misalnya `/dashboard`, `/dashboard/insiden`, `/dashboard/advisori`).
- **Halaman publik**: Halaman publik utama berada di root `/`. Halaman publik lainnya (seperti profil, layanan, RFC 2350, atau panduan masyarakat) disarankan memakai prefix `/pages` atau `/public` sesuai konteks kebutuhan (misalnya `/pages/profil`, `/pages/layanan`, `/public/rfc2350`).
- **Pemisahan lapisan**: Bedakan secara tegas antara prefix URL, nama route, namespace komponen Livewire, dan letak view Blade. Namespace `dashboard` atau `public` tidak otomatis menghasilkan URL dengan prefix yang sama. Prefix `/public` juga tidak mengekspos direktori filesystem `public/`.
- **Pengamanan**: Gunakan named routes dan middleware autentikasi/otorisasi yang tepat. Prefix `/dashboard` bukan pengamanan akses.
- **Proteksi rute existing**: Periksa rute existing sebelum merancang alur baru. Rute yang sudah aktif (`/`, `dashboard`, `settings/...`) tidak boleh diganti namanya atau dialihkan hanya demi keseragaman rekomendasi.

## Konfigurasi Livewire

Berdasarkan keputusan Mazlan pada 6 November 2026 (status: **Diterapkan dan diverifikasi**), konfigurasi Livewire di `config/livewire.php` ditetapkan sebagai berikut:

- **Status `config/livewire.php`**: Telah dipublikasikan dan aktif.
- **Namespace komponen (`component_namespaces`)**:
  - `layouts`: `resource_path('views/layouts')` (bawaan, digunakan oleh `component_layout => 'layouts::app'`)
  - `pages`: `resource_path('views/pages')` (bawaan Livewire 4)
  - `dashboard`: `resource_path('views/dashboard')` (untuk komponen fitur/halaman manajemen & internal baru)
  - `public`: `resource_path('views/public')` (untuk komponen fitur/halaman publik baru)
- **Generator default (`make_command`)**:
  - `type`: `'mfc'` (Multi-File Component)
  - `emoji`: `false` (tanpa prefix emoji `⚡` agar penamaan direktori dan berkas bersih lintas tool)
  - `with`: `['js' => false, 'css' => false, 'test' => false]` (berkas pendamping hanya dibuat saat diperlukan melalui flag eksplisit seperti `--test` atau `--js`)
- **Komponen existing**: Komponen starter kit di `app/Livewire/Settings/` (beserta view di `resources/views/livewire/settings/` dan `resources/views/livewire/auth/`) tetap dipertahankan dalam format class-based. Jangan mengonversi komponen existing ke MFC di luar instruksi eksplisit.
- **Aturan operasional komponen baru**: Gunakan `php artisan make:livewire dashboard::<nama>` untuk fitur manajemen/dashboard dan `php artisan make:livewire public::<nama>` untuk fitur publik.

## Single Source of Truth

- Tetapkan pemilik tunggal untuk state, aturan bisnis, validasi, konfigurasi, dan token visual. Hindari menduplikasi aturan validasi atau logika kalkulasi di berbagai komponen terpisah.
- Untuk tema (light/dark/system), pertahankan satu pengelola state bawaan Flux/Starter Kit di `resources/views/partials/head.blade.php` (`@fluxAppearance`). Jangan membuat pengelola tema tandingan di `localStorage` atau skrip independen.
- Verifikasi sinkronisasi state tema di seluruh tampilan, termasuk reload, navigasi Livewire, dan perangkat seluler.

## Identitas visual dan aksesibilitas

- Gunakan token warna semantik resmi yang didefinisikan pada Tailwind CSS dan Flux UI. README merangkum peruntukan warna, bukan sebagai sumber duplikasi CSS.
- Pastikan kontras teks terhadap latar belakang memenuhi standar WCAG AA (minimal 4.5:1 untuk teks normal).
- Jaga keterlihatan fokus keyboard (`focus-visible`), label formulir yang mudah diakses pembaca layar, layout yang responsif untuk berbagai ukuran layar, serta dukungan `prefers-reduced-motion` bila menyematkan animasi antarmuka.

## Larangan simbol dan tema satanik

Dilarang keras memakai simbol, tema, atau elemen visual satanik dan okultisme, termasuk mata satu sebagai simbol, mata di dalam segitiga, pentagram, angka 666, atau figur pemujaan setan. Gunakan aset visual dan ikonografi yang netral, profesional, dan relevan dengan identitas CSIRT Pemerintah Kota Pekanbaru.

## Keamanan data dan penanganan dummy

- Driver database pada konfigurasi efektif `.env` adalah **MySQL** (`DB_DATABASE=kominfo_csirt_v2`, host `127.0.0.1:3306`). Jangan mengubah konfigurasi database, berpindah ke SQLite, atau menjalankan migrasi/seeder otomatis tanpa instruksi.
- Dilarang menjalankan operasi data yang bersifat destruktif (`migrate:fresh`, `db:wipe`, `truncate`, dsb.) tanpa izin tertulis, verifikasi target koneksi, dan kepastian cadangan data (backup). Lingkungan pengembangan lokal tetap harus diperlakukan secara hati-hati.
- Penanda dummy sementara: Tandai data atau tampilan sementara dengan komentar `DUMMY:` beserta sumber data pengganti riilnya (gunakan komentar Blade pada file Blade, dan sintaks komentar yang sah pada PHP/JS). Untuk blok komponen, gunakan penutup `END DUMMY:` dengan identitas yang sama. Berikan prefix `dummy` (dengan penyesuaian huruf besar/kecil sesuai konteks keperluan, seperti `Dummy`, `DUMMY-`, `dummy_`, atau `dummy-`) pada nilai dan pengenal data dummy agar mudah dibedakan dari data riil. Hapus data dummy dan penandanya setelah integrasi data riil selesai dan diverifikasi.

## Pemeliharaan dokumentasi proyek

### Pemeliharaan README

Periksa `README.md` pada setiap perubahan material pada proyek. Jika struktur, dependensi, alur kerja, instalasi, atau cara penggunaan berubah, perbarui README dalam rangkaian pekerjaan yang sama. Dokumentasikan kondisi yang benar-benar ada; bedakan fitur yang aktif, yang masih berupa rencana, dan asumsi. `README.md` menautkan `AGENTS.md` untuk aturan operasional agent tanpa menyalin ulang ketentuannya.

### Penempatan dokumentasi di `docs/`

Rujuk [Dokumentasi proyek](docs/README.md). Ketentuan penempatan dokumentasi:

- Seluruh direktori di bawah `docs/` wajib menggunakan nama dalam bahasa Inggris (misalnya `docs/decisions/`, `docs/briefs/`, `docs/architecture/`), termasuk subdirektori bertingkat.
- Semua berkas Markdown tambahan wajib ditempatkan di dalam subdirektori sesuai topiknya. Berkas `docs/README.md` adalah satu-satunya file Markdown yang boleh berada langsung di tingkat root direktori `docs/`.
- Pola nama berkas brief baru direkomendasikan memakai `yymmdd-NN-judul-singkat.md` (tanggal Asia/Jakarta, urutan harian dua digit, kebab-case). Berkas existing tidak perlu diubah namanya.

### Catatan keputusan pengecualian

Catatan di `docs/decisions/YYMMDD-NN-judul-singkat.md` (Asia/Jakarta) dibuat **hanya jika** agent memilih penyimpangan atau pengecualian terhadap keputusan, arahan, atau aturan yang telah ditetapkan Mazlan. Tindakan rutin, implementasi fitur sesuai instruksi, perbaikan bug, atau tugas biasa tidak memerlukan catatan keputusan. Catatan harus memuat aturan asal, alasan, alternatif yang dipertimbangkan, konsekuensi, dasar kewenangan, dan lingkup verifikasi.

## Kewajiban pencatatan reusable global

### Panduan reusable di `~/Documents/Agents/guides`

Ketika agent merancang, menemukan, atau menghasilkan fitur, modul, komponen, pola antarmuka, prosedur diagnosis, atau solusi masalah yang memenuhi kriteria reusable lintas proyek, agent **wajib** membuat atau memperbarui panduan bersama di `/Users/macprom4/Documents/Agents/guides/` dalam rangkaian pekerjaan yang sama.

- Baca kriteria, alur, dan format pada [Panduan Reusable Bersama](/Users/macprom4/Documents/Agents/guides/README.md); jangan menggandakan kriterianya di sini.
- Periksa panduan existing sebelum membuat file baru; perbarui panduan yang sudah ada bila topiknya sejenis.
- Pisahkan logika bisnis spesifik, nama instansi, data privat, dan kredensial.
- Sertakan prasyarat versi, langkah adopsi, penanganan kendala, dan hasil verifikasi yang jujur.
- Perbarui tabel indeks pada `guides/README.md`. Tindakan pencatatan lokal ini sudah diizinkan tanpa memerlukan persetujuan ulang.

### Prompt reusable di `AutonomousCoffer/docs/prompts`

Catat prompt yang memiliki kegunaan berulang, langkah terstruktur, parameter yang dapat disesuaikan, dan cara pemeriksaan hasil ke `/Users/macprom4/Projects/AutonomousCoffer/docs/prompts/`.

- Baca aturan dan indeks di `/Users/macprom4/Projects/AutonomousCoffer/docs/README.md` sebelum menulis.
- Gunakan format penamaan `yymmdd-NN-judul-singkat.md` (Asia/Jakarta).
- Generalisasikan nilai konfigurasi, path, dan identitas menjadi parameter prompt.
- Perbarui indeks pada `AutonomousCoffer/docs/README.md` secara rapi. Pencatatan lokal ini diizinkan tanpa izin tambahan; jangan melakukan commit atau push pada repositori AutonomousCoffer tanpa instruksi eksplisit.

## Verifikasi dan penyerahan hasil

Setiap perubahan teknis wajib diverifikasi secara proporsional sesuai tingkat risikonya:

- **Frontend & antarmuka**: Jalankan `npm run build` untuk memverifikasi kompilasi Vite dan Tailwind. Pemeriksaan build bukan pengganti verifikasi visual di browser.
- **PHP Code Style**: Jalankan `composer run lint:check` (Pint) untuk memastikan tidak ada pelanggaran style pada file yang diubah.
- **Analisis statis**: Jalankan `composer run types:check` (`phpstan analyse`) untuk mendeteksi potensi cacat tipe.
- **Pengujian unit & fitur**: Jalankan tes relevan menggunakan `php artisan test --compact <path-atau-filter>`. Pastikan koneksi database untuk testing aman sebelum menjalankan tes. Jangan menjalankan `composer setup` untuk keperluan verifikasi biasa, karena perintah tersebut memicu migrasi paksa.
- **Pemeriksaan CI penuh**: Jalankan `composer run ci:check` jika diminta atau sebelum rilis material.
- Laporkan secara transparan file yang diubah, hasil pemeriksaan yang benar-benar dijalankan, dan hal-hal yang belum atau tidak dapat diverifikasi secara lokal. Jangan mengklaim status build, test, atau deploy tanpa verifikasi faktual.

## Pengarah agent dan kebijakan Git

- Berkas pengarah `CODEX.md`, `CLAUDE.md`, dan `GEMINI.md` merujuk ke [AGENTS.md](AGENTS.md) dan mewajibkan pembacaan aturan serta penggunaan skill bersama yang relevan.
- Keempat berkas panduan agent (`AGENTS.md`, `CODEX.md`, `CLAUDE.md`, `GEMINI.md`) wajib dapat dilacak oleh Git dan **dilarang dimasukkan ke dalam `.gitignore`**. Entri ignore khusus untuk berkas-berkas ini harus dihapus.
- Status Git saat adopsi ini dicatat secara objektif: repositori lokal belum diinisialisasi Git (`.git` belum dibuat). File yang tidak di-ignore tidak otomatis berstatus tracked, staged, atau committed sampai repositori diinisialisasi dan perintah terkait dijalankan secara sadar.
- Adopsi dokumentasi tidak boleh mengubah area staging Git, tidak melakukan force-add, dan tidak memodifikasi ignore global mesin pengguna.

## Perintah push

Penyebutan kata "push" dalam dokumen panduan atau aturan bukan izin untuk mengirim perubahan ke remote Git. Jika Mazlan secara eksplisit memerintahkan push:

1. Periksa `git status` dan diff secara menyeluruh (termasuk staged dan untracked).
2. Kelompokkan perubahan ke dalam commit yang koheren dan urutkan sesuai dependensinya.
3. Perbarui `CHANGELOG.md` bila ada perubahan fitur atau perbaikan material.
4. Stage hanya file/hunk yang relevan untuk setiap kelompok commit dengan pesan yang jelas.
5. Jalankan push berurutan sesuai konfigurasi remote.
6. Jangan menjalankan rangkaian pengujian sebagai bagian dari script push; verifikasi dilakukan pada fase verifikasi.
7. Jangan memakai force push atau membypass proteksi branch, dan laporkan secara jujur commit serta branch tujuan yang benar-benar berhasil dikirim.

