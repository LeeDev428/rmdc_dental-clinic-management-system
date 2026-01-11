# 🔽 Download MongoDB Extension Manually

The automated download failed. Please follow these manual steps:

## Step 1: Download Correct Version

### Go to PECL MongoDB Page:
**URL**: https://pecl.php.net/package/mongodb/1.19.4/windows

### OR Direct Download Links (Choose ONE):

**For PHP 8.2 Thread Safe (TS) x64**:
- Try version 1.19.4: https://windows.php.net/downloads/pecl/releases/mongodb/1.19.4/php_mongodb-1.19.4-8.2-ts-vs16-x64.zip
- Try version 1.19.3: https://windows.php.net/downloads/pecl/releases/mongodb/1.19.3/php_mongodb-1.19.3-8.2-ts-vs16-x64.zip
- Try version 1.18.1: https://windows.php.net/downloads/pecl/releases/mongodb/1.18.1/php_mongodb-1.18.1-8.2-ts-vs16-x64.zip

### Alternative: Browse All Versions
https://pecl.php.net/package/mongodb

Click "DLL" next to any version, then download the file matching:
- **PHP 8.2**
- **Thread Safe (TS)**
- **x64**

---

## Step 2: Extract and Install

Once downloaded:

```powershell
# Navigate to Downloads folder
cd $env:USERPROFILE\Downloads

# List the downloaded file (verify it exists)
Get-ChildItem *mongodb*.zip

# Extract (replace filename with actual downloaded file)
Expand-Archive -Path "php_mongodb-1.19.4-8.2-ts-vs16-x64.zip" -DestinationPath "mongodb-temp" -Force

# Copy to PHP extensions folder
Copy-Item "mongodb-temp\php_mongodb.dll" -Destination "C:\xampp\php\ext\" -Force

# Verify it was copied
Test-Path "C:\xampp\php\ext\php_mongodb.dll"

# Cleanup
Remove-Item "mongodb-temp" -Recurse -Force
```

**Expected output**: `True` (confirms DLL is in place)

---

## Step 3: Enable in php.ini

```powershell
# Open php.ini in notepad
notepad C:\xampp\php\php.ini
```

**Add this line** in the extensions section:
```ini
extension=mongodb
```

**Save and close** notepad.

---

## Step 4: Restart Apache

1. Open XAMPP Control Panel
2. Stop Apache
3. Start Apache

---

## Step 5: Verify

```powershell
php -m | Select-String mongodb
```

**If you see "mongodb"** → Success! ✅

**If you DON'T see it**:
```powershell
# Check if DLL exists
Test-Path "C:\xampp\php\ext\php_mongodb.dll"

# Check if extension is in php.ini
Get-Content "C:\xampp\php\php.ini" | Select-String "mongodb"

# Check PHP error log
Get-Content "C:\xampp\php\logs\php_error_log" -Tail 20
```

---

## 🆘 Still Having Issues?

### Alternative Option: Use MySQL Instead

If MongoDB installation is too complex, I can implement the **exact same features** using MySQL:
- ✅ Real-time messaging with Pusher
- ✅ Admin ↔ Customer chat
- ✅ Live notifications
- ✅ Message history

MongoDB is nice to have but **NOT required** for real-time features!

---

## What Next?

**Option A**: Continue with MongoDB
- Download manually from links above
- Follow steps 2-5
- Tell me when `php -m | Select-String mongodb` shows "mongodb"

**Option B**: Skip MongoDB, use MySQL
- Tell me "use MySQL instead"
- I'll implement everything immediately with MySQL + Pusher

**Which option?** 🤔
