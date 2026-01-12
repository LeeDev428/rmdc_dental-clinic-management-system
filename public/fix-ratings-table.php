<?php
// Quick fix script to add missing columns to ratings_review table
// SECURITY: Add basic protection
$secret = 'fix2026'; // You need to pass ?key=fix2026 in URL

if (!isset($_GET['key']) || $_GET['key'] !== $secret) {
    die('Access denied');
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain');

try {
    echo "Checking ratings_review table...\n";
    
    // Get current columns
    $columns = DB::select('DESCRIBE ratings_review');
    $columnNames = array_column($columns, 'Field');
    
    echo "Current columns: " . implode(', ', $columnNames) . "\n";
    
    $hasUserId = in_array('user_id', $columnNames);
    $hasAppointmentId = in_array('appointment_id', $columnNames);
    
    if ($hasUserId && $hasAppointmentId) {
        echo "✓ Both columns already exist!\n";
        exit(0);
    }
    
    echo "Adding missing columns...\n";
    
    if (!$hasUserId) {
        DB::statement('ALTER TABLE ratings_review ADD COLUMN user_id BIGINT UNSIGNED NULL AFTER id');
        echo "✓ Added user_id column\n";
        
        // Add foreign key
        DB::statement('ALTER TABLE ratings_review ADD CONSTRAINT ratings_review_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
        echo "✓ Added user_id foreign key\n";
    }
    
    if (!$hasAppointmentId) {
        $afterColumn = $hasUserId ? 'user_id' : 'id';
        DB::statement("ALTER TABLE ratings_review ADD COLUMN appointment_id BIGINT UNSIGNED NULL AFTER $afterColumn");
        echo "✓ Added appointment_id column\n";
        
        // Add foreign key
        DB::statement('ALTER TABLE ratings_review ADD CONSTRAINT ratings_review_appointment_id_foreign FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE');
        echo "✓ Added appointment_id foreign key\n";
    }
    
    echo "\n✓ SUCCESS! ratings_review table has been fixed.\n";
    
    // Verify
    $newColumns = DB::select('DESCRIBE ratings_review');
    $newColumnNames = array_column($newColumns, 'Field');
    echo "Final columns: " . implode(', ', $newColumnNames) . "\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
