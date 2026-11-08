export function registerLayoutControllers(Alpine) {
    if (!Alpine || typeof Alpine.data !== 'function') return;
    if (Alpine._appLayoutRegistered) return;
    Alpine._appLayoutRegistered = true;

    Alpine.data('appLayout', () => ({
        desktopCollapsed: false,
        mobileOpen: false,
        originalOverflow: '',
        tooltipText: '',
        tooltipTrigger: null,
        tooltipTimer: null,

        cancelTooltipHide() {
            clearTimeout(this.tooltipTimer);
        },

        showSidebarTooltip(trigger) {
            if (!this.desktopCollapsed || window.innerWidth < 1024) return;
            this.hideSidebarTooltip();
            this.tooltipTrigger = trigger;
            this.tooltipText = trigger.getAttribute('aria-label') || '';
            trigger.setAttribute('aria-describedby', 'sidebar-tooltip');
            this.$nextTick(() => {
                if (this.tooltipTrigger !== trigger) return;
                const tip = this.$refs.sidebarTooltip;
                if (tip && typeof tip.showPopover === 'function') {
                    tip.showPopover();
                    this.positionSidebarTooltip();
                }
            });
        },

        positionSidebarTooltip() {
            const trigger = this.tooltipTrigger;
            const tip = this.$refs.sidebarTooltip;
            if (!trigger || !tip?.matches?.(':popover-open')) return;
            const rect = trigger.getBoundingClientRect();
            const sidebar = trigger.closest('#desktop-sidebar');
            const bounds = sidebar?.getBoundingClientRect();
            if (
                window.innerWidth < 1024 ||
                !this.desktopCollapsed ||
                !trigger.isConnected ||
                rect.bottom <= (bounds?.top ?? 0) ||
                rect.top >= (bounds?.bottom ?? window.innerHeight)
            ) {
                this.hideSidebarTooltip();
                return;
            }
            const box = tip.getBoundingClientRect();
            tip.style.left = `${Math.max(8, Math.min(rect.right + 4, window.innerWidth - box.width - 8))}px`;
            tip.style.top = `${Math.max(8, Math.min(rect.top + (rect.height - box.height) / 2, window.innerHeight - box.height - 8))}px`;
        },

        scheduleTooltipHide() {
            this.cancelTooltipHide();
            this.tooltipTimer = setTimeout(() => {
                const tip = this.$refs.sidebarTooltip;
                if (this.tooltipTrigger?.matches?.(':hover, :focus') || tip?.matches?.(':hover')) return;
                this.hideSidebarTooltip();
            }, 120);
        },

        hideSidebarTooltip() {
            this.cancelTooltipHide();
            this.tooltipTrigger?.removeAttribute('aria-describedby');
            this.tooltipTrigger = null;
            if (this.$refs.sidebarTooltip && typeof this.$refs.sidebarTooltip.hidePopover === 'function') {
                this.$refs.sidebarTooltip.hidePopover();
            }
        },

        cleanupLayout() {
            this.hideSidebarTooltip();
            if (this.mobileOpen) {
                document.body.style.overflow = this.originalOverflow;
            }
        },

        destroy() {
            this.cleanupLayout();
        },

        init() {
            try {
                const stored = localStorage.getItem('flux-sidebar-collapsed-desktop');
                this.desktopCollapsed = stored === 'true';
            } catch (e) {
                this.desktopCollapsed = false;
            }
            this.$nextTick(() => {
                document.documentElement.classList.remove('sidebar-collapsed-init');
            });
            this.handleResize();
        },

        toggleDesktopSidebar() {
            this.hideSidebarTooltip();
            this.desktopCollapsed = !this.desktopCollapsed;
            try {
                localStorage.setItem('flux-sidebar-collapsed-desktop', String(this.desktopCollapsed));
            } catch (e) {}
        },

        toggleMobileDrawer() {
            this.mobileOpen ? this.closeMobileDrawer() : this.openMobileDrawer();
        },

        openMobileDrawer() {
            this.mobileOpen = true;
            this.originalOverflow = document.body.style.overflow || '';
            document.body.style.overflow = 'hidden';
            this.$nextTick(() => this.$refs.closeButton?.focus());
        },

        closeMobileDrawer() {
            if (!this.mobileOpen) return;
            this.mobileOpen = false;
            document.body.style.overflow = this.originalOverflow;
            this.$nextTick(() => this.$refs.mobileTrigger?.focus());
        },

        handleResize() {
            this.hideSidebarTooltip();
            if (window.innerWidth >= 1024 && this.mobileOpen) {
                this.mobileOpen = false;
                document.body.style.overflow = this.originalOverflow;
                this.$nextTick(() => this.$refs.desktopTrigger?.focus());
            }
        },

        handleEscape(event) {
            if (this.tooltipTrigger) {
                this.hideSidebarTooltip();
                event.preventDefault();
                return;
            }
            if (this.mobileOpen) {
                this.closeMobileDrawer();
                event.stopPropagation();
            }
        },

        handleDrawerKeydown(event) {
            if (!this.mobileOpen || event.key !== 'Tab') return;
            const drawer = this.$refs.mobileDrawer;
            if (!drawer) return;
            const focusables = Array.from(
                drawer.querySelectorAll('button, [href], input, select, textarea, [tabindex]')
            ).filter((el) => el.offsetParent !== null && !el.disabled && el.tabIndex >= 0);
            if (focusables.length === 0) return;
            const first = focusables[0];
            const last = focusables[focusables.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                last.focus();
                event.preventDefault();
            } else if (!event.shiftKey && document.activeElement === last) {
                first.focus();
                event.preventDefault();
            }
        },
    }));
}

