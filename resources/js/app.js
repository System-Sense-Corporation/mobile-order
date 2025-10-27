import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.querySelector('[data-mobile-menu-toggle]');
    const menu = document.querySelector('[data-mobile-menu]');

    if (!toggleButton || !menu) {
        return;
    }

    const setExpanded = (expanded) => {
        toggleButton.setAttribute('aria-expanded', expanded ? 'true' : 'false');
        menu.classList.toggle('hidden', !expanded);
    };

    toggleButton.addEventListener('click', () => {
        const isExpanded = toggleButton.getAttribute('aria-expanded') === 'true';
        setExpanded(!isExpanded);
    });

    const mdMediaQuery = window.matchMedia('(min-width: 768px)');
    const handleViewportChange = (event) => {
        if (event.matches) {
            setExpanded(true);
        }
    };

    mdMediaQuery.addEventListener('change', handleViewportChange);

    setExpanded(mdMediaQuery.matches);
});
