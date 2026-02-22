<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/models/Product.php';

/*
|--------------------------------------------------------------------------
| Global Image Path (ABSOLUTE - NEVER BREAKS)
|--------------------------------------------------------------------------
*/
define('BASE_URL', '/'); 
define('PRODUCT_IMG_PATH', BASE_URL . 'uploads/products/');

// Add to cart logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    
    switch ($_POST['action']) {
        case 'add':
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            $product = Product::getById($productId);
            if ($product) {
                $price = (float)$product['price'];
                $discounted_price = $price * 0.90; // Apply 10% discount to match display
                if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
                if (isset($_SESSION['cart'][$productId])) {
                    $_SESSION['cart'][$productId]['quantity'] += $quantity;
                } else {
                    $_SESSION['cart'][$productId] = [
                        'id' => $productId,
                        'name' => $product['name'],
                        'price' => $discounted_price,
                        'image' => $product['image'],
                        'category_name' => $product['category_name'],
                        'quantity' => $quantity
                    ];
                }
            }
            break;
            
        case 'update':
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            if (isset($_SESSION['cart'][$productId])) {
                if ($quantity > 0) {
                    $_SESSION['cart'][$productId]['quantity'] = $quantity;
                } else {
                    unset($_SESSION['cart'][$productId]);
                }
            }
            break;
            
        case 'remove':
            if (isset($_SESSION['cart'][$productId])) {
                unset($_SESSION['cart'][$productId]);
            }
            break;
    }
    
    header('Location: cart.php');
    exit;
}

$pageTitle = 'Shopping Cart';
$cartItems = $_SESSION['cart'] ?? [];
$subtotal = getCartTotal();
$shipping = getShippingFee($subtotal);
$total = $subtotal + $shipping;

// Get featured products
$featuredProducts = Product::getFeatured();

require_once 'includes/header.php';
?>

<div class="cart-page-wrapper">
    <div class="container">
        <h1 class="cart-title">Your Cart</h1>

        <?php if (empty($cartItems)): ?>
            <div class="cart-card" style="text-align: center; padding: 80px 0;">
                <h2>Your cart is currently empty.</h2>
                <a href="products.php" class="checkout-btn" style="display: inline-block; width: auto; padding: 15px 40px;">Return To Shop</a>
            </div>
        <?php else: ?>
            <div class="cart-grid">
                <div class="cart-main">
                    <div class="cart-card">
                        <?php foreach ($cartItems as $item): 
                            $displayImg = PRODUCT_IMG_PATH . $item['image'];
                        ?>
                            <div class="cart-item">
                                <div class="cart-item-img-wrapper">
                                    <img src="<?php echo htmlspecialchars($displayImg); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                </div>
                                <div class="cart-item-info">
                                    <span class="cart-item-cat"><?php echo htmlspecialchars($item['category_name'] ?? 'Wellness'); ?></span>
                                    <a href="product-detail.php?id=<?php echo $item['id']; ?>" class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></a>
                                    <div class="cart-item-price-qty">
                                        <span class="item-price">₹<?php echo number_format($item['price'], 2); ?></span>
                                        <div class="qty-box">
                                            <form action="cart.php" method="post" style="display: contents;">
                                                <input type="hidden" name="action" value="update">
                                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                                <button type="submit" name="quantity" value="<?php echo $item['quantity'] - 1; ?>" <?php echo $item['quantity'] <= 1 ? 'disabled' : ''; ?>>-</button>
                                                <input type="number" value="<?php echo $item['quantity']; ?>" readonly>
                                                <button type="submit" name="quantity" value="<?php echo $item['quantity'] + 1; ?>">+</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="cart-item-total">
                                    <span class="total-amount">₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                                    <form action="cart.php" method="post" style="display: inline;">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" class="remove-item">Remove</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="cart-sidebar">
                    <div class="cart-card summary-card">
                        <h3 class="summary-title">Order Summary</h3>
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>₹<?php echo number_format($subtotal, 2); ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span><?php echo $shipping == 0 ? 'FREE' : '₹' . number_format($shipping, 2); ?></span>
                        </div>
                        <div class="summary-total">
                            <span>Total</span>
                            <span>₹<?php echo number_format($total, 2); ?></span>
                        </div>
                        <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Featured Products Section -->
    <div class="container featured-section">
        <h2 class="section-title">You Might Also Like</h2>
        <div class="featured-grid">
            <?php foreach (array_slice($featuredProducts, 0, 4) as $fp): ?>
                <div class="featured-item">
                    <div class="product-img">
                        <img src="<?php echo PRODUCT_IMG_PATH . $fp['image']; ?>" alt="<?php echo htmlspecialchars($fp['name']); ?>">
                    </div>
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($fp['name']); ?></h3>
                        <div class="product-price">₹<?php echo number_format($fp['price'], 2); ?></div>
                        <a href="product-detail.php?id=<?php echo $fp['id']; ?>" class="view-btn">View Product</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
.cart-page-wrapper {
    background: #f8f9fa;
    padding: 60px 0;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

.cart-title {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 40px;
    color: #212529;
}

.cart-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 30px;
}

.cart-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    padding: 30px;
}

.cart-item {
    display: grid;
    grid-template-columns: 100px 1fr auto;
    gap: 20px;
    padding: 20px 0;
    border-bottom: 1px solid #eee;
}

.cart-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.cart-item:first-child {
    padding-top: 0;
}

.cart-item-img-wrapper img {
    width: 100px;
    height: 100px;
    object-fit: contain;
    background: #fdfdfd;
    border-radius: 8px;
}

.cart-item-info {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.cart-item-cat {
    font-size: 12px;
    text-transform: uppercase;
    color: #b57d62;
    font-weight: 600;
    margin-bottom: 4px;
}

.cart-item-name {
    font-size: 16px;
    font-weight: 600;
    color: #212529;
    text-decoration: none;
    margin-bottom: 10px;
}

.cart-item-price-qty {
    display: flex;
    align-items: center;
    gap: 20px;
}

.item-price {
    font-size: 16px;
    font-weight: 700;
    color: #212529;
}

.qty-box {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 6px;
}

.qty-box button {
    border: none;
    background: none;
    padding: 5px 12px;
    cursor: pointer;
    font-size: 18px;
}

.qty-box input {
    width: 40px;
    text-align: center;
    border: none;
    border-left: 1px solid #eee;
    border-right: 1px solid #eee;
    font-weight: 600;
}

.cart-item-total {
    text-align: right;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-end;
}

.total-amount {
    font-size: 18px;
    font-weight: 700;
    color: #4f7c5b;
    margin-bottom: 10px;
}

.remove-item {
    color: #dc3545;
    background: none;
    border: none;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    padding: 0;
}

.summary-title {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    color: #6c757d;
}

.summary-total {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 2px solid #eee;
    font-size: 20px;
    font-weight: 700;
    color: #212529;
    margin-bottom: 30px;
}

.checkout-btn {
    display: block;
    width: 100%;
    background: #212529;
    color: #fff;
    text-align: center;
    padding: 18px;
    border-radius: 30px;
    font-weight: 600;
    text-decoration: none;
    transition: 0.3s;
}

.checkout-btn:hover {
    background: #000;
}

.featured-section {
    margin-top: 80px;
}

.section-title {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 30px;
}

.featured-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.featured-item {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    transition: 0.3s;
}

.featured-item:hover {
    transform: translateY(-5px);
}

.product-img img {
    width: 100%;
    height: 200px;
    object-fit: contain;
    padding: 20px;
    background: #fdfdfd;
}

.product-info {
    padding: 20px;
}

.product-info h3 {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 8px;
    height: 40px;
    overflow: hidden;
}

.product-price {
    font-size: 16px;
    font-weight: 700;
    color: #212529;
    margin-bottom: 15px;
}

.view-btn {
    display: block;
    text-align: center;
    color: #b57d62;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
    border: 1.5px solid #b57d62;
    padding: 8px;
    border-radius: 20px;
}

@media (max-width: 991px) {
    .cart-grid {
        grid-template-columns: 1fr;
    }
    .featured-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 575px) {
    .cart-item {
        grid-template-columns: 80px 1fr;
    }
    .cart-item-img-wrapper img {
        width: 80px;
        height: 80px;
    }
    .cart-item-total {
        grid-column: span 2;
        flex-direction: row;
        justify-content: space-between;
        margin-top: 10px;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>