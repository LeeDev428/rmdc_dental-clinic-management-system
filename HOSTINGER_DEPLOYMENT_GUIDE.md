# 🚀 Hostinger Deployment Guide - RMDC Dental Clinic

## 📦 Method 1: ZIP Upload via File Manager (RECOMMENDED FOR YOU)

### **Step 1: Build Production Assets**
```bash
# In your local project directory
npm run build
```

### **Step 2: Create ZIP File (WITHOUT vendor folder)**

#### Option A: Exclude vendor folder manually
1. **DO NOT** include the `vendor/` folder (it's huge - 100MB+)
2. **DO NOT** include `node_modules/` folder
3. Zip everything else:
   - app/
   - bootstrap/
   - config/
   - database/
   - public/
   - resources/
   - routes/
   - storage/
   - .env.example
   - artisan
   - composer.json
   - composer.lock
   - package.json
   - etc.

#### Option B: Use PowerShell to create ZIP (excludes vendor)
```powershell
# Run this in your project root
$source = "D:\Programming\Systems\Web-Systems\Laravel\rmdc_dental-clinic-management-system"
$destination = "D:\rmdc-deploy.zip"

# Create ZIP excluding vendor and node_modules
$compress = @{
    Path = "$source\*"
    DestinationPath = $destination
    CompressionLevel = "Fastest"
}

# Get all items except vendor and node_modules
Get-ChildItem -Path $source -Exclude "vendor","node_modules",".git" | 
    Compress-Archive -DestinationPath $destination -Force
```

### **Step 3: Upload to Hostinger**

1. **Login to Hostinger Control Panel**
2. **Go to File Manager**
3. **Navigate to your home directory** (NOT public_html yet)
   - Example: `/home/u314333613/`
4. **Create a folder**: `laravel_app`
5. **Upload the ZIP file** to `/home/u314333613/laravel_app/`
6. **Extract the ZIP** using File Manager's extract option

### **Step 4: Install Composer Dependencies via SSH**

```bash
# SSH into Hostinger
ssh u314333613@46.202.186.219 -p 65002

# Navigate to your Laravel app
cd ~/laravel_app

# Install composer dependencies (this recreates vendor folder)
composer install --no-dev --optimize-autoloader

# If composer is not available, use Hostinger's PHP composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php composer.phar install --no-dev --optimize-autoloader
```

### **Step 5: Configure Environment**

```bash
# Still in SSH
cd ~/laravel_app

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Set permissions
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs
```

### **Step 6: Setup public_html**

```bash
# Clear public_html
cd ~/domains/yourdomain.com/public_html
# OR just: cd ~/public_html
rm -rf *

# Copy public folder contents
cp -r ~/laravel_app/public/* .
cp ~/laravel_app/public/.htaccess .

# Verify files are there
ls -la
```

### **Step 7: Update index.php**

Edit `/public_html/index.php` to point to your Laravel installation:

**Change lines 17-18 from:**
```php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
```

**To:**
```php
require __DIR__.'/../laravel_app/vendor/autoload.php';
$app = require_once __DIR__.'/../laravel_app/bootstrap/app.php';
```

### **Step 8: Configure .env file**

Edit `~/laravel_app/.env` with your Hostinger database credentials:

```env
APP_NAME="RMDC Dental Clinic"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u314333613_rmdc
DB_USERNAME=u314333613_rmdc
DB_PASSWORD=your_database_password

# Disable problematic features for shared hosting
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
CACHE_DRIVER=file
```

### **Step 9: Run Migrations**

```bash
cd ~/laravel_app
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🔄 Method 2: Git Clone (ALTERNATIVE)

If you prefer Git (cleaner updates):

```bash
# SSH into Hostinger
ssh u314333613@46.202.186.219 -p 65002

# Clone repository
cd ~
git clone https://github.com/LeeDev428/rmdc_dental-clinic-management-system.git laravel_app

# Install dependencies
cd laravel_app
composer install --no-dev --optimize-autoloader

# Follow steps 5-9 above
```

---

## 🛠️ Quick Commands Cheat Sheet

### SSH Connection:
```bash
ssh u314333613@46.202.186.219 -p 65002
```

### Check PHP Version:
```bash
php -v
```

### Check Composer:
```bash
composer --version
```

### Clear Laravel Caches:
```bash
cd ~/laravel_app
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Fix Permissions:
```bash
cd ~/laravel_app
find storage -type d -exec chmod 755 {} \;
find storage -type f -exec chmod 644 {} \;
find bootstrap/cache -type d -exec chmod 755 {} \;
find bootstrap/cache -type f -exec chmod 644 {} \;
```

---

## ⚠️ IMPORTANT: Why Exclude vendor Folder?

1. **Size**: vendor folder is 100MB+ (slow upload)
2. **Platform-specific**: May have Windows binaries that won't work on Linux
3. **Best Practice**: Always run `composer install` on production server
4. **Faster**: Uploading without vendor takes 1-2 minutes vs 30+ minutes

---

## 🎯 What You Get:

```
/home/u314333613/
├── laravel_app/                    ← Full Laravel app here
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── vendor/                     ← Created by composer install
│   ├── .env                        ← Your production config
│   └── ...
│
└── domains/yourdomain.com/
    └── public_html/                ← Only public files here
        ├── index.php               ← Modified to point to ../laravel_app
        ├── css/
        ├── js/
        └── .htaccess
```

---

## 📝 Troubleshooting

### Issue: "500 Internal Server Error"
```bash
# Check Laravel logs
tail -50 ~/laravel_app/storage/logs/laravel.log
```

### Issue: "Route not found"
```bash
cd ~/laravel_app
php artisan route:cache
```

### Issue: "Class not found"
```bash
cd ~/laravel_app
composer dump-autoload --optimize
```

### Issue: "Permission denied"
```bash
cd ~/laravel_app
chmod -R 755 storage bootstrap/cache
chown -R u314333613:u314333613 storage bootstrap/cache
```

---

## 🔐 Security Checklist

- ✅ Set `APP_DEBUG=false` in .env
- ✅ Set `APP_ENV=production` in .env
- ✅ Keep `.env` file outside public_html
- ✅ Run `php artisan config:cache` to cache configs
- ✅ Ensure storage and bootstrap/cache are writable
- ✅ Remove any debug/test files from public folder

---

## 📞 Need Help?

If something goes wrong, SSH in and check:
```bash
cd ~/laravel_app
php artisan --version          # Check Laravel is working
php artisan route:list          # Check routes are registered
tail storage/logs/laravel.log  # Check for errors
```
