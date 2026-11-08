<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        @php
            $appName = config('app.name', 'CSIRT Pekanbaru');
            $rawTitle = trim($title ?? '');

            if (filled($rawTitle)) {
                $normalizedTitle = str_replace(' ', '', mb_strtolower($rawTitle));
                $normalizedAppName = str_replace(' ', '', mb_strtolower($appName));

                $pageTitle = str_contains($normalizedTitle, $normalizedAppName)
                    ? $rawTitle
                    : $rawTitle . ' - ' . $appName;
            } else {
                $pageTitle = $appName;
            }
        @endphp

        @include('partials.head', ['title' => $pageTitle])
    </head>
    <body class="min-h-full bg-app-bg text-app-text antialiased selection:bg-primary-soft selection:text-primary-content flex flex-col">
        <!-- Aksesibilitas: Lewati ke konten utama -->
        <a
            href="#main-content"
            class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:px-4 focus:py-2.5 focus:bg-primary focus:text-primary-foreground focus:font-medium focus:rounded-lg focus:shadow-lg focus:outline-hidden focus:ring-2 focus:ring-primary focus:ring-offset-2"
        >
            {{ __('Lewati ke konten utama') }}
        </a>

        <!-- Header Minimalis Galat -->
        <header
            class="sticky top-0 z-30 flex h-14 w-full items-center justify-between border-b border-app-border bg-app-surface px-4 lg:px-6 shadow-2xs"
            role="banner"
        >
            <!-- Sisi Kiri: Logo Menuju Beranda Publik -->
            <div class="flex items-center gap-3">
                <x-app-logo :href="route('home')" size="sm" :name="config('app.name', 'CSIRT Pekanbaru')" :subtitle="__('Pemerintah Kota Pekanbaru')" />
            </div>

            <!-- Sisi Kanan: Theme Toggle Tunggal & Navigasi Cepat -->
            <div class="flex items-center gap-2 sm:gap-3">
                <x-theme-toggle size="sm" />

                @php
                    $userAuthenticated = false;
                    try {
                        $userAuthenticated = auth()->check();
                    } catch (\Throwable $e) {
                        $userAuthenticated = false;
                    }
                @endphp

                @if ($userAuthenticated)
                    <a
                        href="{{ route('dashboard') }}"
                        wire:navigate
                        class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3 py-1.5 text-xs font-semibold text-primary-foreground shadow-xs hover:bg-primary-dark focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-colors cursor-pointer"
                    >
                        <flux:icon.home class="size-3.5" />
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                @else
                    <a
                        href="{{ route('home') }}"
                        wire:navigate
                        class="inline-flex items-center gap-1.5 rounded-lg border border-app-border bg-app-surface px-3 py-1.5 text-xs font-semibold text-app-text hover:bg-app-surface-muted focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-colors cursor-pointer"
                    >
                        <flux:icon.home class="size-3.5 text-app-text-muted" />
                        <span>{{ __('Beranda') }}</span>
                    </a>
                @endif
            </div>
        </header>

        <!-- Area Konten Utama Galat -->
        <main id="main-content" class="flex-1 w-full flex items-center justify-center p-4 sm:p-6" role="main" tabindex="-1">
            <div class="w-full max-w-2xl mx-auto">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer Minimalis -->
        <footer class="border-t border-app-border bg-app-surface text-app-text-muted py-4 text-center text-xs" role="contentinfo">
            <div class="mx-auto max-w-7xl px-4">
                <span>&copy; {{ date('Y') }} {{ config('app.name', 'CSIRT Pekanbaru') }} • {{ __('Pemerintah Kota Pekanbaru') }}</span>
            </div>
        </footer>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
        @livewireScripts
    </body>
</html>

