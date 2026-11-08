<?php

test('non existent page renders 404 error page with error layout', function () {
    $response = $this->get('/halaman-yang-pasti-tidak-ada-404');

    $response->assertNotFound();
    $response->assertSee('404');
    $response->assertSee('Halaman Tidak Ditemukan');
    $response->assertSee('CSIRT Pekanbaru');
    $response->assertSee('Kembali ke Beranda');
});
