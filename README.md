# 🦷 RMDC Dental Clinic Management System

<p align="center">
  <img src="public/img/dcms_iconmini(1).png" alt="RMDC Logo" width="120">
</p>

<p align="center">
  <strong>A comprehensive dental clinic management system built with Laravel 11</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.9-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Tailwind-3.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind">
</p>

---

## 📋 Table of Contents

- [About](#about)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Setup](#database-setup)
- [Running the Application](#running-the-application)
- [Project Structure](#project-structure)
- [Key Features Documentation](#key-features-documentation)
- [Payment Integration](#payment-integration)
- [Firebase Integration](#firebase-integration)
- [Dev Tunnel Setup](#dev-tunnel-setup)
- [API Endpoints](#api-endpoints)
- [Testing](#testing)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)
- [License](#license)

---

## 🎯 About

**Robles Moncayo Dental Clinic (RMDC)** Management System is a full-featured web application designed to streamline dental clinic operations. It provides comprehensive appointment management, patient records, inventory tracking, payment processing, and real-time notifications.

### 🏥 Clinic Information
- **Main Clinic**: Unit F Medina Bldg, Niog Elementary School, Bacoor, Cavite
- **Operating Hours**: Mon-Sat: 7:00 AM - 2:00 PM
- **Branch Clinic**: Marigold corner Hyacinth Sts, F E De Castro Village, Bacoor, Cavite
- **Operating Hours**: Mon-Sat: 3:00 PM - 8:00 PM | Sunday: 1:00 PM - 8:00 PM

---

## ✨ Features

### 👥 Patient Features
- ✅ User registration and authentication (email + Google/Facebook OAuth)
- ✅ Online appointment booking with calendar view
- ✅ 25% down payment system (GCash, PayMaya, Card) via PayMongo
- ✅ 20% refund if appointment is declined by admin
- ✅ Double-booking prevention (one pending/accepted appointment at a time)
- ✅ Real-time appointment status tracking
- ✅ Digital teeth layout visualization
- ✅ Dental records management
- ✅ Service feedback and ratings
- ✅ In-app messaging with admin (MongoDB-backed)
- ✅ Notification bell with refund and status updates
- ✅ Appointment history, cancellation, and reschedule requests

### 🏥 Admin Features
- ✅ Comprehensive admin dashboard
- ✅ Appointment management (Accept/Decline with automatic refund)
- ✅ Separate Cancellation Requests page and Reschedule Requests page
- ✅ Patient records management
- ✅ Inventory tracking system
- ✅ Procedure pricing management
- ✅ Revenue and analytics reports
- ✅ Real-time notifications via Pusher
- ✅ Review and feedback moderation

### 🎨 UI/UX Features
- ✅ Modern minimalist design
- ✅ Responsive layout (mobile, tablet, desktop)
- ✅ Smooth scroll animations
- ✅ Interactive service cards with expandable descriptions
- ✅ Click-toggled OAuth security tooltip on login
- ✅ Modal-based interactions
- ✅ AJAX pagination
- ✅ Real-time search and filters

---

## 🛠 Tech Stack

### Backend
- **Framework**: Laravel 11.9
- **PHP**: 8.2+
- **Database**: MySQL 8.0
- **Authentication**: Laravel Breeze
- **Real-time**: Pusher / Laravel Echo
- **Cache**: Redis (optional)
- **Queue**: Database/Redis
- **File Storage**: Local/S3 compatible

### Frontend
- **CSS Framework**: Tailwind CSS 3.x
- **JavaScript**: Vanilla JS + jQuery
- **Build Tool**: Vite 5.x
- **Maps**: Leaflet.js
- **Icons**: Font Awesome 6.4
- **Fonts**: Google Fonts (Poppins)

### Third-Party Services
- **Payment**: PayMongo API (GCash, PayMaya, Card)
- **AI Chatbot**: Groq API (llama-3.3-70b-versatile)
- **Messaging**: MongoDB Atlas (messages only)
- **Real-time**: Pusher (appointment status & messaging)
- **OAuth**: Google + Facebook Login (Laravel Socialite)
- **Captcha**: mews/captcha
- **Image Processing**: Intervention Image

---

## 📦 Prerequisites

Before you begin, ensure you have the following installed:

- **PHP** >= 8.2 ([Download](https://www.php.net/downloads))
- **Composer** >= 2.6 ([Download](https://getcomposer.org/download/))
- **Node.js** >= 18.x and npm ([Download](https://nodejs.org/))
- **MySQL** >= 8.0 or MariaDB ([Download](https://dev.mysql.com/downloads/))
- **Git** ([Download](https://git-scm.com/downloads))

Optional:
- **Redis** for caching and queues
- **VS Code** with PHP and Laravel extensions

---

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/LeeDev428/rmdc_dental-clinic-management-system.git
cd rmdc_dental-clinic-management-system
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Setup

```bash
# Copy environment file
copy .env.example .env  # Windows
# OR
cp .env.example .env    # Linux/Mac

# Generate application key
php artisan key:generate
```

### 5. Configure Environment Variables

Open `.env` and update the following:

```env
APP_NAME="RMDC Dental Clinic"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rmdc_dental
DB_USERNAME=root
DB_PASSWORD=your_password

# Firebase (See OAUTH_SETUP_GUIDE.md)
FIREBASE_CREDENTIALS=path/to/firebase-credentials.json

# Pusher (Real-time notifications)
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=your_cluster

# Mail (Optional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls

# Payment (See PAYMONGO_IMPLEMENTATION_GUIDE.md)
PAYMONGO_PUBLIC_KEY=pk_test_xxx
PAYMONGO_SECRET_KEY=sk_test_xxx

# Google OAuth (See OAUTH_SETUP_GUIDE.md)
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT=http://localhost:8000/auth/google/callback

# Captcha
RECAPTCHA_SITE_KEY=your_site_key
RECAPTCHA_SECRET_KEY=your_secret_key
```

---

## 💾 Database Setup

### 1. Create Database

```sql
CREATE DATABASE rmdc_dental CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Run Migrations and Seeders

```bash
# Run all migrations
php artisan migrate

# OR run fresh with seeders (includes sample data)
php artisan migrate:fresh --seed
```

### 3. Create Storage Symlink

```bash
php artisan storage:link
```

### 4. Seed Procedure Images (Optional)

See `PROCEDURE_IMAGES_GUIDE.md` for detailed instructions on setting up procedure images.

```bash
# Create procedures directory
New-Item -ItemType Directory -Path "storage\app\public\procedures" -Force

# Copy placeholder images (if available)
```

---

## ▶️ Running the Application

### Development Mode

#### Terminal 1: Laravel Server
```bash
php artisan serve
# Access at http://localhost:8000
```

#### Terminal 2: Vite Dev Server (for hot reload)
```bash
npm run dev
```

#### Terminal 3: Queue Worker (for jobs/notifications)
```bash
php artisan queue:work
```

### Production Build

```bash
# Build frontend assets
npm run build

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📁 Project Structure

```
rmdc_dental-clinic-management-system/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # All controllers
│   │   │   ├── AdminAppointment.php
│   │   │   ├── AppointmentController.php
│   │   │   ├── WelcomeController.php
│   │   │   └── ...
│   │   ├── Middleware/           # Custom middleware
│   │   └── Requests/             # Form requests
│   ├── Models/                   # Eloquent models
│   │   ├── Appointment.php
│   │   ├── User.php
│   │   ├── DentalRecord.php
│   │   └── ...
│   ├── Events/                   # Event classes
│   ├── Notifications/            # Notification classes
│   └── Providers/                # Service providers
├── database/
│   ├── migrations/               # Database migrations
│   ├── seeders/                  # Database seeders
│   └── factories/                # Model factories
├── resources/
│   ├── views/                    # Blade templates
│   │   ├── welcome.blade.php    # Landing page
│   │   ├── dashboard.blade.php  # User dashboard
│   │   ├── admin/               # Admin views
│   │   └── partials/            # Reusable components
│   ├── css/                      # Stylesheets
│   └── js/                       # JavaScript files
├── routes/
│   ├── web.php                   # Web routes
│   ├── auth.php                  # Authentication routes
│   └── console.php               # Artisan commands
├── public/
│   ├── img/                      # Public images
│   ├── css/                      # Compiled CSS
│   ├── js/                       # Compiled JavaScript
│   └── storage/                  # Symlinked storage
├── storage/
│   ├── app/
│   │   └── public/              # User uploaded files
│   │       ├── procedures/      # Procedure images
│   │       ├── avatars/         # User avatars
│   │       └── valid_ids/       # ID uploads
│   └── logs/                    # Application logs
└── tests/                        # PHPUnit tests
```

---

## 📚 Key Features Documentation

### 🗓 Appointment System

**User Flow:**
1. Browse services on welcome page
2. Click "Book Appointment" button
3. Fill appointment modal with:
   - Procedure selection
   - Date and time
   - Valid ID upload
4. Select payment method (optional 20% down payment)
5. Submit and wait for admin approval

**Admin Flow:**
1. View pending appointments
2. Click "Details" button to see full information
3. Accept or decline with reason
4. System sends notification to patient

**Key Files:**
- `app/Http/Controllers/AppointmentController.php`
- `resources/views/appointments.blade.php`
- `resources/views/admin/upcoming_appointments.blade.php`

### 💳 Payment System

The system supports 3 payment methods:
- **GCash**: E-wallet payment
- **PayMaya**: E-wallet payment
- **Card**: Visa/Mastercard

**Implementation:**
- See `PAYMONGO_IMPLEMENTATION_GUIDE.md` for PayMongo setup
- 20% down payment calculated automatically
- Remaining balance tracked in database
- Payment status: `unpaid`, `partially_paid`, `fully_paid`

### 🔔 Notification System

**Firebase Push Notifications:**
- Real-time appointment status updates
- Admin messages
- Feedback reminders

**Setup:**
1. See `OAUTH_SETUP_GUIDE.md` for Firebase setup
2. Configure `FIREBASE_CREDENTIALS` in `.env`
3. Enable FCM in Firebase Console

### 🦷 Teeth Layout System

Interactive SVG-based teeth layout visualization:
- 32 adult teeth representation
- Color-coded status (healthy, cavity, missing, etc.)
- Quadrant-based organization
- Click-to-select functionality

**Key Files:**
- `resources/views/dashboard.blade.php` (view layout button)
- JavaScript SVG generation logic

---

## 🌐 Dev Tunnel Setup

For remote access and testing with friends, see `DEV_TUNNEL_SETUP.md`.

**Quick Start:**
```bash
# 1. Add to .env
VITE_HMR_HOST=your-tunnel-url.devtunnels.ms

# 2. Start servers
npm run dev
php artisan serve --host=0.0.0.0

# 3. Create VS Code tunnel (F1 > Dev Tunnels: Turn on)
```

---

## 🔌 API Endpoints

### Public Endpoints
```
GET  /                         # Welcome page
GET  /get-services             # Fetch services (AJAX pagination)
POST /appointments             # Create appointment
GET  /appointments             # View appointments (auth required)
```

### Admin Endpoints (Protected)
```
GET  /admin/dashboard          # Admin dashboard
GET  /admin/upcoming-appointments
GET  /admin/appointments/{id}/details  # Get appointment details (AJAX)
POST /admin/appointment/{id}/accept
POST /admin/appointment/{id}/decline
GET  /admin/reviews            # View ratings/feedback
GET  /admin/inventory          # Inventory management
```

### User Endpoints (Auth Required)
```
GET  /dashboard                # User dashboard
POST /appointments/cancel      # Cancel appointment
POST /feedback/submit          # Submit service feedback
GET  /messages                 # View admin messages
```

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter AppointmentTest

# With coverage
php artisan test --coverage
```

---

## 🐛 Troubleshooting

### Common Issues

#### 1. **403 Forbidden on Images**
```bash
php artisan storage:link
# Verify public/storage symlink exists
```

#### 2. **jQuery is not defined**
- Ensure jQuery is loaded before custom scripts
- Check `welcome.blade.php` has jQuery CDN link

#### 3. **CSS Not Loading on Dev Tunnel**
```bash
npm run build
php artisan config:clear
```

#### 4. **Database Connection Error**
- Check `.env` database credentials
- Verify MySQL service is running
- Test connection: `php artisan migrate:status`

#### 5. **Pusher Not Working**
- Verify Pusher credentials in `.env`
- Check browser console for connection errors
- Enable Pusher in `config/broadcasting.php`

#### 6. **Payment Errors**
- See `PAYMONGO_IMPLEMENTATION_GUIDE.md`
- Verify API keys (test vs production)
- Check PayMongo dashboard for webhook logs

### Clear All Caches

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
composer dump-autoload
npm run build
```

---

## 🤝 Contributing

Contributions are welcome! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Code Style
- Follow PSR-12 coding standards
- Use meaningful variable and function names
- Add comments for complex logic
- Write tests for new features

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 👨‍💻 Developer

**Developed by:** LeeDev428  
**Repository:** [github.com/LeeDev428/rmdc_dental-clinic-management-system](https://github.com/LeeDev428/rmdc_dental-clinic-management-system)

---

## 📞 Support

For support, please:
- Open an issue on GitHub
- Check existing documentation files:
  - `OAUTH_SETUP_GUIDE.md`
  - `PAYMONGO_IMPLEMENTATION_GUIDE.md`
  - `DEV_TUNNEL_SETUP.md`
  - `PROCEDURE_IMAGES_GUIDE.md`

---

## 🙏 Acknowledgments

- Laravel Framework Team
- Tailwind CSS Team
- All open-source contributors

---

<p align="center">Made with ❤️ for Robles Moncayo Dental Clinic</p>

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
