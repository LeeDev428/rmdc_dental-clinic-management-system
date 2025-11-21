#!/bin/bash
cd ~/domains/roblesmoncayo.com/public_html || exit
git config pull.rebase false
git pull origin master
composer install --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-req=ext-sodium
php artisan migrate --force
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
chmod -R 775 storage bootstrap/cache
echo "✅ Deployment complete!"
