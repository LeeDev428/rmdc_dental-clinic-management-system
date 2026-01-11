# 🚀 RMDC Dental Clinic - Hostinger Upload Preparation Script
# This script prepares your Laravel project for Hostinger deployment

Write-Host "================================================" -ForegroundColor Cyan
Write-Host "  RMDC Dental Clinic - Hostinger Preparation" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

# Set paths
$projectPath = $PSScriptRoot
$tempPath = Join-Path $env:TEMP "rmdc-deploy-temp"
$zipPath = Join-Path ([Environment]::GetFolderPath("Desktop")) "rmdc-hostinger-deploy.zip"

Write-Host "📁 Project Path: $projectPath" -ForegroundColor Yellow
Write-Host "📦 ZIP will be saved to: $zipPath" -ForegroundColor Yellow
Write-Host ""

# Ask user confirmation
$confirmation = Read-Host "Do you want to build production assets first? (y/n)"
if ($confirmation -eq 'y') {
    Write-Host "🔨 Building production assets..." -ForegroundColor Green
    npm run build
    if ($LASTEXITCODE -ne 0) {
        Write-Host "⚠️  Build failed, but continuing..." -ForegroundColor Yellow
    } else {
        Write-Host "✅ Assets built successfully!" -ForegroundColor Green
    }
    Write-Host ""
}

# Create temp directory
Write-Host "📂 Creating temporary directory..." -ForegroundColor Green
if (Test-Path $tempPath) {
    Remove-Item -Path $tempPath -Recurse -Force
}
New-Item -Path $tempPath -ItemType Directory | Out-Null

# List of folders/files to include
$itemsToInclude = @(
    "app",
    "bootstrap",
    "config",
    "database",
    "public",
    "resources",
    "routes",
    "storage",
    ".env.example",
    "artisan",
    "composer.json",
    "composer.lock",
    "package.json",
    "package-lock.json",
    "tailwind.config.js",
    "vite.config.js",
    "postcss.config.js",
    "phpunit.xml"
)

Write-Host "📋 Copying files (excluding vendor & node_modules)..." -ForegroundColor Green
$copiedCount = 0
foreach ($item in $itemsToInclude) {
    $sourcePath = Join-Path $projectPath $item
    if (Test-Path $sourcePath) {
        $destPath = Join-Path $tempPath $item
        Copy-Item -Path $sourcePath -Destination $destPath -Recurse -Force
        Write-Host "  ✓ Copied: $item" -ForegroundColor Gray
        $copiedCount++
    } else {
        Write-Host "  ⊘ Skipped (not found): $item" -ForegroundColor DarkGray
    }
}

Write-Host ""
Write-Host "✅ Copied $copiedCount items" -ForegroundColor Green
Write-Host ""

# Create ZIP file
Write-Host "📦 Creating ZIP file..." -ForegroundColor Green
if (Test-Path $zipPath) {
    Remove-Item -Path $zipPath -Force
}

Compress-Archive -Path "$tempPath\*" -DestinationPath $zipPath -CompressionLevel Optimal

# Get file size
$zipSize = (Get-Item $zipPath).Length / 1MB
Write-Host "✅ ZIP file created successfully!" -ForegroundColor Green
Write-Host "📊 Size: $([math]::Round($zipSize, 2)) MB" -ForegroundColor Cyan
Write-Host ""

# Cleanup temp directory
Write-Host "🧹 Cleaning up..." -ForegroundColor Green
Remove-Item -Path $tempPath -Recurse -Force

# Final instructions
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "  ✅ PREPARATION COMPLETE!" -ForegroundColor Green
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "📦 ZIP File Location:" -ForegroundColor Yellow
Write-Host "   $zipPath" -ForegroundColor White
Write-Host ""
Write-Host "📋 Next Steps:" -ForegroundColor Yellow
Write-Host "   1. Login to Hostinger File Manager" -ForegroundColor White
Write-Host "   2. Navigate to /home/u314333613/" -ForegroundColor White
Write-Host "   3. Create folder: laravel_app" -ForegroundColor White
Write-Host "   4. Upload rmdc-hostinger-deploy.zip" -ForegroundColor White
Write-Host "   5. Extract the ZIP file" -ForegroundColor White
Write-Host "   6. SSH in and run: composer install --no-dev" -ForegroundColor White
Write-Host ""
Write-Host "📖 Full Instructions:" -ForegroundColor Yellow
Write-Host "   See HOSTINGER_DEPLOYMENT_GUIDE.md" -ForegroundColor White
Write-Host ""
Write-Host "🚨 IMPORTANT REMINDERS:" -ForegroundColor Red
Write-Host "   • The vendor folder is NOT included (you'll install it via SSH)" -ForegroundColor White
Write-Host "   • Run 'composer install' after extracting on server" -ForegroundColor White
Write-Host "   • Update public_html/index.php paths" -ForegroundColor White
Write-Host "   • Configure .env file with database credentials" -ForegroundColor White
Write-Host ""

# Open folder with ZIP file
$openFolder = Read-Host "Open folder with ZIP file? (y/n)"
if ($openFolder -eq 'y') {
    Start-Process explorer.exe -ArgumentList "/select,`"$zipPath`""
}

Write-Host "Press any key to exit..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
