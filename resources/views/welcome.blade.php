<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('img/dcms_iconmini(1).png') }}" type="image/png">
    <title>RMDC</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Global Styles */
       * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: url('{{ asset('img/dcms_bg.jpg') }}') no-repeat center center fixed;
    background-size: cover;
    color: #333;
    scroll-behavior: smooth;
    animation: fadeIn 1s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

header {
    position: relative;
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    animation: slideInFromTop 0.8s ease-out;
}

@keyframes slideInFromTop {
    from {
        transform: translateY(-100px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Navigation Bar */
nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 45px;
    background-color: rgba(255, 255, 255, 0.95);
    box-shadow: 0 1.8px 4.5px rgba(0, 0, 0, 0.1);
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
    animation: fadeIn 1s ease-in-out;
}

nav img {
    height: 54px;
    cursor: pointer;
}

nav ul {
    list-style: none;
    display: flex;
    gap: 36px;
    margin-right: 18px;
}

nav ul li {
    display: inline;
}

nav ul li a {
    text-decoration: none;
    color: #333;
    font-size: 15.4px;
    font-weight: 500;
    padding: 9px 13.5px;
    transition: background-color 0.3s ease, color 0.3s ease;
    border-radius: 9px;
}

nav ul li a:hover {
    background-color: #007BFF;
    color: #fff;
    border-radius: 9px;
}

.btn-nav {
    background-color: #007BFF;
    color: #fff;
    text-decoration: none;
    padding: 10.8px 27px;
    font-size: 14.4px;
    font-weight: 600;
    border-radius: 9px;
    transition: background-color 0.3s ease;
    margin-right: -18px;
}

.btn-nav:hover {
    background-color: #0056b3;
}

/* Hamburger Menu */
.hamburger {
    display: none;
    flex-direction: column;
    gap: 4.5px;
    cursor: pointer;
}

.hamburger div {
    width: 27px;
    height: 3.6px;
    background-color: #333;
    border-radius: 4.5px;
}

.menu {
    display: flex;
    justify-content: flex-end;
    gap: 18px;
}

.menu.active {
    display: flex;
    position: absolute;
    top: 54px;
    right: 18px;
    background-color: rgba(255, 255, 255, 0.9);
    border-radius: 7.2px;
    padding: 13.5px;
    flex-direction: column;
    width: 180px;
}

.menu li a {
    padding: 9px;
    text-align: right;
    border-radius: 4.5px;
}

/* Hero Section */
.hero {
    position: relative;
    top: 45px;
    text-align: center;
    color: white;
    text-shadow: 1.8px 1.8px 4.5px rgba(0, 0, 0, 0.8);
    margin-top: 108px;
    padding: 0 18px;
    animation: slideInFromTop 0.8s ease-out;
}

.hero h1 {
    font-size: 3.4rem;
    font-weight: 700;
    margin-bottom: 18px;
}

.hero p {
    font-size: 1.2rem;
    font-weight: 300;
    margin-bottom: 36px;
}

.btn-hero {
    background-color: #007BFF;
    color: white;
    text-decoration: none;
    padding: 13.5px 36px;
    font-size: 16.2px;
    font-weight: 600;
    border-radius: 9px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.btn-hero:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

/* Contact Info */
.contact-info {
    text-align: center;
    font-size: 1.08rem;
    font-weight: 500;
    margin-bottom: 18px;
    color: rgb(44, 41, 41);
    animation: fadeIn 1.5s ease-out;
}

/* Scroll to Top Button */
.scroll-to-top {
    position: fixed;
    bottom: 18px;
    right: 18px;
    background-color: #007BFF;
    color: #fff;
    border: none;
    border-radius: 45%;
    padding: 13.5px;
    font-size: 16.2px;
    cursor: pointer;
    display: none;
    transition: background-color 0.3s ease;
}

.scroll-to-top:hover {
    background-color: #0056b3;
}



        /* Sections */
        section {
            padding: 72px 18px;
            text-align: center;
        }

        section h2 {
            font-size: 2.7rem;
            font-weight: 600;
            margin-bottom: 18px;
        }

        section p {
            font-size: 1.125rem;
            margin-bottom: 18px;
        }


        /* Responsive Design */
        @media (max-width: 768px) {
            nav {
                padding: 13.5px 18px;
            }

            nav ul {
                display: none;
            }

            .hamburger {
                display: flex;
            }

            .menu {
                display: none;
                flex-direction: column;
                align-items: flex-end;
                gap: 9px;
                position: absolute;
                top: 63px;
                right: 18px;
                background-color: rgba(255, 255, 255, 0.9);
                padding: 9px;
                border-radius: 4.5px;
            }

            .menu.active {
                display: flex;
            }

            .hero {
                margin-top: 144px;
            }

            .hero h1 {
                font-size: 2.25rem;
            }

            .hero p {
                font-size: 1.08rem;
            }

            .btn-hero {
                padding: 10.8px 22.5px;
                font-size: 14.4px;
            }

            .contact-info {
                font-size: 0.9rem;
            }

            .btn-nav {
                display: none;
            }
        }

        /* For large screen */
        @media (min-width: 769px) {
            nav ul {
                margin-left: auto;
                gap: 36px;
            }

            .btn-nav {
                display: inline-block;
            }
        }
        /* Hide scrollbar on WebKit browsers */
        ::-webkit-scrollbar {
        display: none;
       }

    </style>

</head>
<body>
    <header>
        <!-- Navigation Bar -->
        <nav>
            <img src="{{ asset('img/dcms_iconmini(1).png') }}" alt="Clinic Logo" style="width: 50px !important; height: 50px !important;">
            &nbsp;&nbsp;
            <div style="font-size: 2rem; font-weight: 700; margin-bottom: 0px;">RMDC</div>
            &nbsp;&nbsp;


            <!-- Hamburger Menu Icon -->
            <div class="hamburger" onclick="toggleMenu()">
                <div></div>
                <div></div>
                <div></div>
            </div>

            <!-- Menu Items -->
            <ul class="menu">
                <li><a href="#about">About</a></li>
                <li><a href="#services">Services</a></li>
            </ul>

            <!-- Request Appointment Button (visible only on PC) -->
            <a href="{{ route('login') }}" class="btn-nav">Log in</a>
        </nav>

        <!-- Hero Section -->
        <div class="hero">
            <h1>Trusted Dental Care</h1>
            <p>Healthy Smiles for the Whole Family</p>
            <a href="{{ route('login') }}" class="btn-hero">Request Appointment</a>
        </div>

        <div class="contact-info">
            <p>
                <span style="font-size: 15px; font-weight: 500;">Contact us at: </span>
                <span style="font-size: 17px; font-weight: 600;">(+63) 912-3456-789</span>
                <span style="font-size: 15px; font-weight: 500;"> &nbsp; |  &nbsp; Email: </span>
                <span style="font-size: 17px; font-weight: 600;">robles_moncayo@yahoo.com</span>
              </p>

        </div>
    </header>

    <!-- About Section -->
    <section id="about">
        <h2>About Us</h2>
        <p>We are a trusted dental clinic providing high-quality care to our patients. Our team of professionals is committed to offering top-notch dental services to keep your smile bright and healthy.</p>

    </section>

    <!-- Services Section -->
    <section id="services">
        <h2>Our Services</h2>
<div style="position: relative; overflow-x: auto; white-space: nowrap; padding-bottom: 10px;">
    @foreach($procedures as $procedure)
        <div style="
            display: inline-flex;
            flex-direction: column;
            display: inline-flex;
            flex-direction: column;
            justify-content: space-between;
            vertical-align: top;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 380px;
            height: 410px;
            margin-right: 20px;
            padding: 20px;
            box-sizing: border-box;
            overflow: hidden;
            white-space: normal;
            word-wrap: break-word;
            text-align: left;
        ">
            @if($procedure->image_path)
                <img src="{{ asset('storage/' . $procedure->image_path) }}" alt="{{ $procedure->procedure_name }}"
                    style="width: 100%; height: 180px; object-fit: cover; border-radius: 10px;">
            @endif

            <div>
                <h3 style=" font-size: 1.1rem; font-weight: bold;  overflow: hidden; text-overflow: ellipsis;">
                    {{ $procedure->procedure_name }}
                </h3>
                <p style="font-size: 14px; color: #333; line-height: 1.4; max-height: 100px; overflow: hidden; text-overflow: ellipsis;">
                    {{ \Illuminate\Support\Str::limit($procedure->description, 160) }}
                </p>
                <p style="font-size: 14px; color: #555; margin-bottom: 8px;"><strong>Estimated Duration:</strong> {{ $procedure->duration }}</p>
                <p style="margin: 8px 0 4px;font-size: 14px; color: #555;"><strong>Price:</strong> ₱{{ number_format($procedure->price, 2) }}</p>


            </div>
        </div>
    @endforeach
</div>
    </section>
       <footer style="text-align: center; padding: 20px; background-color: #333; color: white; font-size: 15px; position: absolute; bottom: 100; width: 100%; height: 145px;">
        <p style="position: relative; top: 30px;">&copy; 2025 RMDC. All Rights Reserved.</p>
        <p><a href="#" style="color: #fff; text-decoration: none; position: relative; top: 30px;">Privacy Policy &nbsp;&nbsp;|&nbsp;&nbsp;</a>  <a href="#" style="color: #fff; text-decoration: none; position: relative; top: 30px;">Terms of Service</a></p>
       </footer>


    <script>
        // Toggle menu visibility on mobile
        function toggleMenu() {
            const menu = document.querySelector('.menu');
            menu.classList.toggle('active');
        }

        // Scroll to Top button functionality
        window.onscroll = function() {
            toggleScrollButton();
        };

        function toggleScrollButton() {
            const scrollToTopBtn = document.getElementById("scrollToTopBtn");
            if (document.body.scrollTop > 500 || document.documentElement.scrollTop > 500) {
                scrollToTopBtn.style.display = "block";
            } else {
                scrollToTopBtn.style.display = "none";
            }
        }

        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
</body>
</html>

