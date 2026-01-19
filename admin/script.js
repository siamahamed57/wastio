// Theme Management
const themeToggle = document.getElementById('themeToggle');
const html = document.documentElement;

// Load saved theme
const savedTheme = localStorage.getItem('theme') || 'light';
html.setAttribute('data-theme', savedTheme);
updateThemeIcon(savedTheme);

themeToggle.addEventListener('click', () => {
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    html.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    updateThemeIcon(newTheme);
});

function updateThemeIcon(theme) {
    const icon = themeToggle.querySelector('i');
    icon.className = theme === 'light' ? 'fas fa-moon' : 'fas fa-sun';
}

// Tab Management
const tabButtons = document.querySelectorAll('.tab-btn');
const tabContents = document.querySelectorAll('.tab-content');

tabButtons.forEach(button => {
    button.addEventListener('click', () => {
        const tabName = button.getAttribute('data-tab');

        tabButtons.forEach(btn => btn.classList.remove('active'));
        tabContents.forEach(content => content.classList.remove('active'));

        button.classList.add('active');
        document.getElementById(`${tabName}-tab`).classList.add('active');
    });
});

// Global Variables
let users = [];
let wasteItems = [];
let buyRequests = [];
let deleteCallback = null;

// Initialize Dashboard
document.addEventListener('DOMContentLoaded', () => {
    loadStats();
    loadUsers();
    loadWasteItems();
    loadBuyRequests();

    // Filter listeners
    document.getElementById('roleFilter').addEventListener('change', filterUsers);
    document.getElementById('statusFilter').addEventListener('change', filterUsers);
});

// Load Statistics
async function loadStats() {
    try {
        const response = await fetch('api/get_admin_stats.php');
        const result = await response.json();

        if (result.success) {
            document.getElementById('totalUsers').textContent = result.data.total_users;
            document.getElementById('pendingApprovals').textContent = result.data.pending_approvals;
            document.getElementById('totalWasteItems').textContent = result.data.total_waste_items;
            document.getElementById('totalRequests').textContent = result.data.total_requests;
        }
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

// Load Users
async function loadUsers() {
    try {
        const response = await fetch('api/get_users.php');
        const result = await response.json();

        if (result.success) {
            users = result.data;
            displayUsers(users);
        } else {
            document.getElementById('usersTableBody').innerHTML = '<tr><td colspan="7">No users found</td></tr>';
        }
    } catch (error) {
        console.error('Error loading users:', error);
        document.getElementById('usersTableBody').innerHTML = '<tr><td colspan="7">Error loading users</td></tr>';
    }
}

// Display Users
function displayUsers(usersToDisplay) {
    const tbody = document.getElementById('usersTableBody');

    if (usersToDisplay.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7">No users found</td></tr>';
        return;
    }

    tbody.innerHTML = usersToDisplay.map(user => {
        const approvedBadge = user.is_approved == 1 ?
            '<span class="badge badge-success">Approved</span>' :
            '<span class="badge badge-warning">Pending</span>';

        const blockedBadge = user.is_blocked == 1 ?
            '<span class="badge badge-danger">Blocked</span>' : '';

        return `
            <tr>
                <td>${user.user_id}</td>
                <td>${user.full_name}</td>
                <td>${user.email}</td>
                <td>${user.phone || 'N/A'}</td>
                <td><span class="badge badge-info">${user.role_name}</span></td>
                <td>${approvedBadge} ${blockedBadge}</td>
                <td>
                    <div class="action-btns">
                        ${user.is_approved == 0 ? `<button class="btn btn-success btn-sm" onclick="approveUser(${user.user_id})"><i class="fas fa-check"></i> Approve</button>` : ''}
                        ${user.is_blocked == 0 ?
                `<button class="btn btn-warning btn-sm" onclick="blockUser(${user.user_id}, true)"><i class="fas fa-ban"></i> Block</button>` :
                `<button class="btn btn-success btn-sm" onclick="blockUser(${user.user_id}, false)"><i class="fas fa-check"></i> Unblock</button>`
            }
                        <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.user_id})"><i class="fas fa-trash"></i> Delete</button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

// Filter Users
function filterUsers() {
    const roleFilter = document.getElementById('roleFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;

    let filtered = users;

    if (roleFilter) {
        filtered = filtered.filter(u => u.role_name === roleFilter);
    }

    if (statusFilter === 'approved') {
        filtered = filtered.filter(u => u.is_approved == 1 && u.is_blocked == 0);
    } else if (statusFilter === 'pending') {
        filtered = filtered.filter(u => u.is_approved == 0);
    } else if (statusFilter === 'blocked') {
        filtered = filtered.filter(u => u.is_blocked == 1);
    }

    displayUsers(filtered);
}

// Approve User
async function approveUser(userId) {
    try {
        const response = await fetch('api/approve_user.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: userId })
        });

        const result = await response.json();

        if (result.success) {
            showToast(result.message, 'success');
            loadUsers();
            loadStats();
        } else {
            showToast(result.message, 'error');
        }
    } catch (error) {
        showToast('An error occurred', 'error');
        console.error('Error:', error);
    }
}

// Block/Unblock User
async function blockUser(userId, blockStatus) {
    const action = blockStatus ? 'block' : 'unblock';
    const message = blockStatus ?
        'Are you sure you want to block this user? They will not be able to access the system.' :
        'Are you sure you want to unblock this user? They will regain access to the system.';

    showBlockModal(message, action, async () => {
        try {
            const response = await fetch('api/block_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ user_id: userId, block_status: blockStatus })
            });

            const result = await response.json();

            if (result.success) {
                showToast(result.message, 'success');
                loadUsers();
                loadStats();
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('An error occurred', 'error');
            console.error('Error:', error);
        }
    });
}

// Delete User
async function deleteUser(userId) {
    showDeleteModal('Are you sure you want to delete this user? This action cannot be undone.', async () => {
        try {
            const response = await fetch('api/delete_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ user_id: userId })
            });

            const result = await response.json();

            if (result.success) {
                showToast(result.message, 'success');
                loadUsers();
                loadStats();
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('An error occurred', 'error');
            console.error('Error:', error);
        }
    });
}

// Load Waste Items
async function loadWasteItems() {
    try {
        const response = await fetch('api/get_all_waste.php');
        const result = await response.json();

        if (result.success) {
            wasteItems = result.data;
            displayWasteItems(wasteItems);
        } else {
            document.getElementById('wasteTableBody').innerHTML = '<tr><td colspan="8">No waste items found</td></tr>';
        }
    } catch (error) {
        console.error('Error loading waste items:', error);
        document.getElementById('wasteTableBody').innerHTML = '<tr><td colspan="8">Error loading waste items</td></tr>';
    }
}

// Display Waste Items
function displayWasteItems(items) {
    const tbody = document.getElementById('wasteTableBody');

    if (items.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8">No waste items found</td></tr>';
        return;
    }

    tbody.innerHTML = items.map(item => {
        const statusClass = item.status === 'Available' ? 'badge-success' :
            item.status === 'Requested' ? 'badge-warning' : 'badge-danger';

        return `
            <tr>
                <td>${item.waste_id}</td>
                <td>${item.title}</td>
                <td>${item.seller_name}</td>
                <td>${item.category_name}</td>
                <td>৳${parseFloat(item.price).toFixed(2)}</td>
                <td><span class="badge ${statusClass}">${item.status}</span></td>
                <td>${item.request_count}</td>
                <td>
                    <div class="action-btns">
                        <button class="btn btn-danger btn-sm" onclick="deleteWasteItem(${item.waste_id})"><i class="fas fa-trash"></i> Delete</button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

// Delete Waste Item
async function deleteWasteItem(wasteId) {
    showDeleteModal('Are you sure you want to delete this waste item? This action cannot be undone.', async () => {
        try {
            const response = await fetch('api/delete_waste_item.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ waste_id: wasteId })
            });

            const result = await response.json();

            if (result.success) {
                showToast(result.message, 'success');
                loadWasteItems();
                loadStats();
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('An error occurred', 'error');
            console.error('Error:', error);
        }
    });
}

// Load Buy Requests
async function loadBuyRequests() {
    try {
        const response = await fetch('api/get_all_requests.php');
        const result = await response.json();

        if (result.success) {
            buyRequests = result.data;
            displayBuyRequests(buyRequests);
        } else {
            document.getElementById('requestsTableBody').innerHTML = '<tr><td colspan="8">No buy requests found</td></tr>';
        }
    } catch (error) {
        console.error('Error loading buy requests:', error);
        document.getElementById('requestsTableBody').innerHTML = '<tr><td colspan="8">Error loading buy requests</td></tr>';
    }
}

// Display Buy Requests
function displayBuyRequests(requests) {
    const tbody = document.getElementById('requestsTableBody');

    if (requests.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8">No buy requests found</td></tr>';
        return;
    }

    tbody.innerHTML = requests.map(req => {
        const statusClass = req.status === 'Pending' ? 'badge-warning' :
            req.status === 'Accepted' ? 'badge-success' : 'badge-danger';

        const date = new Date(req.request_date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });

        return `
            <tr>
                <td>${req.request_id}</td>
                <td>${req.waste_title}</td>
                <td>${req.buyer_name}</td>
                <td>${req.seller_name}</td>
                <td>৳${parseFloat(req.price).toFixed(2)}</td>
                <td><span class="badge ${statusClass}">${req.status}</span></td>
                <td>${date}</td>
                <td>
                    <div class="action-btns">
                        <button class="btn btn-danger btn-sm" onclick="deleteBuyRequest(${req.request_id})"><i class="fas fa-trash"></i> Delete</button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

// Delete Buy Request
async function deleteBuyRequest(requestId) {
    showDeleteModal('Are you sure you want to delete this buy request? This action cannot be undone.', async () => {
        try {
            const response = await fetch('api/delete_request.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ request_id: requestId })
            });

            const result = await response.json();

            if (result.success) {
                showToast(result.message, 'success');
                loadBuyRequests();
                loadStats();
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('An error occurred', 'error');
            console.error('Error:', error);
        }
    });
}

// Block Modal Functions
let blockCallback = null;

function showBlockModal(message, action, callback) {
    const modal = document.getElementById('blockModal');
    const title = document.getElementById('blockTitle');
    const icon = modal.querySelector('.block-modal-icon i');
    const confirmBtn = document.getElementById('confirmBlockBtn');

    document.getElementById('blockMessage').textContent = message;

    if (action === 'block') {
        title.textContent = 'Block User';
        icon.className = 'fas fa-ban';
        confirmBtn.className = 'btn btn-warning';
        confirmBtn.innerHTML = '<i class="fas fa-ban"></i> Block User';
    } else {
        title.textContent = 'Unblock User';
        icon.className = 'fas fa-check-circle';
        confirmBtn.className = 'btn btn-success';
        confirmBtn.innerHTML = '<i class="fas fa-check"></i> Unblock User';
    }

    modal.classList.add('active');
    blockCallback = callback;
}

function closeBlockModal() {
    document.getElementById('blockModal').classList.remove('active');
    blockCallback = null;
}

document.getElementById('confirmBlockBtn').addEventListener('click', () => {
    if (blockCallback) {
        blockCallback();
    }
    closeBlockModal();
});

// Close block modal on outside click
document.getElementById('blockModal').addEventListener('click', (e) => {
    if (e.target.id === 'blockModal') {
        closeBlockModal();
    }
});

// Delete Modal Functions
function showDeleteModal(message, callback) {
    document.getElementById('deleteMessage').textContent = message;
    document.getElementById('deleteModal').classList.add('active');
    deleteCallback = callback;
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
    deleteCallback = null;
}

document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
    if (deleteCallback) {
        deleteCallback();
    }
    closeDeleteModal();
});

// Close modal on outside click
document.getElementById('deleteModal').addEventListener('click', (e) => {
    if (e.target.id === 'deleteModal') {
        closeDeleteModal();
    }
});

// Toast Notification
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast ${type} show`;

    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}
