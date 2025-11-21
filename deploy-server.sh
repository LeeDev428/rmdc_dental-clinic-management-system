#!/bin/bash

# Go to the project directory
cd ~/domains/roblesmoncayo.com/public_html

# Pull latest changes from GitHub
git pull origin master

# Install/update PHP dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-req=ext-sodium

# Run Laravel migrations
php artisan migrate --force

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Fix permissions
chmod -R 775 storage bootstrap/cache

echo "Deployment completed successfully!"
