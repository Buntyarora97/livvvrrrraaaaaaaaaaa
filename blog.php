<?php  
$header_path = __DIR__ . '/includes/header.php';  
if (file_exists($header_path)) {  
    include $header_path;  
} else {  
    include 'header.php';  
}  
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIVVRA Ayurveda Blog - Wellness & Health</title>
    <meta name="description" content="Discover Ayurvedic wisdom for modern wellness. Immunity boosting herbs, daily routines, preventive healthcare, and holistic balance.">
    
    <style>
        :root {
            --livvra-deep-green: #556B2F;
            --livvra-olive-green: #6B8E23;
            --livvra-light-green: #9ACD32;
            --livvra-gold: #FFD700;
            --livvra-cream: #FDF6E3;
            --livvra-text-dark: #2C3E50;
            --livvra-shadow: 0 8px 32px rgba(85, 107, 47, 0.2);
        }
        
        body {
            background: var(--livvra-cream);
            color: var(--livvra-text-dark);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        
        .livvra-blog-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .livvra-page-hero {
            text-align: center;
            background: linear-gradient(135deg, var(--livvra-deep-green), var(--livvra-olive-green));
            color: white;
            padding: 80px 20px;
            border-radius: 20px;
            margin-bottom: 60px;
            box-shadow: var(--livvra-shadow);
        }
        
        .livvra-hero-title {
            font-size: 3rem;
            margin: 0 0 20px;
            font-weight: 700;
        }
        
        .livvra-hero-subtitle {
            font-size: 1.3rem;
            opacity: 0.95;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .livvra-blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .livvra-blog-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--livvra-shadow);
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
            height: 510px; /* Increased from 420px */
            display: flex;
            flex-direction: column;
        }
        
        .livvra-blog-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 48px rgba(85, 107, 47, 0.3);
        }
        
        .livvra-card-image {
            height: 250px; /* Increased from 200px - ab bada image! */
            background-size: cover;
            background-position: center;
            position: relative;
            flex-shrink: 0;
        }
   
        
        .livvra-card-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            /* background: linear-gradient(45deg, var(--livvra-deep-green), var(--livvra-gold)); */
            opacity: 0.7;
        }
        
        .livvra-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.8);
        }
        
        .livvra-card-date {
            position: absolute;
            bottom: -243px;
            right: 15px;
            background: var(--livvra-gold);
            color: var(--livvra-deep-green);
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(255, 215, 0, 0.4);
            z-index: 2;
        }
        
        .livvra-card-content {
            padding: 25px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .livvra-card-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0 0 12px;
            color: var(--livvra-deep-green);
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .livvra-card-excerpt {
            color: var(--livvra-text-dark);
            font-size: 0.95rem;
            margin-bottom: 20px;
            flex-grow: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .livvra-read-more {
            display: inline-block;
            background: linear-gradient(135deg, var(--livvra-olive-green), var(--livvra-light-green));
            color: white;
            padding: 12px 24px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            align-self: flex-start;
        }
        
        .livvra-read-more:hover {
            transform: translateX(8px);
            box-shadow: 0 8px 24px rgba(107, 142, 35, 0.4);
        }
        
        @media (max-width: 768px) {
            .livvra-hero-title {
                font-size: 2.2rem;
            }
            .livvra-blog-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            .livvra-blog-card {
                height: 540px; /* Mobile me bhi bada */
            }
            .livvra-card-image {
                height: 250px; /* Mobile me aur bada */
            }
        }
        
        .livvra-section-title {
            text-align: center;
            font-size: 2.5rem;
            color: var(--livvra-deep-green);
            margin-bottom: 10px;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="livvra-blog-container">
        <section class="livvra-page-hero">
            <h1 class="livvra-hero-title">Ayurveda Wellness Blog</h1>
            <p class="livvra-hero-subtitle">Ancient wisdom meets modern living. Discover herbal remedies, daily routines, and holistic health principles for immunity, balance, and vitality.</p>
        </section>
        
        <h2 class="livvra-section-title">Latest Articles</h2>
        
        <div class="livvra-blog-grid">
            <!-- Blog Card 1 -->
            <div class="livvra-blog-card">
                <div class="livvra-card-image" style="background-image: url('assets/images/blogs/blog1.png');">
                    <div class="livvra-card-date">📅 8 January 2026</div>
                </div>
                <div class="livvra-card-content">
                    <h3 class="livvra-card-title">
                        <a href="/daily-immunity-boost-ayurvedic-herbs-modern-wellness.php" style="color: inherit; text-decoration: none;">Daily Immunity Boost: The Role of Ayurvedic Herbs in Modern Wellness</a>
                    </h3>
                    <p class="livvra-card-excerpt">Discover how Ayurvedic herbs support daily immunity and overall wellness. Learn how ancient remedies fit seamlessly into modern healthy living.</p>
                    <a href="/daily-immunity-boost-ayurvedic-herbs-modern-wellness.php" class="livvra-read-more">Read More →</a>
                </div>
            </div>
            
            <!-- Blog Card 2 -->
            <div class="livvra-blog-card">
                <div class="livvra-card-image" style="background-image: url('assets/images/blogs/blog2.png');">
                    <div class="livvra-card-date">📅 12 January 2026</div>
                </div>
                <div class="livvra-card-content">
                    <h3 class="livvra-card-title">
                        <a href="/ayurveda-2026-modern-preventive-healthcare.php" style="color: inherit; text-decoration: none;">Ayurveda in 2026: How Ancient Herbal Science Fits Modern Preventive Healthcare</a>
                    </h3>
                    <p class="livvra-card-excerpt">Explore how Ayurveda in 2026 is shaping modern preventive healthcare through herbal science, immunity building, lifestyle balance, and holistic wellness.</p>
                    <a href="/ayurveda-2026-modern-preventive-healthcare.php" class="livvra-read-more">Read More →</a>
                </div>
            </div>
            
            <!-- Blog Card 3 -->
            <div class="livvra-blog-card">
                <div class="livvra-card-image" style="background-image: url('assets/images/blogs/blog3.png');">
                    <div class="livvra-card-date">📅 18 January 2026</div>
                </div>
                <div class="livvra-card-content">
                    <h3 class="livvra-card-title">
                        <a href="/7-step-daily-ayurvedic-routine-optimal-health.php" style="color: inherit; text-decoration: none;">7-Step Daily Ayurvedic Routine for Optimal Health and Wellness</a>
                    </h3>
                    <p class="livvra-card-excerpt">Follow a 7-step daily Ayurvedic routine designed for modern life to improve digestion, immunity, energy, and long-term holistic wellness.</p>
                    <a href="/7-step-daily-ayurvedic-routine-optimal-health.php" class="livvra-read-more">Read More →</a>
                </div>
            </div>
            
            <!-- Blog Card 4 -->
            <div class="livvra-blog-card">
                <div class="livvra-card-image" style="background-image: url('assets/images/blogs/blog4.png');">
                    <div class="livvra-card-date">📅 22 January 2026</div>
                </div>
                <div class="livvra-card-content">
                    <h3 class="livvra-card-title">
                        <a href="/ayurveda-modern-diets-balanced-weight-management.php" style="color: inherit; text-decoration: none;">Ayurveda and Modern Diets: A Balanced Approach to Weight Management</a>
                    </h3>
                    <p class="livvra-card-excerpt">Learn how Ayurveda complements modern diets for sustainable weight management through digestion balance, mindful eating, and lifestyle alignment.</p>
                    <a href="/ayurveda-modern-diets-balanced-weight-management.php" class="livvra-read-more">Read More →</a>
                </div>
            </div>
            
            <!-- Blog Card 5 -->
            <div class="livvra-blog-card">
                <div class="livvra-card-image" style="background-image: url('assets/images/blogs/blog5.png');">
                    <div class="livvra-card-date">📅 25 January 2026</div>
                </div>
                <div class="livvra-card-content">
                    <h3 class="livvra-card-title">
                        <a href="/core-principles-of-ayurveda-modern-life.php" style="color: inherit; text-decoration: none;">Core Principles of Ayurveda: Their Importance and Relevance in Today's Life</a>
                    </h3>
                    <p class="livvra-card-excerpt">Understand the core principles of Ayurveda and why they remain deeply relevant in today's fast-paced lifestyle for health, balance, and well-being.</p>
                    <a href="/core-principles-of-ayurveda-modern-life.php" class="livvra-read-more">Read More →</a>
                </div>
            </div>
        </div>
    </div>

<?php  
$footer_path = __DIR__ . '/includes/footer.php';  
if (file_exists($footer_path)) {  
    include $footer_path;  
} else {  
    include 'footer.php';  
}  
?>  
</body>
</html>
