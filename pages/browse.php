<?php 
$pageTitle = "Browse Waste Items";
include '../includes/header.php';
require_once '../config/db.php';

// Get all available waste items
$query = "SELECT w.waste_id, w.title, w.description, w.price, w.image_path, w.created_at,
          c.category_name,
          u.full_name as seller_name, u.phone as seller_phone
          FROM waste_items w
          JOIN waste_categories c ON w.category_id = c.category_id
          JOIN users u ON w.seller_id = u.user_id
          WHERE w.status = 'Available'
          ORDER BY w.created_at DESC";

$result = mysqli_query($conn, $query);
$wasteItems = [];
while ($row = mysqli_fetch_assoc($result)) {
    $wasteItems[] = $row;
}

// Get all categories for filter
$catQuery = "SELECT * FROM waste_categories ORDER BY category_name";
$catResult = mysqli_query($conn, $catQuery);
$categories = [];
while ($row = mysqli_fetch_assoc($catResult)) {
    $categories[] = $row;
}
?>

<style>
    .browse-hero {
        background: linear-gradient(135deg, #005461 0%, #018790 100%);
        color: white;
        padding: 3rem 2rem;
        text-align: center;
    }
    
    .browse-hero h1 {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }
    
    .browse-hero p {
        font-size: 1.1rem;
        opacity: 0.9;
    }
    
    .browse-container {
        max-width: 1400px;
        margin: 3rem auto;
        padding: 0 2rem;
    }
    
    .filter-section {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .filter-section label {
        font-weight: 500;
        color: #005461;
    }
    
    .filter-section select,
    .filter-section input {
        padding: 0.75rem 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-family: 'Poppins', sans-serif;
        transition: border-color 0.3s ease;
    }
    
    .filter-section select:focus,
    .filter-section input:focus {
        outline: none;
        border-color: #00B7B5;
    }
    
    .items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
    }
    
    .item-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .item-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }
    
    .item-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: #f0f0f0;
    }
    
    .item-content {
        padding: 1.5rem;
    }
    
    .item-category {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: #e3f2fd;
        color: #1976d2;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        margin-bottom: 0.75rem;
    }
    
    .item-title {
        font-size: 1.25rem;
        color: #005461;
        margin-bottom: 0.5rem;
    }
    
    .item-description {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .item-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #e0e0e0;
    }
    
    .item-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: #00B7B5;
    }
    
    .item-seller {
        font-size: 0.85rem;
        color: #666;
    }
    
    .item-seller i {
        color: #00B7B5;
    }
    
    .contact-btn {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #00B7B5, #018790);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 500;
        margin-top: 1rem;
        transition: transform 0.3s ease;
    }
    
    .contact-btn:hover {
        transform: translateY(-2px);
    }
    
    .no-items {
        text-align: center;
        padding: 4rem 2rem;
        color: #666;
    }
    
    .no-items i {
        font-size: 4rem;
        color: #ccc;
        margin-bottom: 1rem;
    }
</style>

<div class="browse-hero">
    <h1>Browse Waste Items</h1>
    <p>Discover recyclable materials and contribute to a sustainable future</p>
</div>

<div class="browse-container">
    <div class="filter-section">
        <label for="categoryFilter">Category:</label>
        <select id="categoryFilter" onchange="filterItems()">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['category_id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
            <?php endforeach; ?>
        </select>
        
        <label for="searchInput">Search:</label>
        <input type="text" id="searchInput" placeholder="Search items..." onkeyup="filterItems()">
        
        <label for="sortBy">Sort by:</label>
        <select id="sortBy" onchange="filterItems()">
            <option value="newest">Newest First</option>
            <option value="price_low">Price: Low to High</option>
            <option value="price_high">Price: High to Low</option>
        </select>
    </div>
    
    <div class="items-grid" id="itemsGrid">
        <?php if (count($wasteItems) > 0): ?>
            <?php foreach ($wasteItems as $item): ?>
                <div class="item-card" data-category="<?php echo $item['category_name']; ?>" data-price="<?php echo $item['price']; ?>" data-title="<?php echo strtolower($item['title']); ?>" data-date="<?php echo strtotime($item['created_at']); ?>">
                    <img src="<?php echo $item['image_path'] ?: '/wastio/uploads/waste_items/placeholder.jpg'; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="item-image" onerror="this.src='/wastio/uploads/waste_items/placeholder.jpg'">
                    <div class="item-content">
                        <span class="item-category"><?php echo htmlspecialchars($item['category_name']); ?></span>
                        <h3 class="item-title"><?php echo htmlspecialchars($item['title']); ?></h3>
                        <p class="item-description"><?php echo htmlspecialchars($item['description'] ?: 'No description available'); ?></p>
                        <div class="item-footer">
                            <div>
                                <div class="item-price">৳<?php echo number_format($item['price'], 2); ?></div>
                                <div class="item-seller">
                                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($item['seller_name']); ?>
                                </div>
                            </div>
                        </div>
                        <a href="tel:<?php echo $item['seller_phone']; ?>" class="contact-btn">
                            <i class="fas fa-phone"></i> Contact Seller
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-items">
                <i class="fas fa-inbox"></i>
                <h3>No items available</h3>
                <p>Check back later for new listings</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function filterItems() {
    const categoryFilter = document.getElementById('categoryFilter').value;
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const sortBy = document.getElementById('sortBy').value;
    const items = Array.from(document.querySelectorAll('.item-card'));
    
    // Filter items
    items.forEach(item => {
        const category = item.dataset.category;
        const title = item.dataset.title;
        
        const categoryMatch = !categoryFilter || category === categoryFilter;
        const searchMatch = !searchInput || title.includes(searchInput);
        
        if (categoryMatch && searchMatch) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
    
    // Sort visible items
    const visibleItems = items.filter(item => item.style.display !== 'none');
    const grid = document.getElementById('itemsGrid');
    
    visibleItems.sort((a, b) => {
        switch(sortBy) {
            case 'price_low':
                return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
            case 'price_high':
                return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
            case 'newest':
            default:
                return parseInt(b.dataset.date) - parseInt(a.dataset.date);
        }
    });
    
    visibleItems.forEach(item => grid.appendChild(item));
}
</script>

<?php include '../includes/footer.php'; ?>
