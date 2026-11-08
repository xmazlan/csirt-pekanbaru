<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\CsirtDataService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

test('halaman arsip berita publik dapat diakses dan menampilkan daftar artikel', function () {
    $response = $this->get(route('public.articles'));

    $response->assertOk()
        ->assertSee('Berita Kegiatan &amp; Advisori Keamanan Siber', false)
        ->assertSee('Semua Kategori')
        ->assertSee('Pemerintah Kota Pekanbaru Perkuat Sistem Ketahanan Siber Terpadu');
});

test('halaman arsip berita publik mendukung filter kategori dan pencarian', function () {
    Livewire::test('public::articles')
        ->assertSee('Pemerintah Kota Pekanbaru Perkuat Sistem Ketahanan Siber Terpadu')
        ->set('category', 'Advisori Insiden')
        ->assertSee('Respons Cepat Mitigasi Kampanye Phishing Berkedok Layanan Pemerintahan')
        ->assertDontSee('Workshop Keamanan Data Pribadi')
        ->set('category', '')
        ->set('search', 'Workshop')
        ->assertSee('Workshop Keamanan Data Pribadi & Manajemen Kredensial')
        ->assertDontSee('Respons Cepat Mitigasi Kampanye Phishing')
        ->call('resetFilter')
        ->assertSee('Pemerintah Kota Pekanbaru Perkuat Sistem Ketahanan Siber Terpadu');
});

test('halaman detail berita menampilkan konten lengkap, metadata, dan artikel terkait', function () {
    $service = app(CsirtDataService::class);
    $firstArticle = $service->articles()[0];

    $response = $this->get(route('public.article-detail', ['id' => $firstArticle['id']]));

    $response->assertOk()
        ->assertSee($firstArticle['title'])
        ->assertSee($firstArticle['category'])
        ->assertSee('Dinas Komunikasi, Informatika, Statistik, dan Persandian Kota Pekanbaru')
        ->assertSee('Kesiapsiagaan &amp; Pelaporan Insiden Siber', false)
        ->assertSee('Bagikan Artikel:');
});

test('halaman detail berita mengembalikan 404 untuk id artikel yang tidak ada', function () {
    $this->get(route('public.article-detail', ['id' => 999999]))
        ->assertNotFound();
});

test('rute alias publikasi mengarahkan ke rute berita', function () {
    $this->get('/public/publikasi')
        ->assertRedirect('/public/berita');
});

test('pengguna tamu tidak dapat mengakses dashboard manajemen berita dan diarahkan ke login', function () {
    $this->get(route('dashboard.articles'))
        ->assertRedirect(route('login'));
});

test('pengguna terautentikasi dapat melihat halaman manajemen publikasi di dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard.articles'))
        ->assertOk()
        ->assertSee('Manajemen Publikasi &amp; Berita Kegiatan', false)
        ->assertSee('Tambah Publikasi Baru');
});

test('pengguna terautentikasi dapat membuat artikel berita baru dengan status published', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test('dashboard::article-management')
        ->call('startCreate')
        ->assertSet('showForm', true)
        ->set('title', 'Pengujian Publikasi Berita Baru CSIRT Pekanbaru')
        ->set('category', 'Keamanan Siber')
        ->set('status', 'published')
        ->set('published_at', '2026-11-06')
        ->set('author', 'Tim Sandiman Diskominfotiksan')
        ->set('thumbnail', '/sandiman/perkuat.jpg')
        ->set('content', 'Ini adalah konten materi lengkap untuk pengujian publikasi berita dan advisori siber di Kota Pekanbaru.')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('showForm', false)
        ->assertSee('Pengujian Publikasi Berita Baru CSIRT Pekanbaru');

    $this->get(route('public.articles'))
        ->assertOk()
        ->assertSee('Pengujian Publikasi Berita Baru CSIRT Pekanbaru');
});

test('pengguna terautentikasi dapat membuat artikel berita berstatus draft dan memfilternya', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test('dashboard::article-management')
        ->call('startCreate')
        ->set('title', 'Draf Rencana Panduan Penanganan Insiden 2027')
        ->set('category', 'Panduan Siber')
        ->set('status', 'draft')
        ->set('published_at', '2026-11-06')
        ->set('author', 'Analis CSIRT')
        ->set('thumbnail', '/sandiman/perkuat.jpg')
        ->set('content', 'Isi dokumen draf kebijakan penanganan insiden yang masih memerlukan tinjauan kepala dinas.')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('showForm', false);

    Livewire::test('dashboard::article-management')
        ->call('setStatusFilter', 'draft')
        ->assertSee('Draf Rencana Panduan Penanganan Insiden 2027')
        ->call('setStatusFilter', 'published')
        ->assertDontSee('Draf Rencana Panduan Penanganan Insiden 2027');
});

test('pengguna terautentikasi dapat mengunggah thumbnail langsung saat membuat artikel', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $this->actingAs($user);

    $fakeImage = UploadedFile::fake()->image('custom-cover.jpg', 800, 600);

    Livewire::test('dashboard::article-management')
        ->call('startCreate')
        ->set('title', 'Berita Dengan Unggahan Cover Gambar Mandiri')
        ->set('category', 'Keamanan Siber')
        ->set('status', 'published')
        ->set('published_at', '2026-11-06')
        ->set('author', 'Diskominfotiksan Pekanbaru')
        ->set('thumbnailFile', $fakeImage)
        ->set('content', 'Isi berita dengan lampiran berkas thumbnail langsung dari form artikel.')
        ->call('save')
        ->assertHasNoErrors();

    $service = app(CsirtDataService::class);
    $created = collect($service->articles())->firstWhere('title', 'Berita Dengan Unggahan Cover Gambar Mandiri');

    expect($created)->not->toBeNull()
        ->and($created['thumbnail'])->toStartWith('/storage/media/');
});

test('pengguna dapat memilih thumbnail dari pustaka media dalam modal picker', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test('dashboard::article-management')
        ->call('startCreate')
        ->call('openMediaPicker')
        ->assertSet('showMediaPicker', true)
        ->call('selectMediaFromLibrary', '/sandiman/workshop.jpg')
        ->assertSet('showMediaPicker', false)
        ->assertSet('thumbnail', '/sandiman/workshop.jpg');
});

test('validasi pembuatan artikel berita bekerja dengan benar', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test('dashboard::article-management')
        ->call('startCreate')
        ->set('title', '')
        ->set('content', '')
        ->call('save')
        ->assertHasErrors(['title', 'content']);
});

test('pengguna terautentikasi dapat memperbarui artikel berita existing', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $service = app(CsirtDataService::class);
    $firstArticle = $service->articles()[0];

    Livewire::test('dashboard::article-management')
        ->call('startEdit', $firstArticle['id'])
        ->assertSet('showForm', true)
        ->assertSet('editingId', $firstArticle['id'])
        ->set('title', 'Judul Telah Direvisi untuk Pengujian')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('showForm', false);

    $updated = $service->findArticle($firstArticle['id']);
    expect($updated)->not->toBeNull()
        ->and($updated['title'])->toBe('Judul Telah Direvisi untuk Pengujian');
});

test('pengguna terautentikasi dapat menghapus artikel berita melalui dialog konfirmasi', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $service = app(CsirtDataService::class);
    $created = $service->saveArticle([
        'title' => 'Artikel Sementara untuk Dihapus',
        'category' => 'Keamanan Siber',
        'status' => 'draft',
        'published_at' => '2026-11-06',
        'author' => 'Tim Pengujian',
        'thumbnail' => '/sandiman/perkuat.jpg',
        'content' => 'Konten pengujian sementara yang akan dihapus dari sistem pengujian.',
    ]);

    Livewire::test('dashboard::article-management')
        ->call('confirmDelete', $created['id'], $created['title'])
        ->assertSet('pendingDeleteId', $created['id'])
        ->call('deleteConfirmed')
        ->assertSet('pendingDeleteId', null);

    expect($service->findArticle($created['id']))->toBeNull();
});

test('rute alias dashboard publikasi mengarahkan ke dashboard berita', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/dashboard/publikasi')
        ->assertRedirect('/dashboard/berita');
});

test('beranda publik menautkan ke arsip berita dan detail berita', function () {
    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertSee(route('public.articles'))
        ->assertSee('Semua Berita');
});

test('pengguna tamu tidak dapat mengakses dashboard manajemen media dan diarahkan ke login', function () {
    $this->get(route('dashboard.media'))
        ->assertRedirect(route('login'));
});

test('pengguna terautentikasi dapat melihat pustaka media di dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard.media'))
        ->assertOk()
        ->assertSee('Manajemen Media &amp; Aset Digital', false)
        ->assertSee('Unggah Media Baru');
});

test('pengguna terautentikasi dapat mengunggah berkas media ke storage lokal', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $this->actingAs($user);

    $fakeFile = UploadedFile::fake()->image('infografis-phishing.png', 1200, 800);

    Livewire::test('dashboard::media-management')
        ->call('openUpload')
        ->assertSet('showUploadModal', true)
        ->set('file', $fakeFile)
        ->set('label', 'Infografis Edukasi Phishing 2026')
        ->call('upload')
        ->assertHasNoErrors()
        ->assertSet('showUploadModal', false);

    $service = app(CsirtDataService::class);
    $mediaList = $service->mediaItems();
    $uploadedItem = collect($mediaList)->first(fn ($item) => $item['source'] === 'upload');

    expect($uploadedItem)->not->toBeNull()
        ->and($uploadedItem['source'])->toBe('upload')
        ->and(str_contains($uploadedItem['name'], 'infografis-edukasi-phishing-2026'))->toBeTrue()
        ->and(Storage::disk('public')->exists(str_replace('/storage/', '', $uploadedItem['path'])))->toBeTrue();
});

test('validasi unggah berkas media menolak berkas non-gambar atau terlalu besar', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $this->actingAs($user);

    $nonImage = UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf');

    Livewire::test('dashboard::media-management')
        ->call('openUpload')
        ->set('file', $nonImage)
        ->call('upload')
        ->assertHasErrors(['file']);
});

test('pengguna terautentikasi dapat menghapus berkas media yang diunggah', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $this->actingAs($user);

    $fakeFile = UploadedFile::fake()->image('gambar-hapus.jpg', 600, 400);

    $uploaded = app(CsirtDataService::class)->uploadMedia($fakeFile, 'Berkas Untuk Dihapus');
    expect(Storage::disk('public')->exists(str_replace('/storage/', '', $uploaded['path'])))->toBeTrue();

    Livewire::test('dashboard::media-management')
        ->call('confirmDelete', $uploaded['path'], $uploaded['name'])
        ->assertSet('pendingDeletePath', $uploaded['path'])
        ->call('deleteConfirmed')
        ->assertSet('pendingDeletePath', null);

    expect(Storage::disk('public')->exists(str_replace('/storage/', '', $uploaded['path'])))->toBeFalse();
});

test('berkas bawaan sistem tidak dapat dihapus melalui media management', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test('dashboard::media-management')
        ->call('confirmDelete', '/sandiman/perkuat.jpg', 'perkuat.jpg')
        ->call('deleteConfirmed');

    // Berkas di public/sandiman/perkuat.jpg tetap ada di filesystem
    expect(file_exists(public_path('sandiman/perkuat.jpg')))->toBeTrue();
});
