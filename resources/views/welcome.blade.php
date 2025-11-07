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
    <link rel="stylesheet" href="{{ asset('css/animations.css') }}">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: #1a1a1a;
            background: #fafafa url('{{ asset('img/dcms_bg.jpg') }}') center/cover fixed;
            position: relative;
            margin: 0;
            padding: 0;
        }

        /* Animation Classes */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .slide-in-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .slide-in-left.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .slide-in-right {
            opacity: 0;
            transform: translateX(50px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .slide-in-right.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .scale-in {
            opacity: 0;
            transform: scale(0.8);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .scale-in.visible {
            opacity: 1;
            transform: scale(1);
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.55);
            z-index: -1;
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
            min-height: 85vh;
            display: flex;
            align-items: center;
            padding: 80px 0 40px;
            position: relative;
            overflow: hidden;
        }

        .hero-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 40px;
            display: grid;
            grid-template-columns: 1fr 1.3fr;
            gap: 50px;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .hero-content h1 {
            font-size: 42px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 20px;
            color: #1a1a1a;
        }

        .hero-content p {
            font-size: 16px;
            line-height: 1.7;
            color: #555;
            margin-bottom: 25px;
        }

        .btn-primary {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #00b4d8, #0077b6);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0, 119, 182, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 119, 182, 0.35);
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
            background: transparent;
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

        /* Services Section - Using Tailwind classes from dashboard */
        .service-card {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Pagination styling */
        #services-pagination .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }
        
        #services-pagination .page-item {
            list-style: none;
        }
        
        #services-pagination .page-link {
            padding: 0.5rem 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            color: #374151;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        #services-pagination .page-link:hover {
            background-color: #3b82f6;
            color: white;
        }
        
        #services-pagination .page-item.active .page-link {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        
        #services-pagination .page-item.disabled .page-link {
            color: #9ca3af;
            cursor: not-allowed;
        }

        /* About Section */
        .about-section {
            padding: 100px 40px;
            background: transparent;
            position: relative;
        }

        .about-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .about-content h2 {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #1a1a1a;
        }

        .about-content h2 span {
            color: #00b4d8;
        }

        .about-content p {
            font-size: 16px;
            line-height: 1.8;
            color: #666;
            margin-bottom: 25px;
        }

        .about-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 40px;
        }

        .stat-card {
            text-align: center;
            padding: 25px;
            background: linear-gradient(135deg, #00b4d8 0%, #03828b 100%);
            border-radius: 12px;
            color: white;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,180,216,0.3);
        }

        .stat-card h3 {
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .stat-card p {
            font-size: 14px;
            color: rgba(255,255,255,0.9);
            margin: 0;
        }

        .about-image {
            width: 100%;
            height: 500px;
            border-radius: 20px;
            object-fit: cover;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }

        .about-values {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 30px;
        }

        .value-item {
            display: flex;
            align-items: start;
            gap: 15px;
        }

        .value-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #00b4d8, #03828b);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 22px;
            flex-shrink: 0;
        }

        .value-text h4 {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 5px;
            color: #1a1a1a;
        }

        .value-text p {
            font-size: 14px;
            color: #666;
            margin: 0;
            line-height: 1.6;
        }

        /* Clinic Locations */
        .locations {
            padding: 80px 40px;
            background: transparent;
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
            background: transparent;
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
            background: transparent;
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
            color: white;
            padding: 30px 0;
            text-align: center;
            margin: 0;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .footer-content p {
            margin: 8px 0;
        }
        
        .footer-credits {
            font-size: 14px;
            opacity: 0.8;
        }
        
        .footer-credits strong {
            color: #00b4d8;
            font-weight: 600;
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
                <li><a href="#about">About</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#locations">Locations</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="{{ route('login') }}" class="btn-login">Login</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="animated-background">
            <div class="floating-circle circle-1"></div>
            <div class="floating-circle circle-2"></div>
            <div class="floating-circle circle-3"></div>
        </div>
        <div class="hero-container">
            <div class="hero-content">
                <h1>Robles-Moncayo Dental Clinic</h1>
                <p>Experience modern dentistry with compassionate service. We provide comprehensive dental solutions for the whole family.</p>
                <a href="{{ route('login') }}" class="btn-primary">Book Your Appointment</a>
            </div>
            
            <!-- AI Chatbot Component -->
            <x-lee-ai-chatbot type="public" />
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section fade-in" id="about">
        <div class="about-container">
            <div class="about-content slide-in-left">
                <h2><span>About</span> Our Clinic</h2>
                <p>
                    Welcome to Robles-Moncayo Dental Clinic, where your smile is our passion. With over a decade of experience in providing exceptional dental care, we combine modern technology with a personal touch to ensure every patient receives the highest quality treatment.
                </p>
                <p>
                    Our team of experienced dental professionals is dedicated to creating beautiful, healthy smiles in a comfortable and welcoming environment. We believe that excellent dental care goes beyond just treating teeth – it's about building lasting relationships with our patients and their families.
                </p>

                <div class="about-values">
                    <div class="value-item">
                        <div class="value-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="value-text">
                            <h4>Patient-Centered Care</h4>
                            <p>Your comfort and well-being are our top priorities</p>
                        </div>
                    </div>
                    <div class="value-item">
                        <div class="value-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="value-text">
                            <h4>Quality Assurance</h4>
                            <p>We use only the best materials and latest techniques</p>
                        </div>
                    </div>
                    <div class="value-item">
                        <div class="value-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="value-text">
                            <h4>Experienced Team</h4>
                            <p>Highly skilled professionals dedicated to your care</p>
                        </div>
                    </div>
                    <div class="value-item">
                        <div class="value-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="value-text">
                            <h4>Excellence</h4>
                            <p>Committed to delivering outstanding results</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="slide-in-right">
                <img src="{{ asset('img/dcms_iconmini(1).png') }}" alt="Robles-Moncayo Dental Clinic" class="about-image">
                
                <div class="about-stats scale-in">
                    <div class="stat-card">
                        <h3>10+</h3>
                        <p>Years Experience</p>
                    </div>
                    <div class="stat-card">
                        <h3>43+</h3>
                        <p>Services Offered</p>
                    </div>
                    <div class="stat-card">
                        <h3>2</h3>
                        <p>Clinic Locations</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <div class="py-10 fade-in" id="services">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white dark:bg-blue-800 shadow-sm rounded-lg relative">
            <div class="text-center font-semibold text-3xl text-gray-800 dark:text-white mt-6 mb-8 fade-in">
                <span class="text-blue-600">Our</span> Services
            </div>

            <!-- Grid Layout for Services (3 cards per row) -->
            <div id="services-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6 fade-in">
                <!-- Services will be loaded here via AJAX -->
            </div>

            <!-- Pagination -->
            <div id="services-pagination" class="flex justify-center py-6">
                <!-- Pagination will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Clinic Locations -->
    <section class="locations fade-in" id="locations">
        <div class="locations-container">
            <h2 class="section-title fade-in">Our Clinic Locations</h2>
            
            <div class="locations-grid">
                <div class="location-card slide-in-left">
                    <div id="map1" class="location-map"></div>
                    <div class="location-info">
                        <h3><i class="fas fa-map-marker-alt"></i> Clinic 1 - Morning Branch</h3>
                        <p><strong>Address:</strong> Unit F Medina Bldg, in front gate of Niog Elementary School, Bacoor, Cavite</p>
                        <p><strong>Hours:</strong> Monday to Saturday: 7:00 AM - 2:00 PM</p>
                        <p><strong>Phone:</strong> (+63) 912-3456-789</p>
                    </div>
                </div>

                <div class="location-card slide-in-right">
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

       <!-- Info Cards -->
    <section class="info-section fade-in">
        <div class="info-cards">
            <div class="info-card scale-in">
                <h3>24+</h3>
                <p>Dental Services</p>
            </div>
            <div class="info-card scale-in">
                <h3>50%</h3>
                <p>Discounted Rates</p>
            </div>
            <div class="info-card scale-in">
                <h3>2</h3>
                <p>Clinic Locations</p>
            </div>
        </div>
    </section>

  

    <!-- FAQs / Knowledge Base -->
    <section class="faqs fade-in" id="faqs">
        <div class="faqs-container">
            <h2 class="section-title fade-in">Oral Health Knowledge Base</h2>
            <p class="faqs-subtitle fade-in">Essential information for maintaining your dental health</p>
            
            <div class="knowledge-grid fade-in">
                <!-- Brushing Techniques -->
                <div class="knowledge-card scale-in">
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
                <div class="knowledge-card scale-in">
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
                <div class="knowledge-card scale-in">
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
                <div class="knowledge-card scale-in">
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
                <div class="knowledge-card scale-in">
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
                <div class="knowledge-card scale-in">
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
        // Services with AJAX Pagination
        $(document).ready(function() {
            // Load initial services
            loadServices(1);

            // Handle pagination clicks
            $(document).on('click', '#services-pagination .pagination a', function(e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                loadServices(page);
            });
            
            function loadServices(page) {
                $.ajax({
                    url: "/get-services?page=" + page,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#services-container').html(data.html);
                        $('#services-pagination').html(data.pagination);
                    },
                    error: function(xhr) {
                        console.error('Error loading services:', xhr);
                    }
                });
            }

            // Initialize maps after DOM is ready
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

        // Scroll Animation Observer
        const animateOnScroll = () => {
            const elements = document.querySelectorAll('.fade-in, .slide-in-left, .slide-in-right, .scale-in');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });
            
            elements.forEach(element => {
                observer.observe(element);
            });
        };

        // Initialize animations on page load
        document.addEventListener('DOMContentLoaded', () => {
            animateOnScroll();
        });
    </script>
    
</body>
</html>
```