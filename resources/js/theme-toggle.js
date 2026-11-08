// resources/js/theme-toggle.js

export function registerThemeToggle(Alpine) {
    if (!Alpine || typeof Alpine.data !== 'function') return;
    if (Alpine._themeToggleRegistered) return;
    Alpine._themeToggleRegistered = true;

    Alpine.data('themeToggle', () => ({
        isDark: false,

        init() {
            this.updateState();

            // Pantau mutasi atribut class pada <html>
            if (typeof MutationObserver !== 'undefined' && typeof document !== 'undefined') {
                this._themeObserver = new MutationObserver(() => this.updateState());
                this._themeObserver.observe(document.documentElement, {
                    attributes: true,
                    attributeFilter: ['class', 'data-theme'],
                });
            }

            // Sinkronisasi lintas tab browser via Storage Event (Single Source of Truth: flux.appearance)
            this._storageHandler = (e) => {
                if (e.key === 'flux.appearance') {
                    const nextAppearance = e.newValue || 'system';
                    const flux = this.$flux || window.Flux;
                    if (flux && typeof flux.appearance !== 'undefined') {
                        flux.appearance = nextAppearance;
                    } else if (window.Flux && typeof window.Flux.applyAppearance === 'function') {
                        window.Flux.applyAppearance(nextAppearance);
                    }
                    this.updateState();
                }
            };
            window.addEventListener('storage', this._storageHandler);

            // Pantau perubahan preferensi tema OS saat mode 'system'
            this._mediaQuery = window.matchMedia?.('(prefers-color-scheme: dark)');
            this._mediaHandler = () => this.updateState();
            this._mediaQuery?.addEventListener?.('change', this._mediaHandler);

            // Sinkronisasi setelah navigasi Livewire selesai
            this._navigatedHandler = () => this.updateState();
            document.addEventListener('livewire:navigated', this._navigatedHandler);
        },

        destroy() {
            if (this._themeObserver) this._themeObserver.disconnect();
            if (this._storageHandler) window.removeEventListener('storage', this._storageHandler);
            if (this._mediaQuery && this._mediaHandler) {
                this._mediaQuery.removeEventListener?.('change', this._mediaHandler);
            }
            if (this._navigatedHandler) {
                document.removeEventListener('livewire:navigated', this._navigatedHandler);
            }
        },

        updateState() {
            const flux = this.$flux || window.Flux;
            if (flux && typeof flux.dark === 'boolean') {
                this.isDark = flux.dark;
            } else if (flux && flux.appearance) {
                this.isDark =
                    flux.appearance === 'system'
                        ? Boolean(window.matchMedia?.('(prefers-color-scheme: dark)').matches)
                        : flux.appearance === 'dark';
            } else {
                this.isDark = document.documentElement.classList.contains('dark');
            }
        },

        toggle(event) {
            const willBeDark = !this.isDark;
            const targetTheme = willBeDark ? 'dark' : 'light';

            const applyTheme = () => {
                const flux = this.$flux || window.Flux;
                if (flux && typeof flux.appearance !== 'undefined') {
                    flux.appearance = targetTheme;
                } else if (window.Flux && typeof window.Flux.applyAppearance === 'function') {
                    window.Flux.applyAppearance(targetTheme);
                } else {
                    document.documentElement.classList.toggle('dark', willBeDark);
                }

                this.updateState();
            };

            const prefersReducedMotion =
                typeof window !== 'undefined' &&
                window.matchMedia?.('(prefers-reduced-motion: reduce)')?.matches;

            // Fallback tanpa animasi jika View Transition tidak didukung atau pengguna meminta reduced motion
            if (typeof document === 'undefined' || !document.startViewTransition || prefersReducedMotion) {
                applyTheme();
                return;
            }

            // 1. Hitung titik koordinat pusat klik tombol (X, Y)
            let x, y;
            const target = event?.currentTarget || this.$el;
            if (target && typeof target.getBoundingClientRect === 'function') {
                const rect = target.getBoundingClientRect();
                x = rect.left + rect.width / 2;
                y = rect.top + rect.height / 2;
            } else if (event?.clientX && event?.clientY) {
                x = event.clientX;
                y = event.clientY;
            } else {
                x = window.innerWidth / 2;
                y = window.innerHeight / 2;
            }

            // 2. Hitung radius lingkaran hingga ke sudut layar terjauh (Pythagoras)
            const maxRadius = Math.hypot(
                Math.max(x, window.innerWidth - x),
                Math.max(y, window.innerHeight - y)
            );

            // 3. Jalankan View Transition dengan Web Animations API clipPath
            const transition = document.startViewTransition(() => {
                applyTheme();
            });

            transition.ready
                .then(() => {
                    document.documentElement.animate(
                        {
                            clipPath: [
                                `circle(0px at ${x}px ${y}px)`,
                                `circle(${maxRadius}px at ${x}px ${y}px)`,
                            ],
                        },
                        {
                            duration: 600,
                            easing: 'ease-in-out',
                            pseudoElement: '::view-transition-new(root)',
                        }
                    );
                })
                .catch(() => {});
        },
    }));
}

