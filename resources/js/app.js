import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.querySelector('[data-mobile-menu-toggle]');
    const menu = document.querySelector('[data-mobile-menu]');

    if (!toggleButton || !menu) {
        return;
    }

    const syncState = () => {
        const isExpanded = !menu.classList.contains('hidden');
        toggleButton.setAttribute('aria-expanded', String(isExpanded));
    };

    syncState();

    toggleButton.addEventListener('click', (event) => {
        event.preventDefault();
        const isHidden = menu.classList.toggle('hidden');
        toggleButton.setAttribute('aria-expanded', String(!isHidden));
    });
});
