#!/bin/bash

# Navigate to your project folder
cd ~/domains/roblesmoncayo.com/public_html || exit

# Ensure git pulls merge remote changes instead of rebasing
git config pull.rebase false

# Pull the latest changes from GitHub
git pull origin master

# Install/update composer dependencies (ignore sodium extension if missing)
composer install --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-req=ext-sodium

# Run Laravel migrations
php artisan migrate --force

# Clear and cache Laravel configurations
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Ensure storage and cache directories are writable
chmod -R 775 storage bootstrap/cache

echo "✅ Deployment complete!"
