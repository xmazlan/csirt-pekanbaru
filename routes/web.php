<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'public::home')->name('home');
Route::livewire('/public/lapor-insiden', 'public::incident-report')->name('public.incident-report');
Route::livewire('/public/hall-of-fame', 'public::hall-of-fame')->name('public.hall-of-fame');
Route::livewire('/public/rfc2350', 'public::rfc2350')->name('public.rfc2350');
Route::redirect('/RFC-2350', '/public/rfc2350');
Route::get('/rfc2350.pdf', static fn () => response()->file(public_path('rfc2350.pdf'), [
    'Content-Type' => 'application/pdf',
]))->name('public.rfc2350.pdf');
Route::livewire('/public/berita', 'public::articles')->name('public.articles');
Route::redirect('/public/publikasi', '/public/berita');
Route::livewire('/public/berita/{id}', 'public::article-detail')->name('public.article-detail');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::livewire('dashboard/insiden', 'dashboard::incident-management')->name('dashboard.incidents');
    Route::livewire('dashboard/hall-of-fame', 'dashboard::hof-management')->name('dashboard.hof');
    Route::livewire('dashboard/berita', 'dashboard::article-management')->name('dashboard.articles');
    Route::redirect('dashboard/publikasi', '/dashboard/berita');
    Route::livewire('dashboard/media', 'dashboard::media-management')->name('dashboard.media');
});

require __DIR__.'/settings.php';
