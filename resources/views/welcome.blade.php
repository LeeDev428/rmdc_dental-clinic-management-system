<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('img/dcms_iconmini(1).png') }}" type="image/png">
    <title>RMDC - Robles Moncayo Dental Clinic</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: #1a1a1a;
            background: #fafafa;
        }

        /* Navigation */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            background: white;
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #2d2b2b;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            list-style: none;
            align-items: center;
        }

        .nav-links a {
            color: #666;
            text-decoration: none;
            font-weight: 400;
            font-size: 14px;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #333;
        }

        .btn-login {
            background: #00c8d7;
            color: white;
            padding: 10px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background: #03828b;
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 80px 0 50px;
            position: relative;
            overflow: hidden;
        }
        
        /* Animated Background */
        .animated-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #00f2fe 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            opacity: 0.1;
            z-index: 0;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .hero-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .hero-content h1 {
            font-size: 48px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 20px;
            color: #1a1a1a;
        }

        .hero-content p {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .hero-btn {
            background: #00c8d7;
            color: white;
            padding: 14px 32px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            display: inline-block;
            transition: all 0.3s;
        }

        .hero-btn:hover {
            background: #03747c;
        }

        .hero-image {
            background: linear-gradient(135deg, #555 0%, #777 100%);
            border-radius: 12px;
            height: 450px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .hero-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        /* Info Cards */
        .info-section {
            background: #fafafa;
            padding: 40px 0;
        }

        .info-cards {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 40px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .info-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            text-align: center;
        }

        .info-card h3 {
            font-size: 36px;
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
        }

        .info-card p {
            color: #666;
            font-size: 14px;
        }

        /* Services Section */
        .services {
            padding: 80px 40px;
            background: white;
        }

        .services-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 50px;
            color: #1a1a1a;
        }

        .services-carousel {
            position: relative;
            overflow: hidden;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            min-height: 500px;
        }

        .service-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: all 0.3s;
        }

        .service-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }

        .service-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            background: linear-gradient(135deg, #555, #777);
        }

        .service-content {
            padding: 20px;
        }

        .service-content h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .service-content p {
            color: #666;
            font-size: 13px;
            margin-bottom: 16px;
            line-height: 1.5;
        }

        .service-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 12px;
            border-top: 1px solid #eee;
        }

        .service-price {
            font-size: 18px;
            font-weight: 700;
            color: #333;
        }

        .carousel-controls {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
            align-items: center;
        }

        .carousel-btn {
            background: #333;
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .carousel-btn:hover {
            background: #555;
        }

        .carousel-indicators {
            display: flex;
            gap: 8px;
            align-items: center;
            font-size: 14px;
            color: #666;
            font-weight: 500;
        }

        /* Clinic Locations */
        .locations {
            padding: 80px 40px;
            background: #fafafa;
        }

        .locations-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .locations-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            margin-top: 40px;
        }

        .location-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .location-map {
            width: 100%;
            height: 300px;
        }

        .location-info {
            padding: 20px;
        }

        .location-info h3 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }

        .location-info p {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }

        /* Contact Section */
        .contact {
            padding: 60px 40px;
            background: #fafafa;
        }

        .contact-container {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .contact h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .contact p {
            color: #666;
            font-size: 15px;
            margin-bottom: 30px;
        }

        .contact-info {
            display: flex;
            justify-content: center;
            gap: 50px;
            margin-top: 30px;
        }

        .contact-item {
            font-size: 15px;
            color: #666;
        }

        .contact-item strong {
            color: #333;
            font-weight: 600;
        }

        /* FAQs Section */
        .faqs {
            padding: 80px 40px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8edf2 100%);
        }

        .faqs-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .faqs-subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 50px;
            font-size: 16px;
        }

        .knowledge-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-bottom: 60px;
        }

        .knowledge-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: all 0.3s;
            text-align: center;
        }

        .knowledge-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }

        .knowledge-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #00c8d7 0%, #03747c 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 32px;
        }

        .knowledge-card h3 {
            font-size: 20px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 15px;
        }

        .knowledge-card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .knowledge-tips {
            list-style: none;
            padding: 0;
            margin: 0;
            text-align: left;
        }

        .knowledge-tips li {
            padding: 8px 0;
            color: #555;
            font-size: 13px;
            position: relative;
            padding-left: 20px;
        }

        .knowledge-tips li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #00c8d7;
            font-weight: bold;
        }

        .faq-section {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .faq-title {
            font-size: 24px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 30px;
            text-align: center;
        }

        .faq-item {
            border-bottom: 1px solid #eee;
            margin-bottom: 15px;
        }

        .faq-item:last-child {
            border-bottom: none;
        }

        .faq-question {
            padding: 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: 500;
            color: #333;
        }

        .faq-question:hover {
            background: #e9ecef;
        }

        .faq-question i {
            transition: transform 0.3s;
            color: #00c8d7;
        }

        .faq-question.active i {
            transform: rotate(180deg);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out, padding 0.3s ease-out;
            color: #666;
            line-height: 1.8;
            padding: 0 20px;
        }

        .faq-answer.active {
            max-height: 300px;
            padding: 20px;
        }

        /* Footer */
        footer {
            background: #1a1a1a;
            color: #999;
            text-align: center;
            padding: 25px;
            font-size: 13px;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .hero-container {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            
            .hero-content h1 {
                font-size: 36px;
            }
            
            .info-cards {
                grid-template-columns: 1fr;
            }

            .services-slide {
                grid-template-columns: 1fr;
            }

            .locations-grid {
                grid-template-columns: 1fr;
            }
            
            .contact-info {
                flex-direction: column;
                gap: 20px;
            }

            .knowledge-grid {
                grid-template-columns: 1fr;
            }

            .faq-section {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-container">
            <a href="#" class="logo" style="display: flex; align-items: center; gap: 10px;">
                <img src="{{ asset('img/dcms_iconmini(1).png') }}" alt="RMDC Logo" style="height: 40px; width: auto;">
                <span>RMDC</span>
            </a>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#lee-ai">Lee AI</a></li>
                <li><a href="#locations">Locations</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="{{ route('login') }}" class="btn-login">Login</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
        <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="animated-background"></div>
        <div class="hero-container">
            <div class="hero-content">
                <h1>Dr. Cristina Moncayo Dental Clinic</h1>
                <p>Experience modern dentistry with compassionate service. We provide comprehensive dental solutions for the whole family in Bacoor.</p>
                <a href="{{ route('login') }}" class="btn-primary">Book Your Appointment</a>
            </div>
            
            <!-- AI Chatbot replacing hero-image -->
            <div class="hero-chatbot" id="lee-ai">
                <div class="chatbot-card">
                    <div class="chatbot-header">
                        <div class="chatbot-avatar">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div class="chatbot-header-content">
                            <h3>Lee AI Assistant</h3>
                            <p class="chatbot-description">Your 24/7 dental health companion powered by AI</p>
                            <div class="chatbot-status">
                                <span class="status-dot"></span>
                                <span class="status-text">Online - Ready to help!</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="chatbot-messages" id="publicChatMessages">
                        <div class="welcome-message">
                            <i class="fas fa-tooth"></i>
                            <p>Hello! I'm Lee AI, your dental health assistant. I can help you with:</p>
                            <ul>
                                <li>Dental care tips and advice</li>
                                <li>Information about our services</li>
                                <li>Booking guidance</li>
                                <li>General dental health questions</li>
                            </ul>
                            <p>How can I assist you today?</p>
                        </div>
                        <div class="typing-indicator" id="typingIndicator" style="display: none;">
                            <div class="typing-dots">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                            <span class="typing-text">Lee AI is typing...</span>
                        </div>
                    </div>                    <form id="publicChatForm" class="chatbot-input-form">
                        @csrf
                        <div class="chatbot-input-wrapper">
                            <input 
                                type="text" 
                                id="publicChatInput" 
                                placeholder="Ask about dental services, tooth pain, etc..." 
                                maxlength="500"
                                required
                            />
                            <button type="submit" id="publicSendBtn">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                    
                    <p class="chatbot-footer-text">
                        <i class="fas fa-lock"></i> Your privacy is protected
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Info Cards -->
    <section class="info-section">
        <div class="info-cards">
            <div class="info-card">
                <h3>24+</h3>
                <p>Dental Services</p>
            </div>
            <div class="info-card">
                <h3>50%</h3>
                <p>Discounted Rates</p>
            </div>
            <div class="info-card">
                <h3>2</h3>
                <p>Clinic Locations</p>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
        <div class="services-container">
            <h2 class="section-title">Our Services</h2>
            
            <div class="services-carousel">
                <div class="services-grid" id="servicesGrid">
                    <!-- Services will be loaded here via AJAX -->
                </div>
            </div>

            <div class="carousel-controls">
                <button class="carousel-btn" id="prevBtn" onclick="changePage('prev')">
                    <i class="fas fa-chevron-left"></i>
                </button>
                
                <div class="carousel-indicators" id="paginationInfo">
                    <span id="currentPage">1</span> / <span id="totalPages">1</span>
                </div>
                
                <button class="carousel-btn" id="nextBtn" onclick="changePage('next')">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Clinic Locations -->
    <section class="locations" id="locations">
        <div class="locations-container">
            <h2 class="section-title">Our Clinic Locations</h2>
            
            <div class="locations-grid">
                <div class="location-card">
                    <div id="map1" class="location-map"></div>
                    <div class="location-info">
                        <h3><i class="fas fa-map-marker-alt"></i> Clinic 1 - Morning Branch</h3>
                        <p><strong>Address:</strong> Unit F Medina Bldg, in front gate of Niog Elementary School, Bacoor, Cavite</p>
                        <p><strong>Hours:</strong> Monday to Saturday: 7:00 AM - 2:00 PM</p>
                        <p><strong>Phone:</strong> (+63) 912-3456-789</p>
                    </div>
                </div>

                <div class="location-card">
                    <div id="map2" class="location-map"></div>
                    <div class="location-info">
                        <h3><i class="fas fa-map-marker-alt"></i> Clinic 2 - Afternoon & Evening</h3>
                        <p><strong>Address:</strong> Marigold corner Hyacinth Sts, F E De Castro Village, Bacoor, Cavite</p>
                        <p><strong>Hours:</strong> Mon-Sat: 3:00 PM - 8:00 PM | Sunday: 1:00 PM - 8:00 PM</p>
                        <p><strong>Phone:</strong> (+63) 912-3456-790</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

  

    <!-- FAQs / Knowledge Base -->
    <section class="faqs" id="faqs">
        <div class="faqs-container">
            <h2 class="section-title">Oral Health Knowledge Base</h2>
            <p class="faqs-subtitle">Essential information for maintaining your dental health</p>
            
            <div class="knowledge-grid">
                <!-- Brushing Techniques -->
                <div class="knowledge-card">
                    <div class="knowledge-icon">
                        <i class="fas fa-tooth"></i>
                    </div>
                    <h3>Proper Brushing Techniques</h3>
                    <p>Brush twice daily for 2 minutes using circular motions. Hold your brush at a 45-degree angle to your gums. Don't forget to brush your tongue!</p>
                    <ul class="knowledge-tips">
                        <li>Use soft-bristled toothbrush</li>
                        <li>Replace every 3-4 months</li>
                        <li>Use fluoride toothpaste</li>
                    </ul>
                </div>

                <!-- Flossing -->
                <div class="knowledge-card">
                    <div class="knowledge-icon">
                        <i class="fas fa-teeth"></i>
                    </div>
                    <h3>Daily Flossing</h3>
                    <p>Floss at least once daily to remove plaque and food particles between teeth where your brush can't reach. This prevents gum disease and cavities.</p>
                    <ul class="knowledge-tips">
                        <li>Use 18 inches of floss</li>
                        <li>Curve around each tooth</li>
                        <li>Use clean sections for each tooth</li>
                    </ul>
                </div>

                <!-- Nutrition -->
                <div class="knowledge-card">
                    <div class="knowledge-icon">
                        <i class="fas fa-apple-alt"></i>
                    </div>
                    <h3>Dental-Friendly Nutrition</h3>
                    <p>Your diet affects your oral health. Limit sugary foods and drinks. Choose calcium-rich foods like dairy products, leafy greens, and almonds.</p>
                    <ul class="knowledge-tips">
                        <li>Drink plenty of water</li>
                        <li>Eat crunchy vegetables</li>
                        <li>Avoid frequent snacking</li>
                    </ul>
                </div>

                <!-- Regular Checkups -->
                <div class="knowledge-card">
                    <div class="knowledge-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3>Regular Dental Visits</h3>
                    <p>Visit your dentist every 6 months for professional cleaning and examination. Early detection prevents serious dental problems.</p>
                    <ul class="knowledge-tips">
                        <li>Professional cleaning twice yearly</li>
                        <li>X-rays when needed</li>
                        <li>Don't skip appointments</li>
                    </ul>
                </div>

                <!-- Warning Signs -->
                <div class="knowledge-card">
                    <div class="knowledge-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3>Warning Signs</h3>
                    <p>Contact your dentist immediately if you experience: bleeding gums, persistent bad breath, tooth sensitivity, or mouth pain.</p>
                    <ul class="knowledge-tips">
                        <li>Swollen or red gums</li>
                        <li>Loose teeth</li>
                        <li>Jaw pain or clicking</li>
                    </ul>
                </div>

                <!-- Prevention -->
                <div class="knowledge-card">
                    <div class="knowledge-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Prevention Tips</h3>
                    <p>Prevention is better than cure. Use mouthwash, avoid tobacco, limit alcohol, and protect your teeth during sports activities.</p>
                    <ul class="knowledge-tips">
                        <li>Use fluoride mouthwash</li>
                        <li>Wear mouth guards for sports</li>
                        <li>Don't use teeth as tools</li>
                    </ul>
                </div>
            </div>

            <!-- FAQ Accordion -->
            <div class="faq-section">
                <h3 class="faq-title">Frequently Asked Questions</h3>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>How often should I brush my teeth?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        You should brush your teeth at least twice a day - once in the morning and once before bed. Each brushing session should last at least 2 minutes to effectively remove plaque and food particles.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>Is teeth whitening safe?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Professional teeth whitening performed by a dentist is safe and effective. Over-the-counter products can also be safe when used as directed. Always consult with your dentist before starting any whitening treatment.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>What causes tooth sensitivity?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Tooth sensitivity can be caused by worn enamel, exposed roots, cavities, cracked teeth, or gum disease. Using toothpaste for sensitive teeth and avoiding acidic foods can help. Consult your dentist for persistent sensitivity.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>Do I need to floss if I brush regularly?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Yes! Brushing alone cannot reach the tight spaces between teeth where food particles and plaque accumulate. Flossing daily is essential to prevent cavities and gum disease between teeth.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>When should children start visiting the dentist?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Children should have their first dental visit when their first tooth appears or by their first birthday. Early visits help children become comfortable with dental care and allow dentists to catch any issues early.
                    </div>
                </div>
            </div>
        </div>
    </section>

      <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="contact-container">
            <h2>Get In Touch</h2>
            <p>Ready to schedule your appointment? Contact us today!</p>
            <div class="contact-info">
                <div class="contact-item">
                    <strong>Phone:</strong> (+63) 912-3456-789
                </div>
                <div class="contact-item">
                    <strong>Email:</strong> robles_moncayo@yahoo.com
                </div>
            </div>
        </div>
    </section>


    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p>&copy; 2025 Dr. Cristina Moncayo Dental Clinic. All Rights Reserved.</p>
            <p class="footer-credits">Website & AI Creator: <strong>Lee Rafael Torres</strong></p>
        </div>
    </footer>

    <script>
        // Services Carousel with AJAX Pagination
        let currentPage = 1;
        let totalPages = 1;

        function loadServices(page) {
            fetch(`/get-services?page=${page}`)
                .then(response => response.json())
                .then(data => {
                    const grid = document.getElementById('servicesGrid');
                    grid.innerHTML = '';
                    
                    data.data.forEach(procedure => {
                        const card = document.createElement('div');
                        card.className = 'service-card';
                        
                        let imageHtml = '';
                        if (procedure.image_path) {
                            imageHtml = `<img src="/storage/${procedure.image_path}" alt="${procedure.procedure_name}" class="service-image">`;
                        } else {
                            imageHtml = '<div class="service-image"></div>';
                        }
                        
                        const description = procedure.description ? 
                            (procedure.description.length > 80 ? procedure.description.substring(0, 80) + '...' : procedure.description) : '';
                        
                        card.innerHTML = `
                            ${imageHtml}
                            <div class="service-content">
                                <h3>${procedure.procedure_name}</h3>
                                <p>${description}</p>
                                <div class="service-meta">
                                    <span style="font-size: 12px; color: #999;"><i class="far fa-clock"></i> ${procedure.duration} mins</span>
                                    <span class="service-price">₱${Number(procedure.price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                                </div>
                            </div>
                        `;
                        
                        grid.appendChild(card);
                    });
                    
                    currentPage = data.current_page;
                    totalPages = data.last_page;
                    
                    document.getElementById('currentPage').textContent = currentPage;
                    document.getElementById('totalPages').textContent = totalPages;
                    
                    // Update button states
                    document.getElementById('prevBtn').disabled = currentPage === 1;
                    document.getElementById('nextBtn').disabled = currentPage === totalPages;
                })
                .catch(error => console.error('Error loading services:', error));
        }

        function changePage(direction) {
            if (direction === 'prev' && currentPage > 1) {
                loadServices(currentPage - 1);
            } else if (direction === 'next' && currentPage < totalPages) {
                loadServices(currentPage + 1);
            }
        }

        // Load services on page load
        document.addEventListener('DOMContentLoaded', () => {
            loadServices(1);
            initMaps();
        });

        // Initialize Maps
        function initMaps() {
            // Main Clinic Map
            const map1 = L.map('map1').setView([14.4164, 120.9539], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map1);
            L.marker([14.4164, 120.9539]).addTo(map1)
                .bindPopup('<b>RMDC Main Clinic</b><br>Bacoor, Cavite')
                .openPopup();

            // Branch Clinic Map
            const map2 = L.map('map2').setView([14.4200, 120.9600], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map2);
            L.marker([14.4200, 120.9600]).addTo(map2)
                .bindPopup('<b>RMDC Branch</b><br>Bacoor, Cavite')
                .openPopup();
        }

        // FAQ Toggle Function
        function toggleFAQ(element) {
            const answer = element.nextElementSibling;
            const isActive = answer.classList.contains('active');
            
            // Close all FAQs
            document.querySelectorAll('.faq-answer').forEach(item => {
                item.classList.remove('active');
            });
            document.querySelectorAll('.faq-question').forEach(item => {
                item.classList.remove('active');
            });
            
            // Open clicked FAQ if it wasn't already open
            if (!isActive) {
                answer.classList.add('active');
                element.classList.add('active');
            }
        }
        
        // ============================================
        // PUBLIC CHATBOT FUNCTIONALITY
        // ============================================
        const publicChatForm = document.getElementById('publicChatForm');
        const publicChatInput = document.getElementById('publicChatInput');
        const publicChatMessages = document.getElementById('publicChatMessages');
        const publicSendBtn = document.getElementById('publicSendBtn');

        function addPublicMessage(message, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `chat-message ${isUser ? 'user-message' : 'bot-message'}`;
            
            const icon = isUser ? '<i class="fas fa-user"></i>' : '<i class="fas fa-robot"></i>';
            const time = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            
            messageDiv.innerHTML = `
                <div class="message-content">
                    <div class="message-icon">${icon}</div>
                    <div class="message-text">
                        <p>${message}</p>
                        <span class="message-time">${time}</span>
                    </div>
                </div>
            `;
            
            publicChatMessages.appendChild(messageDiv);
            publicChatMessages.scrollTop = publicChatMessages.scrollHeight;
        }

        publicChatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const question = publicChatInput.value.trim();
            if (!question) return;
            
            // Add user message
            addPublicMessage(question, true);
            
            // Clear input and disable
            publicChatInput.value = '';
            publicChatInput.disabled = true;
            publicSendBtn.disabled = true;
            publicSendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            try {
                const response = await fetch('/ask-gemini-public', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ question: question })
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    addPublicMessage(data.response, false);
                } else {
                    addPublicMessage('Sorry, I encountered an error. Please try again!', false);
                }
            } catch (error) {
                console.error('Error:', error);
                addPublicMessage('Connection error. Please check your internet and try again.', false);
            } finally {
                publicChatInput.disabled = false;
                publicSendBtn.disabled = false;
                publicSendBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
                publicChatInput.focus();
            }
        });
    </script>
    
    <style>
        /* ============================================ */
        /* CHATBOT STYLES */
        /* ============================================ */
        .hero-chatbot {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .chatbot-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 580px;
            overflow: hidden;
            border: 2px solid #f0f0f0;
        }
        
        .chatbot-header {
            background: linear-gradient(135deg, #00b4d8 0%, #0077b6 100%);
            padding: 20px;
            color: white;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .chatbot-header-content {
            flex: 1;
        }
        
        .chatbot-avatar {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #0077b6;
            flex-shrink: 0;
        }
        
        .chatbot-header h3 {
            margin: 0 0 4px 0;
            font-size: 18px;
            font-weight: 600;
        }
        
        .chatbot-description {
            margin: 0 0 8px 0;
            font-size: 13px;
            opacity: 0.9;
        }
        
        .chatbot-status {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }
        
        .status-text {
            opacity: 0.95;
        }
        
        .status-dot {
            width: 8px;
            height: 8px;
            background: #4ade80;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .chatbot-messages {
            height: 450px;
            overflow-y: auto;
            padding: 20px;
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .welcome-message {
            background: white;
            padding: 15px;
            border-radius: 12px;
            border-left: 4px solid #00b4d8;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .welcome-message i {
            font-size: 24px;
            color: #00b4d8;
            margin-bottom: 10px;
        }
        
        .welcome-message p {
            margin: 8px 0;
            color: #333;
            line-height: 1.6;
            font-size: 14px;
        }
        
        .welcome-message ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        
        .welcome-message li {
            margin: 5px 0;
            color: #555;
            font-size: 14px;
        }
        
        /* Typing Indicator */
        .typing-indicator {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            max-width: 200px;
        }
        
        .typing-dots {
            display: flex;
            gap: 4px;
        }
        
        .typing-dots span {
            width: 8px;
            height: 8px;
            background: #00b4d8;
            border-radius: 50%;
            animation: typingBounce 1.4s infinite;
        }
        
        .typing-dots span:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        .typing-dots span:nth-child(3) {
            animation-delay: 0.4s;
        }
        
        @keyframes typingBounce {
            0%, 60%, 100% {
                transform: translateY(0);
            }
            30% {
                transform: translateY(-10px);
            }
        }
        
        .typing-text {
            font-size: 13px;
            color: #666;
            font-style: italic;
        }
        
        .chat-message {
            display: flex;
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .message-content {
            display: flex;
            gap: 10px;
            max-width: 85%;
        }
        
        .user-message {
            justify-content: flex-end;
        }
        
        .user-message .message-content {
            flex-direction: row-reverse;
        }
        
        .message-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 16px;
        }
        
        .bot-message .message-icon {
            background: linear-gradient(135deg, #00b4d8 0%, #0077b6 100%);
            color: white;
        }
        
        .user-message .message-icon {
            background: #e9ecef;
            color: #495057;
        }
        
        .message-text {
            flex: 1;
        }
        
        .message-text p {
            background: white;
            padding: 12px 16px;
            border-radius: 12px;
            margin: 0 0 4px 0;
            color: #333;
            line-height: 1.5;
            font-size: 14px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .user-message .message-text p {
            background: #00b4d8;
            color: white;
        }
        
        .message-time {
            font-size: 11px;
            color: #999;
            padding-left: 4px;
        }
        
        .chatbot-input-form {
            padding: 15px;
            background: white;
            border-top: 1px solid #e9ecef;
        }
        
        .chatbot-input-wrapper {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .chatbot-input-wrapper input {
            flex: 1;
            padding: 12px 16px;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            font-size: 14px;
            outline: none;
            transition: all 0.3s;
        }
        
        .chatbot-input-wrapper input:focus {
            border-color: #00b4d8;
            box-shadow: 0 0 0 3px rgba(0, 180, 216, 0.1);
        }
        
        .chatbot-input-wrapper button {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: none;
            background: linear-gradient(135deg, #00b4d8 0%, #0077b6 100%);
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .chatbot-input-wrapper button:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 119, 182, 0.3);
        }
        
        .chatbot-input-wrapper button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: scale(1);
        }
        
        .chatbot-footer-text {
            text-align: center;
            font-size: 12px;
            color: #999;
            margin: 10px 0 0 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        
        .chatbot-footer-text i {
            font-size: 10px;
        }
        
        /* Scrollbar styling */
        .chatbot-messages::-webkit-scrollbar {
            width: 6px;
        }
        
        .chatbot-messages::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .chatbot-messages::-webkit-scrollbar-thumb {
            background: #00b4d8;
            border-radius: 3px;
        }
        
        .chatbot-messages::-webkit-scrollbar-thumb:hover {
            background: #0077b6;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-container {
                flex-direction: column;
            }
            
            .chatbot-card {
                max-width: 100%;
                margin-top: 30px;
            }
            
            .chatbot-messages {
                height: 300px;
            }
        }
    </style>
</body>
</html>
```