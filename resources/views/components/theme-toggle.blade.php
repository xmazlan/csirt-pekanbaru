{{-- resources/views/components/theme-toggle.blade.php --}}
@props([
    'size' => 'md',
])

@php
    $buttonSizeClass = match ($size) {
        'sm' => 'size-9',
        'lg' => 'size-12',
        default => 'size-11',
    };

    $iconSizeClass = match ($size) {
        'sm' => 'size-4',
        'lg' => 'size-6',
        default => 'size-5',
    };
@endphp

<div x-data="themeToggle" {{ $attributes->merge(['class' => 'flex items-center shrink-0']) }}>
    <button
        type="button"
        @click="toggle($event)"
        class="group relative flex {{ $buttonSizeClass }} items-center justify-center rounded-full border border-zinc-200 bg-white text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-all duration-200 cursor-pointer shadow-xs"
        x-bind:aria-label="isDark ? 'Ganti ke tema terang' : 'Ganti ke tema gelap'"
        x-bind:title="isDark ? 'Ganti ke tema terang' : 'Ganti ke tema gelap'"
    >
        <!-- Ikon Matahari (Aktif saat Tema Gelap) -->
        <svg
            class="{{ $iconSizeClass }} text-amber-500 transition-all duration-300 transform"
            x-show="isDark"
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 rotate-90 scale-50"
            x-transition:enter-end="opacity-100 rotate-0 scale-100"
            x-transition:leave="transition ease-in duration-200 absolute"
            x-transition:leave-start="opacity-100 rotate-0 scale-100"
            x-transition:leave-end="opacity-0 -rotate-90 scale-50"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>

        <!-- Ikon Bulan (Aktif saat Tema Terang) -->
        <svg
            class="{{ $iconSizeClass }} text-zinc-600 dark:text-zinc-400 group-hover:text-primary transition-all duration-300 transform"
            x-show="!isDark"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -rotate-90 scale-50"
            x-transition:enter-end="opacity-100 rotate-0 scale-100"
            x-transition:leave="transition ease-in duration-200 absolute"
            x-transition:leave-start="opacity-100 rotate-0 scale-100"
            x-transition:leave-end="opacity-0 rotate-90 scale-50"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
    </button>
</div>

