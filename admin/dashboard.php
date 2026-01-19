<?php
session_start();
require_once '../config/db.php';

// Check if user is logged in and is System Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'System Admin') {
    header("Location: /wastio/auth/login.php");
    exit;
}

$admin_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Wastio</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-shield-alt"></i>
                <span>Wastio Admin</span>
            </div>
            <div class="header-actions">
                <button class="theme-toggle" id="themeToggle" title="Toggle Theme">
                    <i class="fas fa-moon"></i>
                </button>
                <div class="user-menu">
                    <span class="user-name"><?php echo htmlspecialchars($admin_name); ?></span>
                    <a href="../auth/logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <div class="container">
        <!-- Statistics Cards -->
        <div class="stats-grid" id="statsGrid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Users</h3>
                    <p class="stat-number" id="totalUsers">0</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <i class="fas fa-user-clock"></i>
                </div>
                <div class="stat-info">
                    <h3>Pending Approvals</h3>
                    <p class="stat-number" id="pendingApprovals">0</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <i class="fas fa-recycle"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Waste Items</h3>
                    <p class="stat-number" id="totalWasteItems">0</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Requests</h3>
                    <p class="stat-number" id="totalRequests">0</p>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="tabs">
            <button class="tab-btn active" data-tab="users">
                <i class="fas fa-users"></i> User Management
            </button>
            <button class="tab-btn" data-tab="waste">
                <i class="fas fa-recycle"></i> Waste Items
            </button>
            <button class="tab-btn" data-tab="requests">
                <i class="fas fa-inbox"></i> Buy Requests
            </button>
        </div>

        <!-- Users Tab -->
        <div class="tab-content active" id="users-tab">
            <div class="section-header">
                <h2><i class="fas fa-users"></i> User Management</h2>
                <div class="filter-group">
                    <select id="roleFilter" class="filter-select">
                        <option value="">All Roles</option>
                        <option value="Waste Seller">Waste Seller</option>
                        <option value="Waste Buyer">Waste Buyer</option>
                        <option value="Collection Agent">Collection Agent</option>
                        <option value="System Admin">System Admin</option>
                    </select>
                    <select id="statusFilter" class="filter-select">
                        <option value="">All Status</option>
                        <option value="approved">Approved</option>
                        <option value="pending">Pending</option>
                        <option value="blocked">Blocked</option>
                    </select>
                </div>
            </div>
            <div class="table-container">
                <table class="data-table" id="usersTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        <tr><td colspan="7" class="loading">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Waste Items Tab -->
        <div class="tab-content" id="waste-tab">
            <div class="section-header">
                <h2><i class="fas fa-recycle"></i> All Waste Items</h2>
            </div>
            <div class="table-container">
                <table class="data-table" id="wasteTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Seller</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Requests</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="wasteTableBody">
                        <tr><td colspan="8" class="loading">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Buy Requests Tab -->
        <div class="tab-content" id="requests-tab">
            <div class="section-header">
                <h2><i class="fas fa-inbox"></i> All Buy Requests</h2>
            </div>
            <div class="table-container">
                <table class="data-table" id="requestsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Waste Item</th>
                            <th>Buyer</th>
                            <th>Seller</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="requestsTableBody">
                        <tr><td colspan="8" class="loading">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Block/Unblock Confirmation Modal -->
    <div class="modal" id="blockModal">
        <div class="modal-content delete-modal">
            <div class="block-modal-icon">
                <i class="fas fa-ban"></i>
            </div>
            <h3 id="blockTitle">Confirm Action</h3>
            <p id="blockMessage">Are you sure you want to proceed?</p>
            <div class="delete-modal-actions">
                <button class="btn btn-secondary" onclick="closeBlockModal()">Cancel</button>
                <button class="btn btn-warning" id="confirmBlockBtn">
                    <i class="fas fa-ban"></i> Confirm
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-content delete-modal">
            <div class="delete-modal-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 id="deleteTitle">Confirm Delete</h3>
            <p id="deleteMessage">Are you sure you want to delete this item?</p>
            <div class="delete-modal-actions">
                <button class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                <button class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast"></div>

    <script src="script.js"></script>
</body>
</html>
