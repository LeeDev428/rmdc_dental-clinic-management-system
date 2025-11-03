<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('img/dcms_iconmini(1).png') }}" type="image/png">
    <title>RMDC - Robles Moncayo Dental Clinic</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #0084ff;
            --primary-dark: #0070e0;
            --text-dark: #1a1a1a;
            --text-gray: #6b7280;
            --bg-light: #f8f9fa;
            --white: #ffffff;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Navigation */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }

        nav.scrolled {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
            text-decoration: none;
        }

        .logo img {
            width: 40px;
            height: 40px;
        }

        .nav-links {
            display: flex;
            gap: 32px;
            list-style: none;
            align-items: center;
        }

        .nav-links a {
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--primary-color);
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 132, 255, 0.3);
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--text-dark);
        }

        /* Hero Section */
        .hero {
            margin-top: 72px;
            min-height: calc(100vh - 72px);
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #5EBDCC 0%, #3A9FB5 100%);
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('{{ asset('img/dcms_bg.jpg') }}') center/cover;
            opacity: 0.15;
        }

        .hero-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 80px 24px;
            position: relative;
            z-index: 1;
            text-align: center;
            color: white;
        }

        .hero h1 {
            font-size: 56px;
            font-weight: 700;
            margin-bottom: 24px;
            line-height: 1.2;
            animation: fadeInUp 0.8s ease;
        }

        .hero p {
            font-size: 20px;
            margin-bottom: 40px;
            opacity: 0.95;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            animation: fadeInUp 1s ease;
        }

        .hero-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
            animation: fadeInUp 1.2s ease;
        }

        .btn-hero {
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-hero-primary {
            background: white;
            color: var(--primary-color);
        }

        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3);
        }

        .btn-hero-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-hero-secondary:hover {
            background: white;
            color: var(--primary-color);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Sections */
        section {
            padding: 80px 24px;
        }

        .section-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-header h2 {
            font-size: 40px;
            font-weight: 700;
            margin-bottom: 16px;
            color: var(--text-dark);
        }

        .section-header p {
            font-size: 18px;
            color: var(--text-gray);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Stats Section */
        .stats {
            background: var(--bg-light);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 32px;
        }

        .stat-card {
            background: white;
            padding: 32px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 28px;
        }

        .stat-card h3 {
            font-size: 36px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 8px;
        }

        .stat-card p {
            color: var(--text-gray);
            font-size: 16px;
        }

        /* Services Section */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 24px;
        }

        .service-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
        }

        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .service-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .service-content {
            padding: 24px;
        }

        .service-content h3 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 12px;
            color: var(--text-dark);
        }

        .service-content p {
            color: var(--text-gray);
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .service-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }

        .service-duration {
            font-size: 14px;
            color: var(--text-gray);
        }

        .service-price {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-color);
        }

        /* Clinics Section */
        .clinics {
            background: var(--bg-light);
        }

        .clinics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 32px;
        }

        .clinic-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .clinic-map {
            width: 100%;
            height: 300px;
        }

        .clinic-info {
            padding: 32px;
        }

        .clinic-info h3 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 16px;
            color: var(--text-dark);
        }

        .clinic-detail {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 16px;
        }

        .clinic-detail i {
            color: var(--primary-color);
            font-size: 18px;
            width: 20px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .clinic-detail-content {
            flex: 1;
        }

        .clinic-detail-content strong {
            display: block;
            margin-bottom: 4px;
            color: var(--text-dark);
        }

        .clinic-detail-content p {
            color: var(--text-gray);
            font-size: 14px;
            margin: 0;
        }

        /* Contact Section */
        .contact {
            background: linear-gradient(135deg, #5EBDCC 0%, #3A9FB5 100%);
            color: white;
            text-align: center;
        }

        .contact h2 {
            color: white;
        }

        .contact p {
            color: rgba(255, 255, 255, 0.9);
        }

        .contact-info {
            display: flex;
            justify-content: center;
            gap: 48px;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .contact-item i {
            font-size: 24px;
        }

        .contact-item div {
            text-align: left;
        }

        .contact-item strong {
            display: block;
            font-size: 14px;
            margin-bottom: 4px;
            opacity: 0.9;
        }

        .contact-item span {
            font-size: 18px;
            font-weight: 600;
        }

        /* Footer */
        footer {
            background: #1a1a1a;
            color: white;
            padding: 48px 24px 24px;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 32px;
            margin-bottom: 24px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: white;
        }

        .footer-bottom {
            padding-top: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.5);
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }

            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                flex-direction: column;
                padding: 24px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            .nav-links.active {
                display: flex;
            }

            .hero h1 {
                font-size: 36px;
            }

            .hero p {
                font-size: 16px;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .section-header h2 {
                font-size: 32px;
            }

            .clinics-grid {
                grid-template-columns: 1fr;
            }

            .contact-info {
                flex-direction: column;
                gap: 24px;
            }
        }
    </style>
</head>
<body>
</head>
<body>
    <!-- Navigation -->
    <nav id="navbar">
        <div class="nav-container">
            <a href="#" class="logo">
                <img src="{{ asset('img/dcms_iconmini(1).png') }}" alt="RMDC Logo">
                <span>RMDC</span>
            </a>
            
            <ul class="nav-links" id="navLinks">
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#clinics">Clinics</a></li>
                <li><a href="{{ route('login') }}" class="btn-primary">Book Appointment</a></li>
            </ul>
            
            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-container">
            <h1>Robles Moncayo Dental Clinic</h1>
            <p>Your trusted partner for comprehensive dental care in Bacoor. Professional, caring, and modern dentistry for the whole family.</p>
            <div class="hero-buttons">
                <a href="{{ route('login') }}" class="btn-hero btn-hero-primary">Schedule Appointment</a>
                <a href="#clinics" class="btn-hero btn-hero-secondary">Find Our Clinics</a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="section-container">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>1000+</h3>
                    <p>Happy Patients</p>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clinic-medical"></i>
                    </div>
                    <h3>2</h3>
                    <p>Clinic Locations</p>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-tooth"></i>
                    </div>
                    <h3>{{ count($procedures) }}+</h3>
                    <p>Dental Services</p>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3>15+</h3>
                    <p>Years Experience</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about">
        <div class="section-container">
            <div class="section-header">
                <h2>About Us</h2>
                <p>Providing exceptional dental care with a personal touch. Our experienced team is dedicated to maintaining and enhancing your oral health in a comfortable, modern environment.</p>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services">
        <div class="section-container">
            <div class="section-header">
                <h2>Our Services</h2>
                <p>Comprehensive dental treatments tailored to your needs</p>
            </div>
            
            <div class="services-grid">
                @foreach($procedures as $procedure)
                <div class="service-card">
                    @if($procedure->image_path)
                        <img src="{{ asset('storage/' . $procedure->image_path) }}" alt="{{ $procedure->procedure_name }}" class="service-image">
                    @else
                        <div class="service-image" style="background: linear-gradient(135deg, #5EBDCC 0%, #3A9FB5 100%);"></div>
                    @endif
                    
                    <div class="service-content">
                        <h3>{{ $procedure->procedure_name }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($procedure->description, 120) }}</p>
                        
                        <div class="service-meta">
                            <span class="service-duration">
                                <i class="far fa-clock"></i> {{ $procedure->duration }} mins
                            </span>
                            <span class="service-price">₱{{ number_format($procedure->price, 2) }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Clinics Section -->
    <section class="clinics" id="clinics">
        <div class="section-container">
            <div class="section-header">
                <h2>Our Clinic Locations</h2>
                <p>Visit us at either of our convenient locations in Bacoor</p>
            </div>
            
            <div class="clinics-grid">
                <!-- Clinic 1 -->
                <div class="clinic-card">
                    <div id="map1" class="clinic-map"></div>
                    <div class="clinic-info">
                        <h3>Clinic 1 - Niog</h3>
                        
                        <div class="clinic-detail">
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="clinic-detail-content">
                                <strong>Address</strong>
                                <p>Unit F Medina Bldg, in front gate of Niog Elementary School, Bacoor, Philippines</p>
                            </div>
                        </div>
                        
                        <div class="clinic-detail">
                            <i class="far fa-clock"></i>
                            <div class="clinic-detail-content">
                                <strong>Operating Hours</strong>
                                <p>Monday to Saturday: 7:00 AM - 2:00 PM</p>
                                <p style="color: #ef4444; margin-top: 4px;">Sunday: Closed</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Clinic 2 -->
                <div class="clinic-card">
                    <div id="map2" class="clinic-map"></div>
                    <div class="clinic-info">
                        <h3>Clinic 2 - F E De Castro Village</h3>
                        
                        <div class="clinic-detail">
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="clinic-detail-content">
                                <strong>Address</strong>
                                <p>Marigold corner Hyacinth Sts, F E De Castro Village, Bacoor, Philippines</p>
                            </div>
                        </div>
                        
                        <div class="clinic-detail">
                            <i class="far fa-clock"></i>
                            <div class="clinic-detail-content">
                                <strong>Operating Hours</strong>
                                <p>Monday to Saturday: 3:00 PM - 8:00 PM</p>
                                <p style="color: #16a34a; margin-top: 4px;">Sunday: 1:00 PM - 8:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact">
        <div class="section-container">
            <div class="section-header">
                <h2>Get In Touch</h2>
                <p>We're here to answer your questions and schedule your appointment</p>
            </div>
            
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <strong>Call Us</strong>
                        <span>(+63) 912-3456-789</span>
                    </div>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <strong>Email Us</strong>
                        <span>robles_moncayo@yahoo.com</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="logo" style="justify-content: center; margin-bottom: 24px; color: white;">
                <img src="{{ asset('img/dcms_iconmini(1).png') }}" alt="RMDC Logo">
                <span>Robles Moncayo Dental Clinic</span>
            </div>
            
            <div class="footer-links">
                <a href="#home">Home</a>
                <a href="#about">About</a>
                <a href="#services">Services</a>
                <a href="#clinics">Clinics</a>
                <a href="{{ route('login') }}">Login</a>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 Robles Moncayo Dental Clinic. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('active');
        }

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Initialize Leaflet Maps
        document.addEventListener('DOMContentLoaded', function() {
            // Clinic 1 - Niog Elementary School area (approximate coordinates)
            const map1 = L.map('map1').setView([14.4167, 120.9667], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map1);
            
            L.marker([14.4167, 120.9667]).addTo(map1)
                .bindPopup('<b>Clinic 1 - Niog</b><br>Unit F Medina Bldg<br>Near Niog Elementary School')
                .openPopup();

            // Clinic 2 - F E De Castro Village (approximate coordinates)
            const map2 = L.map('map2').setView([14.4300, 120.9700], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map2);
            
            L.marker([14.4300, 120.9700]).addTo(map2)
                .bindPopup('<b>Clinic 2 - F E De Castro Village</b><br>Marigold corner Hyacinth Sts')
                .openPopup();
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    // Close mobile menu if open
                    document.getElementById('navLinks').classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>

