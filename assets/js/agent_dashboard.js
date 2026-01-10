document.addEventListener('DOMContentLoaded', () => {
    // Sidebar Toggle Logic
    const container = document.querySelector('.dashboard-container');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');

    const isMobile = () => window.innerWidth <= 992;

    const applySidebarState = (isCollapsed) => {
        if (isCollapsed) {
            container.classList.add('sidebar-collapsed');
            if (isMobile()) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
        } else {
            container.classList.remove('sidebar-collapsed');
            if (isMobile()) {
                sidebar.classList.add('active');
                overlay.classList.add('active');
            }
        }
    };

    // Initial State on Load
    const storedState = localStorage.getItem('sidebarCollapsed');
    // On desktop: default visible (collapsed=false)
    // On mobile: default hidden (collapsed=true)
    let shouldBeCollapsed = (storedState === 'true');
    if (storedState === null) {
        shouldBeCollapsed = isMobile();
    }
    applySidebarState(shouldBeCollapsed);

    const toggleSidebar = () => {
        const currentlyCollapsed = container.classList.contains('sidebar-collapsed');
        const newState = !currentlyCollapsed;
        applySidebarState(newState);
        localStorage.setItem('sidebarCollapsed', newState);
    };

    // Attach listeners
    document.querySelectorAll('#mobileToggle, #sidebarToggle, #mobileClose').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            toggleSidebar();
        });
    });

    if (overlay) {
        overlay.addEventListener('click', (e) => {
            e.preventDefault();
            toggleSidebar();
        });
    }

    // Logout Confirmation
    const logoutLink = document.getElementById('logoutLink');
    if (logoutLink) {
        logoutLink.addEventListener('click', function (e) {
            if (!confirm('Are you sure you want to log out?')) {
                e.preventDefault();
            }
        });
    }

    // Interactive Status Toggle
    const statusToggle = document.querySelector('.status-toggle');
    const statusIndicator = document.querySelector('.status-indicator');
    const statusText = statusToggle ? statusToggle.querySelector('span') : null;

    if (statusToggle) {
        statusToggle.addEventListener('click', () => {
            // Simulate Status Toggle for UI
            statusIndicator.classList.toggle('active');
            statusText.textContent = statusIndicator.classList.contains('active') ? 'Available' : 'Busy';

            // In real app, send AJAX request here
            // fetch('update_status.php', ...)
        });
    }

    // Theme Switcher Logic
    const themeBtn = document.getElementById('themeToggle');
    const html = document.documentElement;
    const currentTheme = localStorage.getItem('theme');

    // Apply saved theme on load
    if (currentTheme) {
        html.setAttribute('data-theme', currentTheme);
        updateThemeIcon(currentTheme);
    }

    if (themeBtn) {
        themeBtn.addEventListener('click', () => {
            let theme = html.getAttribute('data-theme');
            let newTheme = theme === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        });
    }

    function updateThemeIcon(theme) {
        if (!themeBtn) return;
        if (theme === 'dark') {
            themeBtn.textContent = '☀️'; // Sun for light mode switch
            themeBtn.title = "Switch to Light Mode";
        } else {
            themeBtn.textContent = '🌙'; // Moon for dark mode switch
            themeBtn.title = "Switch to Dark Mode";
        }
    }
});
