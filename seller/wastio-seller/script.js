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

        // Remove active class from all tabs
        tabButtons.forEach(btn => btn.classList.remove('active'));
        tabContents.forEach(content => content.classList.remove('active'));

        // Add active class to clicked tab
        button.classList.add('active');
        document.getElementById(`${tabName}-tab`).classList.add('active');
    });
});

// Global Variables
let categories = [];
let wasteItems = [];
let requests = [];
let editingWasteId = null;

// Initialize Dashboard
document.addEventListener('DOMContentLoaded', () => {
    loadStats();
    loadCategories();
    loadWasteItems();
    loadRequests();

    // Form submission
    document.getElementById('wasteForm').addEventListener('submit', handleWasteFormSubmit);

    // Image preview
    document.getElementById('image').addEventListener('change', previewImage);
});

// Load Statistics
async function loadStats() {
    try {
        const response = await fetch('api/get_stats.php');
        const result = await response.json();

        if (result.success) {
            document.getElementById('totalItems').textContent = result.data.total_items;
            document.getElementById('availableItems').textContent = result.data.available_items;
            document.getElementById('soldItems').textContent = result.data.sold_items;
            document.getElementById('pendingRequests').textContent = result.data.pending_requests;
        }
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

// Load Categories
async function loadCategories() {
    try {
        const response = await fetch('api/get_categories.php');
        const result = await response.json();

        if (result.success) {
            categories = result.data;
            const categorySelect = document.getElementById('category');
            categorySelect.innerHTML = '<option value="">Select Category</option>';

            categories.forEach(cat => {
                const option = document.createElement('option');
                option.value = cat.category_id;
                option.textContent = cat.category_name;
                categorySelect.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error loading categories:', error);
    }
}

// Load Waste Items
async function loadWasteItems() {
    try {
        const response = await fetch('api/get_my_waste.php');
        const result = await response.json();

        const grid = document.getElementById('wasteItemsGrid');

        if (result.success && result.data.length > 0) {
            wasteItems = result.data;
            grid.innerHTML = wasteItems.map(item => createWasteItemCard(item)).join('');
        } else {
            grid.innerHTML = '<div class="loading">No waste items found. Add your first item!</div>';
        }
    } catch (error) {
        console.error('Error loading waste items:', error);
        document.getElementById('wasteItemsGrid').innerHTML = '<div class="loading">Error loading items</div>';
    }
}

// Create Waste Item Card
function createWasteItemCard(item) {
    const statusClass = `status-${item.status.toLowerCase()}`;
    const imageSrc = item.image_path || '/wastio/uploads/waste_items/placeholder.jpg';

    return `
        <div class="item-card">
            <img src="${imageSrc}" alt="${item.title}" class="item-image" onerror="this.src='/wastio/uploads/waste_items/placeholder.jpg'">
            <div class="item-body">
                <div class="item-header">
                    <div>
                        <h3 class="item-title">${item.title}</h3>
                        <span class="item-category">${item.category_name}</span>
                    </div>
                </div>
                <p class="item-description">${item.description || 'No description'}</p>
                <div class="item-footer">
                    <span class="item-price">৳${parseFloat(item.price).toFixed(2)}</span>
                    <span class="item-status ${statusClass}">${item.status}</span>
                </div>
                ${item.request_count > 0 ? `<p style="margin-top: 0.5rem; color: var(--warning-color); font-size: 0.875rem;"><i class="fas fa-shopping-cart"></i> ${item.request_count} request(s)</p>` : ''}
                <div class="item-actions">
                    <button class="btn btn-primary btn-sm" onclick="editWasteItem(${item.waste_id})">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    ${item.status === 'Available' ? `
                        <button class="btn btn-success btn-sm" onclick="markAsSold(${item.waste_id})">
                            <i class="fas fa-check"></i> Mark Sold
                        </button>
                    ` : ''}
                    <button class="btn btn-danger btn-sm" onclick="deleteWasteItem(${item.waste_id})">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    `;
}

// Load Buyer Requests
async function loadRequests() {
    try {
        const response = await fetch('api/get_requests.php');
        const result = await response.json();

        const list = document.getElementById('requestsList');

        if (result.success && result.data.length > 0) {
            requests = result.data;
            list.innerHTML = requests.map(req => createRequestCard(req)).join('');
        } else {
            list.innerHTML = '<div class="loading">No buyer requests found</div>';
        }
    } catch (error) {
        console.error('Error loading requests:', error);
        document.getElementById('requestsList').innerHTML = '<div class="loading">Error loading requests</div>';
    }
}

// Create Request Card
function createRequestCard(req) {
    const statusClass = `status-${req.status.toLowerCase()}`;
    const date = new Date(req.request_date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });

    return `
        <div class="request-card">
            <div class="request-header">
                <div class="request-info">
                    <h3>${req.waste_title}</h3>
                    <div class="request-meta">
                        <span><i class="fas fa-user"></i> ${req.buyer_name}</span>
                        <span><i class="fas fa-envelope"></i> ${req.buyer_email}</span>
                        <span><i class="fas fa-phone"></i> ${req.buyer_phone}</span>
                        <span><i class="fas fa-calendar"></i> ${date}</span>
                    </div>
                </div>
                <span class="item-status ${statusClass}">${req.status}</span>
            </div>
            <p style="color: var(--text-secondary); margin-bottom: 1rem;">Price: <strong style="color: var(--primary-color);">৳${parseFloat(req.price).toFixed(2)}</strong></p>
            ${req.status === 'Pending' ? `
                <div class="request-actions">
                    <button class="btn btn-success btn-sm" onclick="acceptRequest(${req.request_id})">
                        <i class="fas fa-check"></i> Accept
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="rejectRequest(${req.request_id})">
                        <i class="fas fa-times"></i> Reject
                    </button>
                </div>
            ` : ''}
        </div>
    `;
}

// Modal Functions
function openAddModal() {
    editingWasteId = null;
    document.getElementById('modalTitle').textContent = 'Add New Waste Item';
    document.getElementById('wasteForm').reset();
    document.getElementById('wasteId').value = '';
    document.getElementById('imagePreview').innerHTML = '';
    document.getElementById('wasteModal').classList.add('active');
}

function editWasteItem(wasteId) {
    const item = wasteItems.find(w => w.waste_id == wasteId);
    if (!item) return;

    editingWasteId = wasteId;
    document.getElementById('modalTitle').textContent = 'Edit Waste Item';
    document.getElementById('wasteId').value = item.waste_id;
    document.getElementById('category').value = item.category_id;
    document.getElementById('title').value = item.title;
    document.getElementById('description').value = item.description || '';
    document.getElementById('price').value = item.price;

    if (item.image_path) {
        document.getElementById('imagePreview').innerHTML = `<img src="${item.image_path}" alt="Current image">`;
    }

    document.getElementById('wasteModal').classList.add('active');
}

function closeModal() {
    document.getElementById('wasteModal').classList.remove('active');
}

// Handle Form Submission
async function handleWasteFormSubmit(e) {
    e.preventDefault();

    const formData = new FormData(e.target);
    const endpoint = editingWasteId ? 'api/edit_waste.php' : 'api/add_waste.php';

    try {
        const response = await fetch(endpoint, {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            showToast(result.message, 'success');
            closeModal();
            loadWasteItems();
            loadStats();
        } else {
            showToast(result.message, 'error');
        }
    } catch (error) {
        showToast('An error occurred', 'error');
        console.error('Error:', error);
    }
}

// Delete Waste Item
let deleteCallback = null;

function showDeleteModal(message, callback) {
    document.getElementById('deleteMessage').textContent = message;
    document.getElementById('deleteModal').classList.add('active');
    deleteCallback = callback;
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
    deleteCallback = null;
}

// Confirm delete button handler
document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
    if (deleteCallback) {
        deleteCallback();
    }
    closeDeleteModal();
});

async function deleteWasteItem(wasteId) {
    showDeleteModal('Are you sure you want to delete this waste item? This action cannot be undone.', async () => {
        try {
            const response = await fetch('api/delete_waste.php', {
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

// Mark as Sold
async function markAsSold(wasteId) {
    showSoldModal('Mark this item as sold? This will update the item status and it will no longer be available for purchase.', async () => {
        try {
            const response = await fetch('api/mark_sold.php', {
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

// Accept Request
async function acceptRequest(requestId) {
    showDeleteModal('Accept this buyer request? The waste item status will be updated.', async () => {
        try {
            const response = await fetch('api/accept_request.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ request_id: requestId })
            });

            const result = await response.json();

            if (result.success) {
                showToast(result.message, 'success');
                loadRequests();
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

// Reject Request
async function rejectRequest(requestId) {
    showDeleteModal('Reject this buyer request? This action cannot be undone.', async () => {
        try {
            const response = await fetch('api/reject_request.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ request_id: requestId })
            });

            const result = await response.json();

            if (result.success) {
                showToast(result.message, 'success');
                loadRequests();
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('An error occurred', 'error');
            console.error('Error:', error);
        }
    });
}

// Image Preview
function previewImage(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (event) {
            document.getElementById('imagePreview').innerHTML = `<img src="${event.target.result}" alt="Preview">`;
        };
        reader.readAsDataURL(file);
    }
}

// Toast Notification
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast ${type} show`;

    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

// Close modal on outside click
document.getElementById('wasteModal').addEventListener('click', (e) => {
    if (e.target.id === 'wasteModal') {
        closeModal();
    }
});

// Close delete modal on outside click
document.getElementById('deleteModal').addEventListener('click', (e) => {
    if (e.target.id === 'deleteModal') {
        closeDeleteModal();
    }
});

// Mark as Sold Modal
let soldCallback = null;

function showSoldModal(message, callback) {
    document.getElementById('soldMessage').textContent = message;
    document.getElementById('soldModal').classList.add('active');
    soldCallback = callback;
}

function closeSoldModal() {
    document.getElementById('soldModal').classList.remove('active');
    soldCallback = null;
}

// Confirm sold button handler
document.getElementById('confirmSoldBtn').addEventListener('click', () => {
    if (soldCallback) {
        soldCallback();
    }
    closeSoldModal();
});

// Close sold modal on outside click
document.getElementById('soldModal').addEventListener('click', (e) => {
    if (e.target.id === 'soldModal') {
        closeSoldModal();
    }
});
