<?php
// Emergency cache clear script
// Access via: https://roblesmoncayo.com/clear-cache.php
// DELETE THIS FILE AFTER USE!

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<h2>Clearing Laravel Cache...</h2>";

try {
    // Clear config cache
    $kernel->call('config:clear');
    echo "✓ Config cache cleared<br>";
    
    // Clear application cache
    $kernel->call('cache:clear');
    echo "✓ Application cache cleared<br>";
    
    // Clear route cache
    $kernel->call('route:clear');
    echo "✓ Route cache cleared<br>";
    
    // Clear view cache
    $kernel->call('view:clear');
    echo "✓ View cache cleared<br>";
    
    echo "<br><strong style='color: green;'>All caches cleared successfully!</strong><br>";
    echo "<br><strong style='color: red;'>IMPORTANT: Delete this file (clear-cache.php) immediately for security!</strong>";
    
} catch (Exception $e) {
    echo "<br><strong style='color: red;'>Error: " . $e->getMessage() . "</strong>";
}
