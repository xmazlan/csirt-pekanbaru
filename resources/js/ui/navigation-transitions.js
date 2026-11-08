// resources/js/ui/navigation-transitions.js

let swapStyle = null;
let releaseFrame = null;

function clearSwapStyle() {
    if (releaseFrame !== null) cancelAnimationFrame(releaseFrame);
    releaseFrame = null;
    swapStyle?.remove();
    swapStyle = null;
}

// Saat navigasi Livewire dimulai, bekukan transisi CSS sementara untuk mencegah flicker
document.addEventListener('livewire:navigating', () => {
    clearSwapStyle();
    swapStyle = document.createElement('style');
    swapStyle.setAttribute('data-navigation-transitions', '');
    swapStyle.textContent = `
        *, *::before, *::after {
            transition-duration: 0s !important;
            transition-delay: 0s !important;
        }
    `;
    document.head.appendChild(swapStyle);
});

// Setelah navigasi Livewire selesai, pulihkan transisi setelah 2 requestAnimationFrame
document.addEventListener('livewire:navigated', () => {
    if (!swapStyle) return;
    if (releaseFrame !== null) cancelAnimationFrame(releaseFrame);
    releaseFrame = requestAnimationFrame(() => {
        releaseFrame = requestAnimationFrame(clearSwapStyle);
    });
});

window.addEventListener('pagehide', clearSwapStyle);

