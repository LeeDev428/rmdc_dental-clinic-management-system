# 🎯 HOSTINGER DEPLOYMENT - QUICK REFERENCE

## 📦 **STEP 1: Prepare ZIP File Locally**

### Option A: Run PowerShell Script (EASIEST)
```powershell
# Right-click prepare-hostinger-upload.ps1 → Run with PowerShell
# OR in terminal:
.\prepare-hostinger-upload.ps1
```

### Option B: Manual ZIP Creation
1. Exclude these folders:
   - ❌ `vendor/`
   - ❌ `node_modules/`
   - ❌ `.git/`
2. Include everything else
3. Name it: `rmdc-hostinger-deploy.zip`

---

## 📤 **STEP 2: Upload to Hostinger**

1. **Login**: https://hpanel.hostinger.com
2. **File Manager** → Navigate to `/home/u314333613/`
3. **Create folder**: `laravel_app`
4. **Upload ZIP** to `laravel_app` folder
5. **Right-click ZIP** → Extract

---

## 🔧 **STEP 3: SSH Setup**

### Connect to SSH:
```bash
ssh u314333613@46.202.186.219 -p 65002
```

### Run Automated Setup:
```bash
cd ~/laravel_app
bash hostinger-setup.sh
```

### OR Manual Commands:
```bash
# Install dependencies
composer install --no-dev --optimize-autoloader

# Setup environment
cp .env.example .env
php artisan key:generate

# Set permissions
chmod -R 755 storage bootstrap/cache

# Copy to public_html
rm -rf ~/public_html/*
cp -r ~/laravel_app/public/* ~/public_html/
```

---

## ✏️ **STEP 4: Edit Configuration Files**

### A. Update `public_html/index.php`

**Change lines 17-18 to:**
```php
require __DIR__.'/../laravel_app/vendor/autoload.php';
$app = require_once __DIR__.'/../laravel_app/bootstrap/app.php';
```

### B. Configure `.env` file

```bash
nano ~/laravel_app/.env
```

**Update these values:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=u314333613_rmdc
DB_USERNAME=u314333613_rmdc
DB_PASSWORD=your_password_here
```

---

## 🗄️ **STEP 5: Database Setup**

```bash
cd ~/laravel_app

# Run migrations
php artisan migrate --force

# Seed data (if needed)
php artisan db:seed --force

# Create storage link
php artisan storage:link
```

---

## 🚀 **STEP 6: Optimize for Production**

```bash
cd ~/laravel_app

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize composer
composer dump-autoload --optimize
```

---

## ✅ **VERIFICATION CHECKLIST**

- [ ] ZIP uploaded and extracted
- [ ] `composer install` completed
- [ ] `.env` file configured
- [ ] Database credentials updated
- [ ] Migrations run successfully
- [ ] `public_html/index.php` paths updated
- [ ] Storage permissions set (755)
- [ ] Caches optimized
- [ ] Website accessible

---

## 🔍 **TROUBLESHOOTING**

### Check Laravel Logs:
```bash
tail -50 ~/laravel_app/storage/logs/laravel.log
```

### Clear All Caches:
```bash
cd ~/laravel_app
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Fix Permissions:
```bash
cd ~/laravel_app
chmod -R 755 storage bootstrap/cache
chown -R u314333613:u314333613 storage bootstrap/cache
```

### Re-run Composer:
```bash
cd ~/laravel_app
composer dump-autoload --optimize
```

---

## 📞 **SSH Commands Cheat Sheet**

| Command | Purpose |
|---------|---------|
| `ssh u314333613@46.202.186.219 -p 65002` | Connect to server |
| `pwd` | Show current directory |
| `ls -la` | List files with details |
| `cd ~/laravel_app` | Go to Laravel app |
| `php artisan --version` | Check Laravel version |
| `composer --version` | Check Composer |
| `php -v` | Check PHP version |
| `nano filename` | Edit file (Ctrl+X to save) |
| `cat filename` | View file contents |
| `tail -50 storage/logs/laravel.log` | View last 50 log lines |

---

## 🎯 **COMPLETE WORKFLOW (Copy-Paste Ready)**

```bash
# 1. Connect to SSH
ssh u314333613@46.202.186.219 -p 65002

# 2. Navigate and install dependencies
cd ~/laravel_app
composer install --no-dev --optimize-autoloader

# 3. Setup environment
cp .env.example .env
php artisan key:generate
nano .env  # Configure database

# 4. Setup public_html
rm -rf ~/public_html/*
cp -r ~/laravel_app/public/* ~/public_html/
nano ~/public_html/index.php  # Update paths

# 5. Run migrations
php artisan migrate --force
php artisan storage:link

# 6. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Set permissions
chmod -R 755 storage bootstrap/cache
```

---

## 🚨 **IMPORTANT NOTES**

- ✅ **vendor folder**: Installed via composer on server (NOT uploaded)
- ✅ **node_modules**: NOT needed on production
- ✅ **public folder**: Contents go to `public_html`
- ✅ **Laravel root**: Lives in `~/laravel_app/`
- ✅ **Database**: Create via Hostinger control panel first
- ✅ **SSL**: Enable in Hostinger panel for HTTPS

---

## 📧 **Support**

If stuck, check:
1. Laravel logs: `~/laravel_app/storage/logs/laravel.log`
2. PHP errors: Check Hostinger error logs in control panel
3. Permissions: Ensure storage/bootstrap/cache are writable

**All done! Your Laravel app should be live! 🎉**
