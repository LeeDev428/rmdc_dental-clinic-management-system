<?php
// Simple test file to check if images are accessible
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Image Upload Test</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .test { margin: 20px 0; padding: 10px; border: 1px solid #ccc; }
        .success { background: #d4edda; }
        .error { background: #f8d7da; }
        img { max-width: 300px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <h1>🧪 Image Upload Test - RMDC</h1>
    
    <div class="test">
        <h3>1. Test File (test.txt)</h3>
        <p>Path: <code>/storage/test.txt</code></p>
        <iframe src="/storage/test.txt" width="400" height="50"></iframe>
    </div>
    
    <div class="test">
        <h3>2. Check Storage Link</h3>
        <p>Symbolic Link Check:</p>
        <code><?php
            $link = __DIR__ . '/storage';
            if (is_link($link)) {
                echo "✅ Symbolic link exists<br>";
                echo "Points to: " . readlink($link);
            } else {
                echo "❌ Symbolic link NOT found!";
            }
        ?></code>
    </div>
    
    <div class="test">
        <h3>3. Check Folders</h3>
        <?php
        $folders = ['valid_ids', 'procedures', 'avatars', 'dental_records'];
        foreach ($folders as $folder) {
            $path = __DIR__ . '/storage/' . $folder;
            if (is_dir($path)) {
                $files = array_diff(scandir($path), array('.', '..'));
                echo "<p>✅ <strong>$folder/</strong> exists (" . count($files) . " files)</p>";
                
                // Show first image if exists
                foreach ($files as $file) {
                    if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
                        echo "<img src='/storage/$folder/$file' alt='Test Image' style='max-width:200px;'><br>";
                        echo "<small>Path: /storage/$folder/$file</small>";
                        break;
                    }
                }
            } else {
                echo "<p>❌ <strong>$folder/</strong> NOT found!</p>";
            }
        }
        ?>
    </div>
    
    <div class="test">
        <h3>4. Latest Appointments with Images</h3>
        <?php
        // You'll need to run this AFTER uploading to production
        echo "<p>Check database for image paths...</p>";
        echo "<p>Go to phpMyAdmin and run:</p>";
        echo "<code>SELECT id, username, image_path FROM appointments WHERE image_path IS NOT NULL ORDER BY id DESC LIMIT 5;</code>";
        ?>
    </div>
    
    <div class="test">
        <h3>5. Manual Image Test</h3>
        <p>Try loading an image directly:</p>
        <img src="/storage/test.txt" alt="This should fail" onerror="this.src='';this.alt='❌ Image failed to load'">
        <p><small>Note: This will fail because test.txt is not an image, but it tests the path</small></p>
    </div>
</body>
</html>
```