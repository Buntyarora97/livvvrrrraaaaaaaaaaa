<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/models/Product.php';
require_once __DIR__ . '/includes/models/Review.php';
require_once __DIR__ . '/includes/models/ProductImage.php';

$id = $_GET['id'] ?? null;
$product = Product::getById($id);
if (!$product) { header('Location: products.php'); exit; }

// SEO Meta Structure
$pageTitle = $product['name'] . " - Dr. Tridosha Herbotech";
$metaDescription = substr(strip_tags($product['description'] ?? ''), 0, 160);

// Line 17 के आसपास ये replace करें
$conn = db();
$reviews = $conn->prepare("SELECT * FROM reviews WHERE product_id = ? ORDER BY created_at DESC");
$reviews->execute([$id]);
$reviews = $reviews->fetchAll(PDO::FETCH_ASSOC);


// Fetch gallery media
$stmt = $conn->prepare("SELECT * FROM product_media WHERE product_id = ? ORDER BY sort_order ASC");
$stmt->execute([$id]);
$galleryMedia = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';

// Prepare unique content based on product ID/Slug
$ingredients = [];
$nutrition = [];
$faqs = [];

$prodId = (int)$product['id'];
$prodName = $product['name'];



// ================= PRODUCT SPECIFIC BANNERS =================
$product_banners = [];

if ($prodId === 1 || stripos($prodName, 'Kumkumadi') !== false) {
    $product_banners = [
        'assets/products-banners/kumkumadi-benefits.jpg.jpeg',
        'assets/products-banners/how-to-use-kumkumadi-oil.jpeg',
        'assets/products-banners/kumkumadi-ingredients.jpeg',
    ];
} elseif ($prodId === 2 || stripos($prodName, 'Dy-B-Fuel') !== false || stripos($prodName, 'RAS') !== false) {
    $product_banners = [
        'assets/products-banners/ingredients.jpeg', 
        'assets/products-banners/bunty.jpeg',
        'assets/products-banners/Benifits.jpeg',
    ];
} elseif ($prodId === 3 || stripos($prodName, 'Yeast') !== false || stripos($prodName, 'Protein') !== false) {
    $product_banners = [
        'assets/images/banners/protein-1.jpg',
        'assets/images/banners/protein-2.jpg',
        'assets/images/banners/protein-3.jpg',
    ];
}
// baaki products ke liye aur elseif


if ($prodId === 1 || stripos($prodName, 'Kumkumadi') !== false) {
  $ingredients = [
    ['name' => 'Kesar (Saffron) 🌸', 'qty' => '10mg', 'purpose' => 'Skin brightening & glow ✨'],
    ['name' => 'Chandan (Sandalwood) 🌿', 'qty' => '50mg', 'purpose' => 'Cooling & soothing ❄️'],
    ['name' => 'Manjistha 🍃', 'qty' => '30mg', 'purpose' => 'Blood purifier 🩸'],
    ['name' => 'Yashtimadhu (Licorice) 🌱', 'qty' => '25mg', 'purpose' => 'Reduces pigmentation 🎯'],
    ['name' => 'Padmaka 🌼', 'qty' => '20mg', 'purpose' => 'Enhances complexion 🌟'],
    ['name' => 'Daruharidra 🌾', 'qty' => '20mg', 'purpose' => 'Anti-acne 💧'],
    ['name' => 'Lodhra 🌺', 'qty' => '15mg', 'purpose' => 'Tightens skin 🔒'],
    ['name' => 'Neem 🌳', 'qty' => '15mg', 'purpose' => 'Anti-bacterial 🛡️'],
    ['name' => 'Amla 🍊', 'qty' => '25mg', 'purpose' => 'Anti-ageing ⏳'],
    ['name' => 'Base Oil (Sesame Oil) 🫒', 'qty' => 'Q.S.', 'purpose' => 'Deep nourishment 💧'],
  ];

    $faqs = [
        ['q' => 'Is it suitable for oily skin?', 'a' => 'Yes, use 1-2 drops at night.'],
        ['q' => 'Can men use this oil?', 'a' => 'Absolutely, it is gender-neutral.'],
    ];
    $product_sections = [
        'about' => [
            'image' => 'assets/images/products/woman-skincare.png',
            'content' => 'Kumkumadi Beauty Oil is an ancient Ayurvedic formulation enriched with saffron and precious herbs. It penetrates deep into the skin, improving texture, tone, and natural glow.'
        ],
        'how_to_use' => [
            'image' => 'assets/images/products/woman-skincare.png',
            'content' => 'Apply 2–3 drops on clean face at night. Massage gently until absorbed.'
        ],
        'benefits' => [
            'image' => 'assets/images/products/woman-skincare.png',
            'content' => '✔ Natural glow<br>✔ Reduces dark spots<br>✔ Anti-aging support<br>✔ Deep nourishment'
        ]
    ];
} elseif ($prodId === 2 || stripos($prodName, 'Dy-B-Fuel') !== false || stripos($prodName, 'RAS') !== false) {
   $ingredients = [
    ['name' => 'Gudmar 🌿', 'qty' => '500mg', 'purpose' => 'Natural sugar destroyer 🩸'],
    ['name' => 'Karela (Bitter Gourd) 🥒', 'qty' => '500mg', 'purpose' => 'Controls glucose absorption ⚖️'],
    ['name' => 'Jamun Seed 🍇', 'qty' => '200mg', 'purpose' => 'Improves insulin response 💉'],
    ['name' => 'Giloy 🌱', 'qty' => '100mg', 'purpose' => 'Detox & immunity support 🛡️'],
    ['name' => 'Amla 🍊', 'qty' => '100mg', 'purpose' => 'Antioxidant & metabolism booster ⚡'],
    ['name' => 'Methi (Fenugreek) 🌾', 'qty' => '150mg', 'purpose' => 'Reduces blood sugar spikes 📉'],
    ['name' => 'Vijaysar 🪵', 'qty' => '150mg', 'purpose' => 'Supports pancreatic function 🫀'],
    ['name' => 'Neem 🌳', 'qty' => '100mg', 'purpose' => 'Purifies blood & detoxifies 🧪'],
    ['name' => 'Haridra (Turmeric) 🟡', 'qty' => '100mg', 'purpose' => 'Anti-inflammatory & insulin support 🔥'],
    ['name' => 'Daruharidra 🌾', 'qty' => '80mg', 'purpose' => 'Helps regulate glucose metabolism ⚙️'],
    ['name' => 'Triphala 🍃', 'qty' => '150mg', 'purpose' => 'Improves digestion & detox 🌀'],
    ['name' => 'Shilajit (Purified) 🪨', 'qty' => '50mg', 'purpose' => 'Boosts energy & cellular health ⚡'],
    ['name' => 'Paneer Phool (Withania Coagulans) 🌼', 'qty' => '200mg', 'purpose' => 'Natural blood sugar regulator 🎯'],
];

    $faqs = [
        ['q' => 'When to take this?', 'a' => 'Take 20-30ml twice daily before meals.'],
        ['q' => 'Is it safe for long term?', 'a' => 'Yes, it is 100% Ayurvedic.'],
    ];
    $product_sections = [
        'about' => [
            'image' => 'assets/images/products/ingredients-flat.png',
            'content' => 'Dy-B-Fuel Ras is a time-tested Ayurvedic formulation designed to regulate blood sugar naturally. It works by improving insulin sensitivity, supporting pancreatic function, and reducing sugar spikes—without harmful chemicals.'
        ],
        'how_to_use' => [
            'image' => 'assets/images/products/bottle-juice.png',
            'content' => 'Take 20–30 ml twice daily or as prescribed by Ayurvedic physician.'
        ],
        'benefits' => [
            'image' => 'assets/images/products/bottle-juice.png',
            'content' => '✔ Supports blood sugar regulation<br>✔ Improves digestion<br>✔ Enhances energy levels<br>✔ 100% herbal & safe'
        ]
    ];
} elseif ($prodId === 3 || stripos($prodName, 'Yeast') !== false || stripos($prodName, 'Protein') !== false) {
  $ingredients = [
    ['name' => 'Fermented Yeast Protein 🍄', 'qty' => '25g', 'purpose' => 'Complete, dairy-free protein source 💪'],
    ['name' => 'Pea Protein Isolate 🌱', 'qty' => '10g', 'purpose' => 'Supports muscle recovery & growth 🏋️'],
    ['name' => 'Brown Rice Protein 🌾', 'qty' => '8g', 'purpose' => 'Sustained energy & lean muscle ⚡'],
    ['name' => 'Panax Ginseng Extract 🌿', 'qty' => '100mg', 'purpose' => 'Boosts stamina & reduces fatigue 🔋'],
    ['name' => 'Mucuna Pruriens Extract 🌱', 'qty' => '100mg', 'purpose' => 'Supports hormonal balance & strength 🧬'],
    ['name' => 'Ashwagandha Extract 🌿', 'qty' => '150mg', 'purpose' => 'Reduces stress & improves performance 🧘‍♂️'],
    ['name' => 'Shatavari Extract 🌸', 'qty' => '100mg', 'purpose' => 'Supports endurance & vitality ❤️'],
    ['name' => 'Bacillus Coagulans 🦠', 'qty' => '2 Billion CFU', 'purpose' => 'Improves gut health & digestion 🛡️'],
    ['name' => 'Papain Enzyme 🍍', 'qty' => '50mg', 'purpose' => 'Reduces bloating & improves protein absorption 🔄'],
    ['name' => 'Bromelain Enzyme 🍍', 'qty' => '50mg', 'purpose' => 'Enhances digestion & reduces inflammation 🔥'],
    ['name' => 'L-Glutamine 🧪', 'qty' => '500mg', 'purpose' => 'Muscle recovery & reduced soreness 🏃‍♂️'],
    ['name' => 'L-Arginine 🧬', 'qty' => '300mg', 'purpose' => 'Improves blood flow & strength 💥'],
    ['name' => 'Magnesium 🪨', 'qty' => '100mg', 'purpose' => 'Supports muscle function & energy ⚙️'],
    ['name' => 'Zinc ⚙️', 'qty' => '10mg', 'purpose' => 'Hormonal balance & immunity 🛡️'],
    ['name' => 'Vitamin B12 🔴', 'qty' => '2mcg', 'purpose' => 'Energy production & nerve health ⚡'],
    ['name' => 'Vitamin D3 ☀️', 'qty' => '400 IU', 'purpose' => 'Bone strength & immunity 💎'],
    ['name' => 'Natural Coffee Extract ☕', 'qty' => '200mg', 'purpose' => 'Natural caffeine for focus & alertness 🚀'],
];

    $nutrition = [
        ['label' => 'Energy', 'serve' => '131.9 kcal', '100g' => '376.9 kcal'],
        ['label' => 'Protein', 'serve' => '25.0g', '100g' => '71.4g'],
        ['label' => 'Carbohydrates', 'serve' => '6.2g', '100g' => '17.7g'],
        ['label' => 'Fat', 'serve' => '0.8g', '100g' => '2.3g'],
        ['label' => 'Sugar', 'serve' => '0g', '100g' => '0g'],
    ];
    $faqs = [
        ['q' => 'Is it dairy free?', 'a' => 'Yes, it is 100% plant-based yeast protein.'],
        ['q' => 'How many servings?', 'a' => 'Approx 29 servings per pouch.'],
    ];
    $product_sections = [
        'about' => [
            'image' => 'assets/images/products/yeast-oil-1.png',
            'content' => 'Yeast-Based Protein is a next-generation plant protein derived from fermented yeast. Unlike whey or soy, it is naturally light on the stomach and highly bioavailable.'
        ],
        'how_to_use' => [
            'image' => 'assets/images/products/yeast-oil-2.png',
            'content' => 'Mix 1 scoop (35g) with 200–250 ml chilled water. Consume once daily or as advised by your nutrition expert.'
        ],
        'benefits' => [
            'image' => 'assets/images/products/yeast-oil-1.png',
            'content' => '✔ Faster muscle recovery<br>✔ Sustained energy<br>✔ Easy digestion<br>✔ Gut health support<br>✔ Clean & filler-free nutrition'
        ]
    ];
}
?>

<div class="product-page-premium" style="padding: 40px 0; background: #ffffff; font-family: 'Poppins', sans-serif;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 15px;">
        <!-- MAIN PRODUCT SECTION -->
        <div class="product-main-grid" style="display: flex; gap: 40px; flex-wrap: wrap;">
            
            <!-- LEFT: GALLERY -->
            <div class="product-gallery-col" style="flex: 1; min-width: 320px; position: sticky; top: 100px; height: fit-content;">
                <div class="main-media-box" id="mainMediaContainer" style="background: #f7f7f7; border-radius: 16px; padding: 20px; display: flex; align-items: center; justify-content: center; min-height: 450px; box-shadow: 0 15px 40px rgba(0,0,0,0.06); position: relative; overflow: hidden;">
                    <img src="uploads/products/<?php echo htmlspecialchars($product['image']); ?>" id="mainProductImage" style="max-width: 100%; max-height: 500px; object-fit: contain; transition: transform 0.5s ease;">
                </div>

                <div class="thumb-strip" style="display: flex; gap: 12px; margin-top: 20px; overflow-x: auto; padding-bottom: 10px; scrollbar-width: none; -ms-overflow-style: none;">
                    <!-- Main Image Thumb -->
                    <div class="thumb-item active" onclick="updateMedia('uploads/products/<?php echo htmlspecialchars($product['image']); ?>', 'image', this)" style="width: 75px; height: 75px; border: 2px solid #4f7c5b; border-radius: 10px; cursor: pointer; flex-shrink: 0; overflow: hidden; background: #fff;">
                        <img src="uploads/products/<?php echo htmlspecialchars($product['image']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    
                    <!-- Gallery Media -->
                    <?php foreach ($galleryMedia as $media): ?>
                    <div class="thumb-item" onclick="updateMedia('uploads/products/<?php echo htmlspecialchars($media['media_url']); ?>', '<?php echo $media['media_type']; ?>', this)" style="width: 75px; height: 75px; border: 1px solid #eee; border-radius: 10px; cursor: pointer; flex-shrink: 0; overflow: hidden; background: #fff;">
                        <?php if ($media['media_type'] === 'video'): ?>
                            <video src="uploads/products/<?php echo htmlspecialchars($media['media_url']); ?>" style="width: 100%; height: 100%; object-fit: cover;"></video>
                        <?php else: ?>
                            <img src="uploads/products/<?php echo htmlspecialchars($media['media_url']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- RIGHT: INFO -->
            <div class="product-info-col" style="flex: 1; min-width: 320px;">
                <nav style="font-size: 13px; color: #888; margin-bottom: 15px;">Home / <?php echo htmlspecialchars($product['category_name'] ?? 'Ayurvedic'); ?> / <?php echo htmlspecialchars($product['name']); ?></nav>
                
                <h1 style="font-size: 36px; font-weight: 700; color: #1f2937; line-height: 1.2; margin-bottom: 10px; font-family: 'Playfair Display', serif;"><?php echo htmlspecialchars($product['name']); ?></h1>
                <p style="font-size: 16px; color: #666; margin-bottom: 20px;"><?php echo htmlspecialchars($product['short_description'] ?? 'Pure Ayurvedic Excellence'); ?></p>

                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 25px;">
                    <div style="background: #4f7c5b; color: #fff; padding: 2px 8px; border-radius: 4px; font-size: 14px; font-weight: 600;">4.8 <i class="fas fa-star" style="font-size: 10px;"></i></div>
                    <span style="color: #888; font-size: 14px;"><?php echo count($reviews); ?> Verified Reviews</span>
                </div>

                <div class="price-section" style="margin-bottom: 30px; background: #fdfdfd; border: 1px solid #f0f0f0; padding: 20px; border-radius: 12px;">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 8px;">
                        <?php 
                        $discounted_price = $product['price'] * 0.90;
                        ?>
                        <span style="font-size: 32px; font-weight: 700; color: #000;">₹<?php echo number_format($discounted_price, 0); ?></span>
                        <span style="text-decoration: line-through; color: #999; font-size: 18px;">₹<?php echo number_format($product['price'], 0); ?></span>
                        <span style="background: #eef7f1; color: #4f7c5b; padding: 4px 10px; border-radius: 20px; font-size: 14px; font-weight: 600;">10% OFF</span>
                    </div>
                    <p style="font-size: 13px; color: #666;">Inclusive of all taxes</p>
                    <div style="margin-top: 15px; display: flex; align-items: center; gap: 8px; color: #444; font-size: 14px;">
                        <i class="fas fa-coins" style="color: #f1c40f;"></i>
                        <?php 
                        $calc_coins = floor($discounted_price * 0.05); 
                        ?>
                        <span>Earn <b><?php echo $calc_coins; ?> Livvra coins</b> on this purchase</span>
                    </div>
                </div>

                <!-- ADD TO CART -->
                <div style="display: flex; gap: 15px; margin-bottom: 30px;">
                    <div style="display: flex; align-items: center; border: 1px solid #ddd; border-radius: 30px; height: 50px; background: #fff; overflow: hidden; width: 120px;">
                        <button onclick="qtyAdj(-1)" style="flex: 1; border: none; background: none; font-size: 20px; cursor: pointer;">-</button>
                        <input type="text" id="pQty" value="1" readonly style="width: 30px; text-align: center; border: none; font-weight: 600; font-size: 16px; outline: none;">
                        <button onclick="qtyAdj(1)" style="flex: 1; border: none; background: none; font-size: 20px; cursor: pointer;">+</button>
                    </div>
                    <button onclick="pAddToCart()" style="flex: 2; height: 50px; background: #222; color: #fff; border: none; border-radius: 30px; font-weight: 600; cursor: pointer; transition: 0.3s;">ADD TO CART</button>
                    <button onclick="pBuyNow()" style="flex: 2; height: 50px; background: #4f7c5b; color: #fff; border: none; border-radius: 30px; font-weight: 600; cursor: pointer; transition: 0.3s;">BUY NOW</button>
                </div>

                <!-- HIGHLIGHTS -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 30px;">
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: #555;">
                        <div style="width: 35px; height: 35px; background: #f6f6f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #4f7c5b;"><i class="fas fa-truck"></i></div>
                        Free Delivery
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: #555;">
                        <div style="width: 35px; height: 35px; background: #f6f6f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #4f7c5b;"><i class="fas fa-undo"></i></div>
                        Easy Returns
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: #555;">
                        <div style="width: 35px; height: 35px; background: #f6f6f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #4f7c5b;"><i class="fas fa-shield-alt"></i></div>
                        Secure Payment
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: #555;">
                        <div style="width: 35px; height: 35px; background: #f6f6f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #4f7c5b;"><i class="fas fa-leaf"></i></div>
                        100% Ayurvedic
                    </div>
                </div>
            </div>
        </div>

        <!-- LOWER SECTIONS (From original data) -->
        <div style="margin-top: 60px;">
            <!-- Tabs / Navigation for Detail -->
            <div style="display: flex; border-bottom: 2px solid #eee; margin-bottom: 30px; gap: 30px; overflow-x: auto;">
                <div onclick="showSection('desc')" id="tab-desc" style="padding-bottom: 12px; cursor: pointer; color: #4f7c5b; border-bottom: 3px solid #4f7c5b; font-weight: 700;">Description</div>
                <?php if (!empty($ingredients)): ?>
                <div onclick="showSection('ingr')" id="tab-ingr" style="padding-bottom: 12px; cursor: pointer; color: #666; font-weight: 600;">Ingredients</div>
                <?php endif; ?>
                <div onclick="showSection('revs')" id="tab-revs" style="padding-bottom: 12px; cursor: pointer; color: #666; font-weight: 600;">Reviews (<?php echo count($reviews); ?>)</div>
            </div>

            <div id="sec-desc" class="detail-section" style="line-height: 1.8; color: #444;">
                <h3 style="margin-bottom: 20px; font-family: 'Playfair Display', serif;">About the Product</h3>
                <?php echo $product['long_description'] ?? $product['description']; ?>
            </div>

            <?php if (!empty($ingredients)): ?>
            <div id="sec-ingr" class="detail-section" style="display: none;">
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                    <?php foreach ($ingredients as $item): ?>
                    <div style="padding: 20px; border: 1px solid #eee; border-radius: 12px; background: #fafafa;">
                        <h4 style="color: #4f7c5b; margin-bottom: 5px;"><?php echo $item['name']; ?></h4>
                        <p style="font-size: 14px; color: #666;"><?php echo $item['purpose']; ?></p>
                        <span style="font-size: 12px; font-weight: 600; color: #888;"><?php echo $item['qty']; ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <div id="sec-revs" class="detail-section" style="display: none;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                    <h3>Customer Reviews</h3>
                    <button onclick="document.getElementById('revForm').style.display='block'" style="background: #4f7c5b; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer;">Write a Review</button>
                </div>

                <!-- Review Success/Error Messages -->
                <?php if (isset($_GET['review']) && $_GET['review'] === 'success'): ?>
                    <div style="background: #eef7f1; color: #4f7c5b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <i class="fas fa-check-circle"></i> Thank you! Your review has been submitted successfully.
                    </div>
                <?php endif; ?>

                <div id="revForm" style="display: none; background: #f9f9f9; padding: 25px; border-radius: 12px; margin-bottom: 40px; border: 1px solid #eee;">
                    <form action="includes/actions/add_review.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px;">Your Name</label>
                            <input type="text" name="name" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px;">Rating</label>
                            <select name="rating" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                                <option value="5">5 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="2">2 Stars</option>
                                <option value="1">1 Star</option>
                            </select>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px;">Comment</label>
                            <textarea name="comment" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;"></textarea>
                        </div>
                        <button type="submit" style="background: #222; color: #fff; border: none; padding: 12px 25px; border-radius: 30px; cursor: pointer;">Submit Review</button>
                    </form>
                </div>

                <?php if (empty($reviews)): ?>
                    <p style="color: #888;">No reviews yet. Be the first to review!</p>
                <?php else: ?>
                    <?php foreach ($reviews as $rev): ?>
                    <div style="border-bottom: 1px solid #eee; padding: 20px 0;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <b style="font-size: 16px;"><?php echo htmlspecialchars($rev['name']); ?></b>
                            <div style="color: #f1c40f;">
                                <?php for($i=1;$i<=5;$i++): ?>
                                    <i class="fas fa-star<?php echo $i <= $rev['rating'] ? '' : '-o'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <p style="color: #555;"><?php echo htmlspecialchars($rev['comment']); ?></p>
                        <small style="color: #aaa;"><?php echo date('d M, Y', strtotime($rev['created_at'])); ?></small>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- ================== YAHI ADD HUA FINAL PART ================== -->
<?php if (!empty($product_banners)): ?>
<div class="product-multi-banners-vertical">
    <?php foreach ($product_banners as $banner): ?>
        <div class="single-banner-vertical">
            <img src="<?php echo $banner; ?>" alt="Product Banner">
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<script>
function updateMedia(url, type, thumb) {
    const container = document.getElementById('mainMediaContainer');
    document.querySelectorAll('.thumb-item').forEach(el => {
        el.style.borderColor = '#eee';
        el.style.borderWidth = '1px';
    });
    thumb.style.borderColor = '#4f7c5b';
    thumb.style.borderWidth = '2px';
    
    if (type === 'video') {
        container.innerHTML = `<video src="${url}" controls autoplay muted style="width: 100%; max-height: 500px; border-radius: 12px;"></video>`;
    } else {
        container.innerHTML = `<img src="${url}" id="mainProductImage" style="max-width: 100%; max-height: 500px; object-fit: contain; border-radius: 12px;">`;
    }
}

function qtyAdj(val) {
    const el = document.getElementById('pQty');
    let v = parseInt(el.value) + val;
    if (v < 1) v = 1;
    el.value = v;
}

function pAddToCart() {
    const q = document.getElementById('pQty').value;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'cart.php';
    
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = 'add';
    form.appendChild(actionInput);
    
    const idInput = document.createElement('input');
    idInput.type = 'hidden';
    idInput.name = 'product_id';
    idInput.value = '<?php echo $id; ?>';
    form.appendChild(idInput);
    
    const qtyInput = document.createElement('input');
    qtyInput.type = 'hidden';
    qtyInput.name = 'quantity';
    qtyInput.value = q;
    form.appendChild(qtyInput);
    
    document.body.appendChild(form);
    form.submit();
}

function pBuyNow() {
    const q = document.getElementById('pQty').value;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'cart.php';
    
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = 'add';
    form.appendChild(actionInput);
    
    const idInput = document.createElement('input');
    idInput.type = 'hidden';
    idInput.name = 'product_id';
    idInput.value = '<?php echo $id; ?>';
    form.appendChild(idInput);
    
    const qtyInput = document.createElement('input');
    qtyInput.type = 'hidden';
    qtyInput.name = 'quantity';
    qtyInput.value = q;
    form.appendChild(qtyInput);
    
    document.body.appendChild(form);
    form.submit();
}

function showSection(id) {
    document.querySelectorAll('.detail-section').forEach(s => s.style.display = 'none');
    document.getElementById('sec-' + id).style.display = 'block';
    
    document.querySelectorAll('[id^="tab-"]').forEach(t => {
        t.style.color = '#666';
        t.style.borderBottom = 'none';
        t.style.fontWeight = '600';
    });
    
    const activeTab = document.getElementById('tab-' + id);
    activeTab.style.color = '#4f7c5b';
    activeTab.style.borderBottom = '3px solid #4f7c5b';
    activeTab.style.fontWeight = '700';
}

// Mobile responsive helper
const styleTag = document.createElement('style');
styleTag.innerHTML = `
    @media (max-width: 768px) {
        .product-main-grid { flex-direction: column !important; }
        .product-gallery-col { position: relative !important; top: 0 !important; width: 100% !important; }
        .product-info-col { width: 100% !important; }
        .price-section { padding: 15px !important; }
        h1 { font-size: 28px !important; }
    }
    .thumb-strip::-webkit-scrollbar { display: none; }
`;
document.head.appendChild(styleTag);
</script>
<!-- Form को replace करें -->
<form id="reviewForm" onsubmit="submitReview(event)">
    <!-- बाकी form same रहेगा -->
</form>

<script>
function submitReview(event) {
    event.preventDefault();
    
    const form = document.getElementById('reviewForm');
    const formData = new FormData(form);
    
    fetch('includes/actions/add_review.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // Page reload without leaving
        window.location.href = `product-detail.php?id=<?php echo $id; ?>&review=success`;
    })
    .catch(error => {
        alert('Error submitting review');
    });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>