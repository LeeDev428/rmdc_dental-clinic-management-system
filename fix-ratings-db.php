<?php

// Run this file directly on the server: php fix-ratings-db.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "Checking ratings_review table structure...\n";

// Get current columns
$columns = DB::select("SHOW COLUMNS FROM ratings_review");
$columnNames = array_column($columns, 'Field');

echo "Current columns: " . implode(', ', $columnNames) . "\n\n";

// Check if user_id exists
if (!in_array('user_id', $columnNames)) {
    echo "Adding user_id column...\n";
    DB::statement("ALTER TABLE ratings_review ADD COLUMN user_id BIGINT UNSIGNED NULL");
    echo "✓ user_id column added\n";
} else {
    echo "✓ user_id column already exists\n";
}

// Check if appointment_id exists
if (!in_array('appointment_id', $columnNames)) {
    echo "Adding appointment_id column...\n";
    DB::statement("ALTER TABLE ratings_review ADD COLUMN appointment_id BIGINT UNSIGNED NULL");
    echo "✓ appointment_id column added\n";
} else {
    echo "✓ appointment_id column already exists\n";
}

// Add foreign keys
try {
    echo "\nAdding foreign key constraints...\n";
    
    // Check if foreign key exists before adding
    $fks = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS 
                       WHERE TABLE_NAME = 'ratings_review' AND CONSTRAINT_TYPE = 'FOREIGN KEY'");
    $fkNames = array_column($fks, 'CONSTRAINT_NAME');
    
    if (!in_array('ratings_review_user_id_foreign', $fkNames)) {
        DB::statement("ALTER TABLE ratings_review ADD CONSTRAINT ratings_review_user_id_foreign 
                       FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE");
        echo "✓ user_id foreign key added\n";
    } else {
        echo "✓ user_id foreign key already exists\n";
    }
    
    if (!in_array('ratings_review_appointment_id_foreign', $fkNames)) {
        DB::statement("ALTER TABLE ratings_review ADD CONSTRAINT ratings_review_appointment_id_foreign 
                       FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE");
        echo "✓ appointment_id foreign key added\n";
    } else {
        echo "✓ appointment_id foreign key already exists\n";
    }
} catch (Exception $e) {
    echo "Note: Foreign keys might already exist or have different names\n";
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n✅ Done! Table structure fixed.\n";

// Show final structure
echo "\nFinal table structure:\n";
$columns = DB::select("SHOW COLUMNS FROM ratings_review");
foreach ($columns as $column) {
    echo "  - {$column->Field} ({$column->Type})\n";
}
