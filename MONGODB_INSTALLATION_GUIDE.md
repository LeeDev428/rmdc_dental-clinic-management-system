# 🗄️ MongoDB Extension Installation Guide

## Your System Info
- **PHP Version**: 8.2.12
- **Thread Safety**: ZTS (Thread Safe)
- **Architecture**: x64 (64-bit)
- **XAMPP PHP Path**: C:\xampp\php\

---

## 📥 Step 1: Download MongoDB Extension

### Manual Download (Recommended):
1. **Go to**: https://pecl.php.net/package/mongodb
2. **Click**: "DLL" link next to the latest version (1.20.0 or newer)
3. **Download**: `php_mongodb-1.20.0-8.2-ts-vs16-x64.zip`
   - **8.2** = Your PHP version
   - **ts** = Thread Safe
   - **x64** = 64-bit

### Direct Link:
https://windows.php.net/downloads/pecl/releases/mongodb/1.20.0/php_mongodb-1.20.0-8.2-ts-vs16-x64.zip

---

## 📁 Step 2: Install the Extension

1. **Extract** the downloaded ZIP file
2. **Copy** `php_mongodb.dll` from the ZIP
3. **Paste** into: `C:\xampp\php\ext\`

---

## ⚙️ Step 3: Enable in php.ini

1. **Open**: `C:\xampp\php\php.ini` (use Notepad or VS Code)
2. **Find** the extensions section (search for `;extension=`)
3. **Add this line** anywhere in the extensions section:
   ```ini
   extension=mongodb
   ```

**Example location** (around line 900-950):
```ini
;extension=bz2
;extension=curl
;extension=ffi
extension=gd
extension=mongodb    ← ADD THIS LINE
extension=mbstring
extension=sodium
```

4. **Save** the file

---

## 🔄 Step 4: Restart Apache

1. Open **XAMPP Control Panel**
2. Click **Stop** on Apache
3. Wait 2 seconds
4. Click **Start** on Apache

---

## ✅ Step 5: Verify Installation

Run this command in PowerShell:

```powershell
php -m | Select-String mongodb
```

**Expected output**: `mongodb`

If you see "mongodb", it's installed correctly! ✅

---

## 🚨 Alternative: Quick PowerShell Download

Run these commands to download automatically:

```powershell
# Download MongoDB extension
Invoke-WebRequest -Uri "https://windows.php.net/downloads/pecl/releases/mongodb/1.20.0/php_mongodb-1.20.0-8.2-ts-vs16-x64.zip" -OutFile "mongodb-ext.zip"

# Extract
Expand-Archive -Path "mongodb-ext.zip" -DestinationPath "mongodb-temp" -Force

# Copy to PHP extensions folder
Copy-Item "mongodb-temp\php_mongodb.dll" -Destination "C:\xampp\php\ext\" -Force

# Cleanup
Remove-Item "mongodb-ext.zip" -Force
Remove-Item "mongodb-temp" -Recurse -Force

Write-Host "✅ MongoDB DLL copied successfully!" -ForegroundColor Green
Write-Host "Now:" -ForegroundColor Yellow
Write-Host "1. Add 'extension=mongodb' to C:\xampp\php\php.ini" -ForegroundColor White
Write-Host "2. Restart Apache in XAMPP" -ForegroundColor White
Write-Host "3. Run: php -m | Select-String mongodb" -ForegroundColor White
```

---

## 📋 Checklist

- [ ] Downloaded php_mongodb DLL for PHP 8.2 TS x64
- [ ] Copied `php_mongodb.dll` to `C:\xampp\php\ext\`
- [ ] Added `extension=mongodb` to `php.ini`
- [ ] Restarted Apache
- [ ] Verified with `php -m | Select-String mongodb`

---

## 🎯 After Installation

Once you see "mongodb" in the output, tell me and I'll:
1. ✅ Install MongoDB Laravel package
2. ✅ Configure MongoDB Atlas connection
3. ✅ Implement real-time messaging
4. ✅ Create admin ↔ customer chat

---

**Ready?** Follow the steps above and let me know when you see "mongodb" in the verification! 🚀
