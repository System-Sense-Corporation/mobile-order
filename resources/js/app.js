import './bootstrap';

function initMobileMenu() {
    const toggleButton = document.querySelector('[data-mobile-menu-toggle]');
    const drawer = document.querySelector('[data-mobile-menu-panel]');
    const overlay = document.querySelector('[data-mobile-menu-overlay]');
    const closeButton = document.querySelector('[data-mobile-menu-close]');
    const openIcon = toggleButton?.querySelector('[data-mobile-menu-icon="open"]');
    const closeIcon = toggleButton?.querySelector('[data-mobile-menu-icon="close"]');

    if (!toggleButton || !drawer || !overlay) {
        return;
    }

    if (drawer.dataset.mobileMenuInitialized === 'true') {
        return;
    }

    drawer.dataset.mobileMenuInitialized = 'true';

    const setExpanded = (expanded) => {
        drawer.classList.toggle('drawer-panel--open', expanded);
        overlay.classList.toggle('drawer-overlay--visible', expanded);
        drawer.setAttribute('aria-hidden', String(!expanded));
        toggleButton.setAttribute('aria-expanded', String(expanded));

        if (openIcon) {
            openIcon.classList.toggle('hidden', expanded);
        }

        if (closeIcon) {
            closeIcon.classList.toggle('hidden', !expanded);
        }
    };

    const isExpanded = () => drawer.classList.contains('drawer-panel--open');

    setExpanded(false);

    toggleButton.addEventListener('click', (event) => {
        event.preventDefault();
        setExpanded(!isExpanded());
    });

    overlay.addEventListener('click', () => {
        setExpanded(false);
    });

    if (closeButton) {
        closeButton.addEventListener('click', () => {
            setExpanded(false);
        });
    }

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isExpanded()) {
            setExpanded(false);
        }
    });
}

if (document.readyState !== 'loading') {
    initMobileMenu();
} else {
    document.addEventListener('DOMContentLoaded', initMobileMenu, { once: true });
}

import.meta.hot?.accept(() => {
    initMobileMenu();
});
