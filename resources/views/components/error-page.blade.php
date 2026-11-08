@props([
    'code' => '404',
    'badge' => null,
    'title' => __('Terjadi Kesalahan'),
    'message' => '',
    'icon' => 'exclamation-triangle',
    'showReload' => false,
    'showHome' => true,
    'showDashboard' => true,
    'showLogin' => false,
])

@php
    $badgeText = $badge ?? __('Galat :code', ['code' => $code]);

    $badgeVariant = match ((string) $code) {
        '401', '403', '404' => 'bg-status-danger-soft text-status-danger border-status-danger/20',
        '419', '429', '503' => 'bg-status-warning-soft text-status-warning border-status-warning/20',
        '500' => 'bg-status-danger-soft text-status-danger border-status-danger/20',
        default => 'bg-primary-soft text-primary border-primary/20',
    };

    $iconVariant = match ((string) $code) {
        '401', '403', '404', '500' => 'text-status-danger bg-status-danger-soft border-status-danger/20',
        '419', '429', '503' => 'text-status-warning bg-status-warning-soft border-status-warning/20',
        default => 'text-primary bg-primary-soft border-primary/20',
    };

    $isLoggedIn = false;
    try {
        $isLoggedIn = auth()->check();
    } catch (\Throwable $e) {
        $isLoggedIn = false;
    }
@endphp

<div class="min-h-[50vh] flex flex-col items-center justify-center text-center px-4 py-8 sm:py-12">
    <!-- Wadah Ikon -->
    <div class="flex size-20 sm:size-24 items-center justify-center rounded-3xl border {{ $iconVariant }} shadow-xs mb-6 transition-transform hover:scale-105 duration-200">
        @switch($icon)
            @case('lock-closed')
                <flux:icon.lock-closed class="size-10 sm:size-12" />
                @break
            @case('shield-exclamation')
                <flux:icon.shield-exclamation class="size-10 sm:size-12" />
                @break
            @case('magnifying-glass')
                <flux:icon.magnifying-glass class="size-10 sm:size-12" />
                @break
            @case('clock')
                <flux:icon.clock class="size-10 sm:size-12" />
                @break
            @case('bolt')
                <flux:icon.bolt class="size-10 sm:size-12" />
                @break
            @case('wrench-screwdriver')
                <flux:icon.wrench-screwdriver class="size-10 sm:size-12" />
                @break
            @default
                <flux:icon.exclamation-triangle class="size-10 sm:size-12" />
        @endswitch
    </div>

    <!-- Status Badge -->
    <span class="inline-flex items-center gap-1.5 rounded-full px-3.5 py-1 text-xs font-semibold border mb-3 {{ $badgeVariant }}">
        {{ $badgeText }}
    </span>

    <!-- Error Title -->
    <h1 class="text-2xl sm:text-3xl font-extrabold text-app-text tracking-tight mb-2">
        {{ $title }}
    </h1>

    <!-- Error Message -->
    <p class="text-app-text-secondary max-w-md mx-auto mb-8 text-sm leading-relaxed">
        {{ $message }}
    </p>

    <!-- Tombol Aksi Kontekstual -->
    <div class="flex flex-wrap items-center justify-center gap-3">
        @if ($showReload)
            <button
                type="button"
                onclick="window.location.reload()"
                class="inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-primary-foreground shadow-xs hover:bg-primary-dark focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-colors cursor-pointer"
            >
                <flux:icon.arrow-path class="size-4" />
                <span>{{ __('Muat Ulang Halaman') }}</span>
            </button>
        @endif

        @if ($showLogin && ! $isLoggedIn && Route::has('login'))
            <a
                href="{{ route('login') }}"
                wire:navigate
                class="inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-primary-foreground shadow-xs hover:bg-primary-dark focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-colors cursor-pointer"
            >
                <flux:icon.arrow-right-start-on-rectangle class="size-4" />
                <span>{{ __('Masuk ke Akun') }}</span>
            </a>
        @endif

        @if ($showDashboard && $isLoggedIn)
            <a
                href="{{ route('dashboard') }}"
                wire:navigate
                class="inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-primary-foreground shadow-xs hover:bg-primary-dark focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-colors cursor-pointer"
            >
                <flux:icon.home class="size-4" />
                <span>{{ __('Ke Dashboard') }}</span>
            </a>
        @endif

        @if ($showHome)
            <a
                href="{{ route('home') }}"
                wire:navigate
                class="inline-flex items-center gap-2 rounded-xl border border-app-border bg-app-surface px-5 py-2.5 text-sm font-semibold text-app-text hover:bg-app-surface-muted focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-colors cursor-pointer"
            >
                <flux:icon.home class="size-4 text-app-text-muted" />
                <span>{{ __('Kembali ke Beranda') }}</span>
            </a>
        @endif
    </div>
</div>

