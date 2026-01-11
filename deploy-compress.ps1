# PowerShell Script: Compress vendor folder for upload
# Run this on your LOCAL PC before uploading to Hostinger

Write-Host "Compressing vendor folder..." -ForegroundColor Cyan
Compress-Archive -Path vendor -DestinationPath vendor.zip -Force

Write-Host "`nvendor.zip created successfully!" -ForegroundColor Green
Write-Host "`nNext steps:" -ForegroundColor Yellow
Write-Host "1. Go to hPanel File Manager" -ForegroundColor White
Write-Host "2. Navigate to /domains/roblesmoncayo.com/public_html" -ForegroundColor White
Write-Host "3. Upload vendor.zip" -ForegroundColor White
Write-Host "4. Extract vendor.zip" -ForegroundColor White
Write-Host "5. Run deploy-server.sh on the server via SSH" -ForegroundColor White
