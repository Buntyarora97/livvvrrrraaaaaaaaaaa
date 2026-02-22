<?php
require_once 'includes/config.php';

$categorySlug = isset($_GET['category']) ? $_GET['category'] : null;
$searchQuery = isset($_GET['search']) ? $_GET['search'] : null;

$allCategories = Category::getAll(true);

if ($categorySlug) {
    $filteredProducts = Product::getByCategory($categorySlug);
    $currentCategory = Category::getBySlug($categorySlug);
    $pageTitle = $currentCategory ? $currentCategory['name'] : 'Products';
} elseif ($searchQuery) {
    $filteredProducts = Product::search($searchQuery);
    $pageTitle = 'Search Results for "' . htmlspecialchars($searchQuery) . '"';
} else {
    $filteredProducts = Product::getAll(true);
    $pageTitle = 'All Products';
}

require_once 'includes/header.php';
?>

<!-- Page Banner -->
<section class="page-banner">
    <div class="page-banner-content">
        <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a>
            <span>/</span>
            <span>Products</span>
            <?php if ($categorySlug && isset($currentCategory)): ?>
            <span>/</span>
            <span><?php echo htmlspecialchars($currentCategory['name']); ?></span>
            <?php endif; ?>
        </div>
    </div>
</section>

<ul class="filter-list">
    <li>
        <a href="products.php" class="filter-item <?php echo !$categorySlug ? 'active' : ''; ?>">
            <i class="fas fa-layer-group"></i>
            <span>All Products</span>
            <span class="count"><?php echo count(Product::getAll(true)); ?></span>
        </a>
    </li>

    <?php foreach ($allCategories as $cat): ?>
    <li>
        <a href="products.php?category=<?php echo htmlspecialchars($cat['slug']); ?>" 
           class="filter-item <?php echo $categorySlug === $cat['slug'] ? 'active' : ''; ?>">
            <i class="fas <?php echo htmlspecialchars($cat['icon_class']); ?>"></i>
            <span><?php echo htmlspecialchars($cat['name']); ?></span>
        </a>
    </li>
    <?php endforeach; ?>
</ul>



<style>
    /* ================= PREMIUM SIDEBAR ================= */

.products-sidebar {
  position: sticky;
  top: 90px;
}

.filter-card {
  background: #ffffff;
  border-radius: 18px;
  padding: 20px 18px;
  box-shadow: 0 15px 40px rgba(0,0,0,0.06);
  border: 1px solid #eef2f3;
}

.filter-card h4 {
  font-size: 17px;
  font-weight: 600;
  color: #0f3d2e;
  margin-bottom: 18px;
  position: relative;
}

.filter-card h4::after {
  content: "";
  width: 50px;
  height: 3px;
  background: linear-gradient(90deg, #0f3d2e, #2ecc71);
  position: absolute;
  left: 0;
  bottom: -8px;
  border-radius: 10px;
}

.filter-list {
  list-style: none;
  padding: 0;
  margin: 20px 0 0;
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.filter-item {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 12px 14px;
  border-radius: 12px;
  font-size: 14px;
  font-weight: 500;
  color: #475569;
  position: relative;
  transition: all 0.25s ease;
}

.filter-item i {
  font-size: 16px;
  width: 20px;
  text-align: center;
  color: #94a3b8;
}

.filter-item span {
  flex: 1;
}

.filter-item .count {
  background: #f1f5f9;
  color: #0f3d2e;
  font-size: 11px;
  padding: 4px 10px;
  border-radius: 20px;
  font-weight: 600;
}

/* Hover */
.filter-item:hover {
  background: #f8fafc;
  transform: translateX(4px);
}

/* Active */
.filter-item.active {
  background: linear-gradient(135deg, #0f3d2e, #1f7a5a);
  color: #ffffff;
  box-shadow: 0 8px 20px rgba(15,61,46,0.25);
}

.filter-item.active i {
  color: #ffffff;
}

.filter-item.active .count {
  background: rgba(255,255,255,0.2);
  color: #ffffff;
}

/* Left glow indicator */
.filter-item.active::before {
  content: "";
  position: absolute;
  left: -6px;
  top: 50%;
  transform: translateY(-50%);
  width: 6px;
  height: 70%;
  background: linear-gradient(180deg, #2ecc71, #0f3d2e);
  border-radius: 0 6px 6px 0;
}

/* ================= MOBILE – CHIP STYLE ================= */

@media (max-width: 768px) {
  .products-sidebar {
    position: static;
  }

  .filter-card {
    padding: 14px;
  }

  .filter-list {
    flex-direction: row;
    overflow-x: auto;
    gap: 10px;
  }

  .filter-item {
    white-space: nowrap;
    padding: 10px 18px;
    border-radius: 999px;
    background: #f1f5f9;
    font-size: 13px;
  }

  .filter-item i {
    display: none;
  }

  .filter-item.active::before {
    display: none;
  }
}


</style>
                </div>
            </aside>
            
            <!-- Products Grid -->
            <div class="products-content">
                <div class="products-toolbar">
                    <div class="products-count">
                        Showing <strong><?php echo count($filteredProducts); ?></strong> products
                    </div>
                    <div class="products-sort">
                        <select id="sortProducts">
                            <option value="default">Sort by: Default</option>
                            <option value="price-low">Price: Low to High</option>
                            <option value="price-high">Price: High to Low</option>
                            <option value="rating">Customer Rating</option>
                            <option value="newest">Newest First</option>
                        </select>
                    </div>
                </div>
                
                <?php if (empty($filteredProducts)): ?>
                <div class="empty-cart">
                    <i class="fas fa-search"></i>
                    <h3>No Products Found</h3><?php if(($product["stock_status"] ?? "in_stock") == "out_of_stock"): ?><span style="color: #e74c3c; font-size: 12px; font-weight: bold; display: block; margin-top: 5px;">(Out of Stock)</span><?php endif; ?>
                    <p>Try adjusting your filters or search query</p>
                    <a href="products.php" class="view-all-btn">View All Products</a>
                </div>
                <?php else: ?>
                <div class="products-grid">
                    <?php foreach ($filteredProducts as $product): 
                        $discount = $product['mrp'] > 0 ? round((($product['mrp'] - $product['price']) / $product['mrp']) * 100) : 0;
                    ?>
                    <div class="product-card reveal hover-lift">
                        <?php if (($product['stock_status'] ?? 'in_stock') == 'out_of_stock'): ?>
                        <span style="background:#e74c3c;color:#fff;padding:4px 12px;border-radius:4px;font-size:11px;font-weight:bold;position:absolute;top:10px;left:10px;z-index:10;">OUT OF STOCK</span>
                        <?php endif; ?>
                        <?php if ($discount > 0): ?>
                        <span class="product-badge"><?php echo $discount; ?>% OFF</span>
                        <?php endif; ?>
                        <button class="product-wishlist" title="Add to Wishlist">
                            <i class="far fa-heart"></i>
                        </button>
                        <div class="product-image">
                            <img src="uploads/products/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <div class="product-actions">
                                <button class="product-action-btn" onclick="addToCart(<?php echo $product['id']; ?>)" title="Add to Cart">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                                <a href="product-detail.php?id=<?php echo $product['id']; ?>" class="product-action-btn" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        <div class="product-info">
                            <span class="product-category"><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></span>
                            <?php if(($product["stock_status"] ?? "in_stock") == "out_of_stock"): ?><span style="background:#e74c3c;color:#fff;padding:2px 8px;border-radius:4px;font-size:10px;font-weight:bold;margin-bottom:5px;display:inline-block;">OUT OF STOCK</span><?php endif; ?><h3 class="product-name">
                                <a href="product-detail.php?id=<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                            </h3><?php if(($product["stock_status"] ?? "in_stock") == "out_of_stock"): ?><span style="color: #e74c3c; font-size: 12px; font-weight: bold; display: block; margin-top: 5px;">(Out of Stock)</span><?php endif; ?>
                            <div class="product-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= floor($product['rating'])): ?>
                                        <i class="fas fa-star"></i>
                                    <?php elseif ($i - 0.5 <= $product['rating']): ?>
                                        <i class="fas fa-star-half-alt"></i>
                                    <?php else: ?>
                                        <i class="far fa-star"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                <span>(<?php echo $product['reviews_count']; ?>)</span>
                            </div>
                            <div class="product-price">
                                <span class="current-price"><?php echo number_format((float)$product['price'], 2); ?></span>
                                <?php if ((float)$product['mrp'] > (float)$product['price']): ?>
                                <span class="original-price"><?php echo number_format((float)$product['mrp'], 2); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>

<style>
   /* ================= RESET ================= */
* {
  box-sizing: border-box;
}

body {
  margin: 0;
  font-family: 'Inter', 'Poppins', sans-serif;
  background: #f4f6f5;
  color: #1f2937;
}

a {
  text-decoration: none;
  color: inherit;
}

img {
  display: block;
  max-width: 100%;
}

/* ================= PAGE BANNER ================= */
.page-banner {
  background: linear-gradient(135deg, #0f3d2e, #1f7a5a);
  padding: 56px 16px;
  color: #fff;
}

.page-banner-content {
  max-width: 1200px;
  margin: auto;
}

.page-banner h1 {
  font-size: 32px;
  margin-bottom: 8px;
  font-weight: 600;
}

.breadcrumb {
  font-size: 13px;
  opacity: 0.9;
}

/* ================= PRODUCTS SECTION ================= */
.products-section {
  padding: 40px 0;
}

.container {
  max-width: 1280px;
  margin: auto;
  padding: 0 16px;
}

/* ================= LAYOUT ================= */
.products-layout {
  display: grid;
  grid-template-columns: 260px 1fr;
  gap: 28px;
}

/* ================= SIDEBAR ================= */
.products-sidebar {
  position: sticky;
  top: 80px;
  height: fit-content;
}

.filter-card {
  background: #fff;
  border-radius: 14px;
  padding: 20px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.06);
}

.filter-card h4 {
  font-size: 16px;
  margin-bottom: 14px;
  color: #0f3d2e;
}

.filter-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.filter-list li {
  margin-bottom: 8px;
}

.filter-list a {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 9px 12px;
  border-radius: 8px;
  font-size: 14px;
  transition: 0.25s;
}

.filter-list a.active,
.filter-list a:hover {
  background: #0f3d2e;
  color: #fff;
}

/* ================= TOOLBAR ================= */
.products-toolbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 18px;
}

.products-count {
  font-size: 14px;
}

.products-sort select {
  padding: 7px 12px;
  border-radius: 8px;
  border: 1px solid #d1d5db;
  background: #fff;
}

/* ================= GRID ================= */
.products-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 22px;
}

/* ================= PRODUCT CARD ================= */
.product-card {
  background: #fff;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 10px 28px rgba(0,0,0,0.08);
  transition: transform 0.25s ease;
}

.product-card:hover {
  transform: translateY(-4px);
}

/* Badge & Wishlist */
.product-badge {
  position: absolute;
  top: 12px;
  left: 12px;
  background: #b91c1c;
  color: #fff;
  font-size: 11px;
  padding: 4px 8px;
  border-radius: 999px;
  z-index: 2;
}

.product-wishlist {
  position: absolute;
  top: 12px;
  right: 12px;
  width: 34px;
  height: 34px;
  border-radius: 50%;
  border: none;
  background: #fff;
  cursor: pointer;
  z-index: 2;
}

/* ================= IMAGE (FIXED & CLEAN) ================= */
.product-image {
  position: relative;
  width: 100%;
  height: 520px;
  background: #f9fafb;
  display: flex;
  align-items: center;
  justify-content: center;
}

.product-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* Hover actions (desktop only) */
.product-actions {
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,0.45);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  opacity: 0;
  transition: 0.3s;
}

.product-card:hover .product-actions {
  opacity: 1;
}

.product-action-btn {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  border: none;
  background: #fff;
  cursor: pointer;
}

/* ================= INFO ================= */
.product-info {
  padding: 14px 16px 18px;
}

.product-category {
  font-size: 12px;
  color: #6b7280;
}

.product-name {
  font-size: 15px;
  margin: 6px 0;
  font-weight: 500;
}

.product-rating {
  font-size: 13px;
  color: #f59e0b;
}

.product-price {
  margin-top: 8px;
}

.current-price {
  font-size: 16px;
  font-weight: 600;
  color: #0f3d2e;
}

.original-price {
  font-size: 13px;
  color: #9ca3af;
  margin-left: 6px;
  text-decoration: line-through;
}

/* ================= EMPTY ================= */
.empty-cart {
  background: #fff;
  padding: 50px 20px;
  text-align: center;
  border-radius: 16px;
}

/* ================= RESPONSIVE ================= */

/* Tablet */
@media (max-width: 1024px) {
  .products-layout {
    grid-template-columns: 1fr;
  }

  .products-sidebar {
    position: relative;
  }

  .products-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

/* 🔥 MOBILE – PROFESSIONAL FIX */
@media (max-width: 640px) {
  .page-banner h1 {
    font-size: 24px;
  }

  .products-toolbar {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }

  .products-grid {
    grid-template-columns: 1fr;
    gap: 18px;
  }

  .product-image {
      
    height: 250px;
  }

  .product-name {
    font-size: 14px;
  }

  .current-price {
    font-size: 15px;
  }

  /* Disable hover overlay on mobile */
  .product-actions {
    display: none;
  }
}
/* ================= MOBILE FIX (CRITICAL) ================= */
@media (max-width: 768px) {

  /* Page banner height fix */
  .page-banner {
    padding: 36px 16px !important;
  }

  .page-banner h1 {
    font-size: 22px !important;
    margin-bottom: 6px;
  }

  /* Layout fix */
  .products-layout {
    grid-template-columns: 1fr !important;
    gap: 20px;
  }

  /* Sidebar as normal card */
  .products-sidebar {
    position: static !important;
    width: 100%;
  }

  .filter-card {
    padding: 16px !important;
    border-radius: 14px;
  }

  .filter-list a {
    padding: 10px 12px;
    font-size: 14px;
  }

  /* Toolbar spacing fix */
  .products-toolbar {
    margin-top: 10px;
    margin-bottom: 16px;
  }

  /* Products grid spacing */
  .products-grid {
    margin-top: 0;
  }

  /* Remove empty white gap */
  .products-section {
    padding-top: 20px !important;
  }
}


.filter-item {
  display: flex;
  align-items: center;
  gap: 10px;   /* yahin se spacing control hogi */
  padding: 12px 14px;
  border-radius: 12px;
  font-size: 14px;
  font-weight: 500;
  color: #475569;
  position: relative;
  transition: all 0.25s ease;
}

.filter-item i {
  font-size: 15px;
  width: 18px;
  text-align: center;
  color: #94a3b8;
}

.filter-item span {
  white-space: nowrap;
}

.filter-item .count {
  margin-left: auto;   /* right side chip */
  background: #f1f5f9;
  color: #0f3d2e;
  font-size: 11px;
  padding: 4px 10px;
  border-radius: 20px;
  font-weight: 600;
}


</style>
