<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/models/Order.php';
require_once __DIR__ . '/includes/models/Product.php';

$cartItems = $_SESSION['cart'] ?? [];
if (empty($cartItems)) {
    header('Location: cart.php');
    exit;
}

$subtotal = getCartTotal();
$shipping = getShippingFee($subtotal);
$total = $subtotal + $shipping;

$success = false;
$orderNumber = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $pincode = trim($_POST['pincode'] ?? '');
    $paymentMethod = $_POST['payment_method'] ?? 'cod';
    
    if (empty($name) || empty($phone) || empty($address) || empty($city) || empty($pincode)) {
        $error = 'Please fill in all required fields.';
    } else {
        try {
            // Apply Promo Code
            $promo_discount = 0;
            if (!empty($_POST['promo_code'])) {
                $pcode = trim($_POST['promo_code']);
                $stmt = db()->prepare("SELECT * FROM promo_codes WHERE code = ? AND is_active = 1 AND (expiry_date IS NULL OR expiry_date >= CURRENT_DATE) AND (usage_limit = 0 OR used_count < usage_limit)");
                $stmt->execute([$pcode]);
                $promo = $stmt->fetch();
                if ($promo) {
                    if ($subtotal >= $promo['min_order_amount']) {
                        if ($promo['discount_type'] === 'percentage') {
                            $promo_discount = ($subtotal * $promo['discount_value']) / 100;
                            if (($promo['max_discount'] ?? 0) > 0) $promo_discount = min($promo_discount, $promo['max_discount']);
                        } else {
                            $promo_discount = $promo['discount_value'];
                        }
                        $total -= $promo_discount;
                        db()->prepare("UPDATE promo_codes SET used_count = used_count + 1 WHERE id = ?")->execute([$promo['id']]);
                    }
                }
            }

            // Reward Coins Logic
            $reward_discount = 0;
            if (isset($_SESSION['user_id']) && !empty($_POST['use_reward_coins'])) {
                $uid = $_SESSION['user_id'];
                $stmt = db()->prepare("SELECT balance FROM reward_coins WHERE user_id = ?");
                $stmt->execute([$uid]);
                $balance = (int)$stmt->fetchColumn();
                
                if ($balance > 0) {
                    $redeem_rate = (float)(db()->query("SELECT value FROM settings WHERE key = 'reward_redeem_rate'")->fetchColumn() ?: 1);
                    $reward_discount = $balance * $redeem_rate;
                    $reward_discount = min($reward_discount, $total); // Can't exceed remaining total
                    $total -= $reward_discount;
                    
                    // Log redemption
                    db()->prepare("INSERT INTO coin_transactions (user_id, amount, transaction_type, description) VALUES (?, ?, 'redeemed', 'Used at checkout')")
                        ->execute([$uid, $balance]);
                    db()->prepare("UPDATE reward_coins SET balance = 0 WHERE user_id = ?")->execute([$uid]);
                }
            }

            $orderData = [
                'user_id' => $_SESSION['user_id'] ?? null,
                'customer_name' => $name,
                'customer_email' => $email,
                'customer_phone' => $phone,
                'shipping_address' => $address,
                'pincode' => $pincode,
                'city' => $city,
                'state' => $state,
                'subtotal' => $subtotal,
                'shipping_fee' => $shipping,
                'discount_amount' => $promo_discount + $reward_discount,
                'total_amount' => $total,
                'payment_method' => $paymentMethod,
                'items' => array_values($cartItems)
            ];
            
            $orderId = Order::create($orderData);
            if ($orderId) {
                $order = Order::getById($orderId);
                $orderNumber = $order['order_number'];
                
               if ($paymentMethod === 'cod') {
    unset($_SESSION['cart']);
    $success = true;

} else if ($paymentMethod === 'cashfree') {

    $_SESSION['pending_order_id'] = $orderId;
    header('Location: cashfree-init.php?order_id=' . $orderId);
    exit;

} else if ($paymentMethod === 'instamojo') {

    $_SESSION['pending_order_id'] = $orderId;
    header('Location: instamojo-init.php?order_id=' . $orderId);
    exit;

} else if ($paymentMethod === 'payu') {

    $_SESSION['pending_order_id'] = $orderId;
    header('Location: payu-init.php?order_id=' . $orderId);
    exit;

} else if ($paymentMethod === 'upi_manual') {

    $_SESSION['pending_order_id'] = $orderId;
    header('Location: upi-payment.php?order_id=' . $orderId);
    exit;
}
            } else {
                $error = 'Failed to create order.';
            }
        } catch (Exception $e) {
            $error = 'Error: ' . $e->getMessage();
        }
    }
}

$pageTitle = 'Checkout';
require_once 'includes/header.php';

// Fetch best products for bottom section
$bestProducts = Product::getFeatured(8); 
?>

<style>
    .checkout-wrapper {
        background: #fdfbf0;
        padding: 40px 0;
        font-family: 'Poppins', sans-serif;
    }
    .checkout-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 40px;
        padding: 0 20px;
    }
    @media (max-width: 992px) {
        .checkout-container { grid-template-columns: 1fr; }
    }
    .checkout-card {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        margin-bottom: 25px;
    }
    .checkout-section-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .form-group-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }
    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: border-color 0.3s;
    }
    .form-control:focus {
        border-color: #C9A227;
        outline: none;
    }
    .payment-methods-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }
    .payment-method-item {
        border: 1.5px solid #eee;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
    }
    .payment-method-item input {
        position: absolute;
        opacity: 0;
    }
    .payment-method-item.active {
        border-color: #C9A227;
        background: rgba(201, 162, 39, 0.05);
    }
    .payment-method-item i {
        font-size: 1.5rem;
        margin-bottom: 8px;
        display: block;
        color: #C9A227;
    }
    .order-summary-card {
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        position: sticky;
        top: 100px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .summary-item-row {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
    }
    .summary-item-img {
        width: 70px;
        height: 70px;
        border-radius: 8px;
        object-fit: cover;
    }
    .summary-item-info { flex: 1; }
    .summary-item-name { font-weight: 600; font-size: 0.95rem; margin-bottom: 4px; }
    .summary-item-meta { font-size: 0.85rem; color: #777; }
    .price-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 0.95rem;
    }
    .price-row.total {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 2px dashed #eee;
        font-weight: 700;
        font-size: 1.2rem;
        color: #333;
    }
    .place-order-btn {
        width: 100%;
        background: #C9A227;
        color: #fff;
        padding: 15px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 1.1rem;
        margin-top: 20px;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: none;
        cursor: pointer;
    }
    .coupon-section {
        margin: 20px 0;
        padding: 15px;
        background: #f9f9f9;
        border-radius: 10px;
    }
    .coupon-input-group {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }
</style>

<div class="checkout-wrapper">
    <div class="container">
        <?php if ($success): ?>
            <div style="text-align: center; padding: 80px 20px; background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                <i class="fas fa-check-circle" style="font-size: 80px; color: #4A7C59; margin-bottom: 25px;"></i>
                <h2 style="font-family: 'Playfair Display', serif; font-size: 2.5rem; color: #333;">Order Successful!</h2>
                <p style="font-size: 1.2rem; color: #666; margin-bottom: 30px;">Thank you for your purchase. Your order number is <strong>#<?php echo e($orderNumber); ?></strong></p>
                <a href="index.php" class="place-order-btn" style="display: inline-block; width: auto; padding: 15px 40px; text-decoration: none;">Continue Shopping</a>
            </div>
        <?php else: ?>
            <?php if ($error): ?>
                <div style="background: #fee; color: #c33; padding: 15px; border-radius: 8px; margin-bottom: 20px;"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST" id="checkout-form">
                <div class="checkout-container">
                    <div class="checkout-main">
                        <div class="checkout-card">
                            <h3 class="checkout-section-title">Enter Contact Details</h3>
                            <div class="form-group-grid">
                                <input type="text" name="phone" placeholder="Phone No." required class="form-control" value="<?php echo e($_POST['phone'] ?? ''); ?>">
                                <input type="email" name="email" placeholder="Email Address" class="form-control" value="<?php echo e($_POST['email'] ?? ''); ?>">
                            </div>
                            <input type="text" name="name" placeholder="Full Name" required class="form-control" value="<?php echo e($_POST['name'] ?? ''); ?>">
                        </div>

                        <div class="checkout-card">
                            <h3 class="checkout-section-title">Shipping details</h3>
                            <div style="margin-bottom: 20px;">
                                <input type="text" name="address" placeholder="Address (House No., Building, Street, Area)" required class="form-control" value="<?php echo e($_POST['address'] ?? ''); ?>">
                            </div>
                            <div class="form-group-grid">
                                <input type="text" name="pincode" placeholder="Pincode" required class="form-control" value="<?php echo e($_POST['pincode'] ?? ''); ?>">
                                <input type="text" name="city" placeholder="City" required class="form-control" value="<?php echo e($_POST['city'] ?? ''); ?>">
                            </div>
                            <input type="text" name="state" placeholder="State" required class="form-control" value="<?php echo e($_POST['state'] ?? ''); ?>">
                        </div>

                        <div class="checkout-card">
                            <h3 class="checkout-section-title">Choose Payment Method</h3>
                            <div class="payment-methods-grid">
                                <label class="payment-method-item active">
                                    <input type="radio" name="payment_method" value="cashfree" checked onchange="updatePaymentUI(this)">
                                    <i class="fas fa-credit-card"></i>
                                    <span>Cashfree (Online)</span>
                                </label>
                                <!--<label class="payment-method-item">-->
                                <!--    <input type="radio" name="payment_method" value="payu" onchange="updatePaymentUI(this)">-->
                                <!--    <i class="fas fa-shield-alt"></i>-->
                                <!--    <span>PayU</span>-->
                                <!--</label>-->
                                <!--<label class="payment-method-item">-->
                                <!--    <input type="radio" name="payment_method" value="upi_manual" onchange="updatePaymentUI(this)">-->
                                <!--    <i class="fas fa-mobile-alt"></i>-->
                                <!--    <span>Direct UPI</span>-->
                                <!--</label>-->
                                <!--<label class="payment-method-item">-->
                                <!--    <input type="radio" name="payment_method" value="cod" onchange="updatePaymentUI(this)">-->
                                <!--    <i class="fas fa-truck"></i>-->
                                <!--    <span>COD</span>-->
                                <!--</label>-->
                                
<!--                                <label class="payment-method-item">-->
<!--    <input type="radio" name="payment_method" value="instamojo" onchange="updatePaymentUI(this)">-->
<!--    <i class="fas fa-bolt"></i>-->
<!--    <span>Instamojo (Online)</span>-->
<!--</label>-->
                            </div>
                        </div>
                    </div>

                    <div class="checkout-sidebar">
                        <div class="order-summary-card">
                            <h3 style="margin-bottom: 25px; border-bottom: 2px solid #C9A227; display: inline-block;">Order Summary</h3>
                            <div class="summary-items">
                                <?php foreach ($cartItems as $item): ?>
                                    <div class="summary-item-row">
                                        <img src="uploads/products/<?php echo e($item['image']); ?>" class="summary-item-img" alt="<?php echo e($item['name']); ?>" onerror="this.src='assets/images/products/<?php echo e($item['image']); ?>'">
                                        <div class="summary-item-info">
                                            <div class="summary-item-name"><?php echo e($item['name']); ?></div>
                                            <div class="summary-item-meta">Qty: <?php echo $item['quantity']; ?></div>
                                            <div style="font-weight: 600; margin-top: 5px;">₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="price-summary">
                                <div class="price-row">
                                    <span>Total MRP:</span>
                                    <span>₹<?php echo number_format($subtotal, 2); ?></span>
                                </div>
                                <div class="price-row">
                                    <span>Shipping Fee:</span>
                                    <span style="color: #4A7C59;"><?php echo $shipping == 0 ? 'FREE' : '₹' . number_format($shipping, 2); ?></span>
                                </div>
                                <div class="price-row total">
                                    <span>Total Payable:</span>
                                    <span>₹<?php echo number_format($total, 2); ?></span>
                                </div>
                            </div>
                            <button type="submit" class="place-order-btn">Place Order</button>
                        </div>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<script>
    function updatePaymentUI(radio) {
        document.querySelectorAll('.payment-method-item').forEach(item => {
            item.classList.remove('active');
        });
        radio.closest('.payment-method-item').classList.add('active');
    }
</script>

<?php require_once 'includes/footer.php'; ?>