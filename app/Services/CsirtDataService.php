<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CsirtDataService
{
    private const CACHE_HOF_ENTRIES_KEY = 'csirt.dummy.hof.entries';

    private const CACHE_INCIDENTS_KEY = 'csirt.dummy.incidents.reports';

    private const CACHE_ARTICLES_KEY = 'csirt.dummy.articles.data';

    /**
     * Informasi utama identitas & profil PKU-CSIRT Pemerintah Kota Pekanbaru.
     *
     * @return array<string, mixed>
     */
    public function siteProfile(): array
    {
        $defaults = [
            'brand_title' => 'PKU-CSIRT',
            'brand_subtitle' => 'Pemerintah Kota Pekanbaru',
            'institution' => 'Dinas Komunikasi, Informatika, Statistik, dan Persandian Kota Pekanbaru',
            'city_tagline' => 'Kota Bertuah — Bersih, Tertib, Usaha Bersama, Aman, dan Harmonis',
            'malay_motto' => 'Adat dijunjung, marwah terpelihara, siber terjaga, Pekanbaru berjaya.',
            'hero_title' => 'Computer Security Incident Response Team Kota Pekanbaru',
            'hero_description' => 'Garda terdepan pelindung kedaulatan siber dan infrastruktur informasi vital di lingkungan Pemerintah Kota Pekanbaru. Tanggap menangani insiden, proaktif memitigasi kerentanan, dan kolaboratif mengedukasi masyarakat.',
            'constituency' => 'Seluruh Perangkat Daerah (OPD), Badan Layanan Umum, dan aset digital domain *.pekanbaru.go.id',
            'address' => 'Kompleks Perkantoran Walikota Pekanbaru (Tenayan Raya), Gedung Utama Lt. 3, Jl. Abdul Rahman Hamid, Kel. Tuah Negeri, Kec. Tenayan Raya, Kota Pekanbaru, Riau 28285',
            'email' => 'csirt@pekanbaru.go.id',
            'WorkingHours' => 'Senin – Jumat (08.00 – 16.00 WIB) | Siaga Insiden Kritis 24/7',
            'timezone' => 'WIB (UTC+07:00 / Asia/Jakarta)',
            'homepage' => 'https://www.pekanbaru.go.id',
            'instagram' => 'https://instagram.com/csirt_pekanbaru',
            'youtube' => 'https://youtube.com/@diskominfopekanbaru',
            'maps_embed' => 'https://www.google.com/maps?ll=0.5171,101.540638&z=16&t=m&hl=id&output=embed',
            'rfc_pdf_url' => '/docs/RFC-2350 - PKU-CSIRT v.2.pdf',
            'rfc_canonical_url' => 'https://CSIRT.pekanbaru.go.id/rfc2350.pdf',
            'incident_docx_url' => '/docs/Formulir-Aduan-Insiden-Siber-PKU-CSIRT-v.2.docx',
            'incident_docx_filename' => 'Formulir-Aduan-Insiden-Siber-PKU-CSIRT-v.2.docx',
            'gmail_compose_url' => 'https://mail.google.com/mail/?view=cm&fs=1&to=csirt@pekanbaru.go.id&su=%5BInsiden/Kerentanan%5D%20Laporan%20Keamanan%20Siber%20PKU-CSIRT',
            'hof_pantun' => [
                'Dengan rendah hati, CSIRT Pekanbaru menyanjung setinggi-tingginya jasa para Security Researcher.',
                'Ucapan terima kasih kami persembahkan atas laporan celah keamanan yang ditemukan.',
                'Pada ruang lingkup ekosistem *.pekanbaru.go.id, marwah siber tetap terjaga.',
                'Halaman Hall of Fame ini kami hadirkan sebagai tanda penghargaan dan kenangan.',
                'Jasa tuan dan puan terpatri mulia, memperkuat ketahanan digital Kota Pekanbaru.',
            ],
        ];

        if (Schema::hasTable('situs')) {
            /** @var array<string, mixed> $stored */
            $stored = DB::table('situs')->pluck('config_value', 'config_key')->all();
            if (! empty($stored['kontak_email']) && is_string($stored['kontak_email'])) {
                $defaults['email'] = $stored['kontak_email'];
            }
            if (! empty($stored['beranda_subtext']) && is_string($stored['beranda_subtext'])) {
                $defaults['malay_motto'] = trim(preg_replace('/\s+/', ' ', $stored['beranda_subtext']) ?? $defaults['malay_motto']);
            }
        }

        return $defaults;
    }

    /**
     * Katalog layanan reaktif, proaktif, dan manajemen kualitas keamanan PKU-CSIRT.
     *
     * @return array<int, array<string, mixed>>
     */
    public function servicesCatalog(): array
    {
        return [
            [
                'code' => 'REAKTIF-01',
                'category' => 'Layanan Reaktif',
                'title' => 'Peringatan & Advisori Keamanan (Alerts & Warnings)',
                'description' => 'Penyebaran buletin peringatan dini terkait ancaman siber baru, eksploitasi zero-day, serta rekomendasi patching mendesak kepada pengelola TI OPD Kota Pekanbaru.',
                'icon' => 'bell-alert',
                'sla' => '< 4 Jam setelah terdeteksi',
            ],
            [
                'code' => 'REAKTIF-02',
                'category' => 'Layanan Reaktif',
                'title' => 'Penanganan & Pemulihan Insiden Siber (Incident Handling)',
                'description' => 'Triase, analisis forensik awal, isolasi dampak (containment), eradikasi artefak berbahaya, dan pendampingan pemulihan layanan yang terdampak insiden.',
                'icon' => 'shield-exclamation',
                'sla' => 'Respon Awal < 2 Jam',
            ],
            [
                'code' => 'REAKTIF-03',
                'category' => 'Layanan Reaktif',
                'title' => 'Koordinasi Penanganan Kerentanan (Vulnerability Handling)',
                'description' => 'Penerimaan laporan celah keamanan dari peneliti independen (Bug Bounty / Responsible Disclosure) pada domain *.pekanbaru.go.id serta verifikasi remediasi.',
                'icon' => 'bug-ant',
                'sla' => 'Verifikasi 1–3 Hari Kerja',
            ],
            [
                'code' => 'PROAKTIF-01',
                'category' => 'Layanan Proaktif',
                'title' => 'Asesmen Kerentanan & Pengujian Keamanan (VA / Pentest)',
                'description' => 'Pemeriksaan berkala terhadap postur keamanan aplikasi web dan infrastruktur server milik Pemerintah Kota Pekanbaru sebelum maupun sesudah rilis.',
                'icon' => 'magnifying-glass-circle',
                'sla' => 'Terjadwal Berkala',
            ],
            [
                'code' => 'PROAKTIF-02',
                'category' => 'Layanan Proaktif',
                'title' => 'Analisis Artefak & Intelijen Ancaman Siber',
                'description' => 'Pemeriksaan sampel malware, skrip webshell, indikator kompromi (IoC), dan log lalu lintas anomali untuk mencegah serangan berulang.',
                'icon' => 'cpu-chip',
                'sla' => 'Analisis Mendalam',
            ],
            [
                'code' => 'EDUKASI-01',
                'category' => 'Peningkatan Kapasitas',
                'title' => 'Sosialisasi, Workshop & Literasi Keamanan Informasi',
                'description' => 'Bimbingan teknis bagi Sandiman/Pengelola TI OPD dan edukasi kesadaran keamanan siber (security awareness) bagi ASN serta masyarakat Kota Pekanbaru.',
                'icon' => 'academic-cap',
                'sla' => 'Program Berkelanjutan',
            ],
        ];
    }

    /**
     * Daftar artikel berita dan advisori keamanan siber (mendukung filter kategori, status, pencarian, dan batas jumlah).
     *
     * @return array<int, array<string, mixed>>
     */
    public function articles(?string $category = null, ?string $status = null, ?string $search = null, ?int $limit = null): array
    {
        $articles = $this->rawArticles();

        $filtered = array_values(array_filter($articles, static function (array $article) use ($category, $status, $search): bool {
            if ($category !== null && $category !== '' && mb_strtolower((string) ($article['category'] ?? '')) !== mb_strtolower($category)) {
                return false;
            }

            if ($status !== null && $status !== '') {
                $targetStatus = in_array(mb_strtolower($status), ['published', 'selesai', 'terbit'], true) ? 'published' : 'draft';
                $itemStatus = in_array(mb_strtolower((string) ($article['status'] ?? 'published')), ['published', 'selesai', 'terbit'], true) ? 'published' : 'draft';
                if ($targetStatus !== $itemStatus) {
                    return false;
                }
            }

            if ($search !== null && trim($search) !== '') {
                $keyword = mb_strtolower(trim($search));
                $haystack = mb_strtolower(implode(' ', [
                    (string) ($article['title'] ?? ''),
                    (string) ($article['excerpt'] ?? ''),
                    (string) ($article['content'] ?? ''),
                    (string) ($article['category'] ?? ''),
                    (string) ($article['author'] ?? ''),
                ]));

                if (! str_contains($haystack, $keyword)) {
                    return false;
                }
            }

            return true;
        }));

        if ($limit !== null && $limit > 0) {
            return array_slice($filtered, 0, $limit);
        }

        return $filtered;
    }

    /**
     * Ambil data lengkap satu artikel berita/advisori berdasarkan ID.
     *
     * @return array<string, mixed>|null
     */
    public function findArticle(int $id): ?array
    {
        foreach ($this->rawArticles() as $article) {
            if ((int) ($article['id'] ?? 0) === $id) {
                return $article;
            }
        }

        return null;
    }

    /**
     * Simpan (tambah baru atau perbarui) artikel berita kegiatan / advisori siber.
     *
     * @param  array{
     *     title: string,
     *     content: string,
     *     category: string,
     *     status?: string,
     *     thumbnail?: string,
     *     author?: string,
     *     published_at?: string,
     *     excerpt?: string
     * }  $payload
     * @return array<string, mixed>
     */
    public function saveArticle(array $payload, ?int $id = null): array
    {
        $articles = $this->rawArticles();
        $isEdit = $id !== null;
        $now = now()->toDateTimeString();

        $title = trim($payload['title']);
        $content = trim($payload['content']);
        $category = trim($payload['category']);
        $rawStatus = trim((string) ($payload['status'] ?? 'published'));
        $status = in_array($rawStatus, ['published', 'draft'], true)
            ? $rawStatus
            : (in_array($rawStatus, ['selesai'], true) ? 'published' : 'draft');
        $thumbnail = trim($payload['thumbnail'] ?? '/sandiman/perkuat.jpg');
        $author = trim($payload['author'] ?? 'Diskominfotiksan Pekanbaru');
        $publishedAt = isset($payload['published_at']) && trim($payload['published_at']) !== ''
            ? trim($payload['published_at'])
            : now()->toDateString();
        $excerpt = isset($payload['excerpt']) && trim($payload['excerpt']) !== ''
            ? trim($payload['excerpt'])
            : Str::limit(strip_tags($content), 160);

        if (Schema::hasTable('csirt')) {
            $dbData = [
                'judul' => $title,
                'deskripsi' => $content,
                'kategori' => $category,
                'status' => $status,
                'thumbnail' => ltrim($thumbnail, '/'),
                'updated_at' => $now,
            ];

            if ($isEdit) {
                DB::table('csirt')->where('id', $id)->update($dbData);
                $articleId = $id;
            } else {
                $dbData['created_at'] = $now;
                $dbData['created_by'] = auth()->id();
                $articleId = (int) DB::table('csirt')->insertGetId($dbData);
            }

            return [
                'id' => $articleId,
                'title' => $title,
                'excerpt' => $excerpt,
                'content' => $content,
                'category' => $category,
                'status' => $status,
                'thumbnail' => '/'.ltrim($thumbnail, '/'),
                'author' => $author,
                'published_at' => $publishedAt,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($isEdit) {
            $updatedArticle = null;
            foreach ($articles as &$item) {
                if ((int) ($item['id'] ?? 0) === $id) {
                    $item['title'] = $title;
                    $item['excerpt'] = $excerpt;
                    $item['content'] = $content;
                    $item['category'] = $category;
                    $item['status'] = $status;
                    $item['thumbnail'] = '/'.ltrim($thumbnail, '/');
                    $item['author'] = $author;
                    $item['published_at'] = $publishedAt;
                    $item['updated_at'] = $now;
                    $updatedArticle = $item;
                    break;
                }
            }
            unset($item);
            Cache::forever(self::CACHE_ARTICLES_KEY, $articles);

            return $updatedArticle ?? [];
        }

        $nextId = max(0, ...array_map(static fn (array $a): int => (int) ($a['id'] ?? 0), $articles)) + 1;
        $newArticle = [
            'id' => $nextId,
            'title' => $title,
            'excerpt' => $excerpt,
            'content' => $content,
            'category' => $category,
            'status' => $status,
            'thumbnail' => '/'.ltrim($thumbnail, '/'),
            'author' => $author,
            'published_at' => $publishedAt,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        array_unshift($articles, $newArticle);
        Cache::forever(self::CACHE_ARTICLES_KEY, $articles);

        return $newArticle;
    }

    /**
     * Hapus artikel berita berdasarkan ID.
     */
    public function deleteArticle(int $id): bool
    {
        if (Schema::hasTable('csirt')) {
            return DB::table('csirt')->where('id', $id)->delete() > 0;
        }

        $articles = $this->rawArticles();
        $updated = array_values(array_filter(
            $articles,
            static fn (array $item): bool => (int) ($item['id'] ?? 0) !== $id
        ));

        Cache::forever(self::CACHE_ARTICLES_KEY, $updated);

        return count($updated) < count($articles);
    }

    /**
     * Ambil daftar kategori artikel yang tersedia.
     *
     * @return array<int, string>
     */
    public function articleCategories(): array
    {
        $defaultCategories = [
            'Keamanan Siber',
            'Advisori Insiden',
            'Edukasi & Literasi',
            'Peringatan Publik',
        ];

        $categories = $defaultCategories;

        foreach ($this->rawArticles() as $article) {
            $cat = (string) ($article['category'] ?? '');
            if ($cat !== '' && ! in_array($cat, $categories, true)) {
                $categories[] = $cat;
            }
        }

        return $categories;
    }

    /**
     * Ambil katalog gambar thumbnail resmi yang tersedia di public/sandiman.
     *
     * @return array<int, array{path: string, label: string}>
     */
    public function availableThumbnails(): array
    {
        return [
            ['path' => '/sandiman/perkuat.jpg', 'label' => 'Sistem Keamanan Terpadu'],
            ['path' => '/sandiman/phising.jpg', 'label' => 'Mitigasi Serangan Phishing'],
            ['path' => '/sandiman/workshop.jpg', 'label' => 'Workshop & Edukasi ASN'],
            ['path' => '/sandiman/312e380cd76237cf992eef571542fce4.jpg', 'label' => 'Peringatan Rekayasa Sosial / Bank'],
            ['path' => '/sandiman/hoax.jpg', 'label' => 'Literasi & Anti-Hoax Digital'],
            ['path' => '/sandiman/97c22ffccb2e4bec2a8da0253f10031d.jpg', 'label' => 'Pusat Kendali Operasi Siber (SOC)'],
            ['path' => '/sandiman/f182c704243666d2cb6fc4cb9cb7a2a3.png', 'label' => 'Perisai Infrastruktur Digital'],
            ['path' => '/sandiman/b98dab72429ebf59f08aa2b3bfa647ef.jpg', 'label' => 'Sosialisasi Sandiman & Kriptografi'],
        ];
    }

    /**
     * Ambil seluruh berkas media (unggahan di storage/media + katalog resmi di public/sandiman).
     *
     * @return array<int, array{
     *     id: string,
     *     name: string,
     *     path: string,
     *     url: string,
     *     size: string,
     *     source: string,
     *     mime: string,
     *     created_at: string
     * }>
     */
    public function mediaItems(?string $search = null, ?string $source = null): array
    {
        $items = [];

        // 1. Berkas Unggahan di Storage Publik Lokal (storage/app/public/media)
        if (Storage::disk('public')->exists('media')) {
            $uploadedFiles = Storage::disk('public')->files('media');
            foreach ($uploadedFiles as $file) {
                $filename = basename($file);
                $sizeBytes = Storage::disk('public')->size($file);
                $lastModified = Storage::disk('public')->lastModified($file);

                $items[] = [
                    'id' => 'upload_'.md5($file),
                    'name' => $filename,
                    'path' => '/storage/'.$file,
                    'url' => asset('storage/'.$file),
                    'size' => $this->formatBytes($sizeBytes),
                    'source' => 'upload',
                    'mime' => (string) (Storage::disk('public')->mimeType($file) ?: 'image/jpeg'),
                    'created_at' => Carbon::createFromTimestamp($lastModified)->format('Y-m-d H:i:s'),
                ];
            }
        }

        // 2. Berkas Media Koleksi Resmi di public/sandiman
        $systemDir = public_path('sandiman');
        if (is_dir($systemDir)) {
            $systemCatalogLabels = [
                'perkuat.jpg' => 'Sistem Keamanan Terpadu',
                'phising.jpg' => 'Mitigasi Serangan Phishing',
                'workshop.jpg' => 'Workshop & Edukasi ASN',
                '312e380cd76237cf992eef571542fce4.jpg' => 'Peringatan Rekayasa Sosial / Bank',
                'hoax.jpg' => 'Literasi & Anti-Hoax Digital',
                '97c22ffccb2e4bec2a8da0253f10031d.jpg' => 'Pusat Kendali Operasi Siber (SOC)',
                'f182c704243666d2cb6fc4cb9cb7a2a3.png' => 'Perisai Infrastruktur Digital',
                'b98dab72429ebf59f08aa2b3bfa647ef.jpg' => 'Sosialisasi Sandiman & Kriptografi',
                'pekanbaru.png' => 'Lambang Kota Pekanbaru',
                'Logo-PKU-CSIRT.png' => 'Logo Resmi PKU-CSIRT',
            ];

            $systemFiles = glob($systemDir.'/*.{jpg,jpeg,png,webp,svg}', GLOB_BRACE) ?: [];
            foreach ($systemFiles as $fullPath) {
                $filename = basename($fullPath);
                $label = $systemCatalogLabels[$filename] ?? $filename;
                $sizeBytes = filesize($fullPath) ?: 0;

                $items[] = [
                    'id' => 'system_'.md5($filename),
                    'name' => $label,
                    'path' => '/sandiman/'.$filename,
                    'url' => asset('sandiman/'.$filename),
                    'size' => $this->formatBytes($sizeBytes),
                    'source' => 'system',
                    'mime' => str_ends_with(strtolower($filename), '.png') ? 'image/png' : 'image/jpeg',
                    'created_at' => '2026-06-02 00:00:00',
                ];
            }
        }

        // Urutkan: unggahan terbaru di atas, lalu koleksi sistem
        usort($items, static function (array $a, array $b): int {
            if ($a['source'] !== $b['source']) {
                return $a['source'] === 'upload' ? -1 : 1;
            }

            return strcmp((string) $b['created_at'], (string) $a['created_at']);
        });

        // Filter Source & Search
        return array_values(array_filter($items, static function (array $item) use ($source, $search): bool {
            if ($source !== null && $source !== '' && $item['source'] !== $source) {
                return false;
            }

            if ($search !== null && trim($search) !== '') {
                $keyword = mb_strtolower(trim($search));
                $haystack = mb_strtolower(implode(' ', [
                    (string) $item['name'],
                    (string) $item['path'],
                    (string) $item['source'],
                ]));

                if (! str_contains($haystack, $keyword)) {
                    return false;
                }
            }

            return true;
        }));
    }

    /**
     * Unggah berkas media baru ke disk storage publik lokal.
     *
     * @return array{
     *     id: string,
     *     name: string,
     *     path: string,
     *     url: string,
     *     size: string,
     *     source: string,
     *     mime: string,
     *     created_at: string
     * }
     */
    public function uploadMedia(UploadedFile $file, ?string $label = null): array
    {
        Storage::disk('public')->makeDirectory('media');

        $ext = $file->getClientOriginalExtension() ?: 'jpg';
        $labelSlug = Str::slug($label ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $prefix = $labelSlug !== '' ? Str::limit($labelSlug, 40, '') : 'media';
        $safeName = $prefix.'_'.now()->format('Ymd_His').'_'.Str::random(6).'.'.$ext;

        Storage::disk('public')->putFileAs('media', $file, $safeName);

        $path = '/storage/media/'.$safeName;
        $sizeBytes = $file->getSize() ?: 0;

        return [
            'id' => 'upload_'.md5('media/'.$safeName),
            'name' => $label !== null && trim($label) !== '' ? trim($label) : $file->getClientOriginalName(),
            'path' => $path,
            'url' => asset('storage/media/'.$safeName),
            'size' => $this->formatBytes($sizeBytes),
            'source' => 'upload',
            'mime' => (string) ($file->getMimeType() ?: 'image/jpeg'),
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Hapus berkas media unggahan dari storage lokal (hanya media ber-source upload).
     */
    public function deleteMedia(string $path): bool
    {
        $normalized = ltrim($path, '/');
        if (str_starts_with($normalized, 'storage/media/')) {
            $storageRelative = Str::after($normalized, 'storage/');
            if (Storage::disk('public')->exists($storageRelative)) {
                return Storage::disk('public')->delete($storageRelative);
            }
        }

        return false;
    }

    /**
     * Format byte integer menjadi satuan KB/MB yang mudah dibaca manusia.
     */
    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2, '.', '').' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 1, '.', '').' KB';
        }

        return $bytes.' B';
    }

    /**
     * Ambil seluruh entri artikel mentah (dari DB jika tabel csirt tersedia, atau runtime Cache/Dummy).
     *
     * @return array<int, array<string, mixed>>
     */
    public function rawArticles(): array
    {
        if (Schema::hasTable('csirt')) {
            if (DB::table('csirt')->count() === 0 && ! Cache::has('csirt.table.initialized')) {
                foreach ($this->defaultArticles() as $art) {
                    DB::table('csirt')->insert([
                        'id' => $art['id'],
                        'judul' => $art['title'],
                        'deskripsi' => $art['content'],
                        'kategori' => $art['category'],
                        'status' => $art['status'],
                        'thumbnail' => ltrim((string) $art['thumbnail'], '/'),
                        'created_at' => $art['created_at'],
                        'updated_at' => $art['updated_at'],
                    ]);
                }
                Cache::forever('csirt.table.initialized', true);
            }

            $rows = DB::table('csirt')->orderByDesc('id')->get();

            return $rows->map(static fn (object $row): array => [
                'id' => (int) ($row->id ?? 0),
                'title' => (string) ($row->judul ?? ''),
                'content' => (string) ($row->deskripsi ?? ''),
                'excerpt' => Str::limit(strip_tags((string) ($row->deskripsi ?? '')), 160),
                'category' => (string) ($row->kategori ?? 'Keamanan Siber'),
                'status' => in_array((string) ($row->status ?? 'published'), ['selesai', 'published', 'terbit'], true) ? 'published' : 'draft',
                'thumbnail' => '/'.ltrim((string) ($row->thumbnail ?? 'sandiman/perkuat.jpg'), '/'),
                'author' => 'Diskominfotiksan Pekanbaru',
                'published_at' => substr((string) ($row->created_at ?? '2026-06-02'), 0, 10),
                'created_at' => (string) ($row->created_at ?? '2026-06-02 07:07:44'),
                'updated_at' => (string) ($row->updated_at ?? $row->created_at ?? '2026-06-02 07:07:44'),
            ])->all();
        }

        /** @var array<int, array<string, mixed>> $cached */
        $cached = Cache::get(self::CACHE_ARTICLES_KEY, $this->defaultArticles());

        return $cached;
    }

    /**
     * Data artikel default/simulasi awal untuk PKU-CSIRT.
     *
     * @return array<int, array<string, mixed>>
     */
    private function defaultArticles(): array
    {
        // DUMMY: Data simulasi artikel berita & kegiatan PKU-CSIRT sebelum tabel csirt dimigrasikan dan diisi penuh di produksi
        return [
            [
                'id' => 1,
                'title' => 'Dummy: Pemerintah Kota Pekanbaru Perkuat Sistem Ketahanan Siber Terpadu',
                'excerpt' => 'Dummy — Tim PKU-CSIRT bersama Dinas Komunikasi, Informatika, Statistik, dan Persandian memperkuat pemantauan infrastruktur TI dan standardisasi keamanan aplikasi layanan publik di lingkungan Pemko Pekanbaru.',
                'content' => "Dummy — Tim PKU-CSIRT bersama Dinas Komunikasi, Informatika, Statistik, dan Persandian Kota Pekanbaru terus meningkatkan kesiapsiagaan dalam menghadapi potensi ancaman dan insiden siber pada ekosistem Sistem Pemerintahan Berbasis Elektronik (SPBE).\n\nLangkah strategis ini mencakup penguatan pemantauan lalu lintas data pada jaringan intra-pemerintah, audit berkala terhadap kepatuhan keamanan informasi seluruh Organisasi Perangkat Daerah (OPD), serta penerapan standar hardening server secara terpusat di Pusat Data Tenayan Raya.\n\nKepala Diskominfotiksan Kota Pekanbaru menegaskan bahwa ketahanan siber bukan hanya tanggung jawab tim teknis, melainkan komitmen bersama seluruh jajaran aparatur demi menjamin kedaulatan data dan keberlangsungan layanan publik bagi warga Pekanbaru.",
                'category' => 'Keamanan Siber',
                'status' => 'published',
                'thumbnail' => '/sandiman/perkuat.jpg',
                'author' => 'Tim PKU-CSIRT',
                'published_at' => '2026-06-02',
                'created_at' => '2026-06-02 07:07:44',
                'updated_at' => '2026-06-02 07:07:44',
            ],
            [
                'id' => 2,
                'title' => 'Dummy: Respons Cepat Mitigasi Kampanye Phishing Berkedok Layanan Pemerintahan',
                'excerpt' => 'Dummy — PKU-CSIRT melaksanakan langkah isolasi cepat dan pemblokiran domain tiruan yang berupaya mengelabui kredensial aparatur dan masyarakat melalui tautan pesan instan.',
                'content' => "Dummy — Menanggapi laporan masyarakat mengenai peredaran pesan berantai yang mengatasnamakan layanan bantuan sosial dan portal kepegawaian Pemerintah Kota Pekanbaru, Tim Tanggap Insiden Siber PKU-CSIRT langsung melakukan penyelidikan digital secara intensif.\n\nDari hasil analisis teknis forensik, ditemukan sejumlah tautan berbahaya (malicious URL) yang mengarahkan korban ke formulir palsu untuk mencuri kredensial login dan data kependudukan. Tim segera berkoordinasi dengan pengelola DNS nasional dan ID-CERT untuk melakukan tindakan pemblokiran (takedown).\n\nPKU-CSIRT mengimbau seluruh warga dan ASN agar tidak sembarangan mengeklik tautan asing, selalu memastikan akhiran domain resmi adalah '.pekanbaru.go.id', serta segera melapor jika menemukan indikasi serupa.",
                'category' => 'Advisori Insiden',
                'status' => 'published',
                'thumbnail' => '/sandiman/phising.jpg',
                'author' => 'Unit Reaksi Cepat Siber',
                'published_at' => '2026-06-02',
                'created_at' => '2026-06-02 07:07:44',
                'updated_at' => '2026-06-02 07:07:44',
            ],
            [
                'id' => 3,
                'title' => 'Dummy: Workshop Keamanan Data Pribadi & Manajemen Kredensial bagi ASN Pekanbaru',
                'excerpt' => 'Dummy — Pelatihan intensif bagi pengelola sistem informasi dan ASN di Kompleks Perkantoran Tenayan Raya mengenai penerapan autentikasi dua faktor (2FA), passkey, dan prosedur pelaporan insiden.',
                'content' => "Dummy — Guna mewujudkan budaya sadar siber (cyber security awareness) di lingkungan birokrasi, Dinas Komunikasi, Informatika, Statistik, dan Persandian Kota Pekanbaru menyelenggarakan Workshop Teknis Pengamanan Data Pribadi dan Manajemen Kredensial.\n\nKegiatan yang berlangsung di Ruang Rapat Gedung Utama Kompleks Perkantoran Walikota Tenayan Raya ini dihadiri oleh pranata komputer dan pengelola sistem dari seluruh dinas, badan, dan kecamatan se-Kota Pekanbaru.\n\nMateri yang disampaikan mencakup kepatuhan terhadap Undang-Undang Pelindungan Data Pribadi (UU PDP), praktik terbaik penerapan autentikasi multifaktor (MFA / passkey), pengelolaan hak akses berbasis peran (RBAC), serta alur baku pelaporan insiden darurat ke PKU-CSIRT.",
                'category' => 'Edukasi & Literasi',
                'status' => 'published',
                'thumbnail' => '/sandiman/workshop.jpg',
                'author' => 'Seksi Persandian Diskominfotiksan',
                'published_at' => '2026-06-02',
                'created_at' => '2026-06-02 07:07:44',
                'updated_at' => '2026-06-02 07:07:44',
            ],
            [
                'id' => 4,
                'title' => 'Dummy: Waspada Situs Tiruan Perbankan & Modus Penipuan Rekruitmen Digital di Wilayah Riau',
                'excerpt' => 'Dummy — Imbauan keamanan siber kepada masyarakat Kota Pekanbaru untuk selalu memverifikasi sertifikat HTTPS dan domain resmi sebelum memasukkan data sensitif maupun kode OTP.',
                'content' => "Dummy — Patroli siber berkala PKU-CSIRT mengidentifikasi munculnya modus penipuan berbasis rekayasa sosial (social engineering) yang menyasar nasabah bank daerah dan pencari kerja di wilayah Pekanbaru dan Riau sekitarnya.\n\nPelaku menyebarkan lowongan kerja fiktif melalui platform media sosial dan mengarahkan korban mengunduh file instalasi aplikasi Android (.APK) palsu yang berisi spyware/infostealer untuk menyadap SMS OTP perbankan.\n\nPKU-CSIRT merekomendasikan masyarakat untuk hanya mengunduh aplikasi dari toko resmi (Play Store / App Store), tidak memberikan izin akses SMS kepada aplikasi yang tidak dikenal, dan segera menghubungi bank terkait jika mencurigai adanya transaksi tidak sah.",
                'category' => 'Peringatan Publik',
                'status' => 'published',
                'thumbnail' => '/sandiman/312e380cd76237cf992eef571542fce4.jpg',
                'author' => 'PKU-CSIRT Alert Team',
                'published_at' => '2026-06-02',
                'created_at' => '2026-06-02 14:24:47',
                'updated_at' => '2026-06-02 14:24:47',
            ],
        ];
        // END DUMMY: Data simulasi artikel berita & kegiatan PKU-CSIRT sebelum tabel csirt dimigrasikan dan diisi penuh di produksi
    }

    /**
     * Ambil seluruh entri Hall of Fame beserta ringkasan leaderboard peneliti.
     *
     * @return array{
     *     entries: array<int, array<string, mixed>>,
     *     leaderboard: array<int, array<string, mixed>>,
     *     years: array<int, string>,
     *     stats: array{total_entries: int, total_researchers: int, critical_high_count: int}
     * }
     */
    public function hallOfFameData(?string $yearFilter = null, ?string $severityFilter = null, ?string $search = null): array
    {
        $entries = $this->rawHallOfFameEntries();

        // Hitung tahun yang tersedia
        $years = [];
        foreach ($entries as $entry) {
            $year = (string) ($entry['year'] ?? '');
            if ($year !== '' && ! in_array($year, $years, true)) {
                $years[] = $year;
            }
        }
        rsort($years);

        // Leaderboard keseluruhan atau berdasarkan filter tahun
        $entriesForLeaderboard = $entries;
        if ($yearFilter !== null && $yearFilter !== '') {
            $entriesForLeaderboard = array_values(array_filter(
                $entries,
                static fn (array $item): bool => (string) ($item['year'] ?? '') === $yearFilter
            ));
        }

        $leaderboard = $this->buildLeaderboard($entriesForLeaderboard);

        // Filter daftar temuan untuk tampilan tabel/kartu
        $filteredEntries = array_values(array_filter($entries, function (array $item) use ($yearFilter, $severityFilter, $search): bool {
            if ($yearFilter !== null && $yearFilter !== '' && (string) ($item['year'] ?? '') !== $yearFilter) {
                return false;
            }

            if ($severityFilter !== null && $severityFilter !== '') {
                $severity = $this->cvssSeverityLabel((float) ($item['cvss'] ?? 0));
                if (mb_strtolower($severity) !== mb_strtolower($severityFilter)) {
                    return false;
                }
            }

            if ($search !== null && trim($search) !== '') {
                $keyword = mb_strtolower(trim($search));
                $haystack = mb_strtolower(implode(' ', [
                    (string) ($item['name'] ?? ''),
                    (string) ($item['vuln_name'] ?? ''),
                    (string) ($item['vuln_web'] ?? ''),
                    (string) ($item['description'] ?? ''),
                ]));

                if (! str_contains($haystack, $keyword)) {
                    return false;
                }
            }

            return true;
        }));

        $allLeaderboard = $this->buildLeaderboard($entries);
        $criticalHighCount = count(array_filter(
            $entries,
            static fn (array $item): bool => (float) ($item['cvss'] ?? 0) >= 7.0
        ));

        return [
            'entries' => $filteredEntries,
            'leaderboard' => $leaderboard,
            'years' => $years,
            'stats' => [
                'total_entries' => count($entries),
                'total_researchers' => count($allLeaderboard),
                'critical_high_count' => $criticalHighCount,
            ],
        ];
    }

    /**
     * Tambahkan entri baru ke Hall of Fame (mendukung DB bila tabel tersedia atau Cache runtime).
     *
     * @param  array{name: string, url: string, date: string, vuln_name: string, vuln_web: string, cvss: string|float, description: string}  $payload
     * @return array<string, mixed>
     */
    public function addHallOfFameEntry(array $payload): array
    {
        $entries = $this->rawHallOfFameEntries();
        $nextId = max(0, ...array_map(static fn (array $item): int => (int) ($item['id'] ?? 0), $entries)) + 1;
        $normalizedDate = $this->normalizeDate($payload['date']) ?? now()->toDateString();
        $cvssScore = number_format((float) $payload['cvss'], 1, '.', '');

        $newEntry = [
            'id' => $nextId,
            'name' => trim($payload['name']),
            'url' => trim($payload['url']),
            'date' => Carbon::parse($normalizedDate)->translatedFormat('d F Y'),
            'normalized_date' => $normalizedDate,
            'year' => substr($normalizedDate, 0, 4),
            'vuln_name' => trim($payload['vuln_name']),
            'vuln_web' => trim($payload['vuln_web']),
            'cvss' => $cvssScore,
            'severity' => $this->cvssSeverityLabel((float) $cvssScore),
            'description' => trim($payload['description']),
        ];

        if (Schema::hasTable('hof')) {
            DB::table('hof')->insert([
                'entry_order' => count($entries),
                'name' => $newEntry['name'],
                'url' => $newEntry['url'],
                'date' => $normalizedDate,
                'vuln_name' => $newEntry['vuln_name'],
                'vuln_web' => $newEntry['vuln_web'],
                'cvss' => $newEntry['cvss'],
                'description' => $newEntry['description'],
                'created_at' => now(),
            ]);
        } else {
            array_unshift($entries, $newEntry);
            Cache::forever(self::CACHE_HOF_ENTRIES_KEY, $entries);
        }

        return $newEntry;
    }

    /**
     * Hapus entri Hall of Fame berdasarkan ID.
     */
    public function deleteHallOfFameEntry(int $id): bool
    {
        if (Schema::hasTable('hof')) {
            return DB::table('hof')->where('id', $id)->delete() > 0;
        }

        $entries = $this->rawHallOfFameEntries();
        $updated = array_values(array_filter(
            $entries,
            static fn (array $entry): bool => (int) ($entry['id'] ?? 0) !== $id
        ));

        Cache::forever(self::CACHE_HOF_ENTRIES_KEY, $updated);

        return count($updated) < count($entries);
    }

    /**
     * Ambil daftar laporan insiden siber.
     *
     * @return array<int, array<string, mixed>>
     */
    public function incidentReports(?string $statusFilter = null, ?string $search = null): array
    {
        // DUMMY: Data awal simulasi tiket insiden siber PKU-CSIRT sebelum tabel incident_reports produksi aktif
        $dummyReports = [
            [
                'id' => 101,
                'ticket_number' => 'DUMMY-PKU-CSIRT-20261101-8421',
                'reporter_name' => 'Dummy Tim TI Kecamatan Tampan',
                'reporter_email' => 'dummy.ti.tampan@pekanbaru.go.id',
                'reporter_org' => 'Dummy OPD Pemerintah Kota Pekanbaru',
                'category' => 'Phishing & Social Engineering',
                'affected_system' => 'https://dummy-layanan-kecamatan.pekanbaru.go.id',
                'severity' => 'Tinggi',
                'status' => 'diproses',
                'description' => 'Dummy: Ditemukan email phising yang mengatasnamakan pembaruan portal kepegawaian dengan tautan eksternal mencurigakan.',
                'created_at' => '2026-11-01 09:15:00',
            ],
            [
                'id' => 102,
                'ticket_number' => 'DUMMY-PKU-CSIRT-20261028-5190',
                'reporter_name' => 'Dummy Aditya Kurniawan',
                'reporter_email' => 'dummy.aditya.sec@example.com',
                'reporter_org' => 'Dummy Peneliti Keamanan Independen',
                'category' => 'Kerentanan Aplikasi (Responsible Disclosure)',
                'affected_system' => 'https://dummy-arsip-digital.pekanbaru.go.id',
                'severity' => 'Sedang',
                'status' => 'selesai',
                'description' => 'Dummy: Temuan Reflected XSS pada parameter pencarian publik dan telah diverifikasi penambalannya oleh tim pengembang Diskominfotiksan.',
                'created_at' => '2026-10-28 14:40:00',
            ],
            [
                'id' => 103,
                'ticket_number' => 'DUMMY-PKU-CSIRT-20261105-9304',
                'reporter_name' => 'Dummy Pengelola Server Diskominfotiksan',
                'reporter_email' => 'dummy.noc@pekanbaru.go.id',
                'reporter_org' => 'Dummy Internal Pemko Pekanbaru',
                'category' => 'Anomali Trafik / DDoS',
                'affected_system' => 'https://dummy-portal.pekanbaru.go.id',
                'severity' => 'Tinggi',
                'status' => 'baru',
                'description' => 'Dummy: Lonjakan request HTTP GET tidak wajar dari sejumlah IP luar negeri pada pukul 02.15 WIB; mitigasi rate-limiting WAF telah diaktifkan.',
                'created_at' => '2026-11-05 02:30:00',
            ],
        ];
        // END DUMMY: Data awal simulasi tiket insiden siber PKU-CSIRT sebelum tabel incident_reports produksi aktif

        /** @var array<int, array<string, mixed>> $reports */
        $reports = Cache::get(self::CACHE_INCIDENTS_KEY, $dummyReports);

        return array_values(array_filter($reports, static function (array $report) use ($statusFilter, $search): bool {
            if ($statusFilter !== null && $statusFilter !== '' && (string) ($report['status'] ?? '') !== $statusFilter) {
                return false;
            }

            if ($search !== null && trim($search) !== '') {
                $keyword = mb_strtolower(trim($search));
                $haystack = mb_strtolower(implode(' ', [
                    (string) ($report['ticket_number'] ?? ''),
                    (string) ($report['reporter_name'] ?? ''),
                    (string) ($report['category'] ?? ''),
                    (string) ($report['affected_system'] ?? ''),
                    (string) ($report['description'] ?? ''),
                ]));

                if (! str_contains($haystack, $keyword)) {
                    return false;
                }
            }

            return true;
        }));
    }

    /**
     * Simpan laporan insiden siber baru dan hasilkan nomor tiket resmi PKU-CSIRT.
     *
     * @param  array{
     *     reporter_name: string,
     *     reporter_email: string,
     *     reporter_org?: string,
     *     category: string,
     *     affected_system: string,
     *     severity: string,
     *     description: string
     * }  $payload
     * @return array<string, mixed>
     */
    public function submitIncidentReport(array $payload): array
    {
        $reports = $this->incidentReports();
        $nextId = max(100, ...array_map(static fn (array $item): int => (int) ($item['id'] ?? 0), $reports)) + 1;
        $ticketNumber = 'PKU-CSIRT-'.now()->format('Ymd').'-'.strtoupper(Str::random(4));

        $newReport = [
            'id' => $nextId,
            'ticket_number' => $ticketNumber,
            'reporter_name' => trim($payload['reporter_name']),
            'reporter_email' => trim($payload['reporter_email']),
            'reporter_org' => trim((string) ($payload['reporter_org'] ?? 'Pelapor Publik')),
            'category' => trim($payload['category']),
            'affected_system' => trim($payload['affected_system']),
            'severity' => trim($payload['severity']),
            'status' => 'baru',
            'description' => trim($payload['description']),
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];

        array_unshift($reports, $newReport);
        Cache::forever(self::CACHE_INCIDENTS_KEY, $reports);

        return $newReport;
    }

    /**
     * Perbarui status penanganan tiket laporan insiden siber.
     */
    public function updateIncidentStatus(int $id, string $status): bool
    {
        if (! in_array($status, ['baru', 'diproses', 'selesai'], true)) {
            return false;
        }

        $reports = $this->incidentReports();
        $updated = false;

        foreach ($reports as &$report) {
            if ((int) ($report['id'] ?? 0) === $id) {
                $report['status'] = $status;
                $updated = true;
                break;
            }
        }
        unset($report);

        if ($updated) {
            Cache::forever(self::CACHE_INCIDENTS_KEY, $reports);
        }

        return $updated;
    }

    /**
     * Struktur lengkap dokumen kepatuhan IETF RFC-2350 PKU-CSIRT.
     *
     * @return array<int, array{section: string, title: string, summary: string, details: array<int, string>}>
     */
    public function rfc2350Sections(): array
    {
        return [
            [
                'section' => 'Pasal 1',
                'title' => 'Informasi Dokumen (Document Information)',
                'summary' => 'Identitas versi, tanggal publikasi, dan status distribusi dokumen kepatuhan RFC-2350 PKU-CSIRT.',
                'details' => [
                    'Judul Dokumen: RFC-2350 PKU-CSIRT (Pemerintah Kota Pekanbaru)',
                    'Versi Dokumen: 2.0 (Pembaruan Standar Layanan & Kontak Resmi)',
                    '1.3 Lokasi Dokumen (Canonical URL): https://CSIRT.pekanbaru.go.id/rfc2350.pdf',
                    'Distribusi: Publik — Tidak ada batasan distribusi (Unlimited Distribution)',
                ],
            ],
            [
                'section' => 'Pasal 2',
                'title' => 'Informasi Kontak & Komunikasi (Contact Information)',
                'summary' => 'Titik kontak resmi tim tanggap insiden siber Pemerintah Kota Pekanbaru.',
                'details' => [
                    'Nama Resmi Tim: Pekanbaru Computer Security Incident Response Team (PKU-CSIRT)',
                    'Instansi Induk: Dinas Komunikasi, Informatika, Statistik, dan Persandian Kota Pekanbaru',
                    'Alamat Pos: Kompleks Perkantoran Walikota Pekanbaru, Jl. Abdul Rahman Hamid, Tenayan Raya, Pekanbaru, Riau 28285',
                    'Zona Waktu: WIB (Asia/Jakarta, UTC+07:00)',
                    'Pos-el Resmi Pelaporan: csirt@pekanbaru.go.id',
                    'Jam Operasional: Senin – Jumat 08.00–16.00 WIB (Koordinasi darurat 24/7 untuk insiden berdampak kritis)',
                ],
            ],
            [
                'section' => 'Pasal 3',
                'title' => 'Piagam & Konstituen (Charter & Constituency)',
                'summary' => 'Misi, ruang lingkup wewenang, dan konstituen yang dilayani oleh PKU-CSIRT.',
                'details' => [
                    'Misi: Membangun, mengoordinasikan, dan menjaga ketahanan keamanan siber pada penyelenggaraan Sistem Pemerintahan Berbasis Elektronik (SPBE) di Pemerintah Kota Pekanbaru.',
                    'Konstituen: Seluruh Organisasi Perangkat Daerah (Dinas, Badan, Kantor, Sekretariat, Kecamatan, Kelurahan) di lingkungan Pemerintah Kota Pekanbaru.',
                    'Cakupan Domain & Aset: Seluruh aplikasi, jaringan, dan sub-domain di bawah *.pekanbaru.go.id.',
                    'Afiliasi: Berkoordinasi secara struktural dan teknis dengan Gov-CSIRT Indonesia / Badan Siber dan Sandi Negara (BSSN).',
                ],
            ],
            [
                'section' => 'Pasal 4',
                'title' => 'Kebijakan & Klasifikasi Insiden (Policies)',
                'summary' => 'Jenis insiden yang ditangani, tingkat keparahan, serta kebijakan kerahasiaan pelapor (Traffic Light Protocol).',
                'details' => [
                    'Klasifikasi Insiden: Web Defacement, Phishing, Malware/Ransomware, Unauthorized Access, Data Breach, DDoS, dan Celah Keamanan Aplikasi.',
                    'Kebijakan Kerahasiaan: Seluruh data pelapor dan bukti teknis diperlakukan sesuai protokol TLP (Traffic Light Protocol) dan Undang-Undang Pelindungan Data Pribadi (UU PDP).',
                    'Kebijakan Pengungkapan Terkoordinasi (Responsible Disclosure): Peneliti keamanan yang melaporkan celah secara beretika dan tidak merusak layanan berhak dicantumkan pada Hall of Fame PKU-CSIRT.',
                ],
            ],
            [
                'section' => 'Pasal 5',
                'title' => 'Layanan CSIRT (Services)',
                'summary' => 'Rincian layanan reaktif, layanan proaktif, dan manajemen kualitas keamanan informasi.',
                'details' => [
                    'Layanan Reaktif: Peringatan & Advisori Keamanan, Penanganan Insiden Siber, Koordinasi Remediasi Kerentanan, dan Analisis Artefak Digital.',
                    'Layanan Proaktif: Pemindaian Kerentanan Berkala (Vulnerability Assessment), Audit Keamanan Aplikasi SPBE, dan Pemantauan Konfigurasi Keamanan.',
                    'Peningkatan Kapasitas: Literasi Keamanan Informasi, Bimbingan Teknis Sandiman OPD, dan Simulasi Tanggap Insiden.',
                ],
            ],
            [
                'section' => 'Pasal 6',
                'title' => 'Pelaporan Insiden & Penafian (Incident Reporting & Disclaimers)',
                'summary' => 'Tata cara pengiriman laporan insiden menggunakan formulir standar PKU-CSIRT.',
                'details' => [
                    'Format Pelaporan: Pelapor dapat menggunakan Formulir Online PKU-CSIRT atau mengunduh dokumen Formulir-Aduan-Insiden-Siber-PKU-CSIRT-v.2.docx.',
                    'Informasi Minimum Laporan: Identitas & kontak pelapor, waktu kejadian/temuan, URL/IP terdampak, deskripsi teknis/Proof-of-Concept (PoC), dan dampak potensial.',
                    'Penafian: PKU-CSIRT berupaya maksimal menyajikan informasi advisori yang akurat, namun tidak bertanggung jawab atas penyalahgunaan informasi teknis oleh pihak ketiga.',
                ],
            ],
        ];
    }

    /**
     * Label tingkat keparahan berdasarkan skor CVSS v3.
     */
    public function cvssSeverityLabel(float $cvss): string
    {
        if ($cvss >= 9.0) {
            return 'Kritis';
        }
        if ($cvss >= 7.0) {
            return 'Tinggi';
        }
        if ($cvss >= 4.0) {
            return 'Sedang';
        }
        if ($cvss > 0.0) {
            return 'Rendah';
        }

        return 'Informasional';
    }

    /**
     * Ambil daftar mentah entri Hall of Fame (dari tabel DB jika ada, atau dari Cache/Fallback).
     *
     * @return array<int, array<string, mixed>>
     */
    private function rawHallOfFameEntries(): array
    {
        if (Schema::hasTable('hof')) {
            $rows = DB::table('hof')->orderBy('entry_order')->orderByDesc('id')->get();
            if ($rows->isNotEmpty()) {
                return $rows->map(function (object $entry): array {
                    $rawDate = (string) ($entry->date ?? '2026-01-10');
                    $normalized = $this->normalizeDate($rawDate) ?? '2026-01-10';
                    $cvss = (string) ($entry->cvss ?? '7.5');
                    $cvssFloat = (float) ($cvss !== '' ? $cvss : 7.5);

                    return [
                        'id' => (int) ($entry->id ?? 0),
                        'name' => (string) ($entry->name ?? ''),
                        'url' => (string) ($entry->url ?? ''),
                        'date' => $rawDate,
                        'normalized_date' => $normalized,
                        'year' => substr($normalized, 0, 4),
                        'vuln_name' => (string) ($entry->vuln_name ?: 'Pelaporan Celah Keamanan Aplikasi Web'),
                        'vuln_web' => (string) ($entry->vuln_web ?: 'https://pekanbaru.go.id'),
                        'cvss' => number_format($cvssFloat, 1, '.', ''),
                        'severity' => $this->cvssSeverityLabel($cvssFloat),
                        'description' => (string) ($entry->description ?: 'Laporan kerentanan keamanan pada layanan subdomain Pemerintah Kota Pekanbaru telah diverifikasi dan dimitigasi.'),
                    ];
                })->all();
            }
        }

        // DUMMY: Data awal Hall of Fame berdasarkan catatan historis laravel-csirt-v1 sebelum tabel hof dimigrasikan
        $dummyEntries = [
            [
                'id' => 1,
                'name' => 'Dummy William',
                'url' => 'https://dummy.pekanbaru.go.id',
                'date' => '10 Januari 2026',
                'normalized_date' => '2026-01-10',
                'year' => '2026',
                'vuln_name' => 'Dummy: Broken Access Control & IDOR pada Modul Dokumen Publik',
                'vuln_web' => 'https://dummy-layanan.pekanbaru.go.id',
                'cvss' => '8.2',
                'severity' => 'Tinggi',
                'description' => 'Dummy: Mengidentifikasi celah otorisasi objek langsung (IDOR) yang memungkinkan akses metadata dokumen lintas pengguna. Telah diperbaiki melalui validasi policy server-side.',
            ],
            [
                'id' => 2,
                'name' => 'Dummy Aditya Kurniawan',
                'url' => 'https://dummy.pekanbaru.go.id',
                'date' => '15 Februari 2026',
                'normalized_date' => '2026-02-15',
                'year' => '2026',
                'vuln_name' => 'Dummy: Reflected Cross-Site Scripting (XSS) pada Parameter Pencarian',
                'vuln_web' => 'https://dummy-info.pekanbaru.go.id',
                'cvss' => '6.5',
                'severity' => 'Sedang',
                'description' => 'Dummy: Menemukan celah injeksi skrip pada filter kueri pencarian lama. Telah dimitigasi dengan sanitasi input ketat dan penerapan Content Security Policy (CSP).',
            ],
            [
                'id' => 3,
                'name' => 'Dummy Pasno',
                'url' => 'https://dummy.pekanbaru.go.id',
                'date' => '22 Maret 2026',
                'normalized_date' => '2026-03-22',
                'year' => '2026',
                'vuln_name' => 'Dummy: Sensitive Configuration Exposure pada Endpoint Subdomain Lama',
                'vuln_web' => 'https://dummy-sim.pekanbaru.go.id',
                'cvss' => '7.8',
                'severity' => 'Tinggi',
                'description' => 'Dummy: Melaporkan eksposur berkas konfigurasi cadangan pada direktori publik warisan. Berkas telah diamankan dan aturan blokir akses web server telah diterapkan.',
            ],
            [
                'id' => 4,
                'name' => 'Dummy William',
                'url' => 'https://dummy.pekanbaru.go.id',
                'date' => '14 Mei 2026',
                'normalized_date' => '2026-05-14',
                'year' => '2026',
                'vuln_name' => 'Dummy: SQL Injection pada Endpoint Filter Arsip Legacy',
                'vuln_web' => 'https://dummy-arsip.pekanbaru.go.id',
                'cvss' => '9.1',
                'severity' => 'Kritis',
                'description' => 'Dummy: Melaporkan parameter urutan kueri yang belum menggunakan prepared statement. Telah direfaktor penuh menggunakan parameter binding Eloquent.',
            ],
            [
                'id' => 5,
                'name' => 'Dummy Aditya Kurniawan',
                'url' => 'https://dummy.pekanbaru.go.id',
                'date' => '19 Agustus 2025',
                'normalized_date' => '2025-08-19',
                'year' => '2025',
                'vuln_name' => 'Dummy: Unrestricted File Upload Validation Bypass',
                'vuln_web' => 'https://dummy-formulir.pekanbaru.go.id',
                'cvss' => '8.6',
                'severity' => 'Tinggi',
                'description' => 'Dummy: Validasi ekstensi ganda pada fitur unggah lampiran formulir. Telah diperbaiki dengan pemeriksaan MIME-type ketat dan penyimpanan non-eksekusi.',
            ],
        ];
        // END DUMMY: Data awal Hall of Fame berdasarkan catatan historis laravel-csirt-v1 sebelum tabel hof dimigrasikan

        /** @var array<int, array<string, mixed>> $cached */
        $cached = Cache::get(self::CACHE_HOF_ENTRIES_KEY, $dummyEntries);

        return $cached;
    }

    /**
     * Bangun peringkat leaderboard peneliti keamanan berdasarkan jumlah kontribusi & tanggal terbaru.
     *
     * @param  array<int, array<string, mixed>>  $entries
     * @return array<int, array<string, mixed>>
     */
    private function buildLeaderboard(array $entries): array
    {
        /** @var array<string, array<string, mixed>> $reporters */
        $reporters = [];

        foreach ($entries as $entry) {
            $name = trim((string) ($entry['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $key = mb_strtolower($name);
            $date = (string) ($entry['normalized_date'] ?? $this->normalizeDate((string) ($entry['date'] ?? '')) ?? '');
            $cvss = (float) ($entry['cvss'] ?? 0.0);

            if (! isset($reporters[$key])) {
                $reporters[$key] = [
                    'name' => $name,
                    'url' => (string) ($entry['url'] ?? ''),
                    'total' => 0,
                    'highest_cvss' => 0.0,
                    'latest_date' => '',
                    'latest_formatted' => '',
                ];
            }

            $reporters[$key]['total']++;
            if ($cvss > (float) $reporters[$key]['highest_cvss']) {
                $reporters[$key]['highest_cvss'] = $cvss;
            }

            if ($date !== '' && ($reporters[$key]['latest_date'] === '' || $date >= (string) $reporters[$key]['latest_date'])) {
                $reporters[$key]['latest_date'] = $date;
                $reporters[$key]['latest_formatted'] = Carbon::parse($date)->format('d/m/Y');
                $reporters[$key]['name'] = $name;
                $reporters[$key]['url'] = (string) ($entry['url'] ?? '');
            }
        }

        $list = array_values($reporters);
        usort($list, static function (array $left, array $right): int {
            return ((int) $right['total'] <=> (int) $left['total'])
                ?: ((float) $right['highest_cvss'] <=> (float) $left['highest_cvss'])
                ?: strcmp((string) $right['latest_date'], (string) $left['latest_date'])
                ?: strcasecmp((string) $left['name'], (string) $right['name']);
        });

        return array_map(
            static fn (array $item, int $index): array => $item + ['position' => $index + 1],
            $list,
            array_keys($list)
        );
    }

    /**
     * Normalisasi string tanggal Indonesia ("10 Januari 2026") atau ISO ("2026-01-10") menjadi "Y-m-d".
     */
    private function normalizeDate(string $value): ?string
    {
        $value = trim($value);
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) === 1) {
            return $value;
        }

        if (preg_match('/^(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})$/', $value, $parts) !== 1) {
            return null;
        }

        $months = [
            'januari' => '01',
            'februari' => '02',
            'maret' => '03',
            'april' => '04',
            'mei' => '05',
            'juni' => '06',
            'juli' => '07',
            'agustus' => '08',
            'september' => '09',
            'oktober' => '10',
            'november' => '11',
            'desember' => '12',
        ];

        $month = $months[mb_strtolower($parts[2])] ?? null;
        if ($month === null) {
            return null;
        }

        return sprintf('%04d-%s-%02d', (int) $parts[3], $month, (int) $parts[1]);
    }
}
