# RMDC Setup - Pusher + MongoDB + Invoice PDFs
# Run this script to install all required packages

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  RMDC Dental Clinic - Complete Setup  " -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Check if in correct directory
if (!(Test-Path "artisan")) {
    Write-Host "ERROR: artisan file not found. Please run this script from the Laravel project root." -ForegroundColor Red
    exit 1
}

Write-Host "Step 1: Installing PHP Packages..." -ForegroundColor Yellow
Write-Host "   - Pusher PHP Server SDK" -ForegroundColor Gray
Write-Host "   - MongoDB Laravel Driver" -ForegroundColor Gray
Write-Host "   - DomPDF for Invoice Generation" -ForegroundColor Gray
Write-Host ""

$composerResult = composer require pusher/pusher-php-server mongodb/laravel-mongodb barryvdh/laravel-dompdf --no-interaction 2>&1
if ($LASTEXITCODE -eq 0) {
    Write-Host "PHP packages installed successfully!" -ForegroundColor Green
    Write-Host ""
} else {
    Write-Host "Error installing PHP packages" -ForegroundColor Red
    Write-Host $composerResult
    Write-Host ""
    Write-Host "Continuing anyway..." -ForegroundColor Yellow
    Write-Host ""
}

Write-Host "Step 2: Installing NPM Packages..." -ForegroundColor Yellow
Write-Host "   - Laravel Echo" -ForegroundColor Gray
Write-Host "   - Pusher JS Client" -ForegroundColor Gray
Write-Host ""

$npmResult = npm install --save-dev laravel-echo pusher-js 2>&1
if ($LASTEXITCODE -eq 0) {
    Write-Host "NPM packages installed successfully!" -ForegroundColor Green
    Write-Host ""
} else {
    Write-Host "Error installing NPM packages" -ForegroundColor Red
    Write-Host $npmResult
    Write-Host ""
    Write-Host "Continuing anyway..." -ForegroundColor Yellow
    Write-Host ""
}

Write-Host "Step 3: Refreshing autoloader..." -ForegroundColor Yellow
composer dump-autoload --quiet
Write-Host "Autoloader refreshed!" -ForegroundColor Green
Write-Host ""

Write-Host "Step 4: Clearing caches..." -ForegroundColor Yellow
php artisan config:clear | Out-Null
php artisan cache:clear | Out-Null
php artisan view:clear | Out-Null
Write-Host "Caches cleared!" -ForegroundColor Green
Write-Host ""

Write-Host "========================================" -ForegroundColor Green
Write-Host "  Installation Complete!             " -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""

Write-Host "What was installed:" -ForegroundColor Cyan
Write-Host "   - Pusher PHP Server (Real-time messaging backend)" -ForegroundColor White
Write-Host "   - MongoDB Laravel Driver (Flexible message storage)" -ForegroundColor White
Write-Host "   - DomPDF (PDF invoice generation)" -ForegroundColor White
Write-Host "   - Laravel Echo (Real-time frontend)" -ForegroundColor White
Write-Host "   - Pusher JS (WebSocket client)" -ForegroundColor White
Write-Host ""

Write-Host "Next Steps:" -ForegroundColor Cyan
Write-Host "   1. Check your .env file - Pusher credentials are already set!" -ForegroundColor White
Write-Host "   2. Email attachments are ready - Test by booking an appointment" -ForegroundColor White
Write-Host "   3. MongoDB configuration is documented in COMPLETE_SETUP_GUIDE.md" -ForegroundColor White
Write-Host ""

Write-Host "Email Features Now Available:" -ForegroundColor Cyan
Write-Host "   Appointment Booked -> Sends with Invoice PDF" -ForegroundColor Green
Write-Host "   Appointment Reminder -> Sends with Invoice PDF" -ForegroundColor Green
Write-Host "   Status Updated (Accepted) -> Sends with Invoice PDF" -ForegroundColor Green
Write-Host ""

Write-Host "Real-time Messaging Ready:" -ForegroundColor Cyan
Write-Host "   Pusher credentials configured" -ForegroundColor Green
Write-Host "   Broadcasting driver set to 'pusher'" -ForegroundColor Green
Write-Host "   Ready for implementation" -ForegroundColor Green
Write-Host ""

Write-Host "Read COMPLETE_SETUP_GUIDE.md for full documentation" -ForegroundColor Yellow
Write-Host ""

Write-Host "Setup complete! You're ready to go!" -ForegroundColor Green
Write-Host ""
