<?php
session_start();
require_once '../../config/db.php';

// Check if user is logged in and is Waste Seller
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Waste Seller') {
    header("Location: /wastio/auth/login.php");
    exit;
}

$seller_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Seller';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Seller Dashboard - Wastio</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="delete-modal.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-recycle"></i>
                <span>Wastio Seller</span>
            </div>
            <div class="header-actions">
                <button class="theme-toggle" id="themeToggle" title="Toggle Theme">
                    <i class="fas fa-moon"></i>
                </button>
                <div class="user-menu">
                    <span class="user-name"><?php echo htmlspecialchars($seller_name); ?></span>
                    <a href="../../auth/logout.php" class="logout-btn">
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
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Items</h3>
                    <p class="stat-number" id="totalItems">0</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>Available</h3>
                    <p class="stat-number" id="availableItems">0</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-info">
                    <h3>Sold Items</h3>
                    <p class="stat-number" id="soldItems">0</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-info">
                    <h3>Pending Requests</h3>
                    <p class="stat-number" id="pendingRequests">0</p>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="tabs">
            <button class="tab-btn active" data-tab="items">
                <i class="fas fa-th-large"></i> My Waste Items
            </button>
            <button class="tab-btn" data-tab="requests">
                <i class="fas fa-inbox"></i> Buyer Requests
            </button>
        </div>

        <!-- Waste Items Tab -->
        <div class="tab-content active" id="items-tab">
            <div class="section-header">
                <h2><i class="fas fa-recycle"></i> My Waste Items</h2>
                <button class="btn btn-primary" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> Add New Item
                </button>
            </div>
            <div class="items-grid" id="wasteItemsGrid">
                <div class="loading">Loading...</div>
            </div>
        </div>

        <!-- Buyer Requests Tab -->
        <div class="tab-content" id="requests-tab">
            <div class="section-header">
                <h2><i class="fas fa-inbox"></i> Buyer Requests</h2>
            </div>
            <div class="requests-list" id="requestsList">
                <div class="loading">Loading...</div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Waste Item Modal -->
    <div class="modal" id="wasteModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Add New Waste Item</h3>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <form id="wasteForm" enctype="multipart/form-data">
                <input type="hidden" id="wasteId" name="waste_id">
                
                <div class="form-group">
                    <label for="category">Category *</label>
                    <select id="category" name="category_id" required>
                        <option value="">Select Category</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" required placeholder="Enter item title">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4" placeholder="Enter item description"></textarea>
                </div>

                <div class="form-group">
                    <label for="price">Price (৳) *</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" required placeholder="0.00">
                </div>

                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <div class="image-preview" id="imagePreview"></div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Item
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Mark as Sold Confirmation Modal -->
    <div class="modal" id="soldModal">
        <div class="modal-content delete-modal">
            <div class="sold-modal-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3>Mark as Sold</h3>
            <p id="soldMessage">Mark this item as sold? This will update the item status.</p>
            <div class="delete-modal-actions">
                <button class="btn btn-secondary" onclick="closeSoldModal()">Cancel</button>
                <button class="btn btn-success" id="confirmSoldBtn">
                    <i class="fas fa-check"></i> Mark Sold
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
            <h3>Confirm Delete</h3>
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