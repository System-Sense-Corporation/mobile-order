import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.querySelector('[data-mobile-menu-toggle]');
    const drawer = document.querySelector('[data-mobile-menu]');
    const overlay = document.querySelector('[data-mobile-menu-overlay]');
    const closeButton = document.querySelector('[data-mobile-menu-close]');

    if (!toggleButton || !drawer || !overlay) {
        return;
    }

    const openIcons = toggleButton.querySelectorAll('[data-mobile-menu-icon="open"]');
    const closeIcons = toggleButton.querySelectorAll('[data-mobile-menu-icon="close"]');
    let isMenuOpen = drawer.classList.contains('mobile-menu-drawer-open');

    const updateButtonVisuals = (isExpanded) => {
        toggleButton.setAttribute('aria-expanded', String(isExpanded));
        openIcons.forEach((icon) => icon.classList.toggle('hidden', isExpanded));
        closeIcons.forEach((icon) => icon.classList.toggle('hidden', !isExpanded));
    };

    const handleEscape = (event) => {
        if (event.key === 'Escape') {
            setMenuState(false);
        }
    };

    const setMenuState = (isOpen) => {
        drawer.classList.toggle('mobile-menu-drawer-open', isOpen);
        overlay.classList.toggle('mobile-menu-overlay-open', isOpen);
        drawer.setAttribute('aria-modal', String(isOpen));
        drawer.setAttribute('aria-hidden', String(!isOpen));
        overlay.setAttribute('aria-hidden', String(!isOpen));
        updateButtonVisuals(isOpen);

        if (isOpen && !isMenuOpen) {
            document.addEventListener('keydown', handleEscape);
            drawer.focus();
        } else if (!isOpen && isMenuOpen) {
            document.removeEventListener('keydown', handleEscape);
            toggleButton.focus();
        }

        isMenuOpen = isOpen;
    };

    const toggleMenu = () => {
        setMenuState(!isMenuOpen);
    };

    const syncState = () => {
        const nextState = drawer.classList.contains('mobile-menu-drawer-open');
        setMenuState(nextState);
    };

    syncState();

    toggleButton.addEventListener('click', (event) => {
        event.preventDefault();
        toggleMenu();
    });

    overlay.addEventListener('click', (event) => {
        event.preventDefault();
        setMenuState(false);
    });

    if (closeButton) {
        closeButton.addEventListener('click', (event) => {
            event.preventDefault();
            setMenuState(false);
        });
    }

    const mediaQuery = window.matchMedia('(min-width: 768px)');
    const handleMediaChange = (event) => {
        if (event.matches) {
            setMenuState(false);
        }
    };

    if (typeof mediaQuery.addEventListener === 'function') {
        mediaQuery.addEventListener('change', handleMediaChange);
    } else if (typeof mediaQuery.addListener === 'function') {
        mediaQuery.addListener(handleMediaChange);
    }
});
