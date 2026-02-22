<?php
    require_once __DIR__ . '/includes/config.php';
    $pageTitle = 'Home';
    
    try {
        $heroSlides = Hero::getAll(true);
    } catch (Exception $e) {
        error_log("Hero Error: " . $e->getMessage());
        $heroSlides = [];
    }
    
    try {
        $featuredProducts = Product::getFeatured(12);
    } catch (Exception $e) {
        error_log("Featured Products Error: " . $e->getMessage());
        $featuredProducts = [];
    }

    try {
        $newArrivals = Product::getNewArrivals(8);
    } catch (Exception $e) {
        error_log("New Arrivals Error: " . $e->getMessage());
        $newArrivals = [];
    }
    
    try {
        $allCategories = Category::getAll(true);
    } catch (Exception $e) {
        error_log("Categories Error: " . $e->getMessage());
        $allCategories = [];
    }
    
    require_once __DIR__ . '/includes/header.php';
    ?>
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        @media (min-width: 1025px) {
            .desktop-only { display: block !important; }
            .mobile-only { display: none !important; }
        }
        @media (max-width: 1024px) {
            .desktop-only { display: none !important; }
            .mobile-only { display: block !important; }
        }
    </style>
    <style>
        .livvra-hero-section {
            width: 100%;
            height: 120vh;
            position: relative;
            overflow: hidden;
            display: block !important;
        }
    
        .livvra-hero-section .swiper {
            width: 100%;
            height: 100%;
        }
    
        .livvra-hero-section .swiper-slide {
            width: 100%;
            height: 100%;
            position: relative;
        }
    
        .livvra-desktop-view, .livvra-mobile-view {
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: absolute;
            top: 0;
            left: 0;
        }
    
        .livvra-desktop-view { display: block; }
        .livvra-mobile-view { display: none; }
    
        @media (max-width: 768px) {
            .livvra-hero-section { height: 80vh; }
            .livvra-desktop-view { display: none; }
            .livvra-mobile-view { display: block; }
        }
    
        .livvra-hero-section .swiper-button-next, 
        .livvra-hero-section .swiper-button-prev {
            color: #fff !important;
            background: rgba(0,0,0,0.3) !important;
            width: 45px !important;
            height: 45px !important;
            border-radius: 50% !important;
            z-index: 999 !important;
        }
        .livvra-hero-section .swiper-button-next:after, 
        .livvra-hero-section .swiper-button-prev:after { font-size: 18px !important; font-weight: bold; }
        
        .livvra-hero-section .swiper-pagination-bullet-active {
            background: #fff;
            opacity: 1;
            width: 30px;
            border-radius: 10px;
        }
    
        .livra-trust-strip {
            width: 100%;
            overflow: hidden;
            background: #0f3d2e;
            padding: 16px 0;
        }
        .livra-trust-marquee { width: 100%; overflow: hidden; }
        .livra-trust-track {
            display: flex;
            width: max-content;
            gap: 18px;
            animation: livraScroll 25s linear infinite;
        }
        .livra-trust-pill {
            display: flex; align-items: center; gap: 8px;
            padding: 10px 18px; background: rgba(255,255,255,0.1);
            color: #ffffff; border-radius: 999px; font-size: 14px; white-space: nowrap;
        }
        @keyframes livraScroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
    
    /* Category Navigation Styling */
    .category-nav-bar { background: #fdfdfd; border-bottom: 1px solid #f0f0f0; padding: 3px 0; overflow: hidden; position: relative; width: 100%; display: none; }
    
    /* Fixed behavior for mobile (No Sticky) */
    @media (max-width: 768px) {
        .category-nav-bar.mobile-only-bar {
            display: block !important;
            position: relative !important;
            z-index: 100 !important;
            box-shadow: none !important;
            margin-bottom: 0 !important;
        }
        .category-nav-bar.desktop-only-bar {
            display: none !important;
        }
    }
    
    @media (min-width: 769px) {
        .category-nav-bar.desktop-only-bar {
            display: block !important;
        }
        .category-nav-bar.mobile-only-bar {
            display: none !important;
        }
    }

    .category-scroll { 
        display: flex !important; 
        align-items: center !important;
        overflow-x: auto !important; 
        overflow-y: hidden !important;
        -webkit-overflow-scrolling: touch !important;
        scrollbar-width: none !important; 
        -ms-overflow-style: none !important;
        justify-content: flex-start !important; 
        /*padding: 0 15px !important;*/
        white-space: nowrap !important;
        width: 100% !important;
        touch-action: pan-x !important;
        margin: 0 !important;
        gap: 1px !important;
        position: relative !important;
        z-index: 100 !important;
    }
    .category-scroll::-webkit-scrollbar { display: none !important; }
    
    .category-item-mobile { 
        display: inline-flex !important; 
        flex-direction: column !important; 
        align-items: center !important; 
        gap: 8px !important; 
        text-decoration: none !important; 
        flex: 0 0 auto !important;
        position: relative !important;
        pointer-events: auto !important;
        min-width: 70px !important;
    }
    
    .category-icon-circle-mobile {
        width: 65px !important; 
        height: 65px !important; 
        border-radius: 50% !important; 
        display: flex !important;
        align-items: center !important; 
        justify-content: center !important; 
        overflow: hidden !important; 
        border: 1px solid #e8e8e8 !important;
        background: #fdfdfd !important;
        transition: all 0.3s ease;
    }
    
    .category-icon-circle-mobile:hover {
        border-color: #76a33a !important;
        transform: scale(1.05);
    }
    
    .category-icon-circle-mobile img { 
        width: 60% !important; 
        height: 60% !important; 
        object-fit: contain !important; 
    }
    
    .category-item-mobile span { 
        font-size: 11px !important; 
        font-weight: 600 !important; 
        color: #444 !important; 
        text-align: center !important;
        text-transform: capitalize !important;
    }

    .mobile-category-divider {
        height: 60px;
        width: 1px;
        background: #eee;
        align-self: center;
        flex-shrink: 0;
    }

    .nav-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 24px;
        height: 24px;
        background: rgba(255,255,255,0.95);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        color: #999;
        z-index: 10;
        box-shadow: 0 1px 4px rgba(0,0,0,0.1);
        pointer-events: none;
        border: 1px solid #eee;
    }
    .arrow-left { left: 5px; }
    .arrow-right { right: 5px; }
    
        .concern-pills-section { padding: 30px 0; background: #fff; }
        .concern-pills-header { display: flex; align-items: center; gap: 20px; flex-wrap: wrap; }
        .concern-pill {
            display: flex; align-items: center; padding: 6px 16px; background: #fff;
            border: 1px solid #dcdcdc; border-radius: 8px; cursor: pointer; gap: 10px; transition: all 0.3s ease;
        }
        .concern-pill.active { background: #f1f8f1; border-color: #4A7C59; }
        .pill-thumb { width: 32px; height: 32px; border-radius: 50%; overflow: hidden; background: #f0f0f0; }
        .pill-thumb img { width: 100%; height: 100%; object-fit: cover; }
    
        .product-grid-home { display: grid; gap: 25px; grid-template-columns: repeat(4, 1fr); margin-top: 20px; }
        @media (max-width: 1024px) { .product-grid-home { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 768px) { .product-grid-home { grid-template-columns: repeat(2, 1fr); gap: 15px; } }
    </style>
    
    <!-- Hero Section -->
    <style>
        .hero-container {
            width: 100%;
            position: relative;
            background: #f4f4f4;
        }
        /* Maintain Aspect Ratio for Screenshot Style (Approx 3:1 or 2.5:1 for desktop) */
        .hero-carousel-wrapper {
            position: relative;
            width: 100%;
            /* For desktop, let it follow image aspect ratio, but constrain height if needed */
            max-height: 500px; 
            overflow: hidden;
        }
        .hero-slide img {
            width: 100%;
            height: auto;
            object-fit: cover;
            display: block;
        }
        @media (max-width: 768px) {
            .hero-carousel-wrapper {
                max-height: none; /* Let mobile banners define height */
            }
        }
        /* Slider dots styling */
        .glider-dot {
            width: 8px;
            height: 8px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            display: inline-block;
            margin: 0 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .glider-dot.active {
            background: #ffffff;
            width: 24px;
            border-radius: 4px;
        }
        /* Carousel Slide Transition */
        .hero-slide {
            display: none;
            animation: fade 0.5s ease-in-out;
        }
        .hero-slide.active {
            display: block;
        }
        @keyframes fade {
            from { opacity: 0.8; }
            to { opacity: 1; }
        }
    </style>
    
    
    
 <!-- Mobile Category Navigation (Kapiva Style) -->
    <div class="category-nav-bar mobile-only-bar">
        <div class="category-scroll">
            <?php foreach ($allCategories as $cat): 
                if (($cat['show_on_mobile_top_slider'] ?? 0) == 1):
            ?>
                <a href="products.php?category=<?php echo $cat['id']; ?>" class="category-item-mobile">
                    <div class="category-icon-circle-mobile" style="background-color: <?php 
                        $colors = ['#f1f8f1', '#eef7f1', '#fdfbf0', '#fce8e4', '#f3f4f6'];
                        echo $colors[$cat['id'] % count($colors)]; 
                    ?> !important;">
                        <?php if (!empty($cat['icon_upload'])): ?>
                            <img src="uploads/categories/<?php echo htmlspecialchars($cat['icon_upload']); ?>" alt="<?php echo htmlspecialchars($cat['name']); ?>">
                        <?php elseif (!empty($cat['image'])): ?>
                            <img src="uploads/categories/<?php echo htmlspecialchars($cat['image']); ?>" alt="<?php echo htmlspecialchars($cat['name']); ?>">
                        <?php else: ?>
                            <div class="flex items-center justify-center w-full h-full bg-[#f1f8f1]">
                                <i class="fa <?php echo htmlspecialchars($cat['icon_class'] ?? 'fa-leaf'); ?>" style="color: #76a33a; font-size: 20px;"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <span><?php echo htmlspecialchars($cat['name']); ?></span>
                </a>
                <div class="mobile-category-divider"></div>
            <?php 
                endif;
            endforeach; ?>
        </div>
    </div>
    
    
    
    
    
    <main class="hero-container">
        <div id="hero-carousel" class="hero-carousel-wrapper group">
            <div class="relative w-full overflow-hidden">
                <div class="hero-slide active">
                    <img src="assets/images/herobanner/Desktop-banner-1.jpeg" class="desktop-only">
                    <img src="assets/images/herobanner/Mobile-banner-1.jpeg" class="mobile-only">
                </div>
                <div class="hero-slide">
                    <img src="assets/images/herobanner/Desktop-banner-2.jpeg" class="desktop-only">
                    <img src="assets/images/herobanner/Mobile-banner-2.jpeg" class="mobile-only">
                </div>
                <div class="hero-slide">
                    <img src="assets/images/herobanner/Desktop-banner-3.jpeg" class="desktop-only">
                    <img src="assets/images/herobanner/Mobile-banner-3.jpeg" class="mobile-only">
                </div>
                <div class="hero-slide">
                    <img src="assets/images/herobanner/Desktop-banner-4.jpeg" class="desktop-only">
                    <img src="assets/images/herobanner/Mobile-banner-4.jpeg" class="mobile-only">
                </div>
            </div>
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center space-x-1 z-10">
                <span class="glider-dot active" data-slide="0"></span>
                <span class="glider-dot" data-slide="1"></span>
                <span class="glider-dot" data-slide="2"></span>
                <span class="glider-dot" data-slide="3"></span>
            </div>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const slides = document.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('.glider-dot');
        let currentSlide = 0;
        let slideInterval;

        function showSlide(index) {
            slides.forEach(s => s.classList.remove('active'));
            dots.forEach(d => d.classList.remove('active'));
            
            slides[index].classList.add('active');
            dots[index].classList.add('active');
            currentSlide = index;
        }

        function nextSlide() {
            let next = (currentSlide + 1) % slides.length;
            showSlide(next);
        }

        function startAutoplay() {
            slideInterval = setInterval(nextSlide, 4000);
        }

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                clearInterval(slideInterval);
                showSlide(index);
                startAutoplay();
            });
        });

        startAutoplay();
    });
    </script>
    
    
    
    <!-- SELECT CONCERN Section -->
    <style>
        .concern-item {
            transition: all 0.2s ease;
            white-space: nowrap;
            border-radius: 6px;
            border: 1px solid #c2c9b4;
            padding: 6px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            background: white;
            cursor: pointer;
            min-width: fit-content;
            flex-shrink: 0;
        }
        .concern-item.active {
            background-color: #f1f6e9;
            border-color: #76a33a;
        }
        .concern-item:hover {
            border-color: #76a33a;
        }
        /* Horizontal scroll hide */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

   

    <!-- SELECT CONCERN: Filtered for visibility -->
    <section class="bg-white py-6 border-b border-gray-100">
        <div class="max-w-[1400px] mx-auto px-4 lg:px-8">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                <div class="flex items-center gap-2 shrink-0">
                    <i data-lucide="filter" class="w-4 h-4 text-[#76a33a]"></i>
                    <h2 class="font-bold text-[#212121] uppercase text-[12px] tracking-wider">SELECT CONCERN:</h2>
                </div>
                <div class="flex items-center gap-2 lg:gap-3 overflow-x-auto no-scrollbar py-1 flex-wrap">
                    <?php foreach ($allCategories as $cat): 
                        // Show in concern section based on explicit device visibility flags
                        $showOnMobile = ($cat['show_on_mobile_concern'] ?? 1) == 1;
                        $showOnDesktop = ($cat['show_on_desktop_concern'] ?? 1) == 1;
                    ?>
                    <div class="concern-item <?php echo $showOnMobile ? 'flex' : 'hidden'; ?> <?php echo $showOnDesktop ? 'lg:flex' : 'lg:hidden'; ?>" 
                         data-category="<?php echo $cat['id']; ?>">
                        <div class="w-7 h-7 rounded-full overflow-hidden bg-gray-100 flex-shrink-0">
                            <?php if (!empty($cat['icon_upload'])): ?>
                                <img src="uploads/categories/<?php echo htmlspecialchars($cat['icon_upload']); ?>" class="w-full h-full object-cover">
                            <?php elseif (!empty($cat['image'])): ?>
                                <img src="uploads/categories/<?php echo htmlspecialchars($cat['image']); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center bg-[#f1f8f1]">
                                    <i data-lucide="leaf" class="w-3 h-3 text-[#76a33a]"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <span class="text-[13px] font-semibold text-[#212121]"><?php echo htmlspecialchars($cat['name']); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <!-- Best Sellers Section -->
    <style>
        .product-grid-home {
            display: grid;
            gap: 25px;
            grid-template-columns: repeat(4, 1fr);
            margin-top: 20px;
        }
        @media (max-width: 1024px) { .product-grid-home { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 768px) { 
            .product-grid-home { 
                grid-template-columns: repeat(2, 1fr) !important; 
                gap: 10px !important; 
                padding: 0 10px !important;
            } 
            .luxury-image-wrapper {
                height: 180px !important;
                padding: 10px !important;
            }
            .luxury-buy-now-btn {
                font-size: 12px !important;
                height: 40px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            .luxury-cart-icon-btn {
                width: 50px !important;
                height: 40px !important;
            }
            .luxury-rating-badge {
                top: 8px !important;
                left: 8px !important;
                font-size: 10px !important;
                padding: 1px 6px !important;
            }
            .luxury-badge-discount {
                top: 8px !important;
                right: 8px !important;
                font-size: 10px !important;
                padding: 1px 6px !important;
            }
        }

        .product-card-luxury {
            border: 1px solid #f1f1f1;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .product-card-luxury:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .luxury-image-wrapper {
            position: relative;
            background: #f8f8f8;
            padding: 16px;
            display: flex;
            justify-content: center;
            height: 250px;
        }
        .luxury-badge-discount {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #e9f0df;
            color: #76a33a;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            z-index: 10;
        }
        .luxury-rating-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(255, 255, 255, 0.8);
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 4px;
            z-index: 10;
        }
        .luxury-coin-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff8e5;
            padding: 4px 10px;
            border-radius: 50px;
            margin-bottom: 12px;
        }
        .luxury-coin-icon {
            width: 16px;
            height: 16px;
            background: #fbbf24;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 800;
        }
        .luxury-delivery-info {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 16px;
        }
        .luxury-buy-now-btn {
            background: #76a33a;
            color: white;
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            flex-grow: 1;
            transition: background 0.3s;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .luxury-buy-now-btn:hover {
            background: #658c31;
        }
        .luxury-cart-icon-btn {
            width: 64px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #374151;
            color: white;
            border-right: 1px solid #4b5563;
        }
    </style>

  

    <section class="bg-white py-8 px-4 lg:px-8">
        <div class="max-w-[1400px] mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl lg:text-2xl font-bold text-[#212121]">Livvra Bestsellers</h2>
                <a href="products.php" class="text-sm font-semibold text-[#76a33a] hover:underline">View all</a>
            </div>
            <div id="product-container" class="product-grid-home">
                <?php foreach (array_slice($featuredProducts, 0, 8) as $product): 
                    $discounted_price = $product['price'] * 0.90;
                    $coins = floor($discounted_price * 0.05);
                ?>
                <div class="category-product-item product-card-luxury" data-category-id="<?php echo $product['category_id']; ?>">
                    <div class="luxury-image-wrapper">
                        <div class="luxury-rating-badge">
                            <i data-lucide="star" class="w-3 h-3 fill-yellow-400 text-yellow-400"></i>
                            <span>4.5/5 (47)</span>
                        </div>
                        <div class="luxury-badge-discount">10% OFF</div>
                        <img src="uploads/products/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="object-contain h-full">
                    </div>
                    <div class="p-4 flex-grow flex flex-col">
                        <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 min-h-[40px] mb-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <div class="flex items-baseline gap-2 mb-2">
                            <span class="text-xl font-bold text-[#212121]">₹<?php echo number_format($discounted_price); ?></span>
                            <span class="text-xs text-gray-400 line-through">₹<?php echo number_format($product['price']); ?></span>
                        </div>
                        <div class="luxury-coin-badge">
                            <div class="luxury-coin-icon">C</div>
                            <span class="text-[11px] font-bold text-gray-700">Earn <?php echo $coins; ?> Livvra coins</span>
                        </div>
                        
                        <div class="luxury-delivery-info mt-auto">
                            <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                            <span>Delivered by <?php echo date('j', strtotime('+3 days')); ?> - <?php echo date('j M', strtotime('+4 days')); ?></span>
                        </div>
                        
                        <div class="flex items-center mt-2 border-t border-gray-100">
                            <button class="luxury-cart-icon-btn" onclick="addToCart(<?php echo $product['id']; ?>)">
                                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                            </button>
                            <button class="luxury-buy-now-btn" onclick="location.href='product-detail.php?id=<?php echo $product['id']; ?>'">
                                BUY NOW
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-12">
                <a href="products.php" class="text-[#b57d62] font-bold border-b-2 border-[#b57d62] pb-1 hover:text-[#a06b52] hover:border-[#a06b52] transition-all">View all products</a>
            </div>
        </div>
    </section>
    
    <style>
    /* Section Spacing & Header Layout */
    .category-section {
        padding: 60px 0 !important; /* Reduced padding for tighter feel */
    }
    
    .concern-header-layout {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 30px;
        gap: 30px;
    }
    
    .concern-title-group {
        text-align: left;
    }
    
    .concern-subtitle-group {
        text-align: right;
        max-width: 400px;
    }
    
    .concern-subtitle-group .section-subtitle {
        margin: 0;
        font-size: 1.05rem;
        color: #6C757D;
    }
    
    /* Category Filter Row Refinement */
    .category-filter-row {
        margin-bottom: 35px;
    }
    
    .category-filter-scroll {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        padding: 10px 5px 20px;
        scrollbar-width: none;
    }
    
    .filter-pill {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-width: 120px; /* Slightly larger */
        padding: 20px;
        background: #fff;
        border: 1px solid rgba(201, 162, 39, 0.15); /* Softer border */
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04); /* Subtle shadow */
    }
    
    .filter-pill:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(201, 162, 39, 0.08);
        border-color: #C9A227;
    }
    
    .filter-pill i {
        font-size: 1.6rem;
        color: #4A7C59;
        margin-bottom: 10px;
    }
    
    .filter-pill span {
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .filter-pill.active {
        background: #4A7C59;
        border-color: #4A7C59;
        box-shadow: 0 8px 25px rgba(74, 124, 89, 0.25);
    }
    
    /* Product Card Rating UI */
    .product-rating-premium {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }
    
    .product-rating-premium .stars {
        display: flex;
        gap: 2px;
    }
    
    .product-rating-premium .stars i {
        font-size: 0.8rem;
        color: #E5E7EB;
    }
    
    .product-rating-premium .stars i.filled {
        color: #C9A227;
    }
    
    .product-rating-premium .review-count {
        font-size: 0.8rem;
        color: #9CA3AF;
    }
    
    /* Concern Section Layout Refinements */
    .category-grid-premium {
        display: grid !important;
        gap: 25px !important;
        grid-template-columns: repeat(4, 1fr) !important;
    }
    
    @media (max-width: 1024px) {
        .concern-header-layout {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        .concern-subtitle-group {
            text-align: left;
        }
        .category-grid-premium {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }
    
    @media (max-width: 600px) {
        .category-grid-premium {
            grid-template-columns: 1fr !important;
        }
        .filter-pill {
            min-width: 100px;
            padding: 15px;
        }
    }
    
    
    .category-filter-scroll {
        display: flex;
        gap: 15px;
        overflow-x: auto;
        padding: 10px 5px;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none;  /* IE and Edge */
    }
    
    .category-filter-scroll::-webkit-scrollbar {
        display: none; /* Chrome, Safari and Opera */
    }
    
    .filter-pill {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-width: 100px;
        padding: 15px;
        background: #fff;
        border: 1px solid rgba(201, 162, 39, 0.2);
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }
    
    .filter-pill i {
        font-size: 1.5rem;
        color: #4A7C59;
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }
    
    .filter-pill span {
        font-size: 0.85rem;
        font-weight: 600;
        color: #2C3E50;
        white-space: nowrap;
    }
    
    .filter-pill:hover {
        border-color: #C9A227;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(201, 162, 39, 0.1);
    }
    
    .filter-pill.active {
        background: #4A7C59;
        border-color: #4A7C59;
        box-shadow: 0 8px 20px rgba(74, 124, 89, 0.2);
    }
    
    .filter-pill.active i, 
    .filter-pill.active span {
        color: #fff;
    }
    
    .category-product-item {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .category-product-item.hidden {
        display: none;
        opacity: 0;
        transform: scale(0.9);
    }
    
    @media (max-width: 768px) {
        .filter-pill {
            min-width: 90px;
            padding: 12px;
        }
    }
    </style>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Lucide
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const carousel = document.getElementById('hero-carousel');
        if (carousel) {
            const slides = carousel.querySelectorAll('.hero-slide');
            const dots = carousel.querySelectorAll('.glider-dot');
            let currentSlide = 0;
            let slideInterval;

            function showSlide(index) {
                slides.forEach(s => s.classList.remove('active'));
                dots.forEach(d => d.classList.remove('active'));
                
                slides[index].classList.add('active');
                dots[index].classList.add('active');
                currentSlide = index;
            }

            function nextSlide() {
                let next = (currentSlide + 1) % slides.length;
                showSlide(next);
            }

            function startAutoplay() {
                clearInterval(slideInterval);
                slideInterval = setInterval(nextSlide, 4000);
            }

            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    showSlide(index);
                    startAutoplay();
                });
            });

            startAutoplay();
        }

        // Concern filter logic
        const items = document.querySelectorAll('.category-product-item');
        const concernItems = document.querySelectorAll('.concern-item');

        // Story carousel scroll logic
        const storyTrack = document.getElementById('stories-track');
        const prevBtn = document.querySelector('.btn-prev');
        const nextBtn = document.querySelector('.btn-next');

        if (storyTrack && prevBtn && nextBtn) {
            prevBtn.addEventListener('click', () => {
                storyTrack.scrollBy({ left: -300, behavior: 'smooth' });
            });
            nextBtn.addEventListener('click', () => {
                storyTrack.scrollBy({ left: 300, behavior: 'smooth' });
            });
        }
    });
    </script>
    
    
    <style>
        @media (max-width: 768px) {
            .mobile-only-bar { display: block !important; }
            .desktop-only-bar { display: none !important; }
        }
    </style>
    
    <style>.trust-scroller-section {
          background: #fdfaf3;
          overflow: hidden;
          padding: 12px 0;
          border-top: 1px solid rgba(0,0,0,0.05);
          border-bottom: 1px solid rgba(0,0,0,0.05);
        }
    
        .trust-marquee {
          width: 100%;
          overflow: hidden;
        }
    
        .trust-track {
          display: flex;
          gap: 40px;
          width: max-content;
        }
    
        .trust-pill {
          display: flex;
          align-items: center;
          gap: 10px;
          white-space: nowrap;
          background: #fff;
          padding: 6px 16px;
          border-radius: 50px;
          font-size: 0.85rem;
          font-weight: 600;
          color: #4A7C59;
          border: 1px solid rgba(201,162,39,0.2);
        }
    
        .trust-pill i {
          color: #C9A227;
        }
    </style>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
      const track = document.querySelector(".trust-track");
      if (!track) return;
    
      // Duplicate content for infinite loop
      track.innerHTML += track.innerHTML;
    
      let position = 0;
      const speed = 0.5; // control speed (0.3 slow, 1 fast)
    
      function move() {
        position -= speed;
        if (Math.abs(position) >= track.scrollWidth / 2) {
          position = 0;
        }
        track.style.transform = `translateX(${position}px)`;
        requestAnimationFrame(move);
      }
    
      move();
    });
    </script>
    
    
    
      <?php
    try {
        $stories = Story::getAll();
    } catch (Exception $e) {
        error_log("Stories Error: " . $e->getMessage());
        $stories = [];
    }
    ?>
    <!-- Real People, Real Stories Section -->
    <style>
        .stories-section {
            padding: 60px 0;
            background: #fff;
            overflow: hidden;
        }
        .stories-header h2 {
            font-size: 32px;
            font-weight: 700;
            color: #212121;
            margin-bottom: 40px;
            font-family: 'Inter', sans-serif;
        }
        .stories-carousel {
            position: relative;
            width: 100%;
        }
        .stories-track {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding-bottom: 20px;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .stories-track::-webkit-scrollbar {
            display: none;
        }
        .story-card {
            flex: 0 0 280px;
            background: #fff;
        }
        .story-image {
            width: 100%;
            aspect-ratio: 1/1;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 15px;
            background: #f5f5f5;
        }
        .story-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .story-rating {
            display: flex;
            gap: 2px;
            margin-bottom: 10px;
        }
        .story-rating i {
            color: #76a33a;
            font-size: 14px;
        }
        .story-name {
            font-weight: 800;
            font-size: 15px;
            color: #212121;
            margin-bottom: 5px;
            font-family: 'Inter', sans-serif;
        }
        .story-text {
            font-size: 13px;
            color: #444;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .carousel-btn {
            position: absolute;
            top: 40%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            cursor: pointer;
            z-index: 10;
            border: 1px solid #eee;
        }
        .btn-prev { left: -20px; }
        .btn-next { right: -20px; }
        
    <style>
        .stories-track {
            display: flex !important;
            gap: 20px !important;
            overflow-x: auto !important;
            scroll-behavior: smooth !important;
            padding: 10px 5px 30px !important;
            -ms-overflow-style: none !important;
            scrollbar-width: none !important;
            white-space: nowrap !important;
        }
        .stories-track::-webkit-scrollbar {
            display: none !important;
        }
        .story-card {
            flex: 0 0 280px !important;
            background: #fff !important;
            white-space: normal !important;
            display: block !important;
        }
        @media (max-width: 768px) { 
            .story-card {
                flex: 0 0 240px !important;
            }
        }
    </style>
    <section class="stories-section py-8">
        <div class="max-w-[1400px] mx-auto px-4 lg:px-8">
            <div class="stories-header mb-8">
                <h2 class="text-2xl font-bold text-[#212121]">Real people, real stories</h2>
            </div>
            
            <div class="stories-carousel">
                <div class="carousel-btn btn-prev"><i data-lucide="chevron-left"></i></div>
                <div class="carousel-btn btn-next"><i data-lucide="chevron-right"></i></div>
                
                <div class="stories-track" id="stories-track">
                    <?php if (empty($stories)): ?>
                        <!-- Static fallback for preview -->
                        <?php 
                        $demo_stories = [
                            ['name' => 'Bholanath', 'text' => 'Used it as whey protein supplements post...', 'img' => 'https://livvra.in/assets/images/reviews/1.png'],
                            ['name' => 'Dinesh', 'text' => 'I consumed many whey proteins but I got very...', 'img' => 'https://livvra.in/assets/images/reviews/2.png'],
                            ['name' => 'Sonu Bhardwaj', 'text' => 'After using kapiva shilajit gold.. I feel the...', 'img' => 'https://livvra.in/assets/images/reviews/3.png'],
                            ['name' => 'Masarat Jahan', 'text' => 'I ordered for my brother and he is more energe...', 'img' => 'https://livvra.in/assets/images/reviews/4.png'],
                            ['name' => 'Shivam', 'text' => 'Never switching back to other proteins again....', 'img' => 'https://livvra.in/assets/images/reviews/5.png'],
                            ['name' => 'Prabhjot Gill', 'text' => 'Happy to use the product able to do the...', 'img' => 'https://livvra.in/assets/images/reviews/6.png']
                        ];
                        foreach($demo_stories as $s): ?>
                            <div class="story-card">
                                <div class="story-image"><img src="<?php echo $s['img']; ?>"></div>
                                <div class="story-rating">
                                    <i data-lucide="star" class="fill-current" ></i>
                                    <i data-lucide="star" class="fill-current"></i>
                                    <i data-lucide="star" class="fill-current"></i>
                                    <i data-lucide="star" class="fill-current"></i>
                                    <i data-lucide="star" class="fill-current"></i>
                                </div>
                                <div class="story-name"><?php echo $s['name']; ?></div>
                                <div class="story-text"><?php echo $s['text']; ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                       <?php foreach($stories as $s): ?>
    <div class="story-card">
        <div class="story-image">
            <img src="uploads/stories/<?php echo htmlspecialchars($s['image_path']); ?>">
        </div>
        <div class="story-rating">
            <?php for($i=0; $i<$s['rating']; $i++): ?>
                <i data-lucide="star" class="fill-current"></i>
            <?php endfor; ?>
        </div>
        <div class="story-name"><?php echo htmlspecialchars($s['name']); ?></div>
        <div class="story-text"><?php echo htmlspecialchars($s['story_text']); ?></div>
    </div>
<?php endforeach; ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const track = document.getElementById('stories-track');
            const prev = document.querySelector('.btn-prev');
            const next = document.querySelector('.btn-next');
            
            if (track && prev && next) {
                prev.addEventListener('click', () => {
                    track.scrollBy({ left: -300, behavior: 'smooth' });
                });
                next.addEventListener('click', () => {
                    track.scrollBy({ left: 300, behavior: 'smooth' });
                });
            }
        });
    </script>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    <section class="pas-section">
    <div class="container">
        <div class="pas-header">
            <div>
                <h2 class="pas-title text-3xl lg:text-5xl">Ayurveda for a <span>Balanced Life</span></h2>
                <p class="pas-subtitle text-base lg:text-xl">Premium quality products trusted by thousands of customers</p>
            </div>
            <div class="pas-nav-btns">
                <button class="pas-prev"><i class="fas fa-arrow-left"></i></button>
                <button class="pas-next"><i class="fas fa-arrow-right"></i></button>
            </div>
        </div>
    
        <div class="pas-grid">
            <!-- LEFT IMAGE -->
            <div class="pas-left">
                <div class="pas-badge">Naturally Crafted</div>
                <div class="pas-main-img-container shadow-xl border border-gray-100">
                    <img id="pasMainImg" src="assets/images/about/home.png" alt="Main Product">
                </div>
                <div class="pas-thumbs">
                    <img src="assets/images/about/home.png" class="active" data-image="assets/images/about/home.png">
                    <img src="assets/images/about/home1.png" data-image="assets/images/about/home1.png">
                    <img src="assets/images/about/7.jpg.jpeg" data-image="assets/images/about/7.jpg.jpeg">
                    <img src="assets/images/about/home3.png" data-image="assets/images/about/home3.png">
                </div>
            </div>
    
            <!-- RIGHT -->
            <div class="pas-right">
                <div class="pas-filter-wrapper no-scrollbar">
                    <div class="pas-pills">
                        <span class="active" data-filter="all">All Products</span>
                        <!--<?php foreach($allCategories as $cat): ?>-->
                        <!--    <span data-filter="<?= trim(strtolower($cat['slug'])) ?>">-->
                        <!--        <?= htmlspecialchars($cat['name']) ?>-->
                        <!--    </span>-->
                        <!--<?php endforeach; ?>-->
                    </div>
                </div>
    
                <div class="pas-products-viewport">
                    <div class="pas-products" id="pasTrack">
                        <?php foreach($featuredProducts as $p):
                            $img = !empty($p['image']) ? "uploads/products/".$p['image'] : "assets/images/placeholder.png";
                            $cat_slug = !empty($p['category_slug']) ? trim(strtolower($p['category_slug'])) : '';
                        ?>
                        <div class="pas-card group" data-category="<?= $cat_slug ?>">
                            <div class="pas-card-img">
                                <img src="<?= $img ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                                <div class="pas-card-overlay">
                                    <button class="hover:scale-110 transition-transform" onclick="addToCart(<?= $p['id'] ?>)"><i class="fas fa-shopping-basket"></i></button>
                                </div>
                            </div>
                            <div class="pas-card-info">
                                <div class="rating flex gap-1 text-yellow-500 mb-2">
                                    <i class="fas fa-star text-[10px]"></i>
                                    <i class="fas fa-star text-[10px]"></i>
                                    <i class="fas fa-star text-[10px]"></i>
                                    <i class="fas fa-star text-[10px]"></i>
                                    <i class="fas fa-star text-[10px]"></i>
                                </div>
                                <h4 class="text-gray-800 font-bold mb-2 h-auto line-clamp-2"><?= htmlspecialchars($p['name']) ?></h4>
                                <div class="price text-xl font-extrabold text-[#4A7C59] mb-4">₹<?= number_format($p['price']) ?></div>
                                <div class="btns">
                                    <button class="buy-now flex items-center justify-center gap-2 hover:bg-[#4A7C59] hover:text-white transition-all" onclick="window.location.href='product-detail.php?id=<?= $p['id'] ?>'">
                                        <span>View Product</span>
                                        <i class="fas fa-arrow-right text-[10px]"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    
    <style>
    :root {
        --pas-primary: #4A7C59;
        --pas-accent: #C9A227;
        --pas-bg: #fdfaf3;
        --pas-white: #ffffff;
        --pas-text: #2C3E50;
    }
    
    .pas-section {
        padding: 80px 0;
        background: var(--pas-bg);
        overflow: hidden;
    }
    
    .pas-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 50px;
    }
    
    .pas-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.8rem;
        color: var(--pas-text);
        margin-bottom: 10px;
    }
    
    .pas-title span {
        color: var(--pas-primary);
        font-style: italic;
    }
    
    .pas-subtitle {
        color: #666;
        font-size: 1.1rem;
    }
    
    .pas-nav-btns {
        display: flex;
        gap: 15px;
    }
    
    .pas-nav-btns button {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        border: 1px solid #ddd;
        background: #fff;
        cursor: pointer;
        transition: all 0.3s ease;
        color: var(--pas-text);
    }
    
    .pas-nav-btns button:hover {
        background: var(--pas-primary);
        color: #fff;
        border-color: var(--pas-primary);
    }
    
    .pas-grid {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 50px;
        align-items: start;
    }
    
    /* Left Section */
    .pas-left {
        position: sticky;
        top: 20px;
    }
    
    .pas-badge {
        display: inline-block;
        padding: 5px 15px;
        background: rgba(74, 124, 89, 0.1);
        color: var(--pas-primary);
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 20px;
    }
    
    .pas-main-img-container {
        height: 380px;
        background: #fff;
        border-radius: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }
    
    .pas-main-img-container img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.5s ease;
    }
    
    .pas-card-img img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    
    .pas-thumbs {
        display: flex;
        gap: 12px;
        justify-content: center;
    }
    
    .pas-thumbs img {
        width: 65px;
        height: 65px;
        border-radius: 12px;
        border: 2px solid transparent;
        cursor: pointer;
        background: #fff;
        padding: 5px;
        object-fit: contain;
        transition: all 0.3s ease;
    }
    
    .pas-thumbs img.active {
        border-color: var(--pas-accent);
        transform: translateY(-5px);
    }
    
    /* Right Section */
    .pas-filter-wrapper {
        margin-bottom: 30px;
        overflow-x: auto;
        scrollbar-width: none;
    }
    
    .pas-filter-wrapper::-webkit-scrollbar { display: none; }
    
    .pas-pills {
        display: flex;
        gap: 12px;
        white-space: nowrap;
    }
    
    .pas-pills span {
        padding: 10px 25px;
        border-radius: 50px;
        background: #fff;
        color: #555;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.9rem;
        border: 1px solid #eee;
        transition: all 0.3s ease;
    }
    
    .pas-pills .active {
        background: var(--pas-primary);
        color: #fff;
        border-color: var(--pas-primary);
        box-shadow: 0 10px 20px rgba(74, 124, 89, 0.2);
    }
    
    .pas-products-viewport {
        width: 100%;
        overflow: hidden;
        position: relative;
        padding: 10px 0 30px;
    }
    
    .pas-products {
        display: flex;
        gap: 25px;
        transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1);
        will-change: transform;
    }
    
    .pas-card {
        flex: 0 0 280px;
        background: #fff;
        border-radius: 25px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0,0,0,0.03);
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }
    
    .pas-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
    }
    
    .pas-card-img {
        height: 220px;
        background: #fcfcfc;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    
    .pas-card-img img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    
    .pas-card-overlay {
        position: absolute;
        top: 15px;
        right: 15px;
        opacity: 0;
        transform: translateX(10px);
        transition: all 0.3s ease;
    }
    
    .pas-card:hover .pas-card-overlay {
        opacity: 1;
        transform: translateX(0);
    }
    
    .pas-card-overlay button {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--pas-primary);
        color: #fff;
        border: none;
        cursor: pointer;
        box-shadow: 0 5px 15px rgba(74, 124, 89, 0.3);
    }
    
    .pas-card-info {
        padding: 20px;
    }
    
    .pas-card-info .rating {
        color: var(--pas-accent);
        font-size: 0.8rem;
        margin-bottom: 8px;
    }
    
    .pas-card-info h4 {
        font-size: 1.1rem;
        color: var(--pas-text);
        margin-bottom: 10px;
        height: 80px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        line-height: 1.4;
    }
    
    .pas-card-info .price {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--pas-primary);
        margin-bottom: 15px;
    }
    
    .pas-card-info .btns button {
        width: 100%;
        padding: 12px;
        border-radius: 12px;
        border: 1px solid var(--pas-primary);
        background: transparent;
        color: var(--pas-primary);
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .pas-card-info .btns button:hover {
        background: var(--pas-primary);
        color: #fff;
    }
    
    @media (max-width: 1024px) {
        .pas-grid { grid-template-columns: 1fr; }
        .pas-left { position: static; width: 100% !important; max-width: 100% !important; margin: 0 auto 40px; }
    }
    
    @media (max-width: 768px) {
        .pas-grid { 
            display: flex !important;
            flex-direction: column !important;
        }
        .pas-left {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 0 30px 0 !important;
            order: 1 !important;
        }
        .pas-right {
            order: 2 !important;
            width: 100% !important;
        }
        .pas-main-img-container {
            height: 300px !important;
            padding: 20px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            visibility: visible !important;
            opacity: 1 !important;
            background: #fff !important;
            border-radius: 20px !important;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
            margin-bottom: 20px !important;
        }
        .pas-main-img-container img {
            display: block !important;
            max-width: 90% !important;
            max-height: 90% !important;
            width: auto !important;
            height: auto !important;
            object-fit: contain !important;
        }
        .pas-header { flex-direction: column; align-items: flex-start; gap: 15px; }
        .pas-title { font-size: 1.8rem; }
        .pas-card { flex: 0 0 250px; }
    }
    </style>
    
   <script>
document.addEventListener("DOMContentLoaded", function(){

    const track = document.getElementById("pasTrack");
    const prevBtn = document.querySelector(".pas-prev");
    const nextBtn = document.querySelector(".pas-next");
    const cards = document.querySelectorAll(".pas-card");

    if(!track || cards.length === 0) return;

    let currentX = 0;
    let speed = 0.4;
    let isPaused = false;
    let animationId;

    function getCardWidth(){
        const card = document.querySelector(".pas-card");
        return card ? card.offsetWidth + 25 : 0;
    }

    function getVisibleCards(){
        return Array.from(cards).filter(c => c.style.display !== "none");
    }

    function updateSlider(){

        const visibleCards = getVisibleCards();
        if(visibleCards.length === 0){
            animationId = requestAnimationFrame(updateSlider);
            return;
        }

        const viewportWidth = track.parentElement.clientWidth;
        const cardWidth = getCardWidth();
        const totalWidth = visibleCards.length * cardWidth;
        const maxScroll = Math.max(0, totalWidth - viewportWidth);

        if(!isPaused && maxScroll > 0){
            currentX += speed;
            if(currentX >= maxScroll) {
                currentX = 0;
            }
            track.style.transform = `translateX(-${currentX}px)`;
        }

        animationId = requestAnimationFrame(updateSlider);
    }

    updateSlider();

    track.addEventListener("mouseenter", ()=> isPaused = true);
    track.addEventListener("mouseleave", ()=> isPaused = false);

    nextBtn.addEventListener("click", ()=>{
        const viewportWidth = track.parentElement.clientWidth;
        const visibleCards = getVisibleCards();
        const cardWidth = getCardWidth();
        const totalWidth = visibleCards.length * cardWidth;
        const maxScroll = Math.max(0, totalWidth - viewportWidth);
        
        currentX = Math.min(maxScroll, currentX + cardWidth);
        track.style.transform = `translateX(-${currentX}px)`;
    });

    prevBtn.addEventListener("click", ()=>{
        const cardWidth = getCardWidth();
        currentX = Math.max(0, currentX - cardWidth);
        track.style.transform = `translateX(-${currentX}px)`;
    });

    /* FILTER */
    document.querySelectorAll(".pas-pills span").forEach(pill=>{
        pill.addEventListener("click", function(){

            document.querySelectorAll(".pas-pills span")
            .forEach(p=>p.classList.remove("active"));

            this.classList.add("active");

            const filter = (this.dataset.filter || "").toLowerCase();

            cards.forEach(card=>{
                const cat = (card.dataset.category || "").toLowerCase();
                card.style.display =
                    (filter === "all" || filter === cat) ? "block" : "none";
            });

            currentX = 0;
            track.style.transform = "translateX(0px)";
        });
    });

    /* THUMBNAILS */
    const mainImg = document.getElementById("pasMainImg");
    document.querySelectorAll(".pas-thumbs img").forEach(img=>{
        img.addEventListener("click", function(){

            mainImg.style.opacity = 0;

            setTimeout(()=>{
                mainImg.src = this.dataset.image;
                mainImg.style.opacity = 1;
            },200);

            document.querySelectorAll(".pas-thumbs img")
            .forEach(i=>i.classList.remove("active"));

            this.classList.add("active");
        });
    });

});
</script>
    
    
    
    
    <!-- STEP WISE IMAGE SECTION -->
    <section class="livvra-steps">
    
      <div class="livvra-step">
        <img src="assets/images/banners/1.jpg (3).jpeg" alt="Step 01">
      </div>
    
      <div class="livvra-step">
        <img src="assets/images/banners/2 copy.jpg (3).jpeg" alt="Step 02">
      </div>
    
      <div class="livvra-step">
        <img src="assets/images/banners/3 copy.jpg (1).jpeg" alt="Step 03">
      </div>
    
    </section>
    <style>
        /* ===== LIVVRA VERTICAL STEPS ===== */
    .livvra-steps {
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 0; /* no space between steps */
      background: #fff;
    }
    
    /* Each step */
    .livvra-step {
      width: 100%;
      overflow: hidden;
    }
    
    /* Images */
    .livvra-step img {
      width: 100%;
      height: auto;
      display: block;
      object-fit: contain; /* 🔥 image poori dikhe */
    }
    
    /* Mobile safe */
    @media (max-width: 768px) {
      .livvra-step img {
        width: 100%;
        height: auto;
      }
    }
    
    </style>
    

      <section class="bg-white py-8 px-4 lg:px-8">
        <div class="max-w-[1400px] mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl lg:text-2xl font-bold text-[#212121]">New Arrivals</h2>
                <a href="products.php" class="text-sm font-semibold text-[#76a33a] hover:underline">View all</a>
            </div>
            <div class="product-grid-home">
                <?php foreach ($newArrivals as $product): 
                    $discounted_price = $product['price'] * 0.90;
                    $coins = floor($discounted_price * 0.05);
                ?>
                <div class="product-card-luxury">
                    <div class="luxury-image-wrapper">
                        <div class="luxury-rating-badge">
                            <i data-lucide="star" class="w-3 h-3 fill-yellow-400 text-yellow-400"></i>
                            <span>4.5/5 (12)</span>
                        </div>
                        <div class="luxury-badge-discount">10% OFF</div>
                        <img src="uploads/products/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="object-contain h-full">
                    </div>
                    <div class="p-4 flex-grow flex flex-col">
                        <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 min-h-[40px] mb-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <div class="flex items-baseline gap-2 mb-2">
                            <span class="text-xl font-bold text-[#212121]">₹<?php echo number_format($discounted_price); ?></span>
                            <span class="text-xs text-gray-400 line-through">₹<?php echo number_format($product['price']); ?></span>
                        </div>
                        <div class="luxury-coin-badge">
                            <div class="luxury-coin-icon">C</div>
                            <span class="text-[11px] font-bold text-gray-700">Earn <?php echo $coins; ?> Livvra coins</span>
                        </div>
                        
                        <div class="luxury-delivery-info mt-auto">
                            <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                            <span>Delivered by <?php echo date('j', strtotime('+3 days')); ?> - <?php echo date('j M', strtotime('+4 days')); ?></span>
                        </div>
                        
                        <div class="flex items-center mt-2 border-t border-gray-100">
                            <button class="luxury-cart-icon-btn" onclick="addToCart(<?php echo $product['id']; ?>)">
                                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                            </button>
                            <button class="luxury-buy-now-btn" onclick="location.href='product-detail.php?id=<?php echo $product['id']; ?>'">
                                BUY NOW
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    
    
    
  
    
   <!-- FAQ Section -->
<section class="faq1-section faq1-light">
  <div class="faq1-container">
    <div class="faq1-header text-center" style="text-align:center; margin-bottom:50px;">
  
  <span class="faq1-badge" 
        style="display:inline-block; 
               padding:6px 18px; 
               background:linear-gradient(135deg,#4A7C59,#7ec8a0); 
               color:#fff; 
               border-radius:30px; 
               font-size:13px; 
               letter-spacing:1px; 
               font-weight:600;">
    Common Questions
  </span>

  <h2 style="font-size:36px; 
             margin:15px 0 10px; 
             font-weight:700; 
             color:#1f3d2b;">
    Frequently Asked <span style="color:#4A7C59;">Questions</span>
  </h2>

  <p style="color:#666; 
            font-size:16px; 
            max-width:520px; 
            margin:0 auto; 
            line-height:1.6;">
    Find answers to common questions about LIVVRA products
  </p>

</div>


    <div class="faq1-wrapper">

      <div class="faq1-item">
        <div class="faq1-question" onclick="toggleFAQ1(this)">
          <h3>Are LIVVRA products 100% natural and organic?</h3>
          <span class="faq1-toggle">+</span>
        </div>
        <div class="faq1-answer">
          <p>Yes, LIVVRA products are made from pure, natural herbal ingredients sourced from organic farms across India.</p>
        </div>
      </div>

      <div class="faq1-item">
        <div class="faq1-question" onclick="toggleFAQ1(this)">
          <h3>How long does it take to see results?</h3>
          <span class="faq1-toggle">+</span>
        </div>
        <div class="faq1-answer">
          <p>Most customers notice improvements within 2–4 weeks of regular usage.</p>
        </div>
      </div>

      <div class="faq1-item">
        <div class="faq1-question" onclick="toggleFAQ1(this)">
          <h3>Are there any side effects?</h3>
          <span class="faq1-toggle">+</span>
        </div>
        <div class="faq1-answer">
          <p>LIVVRA products use safe Ayurvedic formulations and are generally well tolerated.</p>
        </div>
      </div>

      <div class="faq1-item">
        <div class="faq1-question" onclick="toggleFAQ1(this)">
          <h3>Can I take LIVVRA products daily?</h3>
          <span class="faq1-toggle">+</span>
        </div>
        <div class="faq1-answer">
          <p>Yes, our products are designed for daily consumption as part of a healthy routine.</p>
        </div>
      </div>

      <div class="faq1-item">
        <div class="faq1-question" onclick="toggleFAQ1(this)">
          <h3>Is LIVVRA suitable for all age groups?</h3>
          <span class="faq1-toggle">+</span>
        </div>
        <div class="faq1-answer">
          <p>Most products are suitable for adults. Please consult a doctor for children or elderly.</p>
        </div>
      </div>

      <div class="faq1-item">
        <div class="faq1-question" onclick="toggleFAQ1(this)">
          <h3>Are your products clinically tested?</h3>
          <span class="faq1-toggle">+</span>
        </div>
        <div class="faq1-answer">
          <p>Yes, all products go through quality and safety testing before market release.</p>
        </div>
      </div>

      <div class="faq1-item">
        <div class="faq1-question" onclick="toggleFAQ1(this)">
          <h3>Can pregnant women use LIVVRA products?</h3>
          <span class="faq1-toggle">+</span>
        </div>
        <div class="faq1-answer">
          <p>Pregnant women should consult a healthcare professional before use.</p>
        </div>
      </div>

      <div class="faq1-item">
        <div class="faq1-question" onclick="toggleFAQ1(this)">
          <h3>Do you offer cash on delivery?</h3>
          <span class="faq1-toggle">+</span>
        </div>
        <div class="faq1-answer">
          <p>Yes, we offer COD and multiple secure online payment options.</p>
        </div>
      </div>

      <div class="faq1-item">
        <div class="faq1-question" onclick="toggleFAQ1(this)">
          <h3>How long does delivery take?</h3>
          <span class="faq1-toggle">+</span>
        </div>
        <div class="faq1-answer">
          <p>Delivery usually takes 3–7 working days across India.</p>
        </div>
      </div>

      <div class="faq1-item">
        <div class="faq1-question" onclick="toggleFAQ1(this)">
          <h3>Can I return or replace products?</h3>
          <span class="faq1-toggle">+</span>
        </div>
        <div class="faq1-answer">
          <p>Yes, we offer easy return & replacement within 7 days of delivery.</p>
        </div>
      </div>

    </div>
  </div>
</section>
<style>
    .faq1-wrapper {
  max-width: 850px;
  margin: 60px auto 0;
}

.faq1-item {
  background: #ffffff;
  border-radius: 14px;
  margin-bottom: 16px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.07);
  overflow: hidden;
  transition: all 0.35s ease;
}

.faq1-question {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 24px;
  cursor: pointer;
  background: linear-gradient(90deg,#f8fdfb,#ffffff);
}

.faq1-question h3 {
  margin: 0;
  font-size: 16px;
  font-weight: 600;
  color: #1f3d2b;
}

.faq1-toggle {
  font-size: 26px;
  font-weight: bold;
  color: #4A7C59;
  transition: transform 0.4s ease;
}

.faq1-answer {
  max-height: 0;
  overflow: hidden;
  padding: 0 24px;
  transition: all 0.4s ease;
}

.faq1-answer p {
  margin: 0;
  padding: 18px 0 22px;
  color: #555;
  line-height: 1.7;
  font-size: 15px;
}

.faq1-item.active .faq1-answer {
  max-height: 220px;
}

.faq1-item.active .faq1-toggle {
  transform: rotate(45deg);
  color: #2f6b4f;
}

</style>


<script>
function toggleFAQ1(element) {
  const item = element.parentElement;
  const isActive = item.classList.contains('active');

  document.querySelectorAll('.faq1-item').forEach(i => {
    i.classList.remove('active');
  });

  if (!isActive) {
    item.classList.add('active');
  }
}
</script>
    
    
   <section class="unlock2-section">
  <div class="unlock2-overlay"></div>

  <div class="unlock2-container">

    <h2 class="unlock2-title">
      unlock offers & <br>
      subscribe for content
    </h2>

    <div class="unlock2-form">
      <input type="tel" placeholder="Enter Your Phone Number">
      <button>→</button>
    </div>

    <h3 class="unlock2-mail-title">JOIN OUR MAILING LIST</h3>

    <div class="unlock2-form unlock2-form-email">
      <input type="email" placeholder="Enter Email">
      <button>→</button>
    </div>

    <div class="unlock2-links">
      <div>SHOP ALL</div>
      <div>ABOUT</div>
      <div>OTHER LINKS</div>
    </div>

   <div class="unlock2-social">
  <i class="fab fa-instagram"></i>
  <i class="fab fa-facebook"></i>
  <i class="fab fa-youtube"></i>
  <i class="fab fa-twitter"></i>
</div>


  </div>
</section>

 <style>
     .unlock2-section {
  position: relative;
  width: 100%;
  min-height: 520px;
  background: url("assets/images/banners/imgi_66_unlockbanner.webp") center/cover no-repeat;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-family: 'Poppins', sans-serif;
}

.unlock2-overlay {
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,0.45);
}

.unlock2-container {
  position: relative;
  z-index: 2;
  width: 100%;
  max-width: 700px;
  text-align: center;
  padding: 40px 20px;
}

.unlock2-title {
  font-size: 34px;
  font-weight: 500;
  line-height: 1.2;
  margin-bottom: 20px;
  text-transform: lowercase;
}

.unlock2-form {
  display: flex;
  gap: 10px;
  margin-bottom: 30px;
}

.unlock2-form input {
  flex: 1;
  height: 50px;
  border-radius: 6px;
  border: none;
  padding: 0 15px;
  font-size: 15px;
}

.unlock2-form button {
  width: 52px;
  height: 50px;
  border: none;
  border-radius: 6px;
  background: #bcded0;
  font-size: 26px;
  cursor: pointer;
}

.unlock2-mail-title {
  font-size: 28px;
  font-weight: 500;
  margin: 10px 0 14px;
}

.unlock2-links {
  margin-top: 30px;
  text-align: left;
}

.unlock2-links div {
  padding: 12px 0;
  border-bottom: 1px solid rgba(255,255,255,0.4);
  font-size: 14px;
  letter-spacing: 1px;
}

.unlock2-social {
  margin-top: 20px;
  display: flex;
  gap: 20px;
  justify-content: center;
  font-size: 22px;
}

/* Responsive */
@media(max-width:768px){
  .unlock2-title { font-size: 26px; }
  .unlock2-mail-title { font-size: 22px; }
}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

 </style>
    <?php require_once __DIR__ . '/includes/footer.php'; ?>