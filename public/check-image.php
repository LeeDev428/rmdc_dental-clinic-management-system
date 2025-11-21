<?php
// Diagnostic script to check image accessibility
echo "<h2>Image Path Diagnostics</h2>";

// Check symbolic link
echo "<h3>1. Symbolic Link Check</h3>";
$symlinkPath = __DIR__ . '/storage';
echo "Symbolic link exists: " . (file_exists($symlinkPath) ? "YES" : "NO") . "<br>";
echo "Is link: " . (is_link($symlinkPath) ? "YES" : "NO") . "<br>";
if (is_link($symlinkPath)) {
    echo "Link target: " . readlink($symlinkPath) . "<br>";
}

// Check if valid_ids directory is accessible
echo "<h3>2. Valid IDs Directory Check</h3>";
$validIdsPath = $symlinkPath . '/valid_ids';
echo "Valid IDs path: $validIdsPath<br>";
echo "Directory exists: " . (file_exists($validIdsPath) ? "YES" : "NO") . "<br>";
echo "Is readable: " . (is_readable($validIdsPath) ? "YES" : "NO") . "<br>";

// List files in valid_ids
if (file_exists($validIdsPath) && is_readable($validIdsPath)) {
    echo "<h3>3. Files in valid_ids/</h3>";
    $files = scandir($validIdsPath);
    echo "<ul>";
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $filePath = $validIdsPath . '/' . $file;
            $fileSize = filesize($filePath);
            $isReadable = is_readable($filePath) ? "YES" : "NO";
            echo "<li>$file (Size: " . round($fileSize/1024, 2) . " KB, Readable: $isReadable)</li>";
        }
    }
    echo "</ul>";
}

// Test specific image
echo "<h3>4. Test Specific Image</h3>";
$testImage = '1763766582_siomai.png';
$testImagePath = $validIdsPath . '/' . $testImage;
echo "Test image path: $testImagePath<br>";
echo "File exists: " . (file_exists($testImagePath) ? "YES" : "NO") . "<br>";
echo "Is readable: " . (is_readable($testImagePath) ? "YES" : "NO") . "<br>";

// Try to display the image
if (file_exists($testImagePath)) {
    $imageUrl = '/storage/valid_ids/' . $testImage;
    echo "<h3>5. Display Test Image</h3>";
    echo "<p>Image URL: <a href='$imageUrl' target='_blank'>$imageUrl</a></p>";
    echo "<img src='$imageUrl' style='max-width: 300px; border: 2px solid #ccc;' alt='Test Image'>";
}

// Check .htaccess
echo "<h3>6. .htaccess Check</h3>";
$htaccessPath = __DIR__ . '/.htaccess';
echo ".htaccess exists: " . (file_exists($htaccessPath) ? "YES" : "NO") . "<br>";
if (file_exists($htaccessPath)) {
    echo "<pre>" . htmlspecialchars(file_get_contents($htaccessPath)) . "</pre>";
}
