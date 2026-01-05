document.addEventListener('DOMContentLoaded', () => {
    // Mobile Sidebar Toggle
    const mobileToggle = document.getElementById('mobileToggle');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');

    if (mobileToggle) {
        mobileToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
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
