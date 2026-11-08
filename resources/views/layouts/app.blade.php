<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        @include('partials.head')
    </head>
    <body
        data-app-shell
        class="min-h-full bg-app-bg text-app-text antialiased selection:bg-primary-soft selection:text-primary-content flex flex-col"
        x-data="appLayout()"
        x-on:resize.window.debounce.100ms="handleResize()"
        x-on:keydown.escape.window="handleEscape($event)"
        x-on:scroll.window="positionSidebarTooltip()"
        x-on:livewire:navigating.document="cleanupLayout()"
    >
        <!-- Aksesibilitas: Lewati ke konten utama -->
        <a
            href="#main-content"
            class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:px-4 focus:py-2.5 focus:bg-primary focus:text-primary-foreground focus:font-medium focus:rounded-lg focus:shadow-lg focus:outline-hidden focus:ring-2 focus:ring-primary focus:ring-offset-2"
        >
            {{ __('Lewati ke konten utama') }}
        </a>

        <!-- Header Penuh Selebar Viewport -->
        <header
            class="sticky top-0 z-30 flex h-14 w-full items-center justify-between border-b border-app-border bg-app-surface px-4 lg:px-6 shadow-2xs"
            role="banner"
            x-bind:inert="mobileOpen"
        >
            <!-- Sisi Kiri: Toggle Menu & Identitas CSIRT Pekanbaru -->
            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                <!-- Mobile Menu Button (Hamburger) -->
                <button
                    type="button"
                    x-ref="mobileTrigger"
                    @click="toggleMobileDrawer()"
                    x-bind:aria-expanded="mobileOpen ? 'true' : 'false'"
                    aria-controls="mobile-drawer"
                    aria-label="{{ __('Buka atau tutup menu navigasi') }}"
                    class="lg:hidden flex size-10 items-center justify-center rounded-lg border border-app-border text-app-text-secondary hover:bg-app-surface-muted hover:text-app-text focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-colors cursor-pointer"
                >
                    <template x-if="!mobileOpen">
                        <flux:icon.bars-3 class="size-5" />
                    </template>
                    <template x-if="mobileOpen">
                        <flux:icon.x-mark class="size-5" />
                    </template>
                </button>

                <!-- Desktop Sidebar Collapse/Expand Toggle -->
                <button
                    type="button"
                    @click="hideSidebarTooltip(); toggleDesktopSidebar()"
                    x-ref="desktopTrigger"
                    x-bind:aria-expanded="!desktopCollapsed ? 'true' : 'false'"
                    aria-controls="desktop-sidebar"
                    x-bind:aria-label="desktopCollapsed ? '{{ __('Perluas menu samping') }}' : '{{ __('Perkecil menu samping') }}'"
                    @mouseenter="showSidebarTooltip($el)"
                    @mouseleave="scheduleTooltipHide()"
                    @focus="showSidebarTooltip($el)"
                    @blur="scheduleTooltipHide()"
                    class="max-lg:hidden flex size-10 items-center justify-center rounded-lg border border-app-border text-app-text-secondary hover:bg-app-surface-muted hover:text-app-text focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-colors cursor-pointer"
                >
                    <template x-if="!desktopCollapsed">
                        <flux:icon.bars-3-bottom-left class="size-5" />
                    </template>
                    <template x-if="desktopCollapsed">
                        <flux:icon.bars-3 class="size-5" />
                    </template>
                </button>

                <!-- Logo & Identitas Aplikasi (Menuju Beranda/Publik) -->
                <x-app-logo
                    :href="route('home')"
                    size="sm"
                    textClass="hidden sm:flex flex-col text-start"
                    :name="config('app.name', 'CSIRT Pekanbaru')"
                    :subtitle="__('Pemerintah Kota Pekanbaru')"
                />
            </div>

            <!-- Sisi Kanan: Kontrol Tema Tunggal & Menu Profil Kanan Atas -->
            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                <!-- Theme Toggle Tunggal di Header -->
                <div class="flex items-center shrink-0">
                    <x-theme-toggle size="sm" />
                </div>

                <!-- Menu Akun Pengguna / Profil Kanan Atas -->
                @auth
                    <flux:dropdown position="bottom" align="end">
                        <button
                            type="button"
                            class="flex min-h-[40px] items-center gap-2 rounded-lg py-1 px-2 text-start text-sm hover:bg-app-surface-muted focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-colors cursor-pointer"
                            aria-label="{{ __('Menu pengguna: ') . auth()->user()->name }}"
                        >
                            <div class="flex aspect-square size-8 items-center justify-center rounded-full bg-primary-soft text-primary font-semibold text-xs border border-primary/20 shrink-0">
                                {{ auth()->user()->initials() }}
                            </div>
                            <div class="max-md:hidden flex flex-col text-start">
                                <span class="text-xs font-semibold text-app-text truncate max-w-[130px]">{{ auth()->user()->name }}</span>
                                <span class="text-[10px] text-app-text-muted truncate max-w-[130px]">{{ auth()->user()->email }}</span>
                            </div>
                            <flux:icon.chevron-down class="size-3.5 text-app-text-muted max-md:hidden" />
                        </button>

                        <flux:menu class="w-64 p-1">
                            <div class="flex items-center gap-2.5 px-3 py-2.5 border-b border-app-border mb-1">
                                <div class="flex aspect-square size-9 items-center justify-center rounded-full bg-primary-soft text-primary font-semibold text-xs border border-primary/20 shrink-0">
                                    {{ auth()->user()->initials() }}
                                </div>
                                <div class="grid flex-1 text-start text-xs leading-tight min-w-0">
                                    <span class="font-semibold text-app-text truncate">{{ auth()->user()->name }}</span>
                                    <span class="text-app-text-muted truncate text-[11px]">{{ auth()->user()->email }}</span>
                                </div>
                            </div>

                            <flux:menu.item :href="route('profile.edit')" icon="user" wire:navigate class="rounded-md">
                                {{ __('Profil') }}
                            </flux:menu.item>
                            <flux:menu.item :href="route('security.edit')" icon="shield-check" wire:navigate class="rounded-md">
                                {{ __('Keamanan') }}
                            </flux:menu.item>
                            <flux:menu.item :href="route('appearance.edit')" icon="swatch" wire:navigate class="rounded-md">
                                {{ __('Tampilan') }}
                            </flux:menu.item>

                            <flux:menu.separator class="my-1 border-app-border" />

                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <flux:menu.item
                                    as="button"
                                    type="submit"
                                    icon="arrow-right-start-on-rectangle"
                                    class="w-full cursor-pointer text-status-danger hover:bg-status-danger-soft rounded-md"
                                    data-test="logout-button"
                                >
                                    {{ __('Keluar') }}
                                </flux:menu.item>
                            </form>
                        </flux:menu>
                    </flux:dropdown>
                @endauth
            </div>
        </header>

        <!-- Wadah Baris Bawah: Sidebar (Kiri) dan Konten Utama (Kanan) -->
        <div class="flex flex-1 w-full min-h-[calc(100vh-3.5rem)] relative">
            <!-- 1. DESKTOP SIDEBAR COLLAPSIBLE -->
            <aside
                id="desktop-sidebar"
                @scroll="positionSidebarTooltip()"
                class="max-lg:hidden sticky top-14 h-[calc(100vh-3.5rem)] max-h-[calc(100vh-3.5rem)] border-e border-app-border bg-app-surface transition-[width] duration-200 ease-in-out shrink-0 flex flex-col justify-between overflow-y-auto overscroll-contain z-20"
                x-bind:class="desktopCollapsed ? 'w-16 px-2 py-4' : 'w-64 px-4 py-4'"
                role="navigation"
                aria-label="{{ __('Navigasi utama desktop') }}"
            >
                <!-- Grup Navigasi Atas -->
                <div class="flex flex-col gap-5">
                    <div>
                        <div
                            class="sidebar-label-expand px-2 pb-1.5 text-[11px] font-semibold uppercase tracking-wider text-app-text-muted"
                            x-show="!desktopCollapsed"
                            x-transition:enter="transition-opacity duration-150"
                        >
                            {{ __('Menu Utama') }}
                        </div>

                        <nav class="flex flex-col gap-1">
                            <x-csirt-navigation />
                        </nav>
                    </div>
                </div>

                <!-- Grup Navigasi Bawah (Pengaturan & Keluar) -->
                <div class="flex flex-col gap-1 border-t border-app-border pt-3">
                    <div
                        class="sidebar-label-expand px-2 pb-1 text-[11px] font-semibold uppercase tracking-wider text-app-text-muted"
                        x-show="!desktopCollapsed"
                        x-transition:enter="transition-opacity duration-150"
                    >
                        {{ __('Pengaturan') }}
                    </div>

                    <!-- Profil -->
                    <a
                        href="{{ route('profile.edit') }}"
                        aria-current="{{ request()->routeIs('profile.edit') ? 'page' : null }}"
                        wire:navigate
                        class="group relative flex items-center gap-3 rounded-lg py-2.5 text-sm font-medium transition-colors focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-primary {{ request()->routeIs('profile.edit') ? 'bg-primary-soft text-primary-content font-semibold before:absolute before:inset-y-1.5 before:start-0 before:w-1 before:rounded-e-full before:bg-primary' : 'text-app-text-secondary hover:bg-app-surface-muted hover:text-app-text' }}"
                        x-bind:class="desktopCollapsed ? 'justify-center px-2' : 'px-3'"
                        aria-label="{{ __('Profil') }}"
                        @mouseenter="showSidebarTooltip($el)"
                        @mouseleave="scheduleTooltipHide()"
                        @focus="showSidebarTooltip($el)"
                        @blur="scheduleTooltipHide()"
                        @click="hideSidebarTooltip()"
                    >
                        <flux:icon.user class="size-5 shrink-0 {{ request()->routeIs('profile.edit') ? 'text-primary' : 'text-app-text-muted group-hover:text-app-text' }}" />
                        <span class="sidebar-label-expand truncate" x-show="!desktopCollapsed">{{ __('Profil') }}</span>
                    </a>

                    <!-- Keamanan -->
                    <a
                        href="{{ route('security.edit') }}"
                        aria-current="{{ request()->routeIs('security.edit') ? 'page' : null }}"
                        wire:navigate
                        class="group relative flex items-center gap-3 rounded-lg py-2.5 text-sm font-medium transition-colors focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-primary {{ request()->routeIs('security.edit') ? 'bg-primary-soft text-primary-content font-semibold before:absolute before:inset-y-1.5 before:start-0 before:w-1 before:rounded-e-full before:bg-primary' : 'text-app-text-secondary hover:bg-app-surface-muted hover:text-app-text' }}"
                        x-bind:class="desktopCollapsed ? 'justify-center px-2' : 'px-3'"
                        aria-label="{{ __('Keamanan') }}"
                        @mouseenter="showSidebarTooltip($el)"
                        @mouseleave="scheduleTooltipHide()"
                        @focus="showSidebarTooltip($el)"
                        @blur="scheduleTooltipHide()"
                        @click="hideSidebarTooltip()"
                    >
                        <flux:icon.shield-check class="size-5 shrink-0 {{ request()->routeIs('security.edit') ? 'text-primary' : 'text-app-text-muted group-hover:text-app-text' }}" />
                        <span class="sidebar-label-expand truncate" x-show="!desktopCollapsed">{{ __('Keamanan') }}</span>
                    </a>

                    <!-- Tampilan Tema -->
                    <a
                        href="{{ route('appearance.edit') }}"
                        aria-current="{{ request()->routeIs('appearance.edit') ? 'page' : null }}"
                        wire:navigate
                        class="group relative flex items-center gap-3 rounded-lg py-2.5 text-sm font-medium transition-colors focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-primary {{ request()->routeIs('appearance.edit') ? 'bg-primary-soft text-primary-content font-semibold before:absolute before:inset-y-1.5 before:start-0 before:w-1 before:rounded-e-full before:bg-primary' : 'text-app-text-secondary hover:bg-app-surface-muted hover:text-app-text' }}"
                        x-bind:class="desktopCollapsed ? 'justify-center px-2' : 'px-3'"
                        aria-label="{{ __('Tampilan Tema') }}"
                        @mouseenter="showSidebarTooltip($el)"
                        @mouseleave="scheduleTooltipHide()"
                        @focus="showSidebarTooltip($el)"
                        @blur="scheduleTooltipHide()"
                        @click="hideSidebarTooltip()"
                    >
                        <flux:icon.swatch class="size-5 shrink-0 {{ request()->routeIs('appearance.edit') ? 'text-primary' : 'text-app-text-muted group-hover:text-app-text' }}" />
                        <span class="sidebar-label-expand truncate" x-show="!desktopCollapsed">{{ __('Tampilan Tema') }}</span>
                    </a>

                    <!-- Tombol Keluar -->
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button
                            type="submit"
                            class="group relative flex w-full items-center gap-3 rounded-lg py-2.5 text-sm font-medium text-status-danger hover:bg-status-danger-soft transition-colors cursor-pointer focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-primary"
                            x-bind:class="desktopCollapsed ? 'justify-center px-2' : 'px-3'"
                            aria-label="{{ __('Keluar') }}"
                            @mouseenter="showSidebarTooltip($el)"
                            @mouseleave="scheduleTooltipHide()"
                            @focus="showSidebarTooltip($el)"
                            @blur="scheduleTooltipHide()"
                            @click="hideSidebarTooltip()"
                        >
                            <flux:icon.arrow-right-start-on-rectangle class="size-5 shrink-0" />
                            <span class="sidebar-label-expand truncate" x-show="!desktopCollapsed">{{ __('Keluar') }}</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- 2. MOBILE DRAWER OVERLAY -->
            <div
                x-show="mobileOpen"
                x-cloak
                x-transition:enter="transition-opacity ease-linear duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="closeMobileDrawer()"
                class="fixed inset-0 z-40 bg-black/50 backdrop-blur-xs lg:hidden"
                aria-hidden="true"
            ></div>

            <div
                id="mobile-drawer"
                x-ref="mobileDrawer"
                x-show="mobileOpen"
                x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                @keydown="handleDrawerKeydown($event)"
                class="fixed inset-y-0 start-0 z-50 flex w-72 max-w-[85vw] flex-col justify-between border-e border-app-border bg-app-surface p-4 shadow-xl lg:hidden overflow-y-auto"
                role="dialog"
                aria-modal="true"
                aria-label="{{ __('Menu navigasi mobile') }}"
            >
                <div class="flex flex-col gap-4">
                    <!-- Drawer Header -->
                    <div class="flex items-center justify-between pb-3 border-b border-app-border">
                        <x-app-logo :href="route('home')" size="sm" />
                        <button
                            type="button"
                            x-ref="closeButton"
                            @click="closeMobileDrawer()"
                            aria-label="{{ __('Tutup menu') }}"
                            class="flex size-9 items-center justify-center rounded-lg border border-app-border text-app-text-secondary hover:bg-app-surface-muted hover:text-app-text focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-colors cursor-pointer"
                        >
                            <flux:icon.x-mark class="size-5" />
                        </button>
                    </div>

                    <!-- Navigasi Mobile -->
                    <nav class="flex flex-col gap-1">
                        <x-csirt-navigation :mobile="true" />

                        <div class="my-2 border-t border-app-border"></div>

                        <a
                            href="{{ route('profile.edit') }}"
                            aria-current="{{ request()->routeIs('profile.edit') ? 'page' : null }}"
                            wire:navigate
                            @click="closeMobileDrawer()"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('profile.edit') ? 'bg-primary-soft text-primary-content font-semibold' : 'text-app-text-secondary hover:bg-app-surface-muted hover:text-app-text' }}"
                        >
                            <flux:icon.user class="size-5 shrink-0 {{ request()->routeIs('profile.edit') ? 'text-primary' : 'text-app-text-muted' }}" />
                            <span>{{ __('Profil') }}</span>
                        </a>

                        <a
                            href="{{ route('security.edit') }}"
                            aria-current="{{ request()->routeIs('security.edit') ? 'page' : null }}"
                            wire:navigate
                            @click="closeMobileDrawer()"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('security.edit') ? 'bg-primary-soft text-primary-content font-semibold' : 'text-app-text-secondary hover:bg-app-surface-muted hover:text-app-text' }}"
                        >
                            <flux:icon.shield-check class="size-5 shrink-0 {{ request()->routeIs('security.edit') ? 'text-primary' : 'text-app-text-muted' }}" />
                            <span>{{ __('Keamanan') }}</span>
                        </a>

                        <a
                            href="{{ route('appearance.edit') }}"
                            aria-current="{{ request()->routeIs('appearance.edit') ? 'page' : null }}"
                            wire:navigate
                            @click="closeMobileDrawer()"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('appearance.edit') ? 'bg-primary-soft text-primary-content font-semibold' : 'text-app-text-secondary hover:bg-app-surface-muted hover:text-app-text' }}"
                        >
                            <flux:icon.swatch class="size-5 shrink-0 {{ request()->routeIs('appearance.edit') ? 'text-primary' : 'text-app-text-muted' }}" />
                            <span>{{ __('Tampilan Tema') }}</span>
                        </a>
                    </nav>
                </div>

                <!-- Footer Drawer Mobile -->
                <div class="border-t border-app-border pt-3">
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button
                            type="submit"
                            class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-status-danger hover:bg-status-danger-soft transition-colors cursor-pointer"
                        >
                            <flux:icon.arrow-right-start-on-rectangle class="size-5 shrink-0" />
                            <span>{{ __('Keluar dari Aplikasi') }}</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- 3. KONTEN UTAMA -->
            <main
                id="main-content"
                x-bind:inert="mobileOpen"
                class="flex-1 w-full min-w-0 p-4 sm:p-6 lg:p-8 overflow-x-hidden"
                role="main"
                tabindex="-1"
            >
                <div class="mx-auto w-full max-w-7xl">
                    {{ $slot }}
                </div>
            </main>
        </div>

        <!-- Single top-layer tooltip for rail sidebar items -->
        <div
            id="sidebar-tooltip"
            x-ref="sidebarTooltip"
            popover="manual"
            role="tooltip"
            class="sidebar-tooltip"
            @mouseenter="cancelTooltipHide()"
            @mouseleave="scheduleTooltipHide()"
        ><span x-text="tooltipText"></span></div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
        @livewireScripts
    </body>
</html>
