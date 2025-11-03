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
            margin-top: 70px;
            min-height: 85vh;
            display: flex;
            align-items: center;
            background: white;
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

        .services-wrapper {
            display: flex;
            transition: transform 0.3s ease-in-out;
        }

        .services-slide {
            min-width: 100%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
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
        }

        .carousel-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #ddd;
            cursor: pointer;
            transition: all 0.3s;
        }

        .carousel-dot.active {
            background: #333;
            width: 30px;
            border-radius: 5px;
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
                <li><a href="#locations">Locations</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="{{ route('login') }}" class="btn-login">Login</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-container">
            <div class="hero-content">
                <h1>Robles-Moncayo Dental Clinic</h1>
                <p>Experience modern dentistry with compassionate service. We provide comprehensive dental solutions for the whole family in Bacoor.</p>
                <a href="{{ route('login') }}" class="hero-btn">Book Appointment</a>
            </div>
            <div class="hero-image">
                               <img src="https://images.unsplash.com/photo-1606811841689-23dfddce3e95?w=800&h=600&fit=crop" alt="Modern Dental Clinic">

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
                <div class="services-wrapper" id="servicesWrapper">
                    @php
                        $chunks = $procedures->chunk(3);
                    @endphp
                    
                    @foreach($chunks as $chunk)
                    <div class="services-slide">
                        @foreach($chunk as $procedure)
                        <div class="service-card">
                            @if($procedure->image_path)
                                <img src="{{ asset('storage/' . $procedure->image_path) }}" alt="{{ $procedure->procedure_name }}" class="service-image">
                            @else
                                <div class="service-image"></div>
                            @endif
                            
                            <div class="service-content">
                                <h3>{{ $procedure->procedure_name }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit($procedure->description, 80) }}</p>
                                
                                <div class="service-meta">
                                    <span style="font-size: 12px; color: #999;"><i class="far fa-clock"></i> {{ $procedure->duration }} mins</span>
                                    <span class="service-price">₱{{ number_format($procedure->price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="carousel-controls">
                <button class="carousel-btn" onclick="previousSlide()">
                    <i class="fas fa-chevron-left"></i>
                </button>
                
                <div class="carousel-indicators" id="indicators">
                    @for($i = 0; $i < $chunks->count(); $i++)
                    <div class="carousel-dot {{ $i === 0 ? 'active' : '' }}" onclick="goToSlide({{ $i }})"></div>
                    @endfor
                </div>
                
                <button class="carousel-btn" onclick="nextSlide()">
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
                        <h3><i class="fas fa-map-marker-alt"></i> Bacoor Main Clinic</h3>
                        <p><strong>Address:</strong> 123 Main Street, Bacoor, Cavite</p>
                        <p><strong>Hours:</strong> Mon-Sat: 9:00 AM - 6:00 PM</p>
                        <p><strong>Phone:</strong> (+63) 912-3456-789</p>
                    </div>
                </div>

                <div class="location-card">
                    <div id="map2" class="location-map"></div>
                    <div class="location-info">
                        <h3><i class="fas fa-map-marker-alt"></i> Bacoor Branch</h3>
                        <p><strong>Address:</strong> 456 Secondary Road, Bacoor, Cavite</p>
                        <p><strong>Hours:</strong> Mon-Sat: 9:00 AM - 6:00 PM</p>
                        <p><strong>Phone:</strong> (+63) 912-3456-790</p>
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
        <p>&copy; 2025 Robles Moncayo Dental Clinic. All Rights Reserved.</p>
    </footer>

    <script>
        // Services Carousel
        let currentSlide = 0;
        const wrapper = document.getElementById('servicesWrapper');
        const totalSlides = {{ $chunks->count() }};

        function updateSlide() {
            wrapper.style.transform = `translateX(-${currentSlide * 100}%)`;
            
            // Update dots
            const dots = document.querySelectorAll('.carousel-dot');
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlide();
        }

        function previousSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateSlide();
        }

        function goToSlide(index) {
            currentSlide = index;
            updateSlide();
        }

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

        // Initialize maps on page load
        document.addEventListener('DOMContentLoaded', initMaps);
    </script>
</body>
</html>