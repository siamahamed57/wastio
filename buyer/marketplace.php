<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Waste Buyer') {
    header("Location: /wastio/auth/login.php");
    exit;
}

$buyer_id = $_SESSION['user_id'];

// Handle Search and Filter
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$category_filter = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';

$query = "SELECT wi.*, wc.category_name, u.full_name as seller_name 
          FROM waste_items wi 
          JOIN waste_categories wc ON wi.category_id = wc.category_id 
          JOIN users u ON wi.seller_id = u.user_id 
          WHERE wi.status = 'Available'";

if ($search) {
    $query .= " AND (wi.title LIKE '%$search%' OR wi.description LIKE '%$search%')";
}
if ($category_filter) {
    $query .= " AND wi.category_id = '$category_filter'";
}

$query .= " ORDER BY wi.created_at DESC";
$result = mysqli_query($conn, $query);

// Fetch Categories for Filter
$cat_query = "SELECT * FROM waste_categories";
$cat_result = mysqli_query($conn, $cat_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace - Wastio</title>
    <link rel="stylesheet" href="/wastio/assets/css/agent_dashboard.css?v=2">
    <script src="/wastio/assets/js/agent_dashboard.js"></script>
    <style>
        .marketplace-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .item-card {
            background: var(--white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .item-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .item-image {
            width: 100%;
            height: 180px;
            background: #eee;
            position: relative;
            overflow: hidden;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .category-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0, 183, 181, 0.9);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            backdrop-filter: blur(5px);
        }

        .item-content {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .item-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 8px;
        }

        .item-seller {
            font-size: 0.85rem;
            color: var(--text-light);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .item-price {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .item-footer {
            margin-top: auto;
        }

        .buy-btn {
            width: 100%;
            padding: 12px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .buy-btn:hover {
            background: var(--primary-dark);
            transform: scale(1.02);
        }

        .search-filter-bar {
            background: var(--white);
            padding: 20px;
            border-radius: 15px;
            box-shadow: var(--shadow-sm);
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .search-input {
            flex: 1;
            min-width: 250px;
            padding: 12px 20px;
            border-radius: 10px;
            border: 1px solid #ddd;
            font-family: inherit;
        }

        .filter-select {
            padding: 12px 20px;
            border-radius: 10px;
            border: 1px solid #ddd;
            font-family: inherit;
            min-width: 150px;
        }

        .search-btn {
            padding: 12px 25px;
            background: var(--primary-dark);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
        }

        [data-theme="dark"] .item-card,
        [data-theme="dark"] .search-filter-bar {
            background: #242424;
            border-color: #333;
        }

        [data-theme="dark"] .search-input,
        [data-theme="dark"] .filter-select {
            background: #333;
            color: white;
            border-color: #444;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <?php include "includes/sidebar.php"; ?>

        <main class="main-content">
            <div class="top-bar">
                <div class="welcome-text">
                    <h2><i>🛒</i> Waste Marketplace</h2>
                    <p>Discover available recycled materials for purchase.</p>
                </div>
                <div class="user-profile">
                    <button class="theme-btn" id="themeToggle">🌙</button>
                </div>
            </div>

            <form class="search-filter-bar" method="GET">
                <input type="text" name="search" class="search-input" placeholder="Search for items..."
                    value="<?= htmlspecialchars($search) ?>">
                <select name="category" class="filter-select">
                    <option value="">All Categories</option>
                    <?php while ($cat = mysqli_fetch_assoc($cat_result)): ?>
                        <option value="<?= $cat['category_id'] ?>" <?= $category_filter == $cat['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['category_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <button type="submit" class="search-btn">🔍 Filter</button>
            </form>

            <div class="marketplace-grid">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <div class="item-card">
                            <div class="item-image">
                                <?php if ($row['image_path']): ?>
                                    <img src="/wastio/<?= htmlspecialchars($row['image_path']) ?>"
                                        alt="<?= htmlspecialchars($row['title']) ?>">
                                <?php else: ?>
                                    <div
                                        style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:#f0f0f0; color:#ccc; font-size:3rem;">
                                        📦</div>
                                <?php endif; ?>
                                <span class="category-badge">
                                    <?= htmlspecialchars($row['category_name']) ?>
                                </span>
                            </div>
                            <div class="item-content">
                                <h3 class="item-title">
                                    <?= htmlspecialchars($row['title']) ?>
                                </h3>
                                <div class="item-seller">
                                    <i>👤</i> Seller:
                                    <?= htmlspecialchars($row['seller_name']) ?>
                                </div>
                                <div class="item-price">$
                                    <?= number_format($row['price'], 2) ?>
                                </div>
                                <div class="item-footer">
                                    <form action="process_buy.php" method="POST">
                                        <input type="hidden" name="waste_id" value="<?= $row['waste_id'] ?>">
                                        <button type="submit" name="request_buy" class="buy-btn">
                                            <i>🛒</i> Send Request
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div
                        style="grid-column: 1 / -1; text-align: center; padding: 50px; background: white; border-radius: 20px; box-shadow: var(--shadow-sm);">
                        <div style="font-size: 4rem; margin-bottom: 20px;">🏜️</div>
                        <h3>No items found in the marketplace.</h3>
                        <p style="color: #666;">Check back later or try a different search!</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>

</html>