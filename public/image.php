<?php
/**
 * Direct Image Access Script for Valid IDs
 * This bypasses the symbolic link and serves images directly from storage
 */

// Get the image path from the query parameter
$imagePath = $_GET['path'] ?? '';

// Security: Only allow valid_ids images
if (empty($imagePath) || strpos($imagePath, 'valid_ids/') !== 0) {
    header('HTTP/1.0 404 Not Found');
    die('Image not found');
}

// Build the full path to the image
$fullPath = __DIR__ . '/../storage/app/public/' . $imagePath;

// Check if file exists
if (!file_exists($fullPath)) {
    header('HTTP/1.0 404 Not Found');
    die('Image not found');
}

// Get the image info
$imageInfo = getimagesize($fullPath);
if ($imageInfo === false) {
    header('HTTP/1.0 400 Bad Request');
    die('Invalid image file');
}

// Set the content type header
header('Content-Type: ' . $imageInfo['mime']);
header('Content-Length: ' . filesize($fullPath));
header('Cache-Control: public, max-age=86400'); // Cache for 1 day

// Output the image
readfile($fullPath);
exit;
