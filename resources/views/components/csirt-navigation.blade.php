@props([
    'mobile' => false,
])

@php
    $mainItems = [
        [
            'route' => 'dashboard',
            'label' => __('Dashboard'),
            'icon' => 'home',
            'active' => request()->routeIs('dashboard'),
            'enabled' => true,
        ],
        [
            'route' => 'dashboard.incidents',
            'label' => __('Insiden Siber'),
            'icon' => 'shield-exclamation',
            'active' => request()->routeIs('dashboard.incidents'),
            'enabled' => true,
        ],
        [
            'route' => 'dashboard.hof',
            'label' => __('Hall of Fame'),
            'icon' => 'trophy',
            'active' => request()->routeIs('dashboard.hof'),
            'enabled' => true,
        ],
        [
            'route' => 'dashboard.articles',
            'label' => __('Publikasi & Berita'),
            'icon' => 'newspaper',
            'active' => request()->routeIs('dashboard.articles*'),
            'enabled' => true,
        ],
        [
            'route' => 'dashboard.media',
            'label' => __('Pustaka Media'),
            'icon' => 'photo',
            'active' => request()->routeIs('dashboard.media*'),
            'enabled' => true,
        ],
        [
            'route' => 'public.rfc2350',
            'label' => __('RFC-2350'),
            'icon' => 'document-text',
            'active' => request()->routeIs('public.rfc2350'),
            'enabled' => true,
        ],
        [
            'route' => 'home',
            'label' => __('Beranda Publik'),
            'icon' => 'globe-alt',
            'active' => request()->routeIs('home'),
            'enabled' => true,
        ],
    ];

    $externalItems = [
        [
            'href' => 'https://pekanbaru.go.id',
            'label' => __('Pemko Pekanbaru'),
            'icon' => 'globe-alt',
        ],
    ];
@endphp

<div class="flex flex-col gap-1">
    @foreach ($mainItems as $item)
        @if ($item['enabled'])
            <a
                href="{{ route($item['route']) }}"
                wire:navigate
                aria-current="{{ $item['active'] ? 'page' : null }}"
                class="group relative flex items-center gap-3 rounded-lg py-2.5 text-sm font-medium transition-colors focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-primary {{ $item['active'] ? 'bg-primary-soft text-primary-content font-semibold before:absolute before:inset-y-1.5 before:start-0 before:w-1 before:rounded-e-full before:bg-primary' : 'text-app-text-secondary hover:bg-app-surface-muted hover:text-app-text' }}"
                @if ($mobile)
                    x-bind:class="'px-3'"
                    @click="closeMobileDrawer()"
                @else
                    x-bind:class="desktopCollapsed ? 'justify-center px-2' : 'px-3'"
                    aria-label="{{ $item['label'] }}"
                    @mouseenter="showSidebarTooltip($el)"
                    @mouseleave="scheduleTooltipHide()"
                    @focus="showSidebarTooltip($el)"
                    @blur="scheduleTooltipHide()"
                    @click="hideSidebarTooltip()"
                @endif
            >
                <flux:icon :name="$item['icon']" class="size-5 shrink-0 {{ $item['active'] ? 'text-primary' : 'text-app-text-muted group-hover:text-app-text' }}" />

                <span
                    class="sidebar-label-expand truncate flex-1"
                    @if (! $mobile)
                        x-show="!desktopCollapsed"
                    @endif
                >
                    {{ $item['label'] }}
                </span>
            </a>
        @endif
    @endforeach

    @if (! empty($externalItems))
        <div class="my-2 border-t border-app-border"></div>

        @foreach ($externalItems as $item)
            <a
                href="{{ $item['href'] }}"
                target="_blank"
                rel="noopener noreferrer"
                class="group relative flex items-center gap-3 rounded-lg py-2.5 text-sm font-medium text-app-text-muted hover:bg-app-surface-muted hover:text-app-text transition-colors focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-primary"
                @if ($mobile)
                    x-bind:class="'px-3'"
                @else
                    x-bind:class="desktopCollapsed ? 'justify-center px-2' : 'px-3'"
                    aria-label="{{ $item['label'] }}"
                    @mouseenter="showSidebarTooltip($el)"
                    @mouseleave="scheduleTooltipHide()"
                    @focus="showSidebarTooltip($el)"
                    @blur="scheduleTooltipHide()"
                    @click="hideSidebarTooltip()"
                @endif
            >
                <flux:icon :name="$item['icon']" class="size-5 shrink-0 text-app-text-muted group-hover:text-app-text" />
                <span
                    class="sidebar-label-expand truncate flex-1"
                    @if (! $mobile)
                        x-show="!desktopCollapsed"
                    @endif
                >
                    {{ $item['label'] }}
                </span>
                <span
                    class="sidebar-label-expand shrink-0"
                    @if (! $mobile)
                        x-show="!desktopCollapsed"
                    @endif
                >
                    <flux:icon.arrow-top-right-on-square class="size-3.5 text-app-text-muted opacity-70" />
                </span>
            </a>
        @endforeach
    @endif
</div>
