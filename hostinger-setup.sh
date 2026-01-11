#!/bin/bash
# 🚀 RMDC Dental Clinic - Hostinger Setup Script
# Run this after uploading and extracting your ZIP file

echo "================================================"
echo "  RMDC Dental Clinic - Hostinger Setup"
echo "================================================"
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Check current directory
echo -e "${YELLOW}📂 Current directory: $(pwd)${NC}"
echo ""

# Navigate to Laravel app
cd ~/laravel_app || { echo -e "${RED}❌ laravel_app folder not found!${NC}"; exit 1; }
echo -e "${GREEN}✅ Changed to ~/laravel_app${NC}"
echo ""

# Check if composer is available
echo -e "${YELLOW}🔍 Checking for Composer...${NC}"
if command -v composer &> /dev/null; then
    COMPOSER_CMD="composer"
    echo -e "${GREEN}✅ Composer found!${NC}"
else
    echo -e "${YELLOW}⚠️  Composer not found globally. Downloading...${NC}"
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"
    COMPOSER_CMD="php composer.phar"
    echo -e "${GREEN}✅ Composer downloaded!${NC}"
fi
echo ""

# Install dependencies
echo -e "${YELLOW}📦 Installing Composer dependencies...${NC}"
echo -e "${YELLOW}   (This may take 2-5 minutes)${NC}"
$COMPOSER_CMD install --no-dev --optimize-autoloader
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Dependencies installed successfully!${NC}"
else
    echo -e "${RED}❌ Failed to install dependencies!${NC}"
    exit 1
fi
echo ""

# Copy .env file
echo -e "${YELLOW}📝 Setting up environment file...${NC}"
if [ ! -f .env ]; then
    cp .env.example .env
    echo -e "${GREEN}✅ .env file created${NC}"
else
    echo -e "${YELLOW}⚠️  .env file already exists, skipping...${NC}"
fi
echo ""

# Generate application key
echo -e "${YELLOW}🔑 Generating application key...${NC}"
php artisan key:generate --force
echo -e "${GREEN}✅ Application key generated!${NC}"
echo ""

# Set permissions
echo -e "${YELLOW}🔒 Setting correct permissions...${NC}"
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs
echo -e "${GREEN}✅ Permissions set!${NC}"
echo ""

# Clear public_html
echo -e "${YELLOW}🧹 Clearing public_html directory...${NC}"
# Detect public_html location
if [ -d ~/public_html ]; then
    PUBLIC_HTML=~/public_html
elif [ -d ~/domains/*/public_html ]; then
    PUBLIC_HTML=$(find ~/domains -name "public_html" -type d | head -n 1)
else
    echo -e "${RED}❌ Could not find public_html directory!${NC}"
    echo -e "${YELLOW}   Please manually copy files from ~/laravel_app/public/ to your public_html${NC}"
    PUBLIC_HTML=""
fi

if [ -n "$PUBLIC_HTML" ]; then
    echo -e "${YELLOW}   Found: $PUBLIC_HTML${NC}"
    rm -rf $PUBLIC_HTML/*
    rm -rf $PUBLIC_HTML/.htaccess
    echo -e "${GREEN}✅ public_html cleared!${NC}"
    
    # Copy public files
    echo -e "${YELLOW}📂 Copying public files...${NC}"
    cp -r ~/laravel_app/public/* $PUBLIC_HTML/
    cp ~/laravel_app/public/.htaccess $PUBLIC_HTML/ 2>/dev/null || echo -e "${YELLOW}   No .htaccess found${NC}"
    echo -e "${GREEN}✅ Public files copied!${NC}"
    echo ""
    
    # Update index.php
    echo -e "${YELLOW}✏️  Updating index.php paths...${NC}"
    sed -i "s|__DIR__.'/../vendor/autoload.php'|__DIR__.'/../laravel_app/vendor/autoload.php'|g" $PUBLIC_HTML/index.php
    sed -i "s|__DIR__.'/../bootstrap/app.php'|__DIR__.'/../laravel_app/bootstrap/app.php'|g" $PUBLIC_HTML/index.php
    echo -e "${GREEN}✅ index.php updated!${NC}"
    echo ""
fi

# Display completion message
echo "================================================"
echo -e "${GREEN}  ✅ SETUP COMPLETE!${NC}"
echo "================================================"
echo ""
echo -e "${YELLOW}📋 Next Steps:${NC}"
echo ""
echo -e "${YELLOW}1. Configure Database:${NC}"
echo "   nano ~/laravel_app/.env"
echo "   (Update DB_DATABASE, DB_USERNAME, DB_PASSWORD)"
echo ""
echo -e "${YELLOW}2. Run Migrations:${NC}"
echo "   cd ~/laravel_app"
echo "   php artisan migrate --force"
echo ""
echo -e "${YELLOW}3. Create Storage Link:${NC}"
echo "   php artisan storage:link"
echo ""
echo -e "${YELLOW}4. Optimize for Production:${NC}"
echo "   php artisan config:cache"
echo "   php artisan route:cache"
echo "   php artisan view:cache"
echo ""
echo -e "${YELLOW}5. Set Production Mode in .env:${NC}"
echo "   APP_ENV=production"
echo "   APP_DEBUG=false"
echo ""
echo -e "${GREEN}🎉 Your Laravel app should now be accessible!${NC}"
echo ""
