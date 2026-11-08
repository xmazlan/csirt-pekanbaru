<?php

declare(strict_types=1);

use App\Models\User;
use Livewire\Livewire;

test('beranda publik menampilkan identitas kota pekanbaru dan tiga fitur wajib', function () {
    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertSee('PKU-CSIRT')
        ->assertSee('Kota Bertuah')
        ->assertSee('Adat dijunjung, marwah terpelihara, siber terjaga, Pekanbaru berjaya')
        ->assertSee('Melaporkan Insiden')
        ->assertSee('Hall of Fame')
        ->assertSee('RFC-2350');
});

test('halaman melaporkan insiden dapat diakses dan menerbitkan nomor tiket resmi', function () {
    $this->get(route('public.incident-report'))
        ->assertOk()
        ->assertSee('Melaporkan Insiden Siber & Kerentanan')
        ->assertSee('Formulir-Aduan-Insiden-Siber-PKU-CSIRT-v.2.docx');

    Livewire::test('public::incident-report')
        ->set('reporter_name', 'Dummy Budi Santoso')
        ->set('reporter_email', 'dummy.budi.sec@example.com')
        ->set('reporter_org', 'Dummy Peneliti Siber Riau')
        ->set('category', 'Kerentanan Aplikasi (Responsible Disclosure)')
        ->set('affected_system', 'https://dummy-layanan.pekanbaru.go.id/login')
        ->set('severity', 'Tinggi')
        ->set('description', 'Dummy: Ditemukan celah keamanan open redirect pada parameter return_url setelah proses autentikasi.')
        ->set('agreed_disclosure', true)
        ->call('submitReport')
        ->assertHasNoErrors()
        ->assertSee('Laporan Berhasil Diregistrasi')
        ->assertSee('PKU-CSIRT-');
});

test('halaman hall of fame menampilkan leaderboard peneliti dan mendukung filter pencarian', function () {
    $this->get(route('public.hall-of-fame'))
        ->assertOk()
        ->assertSee('Hall of Fame')
        ->assertSee('Leaderboard Peneliti Keamanan Siber')
        ->assertSee('Dummy William')
        ->assertSee('Dummy Aditya Kurniawan')
        ->assertSee('Dummy Pasno');

    Livewire::test('public::hall-of-fame')
        ->set('severityFilter', 'Kritis')
        ->assertSee('Dummy: SQL Injection pada Endpoint Filter Arsip Legacy')
        ->assertDontSee('Dummy: Reflected Cross-Site Scripting (XSS) pada Parameter Pencarian');
});

test('halaman rfc 2350 menampilkan pasal standar ietf dan tautan pdf resmi serta mendukung redirect legacy', function () {
    $this->get(route('public.rfc2350'))
        ->assertOk()
        ->assertSee('Dokumen Kepatuhan')
        ->assertSee('Pasal 1')
        ->assertSee('Pasal 6')
        ->assertSee('https://CSIRT.pekanbaru.go.id/rfc2350.pdf')
        ->assertSee('RFC-2350 - PKU-CSIRT v.2.pdf');

    $this->get('/RFC-2350')
        ->assertRedirect('/public/rfc2350');

    $this->get(route('public.rfc2350.pdf'))
        ->assertOk()
        ->assertHeader('Content-Type', 'application/pdf');
});

test('pengguna terautentikasi dapat mengelola tiket insiden dan hall of fame di dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->get(route('dashboard.incidents'))
        ->assertOk()
        ->assertSee('Manajemen Tiket Insiden & Kerentanan Siber');

    $this->get(route('dashboard.hof'))
        ->assertOk()
        ->assertSee('Manajemen Hall of Fame Security Researcher');

    Livewire::test('dashboard::hof-management')
        ->set('name', 'Dummy Rizky Pratama')
        ->set('url', 'https://dummy.pekanbaru.go.id')
        ->set('date', '2026-11-06')
        ->set('vuln_name', 'Dummy: Server-Side Request Forgery (SSRF) pada Fitur Pratinjau')
        ->set('vuln_web', 'https://dummy-portal.pekanbaru.go.id')
        ->set('cvss', '8.8')
        ->set('description', 'Dummy: Telah dilakukan pembatasan whitelist domain internal dan validasi skema URL.')
        ->call('addEntry')
        ->assertHasNoErrors();

    $this->get(route('public.hall-of-fame'))
        ->assertOk()
        ->assertSee('Dummy Rizky Pratama');
});
