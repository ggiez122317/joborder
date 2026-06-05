// Laravel Vite entry point. The PDS interface uses Blade and minimal inline JavaScript.

(() => {
    document.querySelectorAll('[data-toast]').forEach((toast) => {
        const close = toast.querySelector('[data-toast-close]');
        const dismiss = () => {
            toast.classList.add('toast-hide');
            window.setTimeout(() => toast.remove(), 220);
        };

        close?.addEventListener('click', dismiss);
        window.setTimeout(dismiss, 3600);
    });

    const shell = document.querySelector('.app-shell');
    const desktopToggle = document.getElementById('desktopSidebarToggle');
    const mobileToggle = document.getElementById('mobileSidebarToggle');
    const sidebar = document.getElementById('appSidebar');
    const sidebarBackdrop = document.getElementById('appSidebarBackdrop');

    if (!shell) {
        return;
    }

    const storageKey = 'pds-sidebar-collapsed';

    if (window.matchMedia('(min-width: 768px)').matches && localStorage.getItem(storageKey) === '1') {
        shell.classList.add('sidebar-collapsed');
    }

    desktopToggle?.addEventListener('click', () => {
        shell.classList.toggle('sidebar-collapsed');
        localStorage.setItem(storageKey, shell.classList.contains('sidebar-collapsed') ? '1' : '0');
    });

    mobileToggle?.addEventListener('click', () => {
        shell.classList.toggle('sidebar-open');
    });

    sidebarBackdrop?.addEventListener('click', () => {
        shell.classList.remove('sidebar-open');
    });

    document.addEventListener('click', (event) => {
        if (!shell.classList.contains('sidebar-open')) {
            return;
        }

        const target = event.target;

        if (sidebar?.contains(target) || mobileToggle?.contains(target)) {
            return;
        }

        shell.classList.remove('sidebar-open');
    });
})();
