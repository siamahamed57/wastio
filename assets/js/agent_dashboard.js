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
    const statusText = statusToggle.querySelector('span');

    if (statusToggle) {
        statusToggle.addEventListener('click', () => {
           // Simulate Status Toggle for UI
           statusIndicator.classList.toggle('active');
           statusText.textContent = statusIndicator.classList.contains('active') ? 'Available' : 'Busy';
           
           // In real app, send AJAX request here
           // fetch('update_status.php', ...)
        });
    }
});
