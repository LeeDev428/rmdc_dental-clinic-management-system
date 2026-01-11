<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('img/dcms_iconmini(1).png') }}" type="image/png">
    <title>RMDC - Robles Moncayo Dental Clinic</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/animations.css') }}">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            overflow-x: hidden;
            width: 100%;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: #1a1a1a;
            background: #fafafa url('{{ asset('img/dcms_bg.jpg') }}') center/cover fixed;
            position: relative;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            width: 100%;
            max-width: 100vw;
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

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: #333;
            cursor: pointer;
            padding: 5px;
        }

        @media (max-width: 768px) {
            .nav-container {
                padding: 15px 20px;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .nav-links {
                position: fixed;
                top: 70px;
                left: -100%;
                width: 100%;
                height: calc(100vh - 70px);
                background: white;
                flex-direction: column;
                padding: 30px;
                gap: 20px;
                transition: left 0.3s ease;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                align-items: flex-start;
            }

            .nav-links.active {
                left: 0;
            }

            .nav-links a {
                font-size: 18px;
                width: 100%;
                padding: 10px 0;
            }

            .btn-login {
                width: 100%;
                text-align: center;
                display: block;
            }
        }

        /* Hero Section */
        .hero {
            min-height: 85vh;
            display: flex;
            align-items: center;
            padding: 70px 20px 40px;
            position: relative;
            overflow: hidden;
        }

        .hero-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
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

        @media (max-width: 768px) {
            .hero {
                min-height: auto;
                padding: 90px 0 30px;
            }

            .hero-container {
                padding: 0;
                gap: 30px;
            }

            .hero-content h1 {
                font-size: 28px;
            }

            .hero-content p {
                font-size: 14px;
            }

            .btn-primary {
                padding: 12px 24px;
                font-size: 14px;
                width: 100%;
                text-align: center;
            }

            .hero-image {
                height: 300px;
            }
        }

        /* Info Cards */
        .info-section {
            background: transparent;
            padding: 40px 20px;
        }

        .info-cards {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
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

        @media (max-width: 768px) {
            .info-section {
                padding: 30px 10px;
            }

            .info-cards {
                grid-template-columns: 1fr;
                gap: 20px;
                padding: 0 10px;
            }

            .info-card {
                padding: 20px;
            }

            .info-card h3 {
                font-size: 28px;
            }
        }

        .info-card p {
            color: #666;
            font-size: 14px;
        }

        /* Services Section */
        .services {
            padding: 80px 40px;
            background: transparent;
        }

        .services-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            width: 100%;
        }

        .section-title {
            text-align: center;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 50px;
            color: #1a1a1a;
            padding: 0 15px;
        }

        .services-carousel {
            position: relative;
            overflow: hidden;
        }

        .services-subtitle {
            text-align: center;
            padding: 0 15px;
        }
            color: #666;
            font-size: 16px;
            margin-top: -10px;
            margin-bottom: 40px;
        }

        .services-grid-modern {
            display: grid !important;
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 40px 30px !important;
            margin-top: 30px;
            padding: 0 20px;
            row-gap: 40px !important;
            column-gap: 30px !important;
        }

        /* Force 3 columns on desktop */
        @media (min-width: 969px) {
            .services-grid-modern {
                grid-template-columns: repeat(3, 1fr) !important;
                display: grid !important;
                gap: 40px 30px !important;
            }
        }

        .service-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease-in-out;
            margin-bottom: 0;
        }

        .service-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .service-image {
            width: 100%;
            height: 176px;
            object-fit: cover;
            background: linear-gradient(135deg, #00b4d8, #03828b);
        }

        .service-content {
            padding: 16px;
        }

        .service-content h3 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #1a1a1a;
        }

        .service-content p {
            color: #666;
            font-size: 14px;
            margin-bottom: 16px;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .service-meta {
            margin-top: 16px;
            font-size: 14px;
            color: #666;
        }

        .service-meta p {
            margin-bottom: 4px;
        }

        .service-meta strong {
            color: #333;
        }

        .carousel-controls {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 50px;
            align-items: center;
        }

        .carousel-btn {
            background: #00b4d8;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s;
            font-weight: 600;
            font-size: 14px;
        }

        .carousel-btn:hover {
            background: #03828b;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,180,216,0.3);
        }

        .carousel-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .carousel-indicators {
            display: flex;
            gap: 8px;
            align-items: center;
            font-size: 15px;
            color: #666;
            font-weight: 600;
            padding: 8px 20px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        /* About Section */
        .about-section {
            padding: 100px 20px;
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
            padding: 0 20px;
            width: 100%;
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
            padding: 80px 20px;
            background: transparent;
        }

        .locations-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            width: 100%;
        }

        .locations-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            margin-top: 40px;
        }

        .location-card {
            background: white;
            width: 100%;
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

        /* Reviews Section */
        .reviews-section {
            padding: 80px 40px;
            background: transparent;
        }

        .reviews-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .reviews-subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 50px;
            font-size: 16px;
        }

        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 40px;
        }

        @media (max-width: 1024px) {
            .reviews-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
        }

        @media (max-width: 768px) {
            .reviews-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }

        .review-card {
            background: white;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border: 1px solid #f0f0f0;
            transition: all 0.2s;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .review-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-color: #e0e0e0;
        }

        .review-stars {
            display: flex;
            gap: 3px;
            color: #fbbf24;
            font-size: 14px;
        }

        .review-stars .far {
            color: #e5e7eb;
        }

        .review-message {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
            flex-grow: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .review-author {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-top: 12px;
            border-top: 1px solid #f3f4f6;
        }

        .author-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-weight: 600;
            font-size: 14px;
        }

        .author-info h4 {
            margin: 0;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
        }

        .author-info p {
            margin: 2px 0 0 0;
            font-size: 12px;
            color: #9ca3af;
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
          
            color: black;
            padding: 30px;
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
            body {
                overflow-x: hidden;
            }

            .hero-container {
                grid-template-columns: 1fr;
                gap: 40px;
                padding: 20px 15px;
            }
            
            .hero-content h1 {
                font-size: 32px;
            }

            .hero-content p {
                font-size: 14px;
            }
            
            .info-cards {
                grid-template-columns: 1fr;
                padding: 0 15px;
            }

            .services-grid-modern {
                grid-template-columns: 1fr !important;
                gap: 30px !important;
                padding: 0 10px !important;
                row-gap: 30px !important;
            }
            
            .service-card {
                margin-bottom: 30px !important;
            }

            .services-slide {
                grid-template-columns: 1fr;
            }

            .services-container,
            .about-container,
            .locations-container,
            .contact-container,
            .knowledge-container {
                padding: 40px 15px !important;
                max-width: 100%;
            }

            .section-title {
                font-size: 28px !important;
            }

            .services-subtitle {
                font-size: 14px !important;
            }

            .locations-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .location-card {
                margin: 0 10px;
            }
            
            .contact-info {
                flex-direction: column;
                gap: 20px;
            }

            .knowledge-grid {
                grid-template-columns: 1fr;
            }

            .faq-section {
                padding: 20px 15px;
            }

            .faq-container {
                padding: 0 10px;
            }

            .about-container {
                grid-template-columns: 1fr !important;
                gap: 30px;
            }

            .about-stats {
                grid-template-columns: 1fr !important;
                gap: 15px;
            }

            .value-item {
                flex-direction: column;
                text-align: center;
            }

            .service-card {
                margin: 0;
            }

            .carousel-controls {
                flex-direction: row;
                gap: 15px;
                padding: 20px 15px;
                flex-wrap: wrap;
            }

            .carousel-btn {
                width: auto;
                min-width: 44px;
                padding: 12px 16px;
            }

            .footer-container {
                padding: 40px 15px !important;
            }

            .footer-grid {
                grid-template-columns: 1fr !important;
                gap: 30px;
            }
        }

        .chatbot-wrapper-welcome {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) {
            .chatbot-wrapper-welcome {
                border-radius: 15px;
                margin: 0;
                width: 100%;
            }
            
            .hero-content {
                padding: 0 15px;
            }
        }

        @media (max-width: 480px) {
            .hero-content h1 {
                font-size: 24px;
            }

            .hero-content p {
                font-size: 13px;
            }

            .btn-primary {
                font-size: 13px;
                padding: 10px 20px;
            }

            .info-card h3 {
                font-size: 24px;
            }

            .info-card p {
                font-size: 13px;
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
            <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle mobile menu">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="nav-links" id="navLinks">
                <li><a href="#home" class="nav-link">Home</a></li>
                <li><a href="#about" class="nav-link">About</a></li>
                <li><a href="#services" class="nav-link">Services</a></li>
                <li><a href="#locations" class="nav-link">Locations</a></li>
                <li><a href="#contact" class="nav-link">Contact</a></li>
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
            <div class="chatbot-wrapper-welcome">
                <x-lee-ai-chatbot type="public" />
            </div>
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
                <img src="{{ asset('img/doc.jpg') }}" alt="Robles-Moncayo Dental Clinic" class="about-image">
                
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
    <section class="services fade-in" id="services">
        <div class="services-container">
            <h2 class="section-title fade-in"><span style="color: #00b4d8;">Our</span> Services</h2>
            <p class="services-subtitle fade-in">Comprehensive dental care for your complete oral health</p>
            <br>
            <div class="services-grid-modern fade-in" id="servicesGrid">
                <!-- Services will be loaded here via AJAX -->
            </div>
            <br>
            <br>

            <div class="carousel-controls">
                <button class="carousel-btn" id="prevBtn" onclick="changePage('prev')">
                    <i class="fas fa-chevron-left"></i>
                </button>
                
                <div class="carousel-indicators" id="paginationInfo">
                    Page <span id="currentPage">1</span> of <span id="totalPages">1</span>
                </div>
                
                <button class="carousel-btn" id="nextBtn" onclick="changePage('next')">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Clinic Locations -->
    <section class="locations fade-in" id="locations">
        <div class="locations-container">
            <h2 class="section-title fade-in">Our Clinic Locations</h2>
            
            <div class="locations-grid">
                <div class="location-card slide-in-left">
                    <div class="location-map">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3863.7234567890!2d120.9517!3d14.4164!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTTCsDI0JzU5LjAiTiAxMjDCsDU3JzA4LjAiRQ!5e0!3m2!1sen!2sph!4v1234567890"
                            width="100%" 
                            height="100%" 
                            style="border:0; border-radius: 10px;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <div class="location-info">
                        <h3><i class="fas fa-map-marker-alt"></i> Clinic 1 - Morning Branch</h3>
                        <p><strong>Address:</strong> Unit F Medina Bldg, in front gate of Niog Elementary School, Bacoor, Cavite</p>
                        <p><strong>Hours:</strong> Monday to Saturday: 7:00 AM - 2:00 PM</p>
                        <p><strong>Phone:</strong> (+63) 912-3456-789</p>
                    </div>
                </div>

                <div class="location-card slide-in-right">
                    <div class="location-map">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3863.6234567890!2d120.9578!3d14.4200!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTTCsDI1JzEyLjAiTiAxMjDCsDU3JzMzLjYiRQ!5e0!3m2!1sen!2sph!4v1234567890"
                            width="100%" 
                            height="100%" 
                            style="border:0; border-radius: 10px;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
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

    <!-- Customer Reviews Section -->
    @if($featuredReviews && $featuredReviews->count() > 0)
    @php
        function anonymizeName($name) {
            if (!$name) return 'Anonymous';
            $parts = explode(' ', $name);
            $anonymized = [];
            foreach ($parts as $part) {
                if (strlen($part) > 0) {
                    $anonymized[] = strtoupper(substr($part, 0, 1)) . str_repeat('*', strlen($part) - 1);
                }
            }
            return implode(' ', $anonymized);
        }
    @endphp
    <section class="reviews-section fade-in" id="reviews">
        <div class="reviews-container">
            <h2 class="section-title fade-in">What Our Patients Say</h2>
            <p class="reviews-subtitle fade-in">Real experiences from our valued patients</p>
            
            <div class="reviews-grid">
                @foreach($featuredReviews as $review)
                <div class="review-card scale-in">
                    <div class="review-stars">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $review->rating)
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="review-message">{{ $review->message ?? 'Excellent service!' }}</p>
                    <div class="review-author">
                        <div class="author-avatar">
                            {{ $review->user ? strtoupper(substr($review->user->name, 0, 1)) : '?' }}
                        </div>
                        <div class="author-info">
                            <h4>{{ anonymizeName($review->user->name ?? 'Anonymous') }}</h4>
                            <p>{{ $review->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

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
            <p>&copy; 2025 <a href="https://www.facebook.com/profile.php?id=100063581260298" target="_blank" style="color: #04748a; text-decoration: underline;">Dr. Cristina Moncayo Dental Clinic</a>. All Rights Reserved.</p>
            <p class="footer-credits">By: <strong><a href="https://www.facebook.com/leedev428/" target="_blank" style="color: #04748a; text-decoration: underline;">Lee Rafael Torres</a></strong></p>
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
                            const filename = procedure.image_path.split('/').pop();
                            imageHtml = `<img src="/procedure-image/${filename}" alt="${procedure.procedure_name}" class="service-image">`;
                        } else {
                            imageHtml = '<div class="service-image"></div>';
                        }
                        
                        const description = procedure.description ? 
                            (procedure.description.length > 80 ? procedure.description.substring(0, 80) + '...' : procedure.description) : '';
                        
                        const fullDescription = procedure.description || 'No description available.';
                        
                        card.innerHTML = `
                            ${imageHtml}
                            <div class="service-content">
                                <h3>${procedure.procedure_name}</h3>
                                <p>${fullDescription}</p>
                                <div class="service-meta">
                                    <p><strong>Estimated Time:</strong> ${procedure.duration} Minutes</p>
                                    <p><strong>Price:</strong> ₱${Number(procedure.price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
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
        });

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

        // Mobile Menu Toggle
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const navLinks = document.getElementById('navLinks');
        
        if (mobileMenuToggle && navLinks) {
            mobileMenuToggle.addEventListener('click', () => {
                navLinks.classList.toggle('active');
                const icon = mobileMenuToggle.querySelector('i');
                if (navLinks.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            });

            // Close mobile menu when clicking on a link
            const navLinksItems = document.querySelectorAll('.nav-link');
            navLinksItems.forEach(link => {
                link.addEventListener('click', () => {
                    navLinks.classList.remove('active');
                    const icon = mobileMenuToggle.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                });
            });

            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!navLinks.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
                    navLinks.classList.remove('active');
                    const icon = mobileMenuToggle.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            });
        }

        // Initialize animations on page load
        document.addEventListener('DOMContentLoaded', () => {
            animateOnScroll();
        });
    </script>
    
</body>
</html>
```