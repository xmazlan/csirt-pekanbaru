@props([
    'size' => 'md',
    'href' => route('home'),
    'showText' => true,
    'name' => 'CSIRT Pekanbaru',
    'subtitle' => 'Pemerintah Kota Pekanbaru',
    'badgeClass' => '',
    'textClass' => 'flex flex-col text-start',
    'ariaLabel' => null,
    'sidebar' => false,
    'useImage' => true,
])

@php
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'md';

    $badgeSizes = [
        'sm' => 'size-8 rounded-lg p-1',
        'md' => 'size-9 sm:size-10 rounded-xl p-1.5',
        'lg' => 'size-10 sm:size-11 rounded-xl p-2',
    ];

    $titleSizes = [
        'sm' => 'text-xs sm:text-sm font-bold',
        'md' => 'text-sm sm:text-base font-bold',
        'lg' => 'text-base sm:text-lg font-bold',
    ];

    $subtitleSizes = [
        'sm' => 'text-[10px] font-medium',
        'md' => 'text-[10px] sm:text-[11px] font-medium',
        'lg' => 'text-xs font-medium',
    ];

    $badgeClassFinal = 'flex aspect-square items-center justify-center bg-white dark:bg-zinc-900 border border-app-border text-primary shadow-xs shrink-0 transition-transform group-hover:scale-105 '
        . $badgeSizes[$size] . ' ' . $badgeClass;

    $isLink = ! empty($href) && $href !== false;
    $computedAriaLabel = $ariaLabel ?? ($name . ($subtitle ? ' - ' . $subtitle : ''));
@endphp

@if ($isLink)
    <a
        href="{{ $href }}"
        wire:navigate
        {{ $attributes->merge([
            'class' => 'group flex items-center gap-2.5 sm:gap-3 rounded-lg py-1 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-opacity',
            'aria-label' => $computedAriaLabel,
        ]) }}
    >
        <div class="{{ $badgeClassFinal }}">
            @if ($useImage && file_exists(public_path('images/brand/logo-csirt.png')))
                <img src="/images/brand/logo-csirt.png" alt="{{ $name }}" class="size-full object-contain" />
            @else
                <x-app-logo-icon class="size-full fill-current text-primary" />
            @endif
        </div>

        @if ($showText)
            <div class="{{ $textClass }}">
                <span class="{{ $titleSizes[$size] }} tracking-tight text-app-text leading-tight group-hover:text-primary transition-colors">
                    {{ $name }}
                </span>
                @if ($subtitle)
                    <span class="{{ $subtitleSizes[$size] }} text-app-text-muted leading-tight">
                        {{ $subtitle }}
                    </span>
                @endif
            </div>
        @endif
    </a>
@else
    <div
        {{ $attributes->merge([
            'class' => 'flex items-center gap-2.5 sm:gap-3',
        ]) }}
    >
        <div class="{{ $badgeClassFinal }}">
            @if ($useImage && file_exists(public_path('images/brand/logo-csirt.png')))
                <img src="/images/brand/logo-csirt.png" alt="{{ $name }}" class="size-full object-contain" />
            @else
                <x-app-logo-icon class="size-full fill-current text-primary" />
            @endif
        </div>

        @if ($showText)
            <div class="{{ $textClass }}">
                <span class="{{ $titleSizes[$size] }} tracking-tight text-app-text leading-tight">
                    {{ $name }}
                </span>
                @if ($subtitle)
                    <span class="{{ $subtitleSizes[$size] }} text-app-text-muted leading-tight">
                        {{ $subtitle }}
                    </span>
                @endif
            </div>
        @endif
    </div>
@endif
