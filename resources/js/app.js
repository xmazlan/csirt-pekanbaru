import './ui/navigation-transitions.js';
import { registerThemeToggle } from './theme-toggle.js';
import { registerLayoutControllers } from './ui/layout-controller.js';

const initAlpineExtensions = () => {
    if (window.Alpine) {
        registerThemeToggle(window.Alpine);
        registerLayoutControllers(window.Alpine);
    }
};

if (window.Alpine) {
    initAlpineExtensions();
} else {
    document.addEventListener('alpine:init', initAlpineExtensions, { once: true });
}

