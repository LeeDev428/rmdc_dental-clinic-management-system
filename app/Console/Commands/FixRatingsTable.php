<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixRatingsTable extends Command
{
    protected $signature = 'fix:ratings-table';
    protected $description = 'Add missing user_id and appointment_id columns to ratings_review table';

    public function handle()
    {
        $this->info('Checking ratings_review table...');
        
        // Check current columns
        $columns = Schema::getColumnListing('ratings_review');
        $this->info('Current columns: ' . implode(', ', $columns));
        
        $hasUserId = in_array('user_id', $columns);
        $hasAppointmentId = in_array('appointment_id', $columns);
        
        if ($hasUserId && $hasAppointmentId) {
            $this->info('✓ Both columns already exist!');
            return 0;
        }
        
        $this->info('Adding missing columns...');
        
        try {
            if (!$hasUserId) {
                DB::statement('ALTER TABLE ratings_review ADD COLUMN user_id BIGINT UNSIGNED NULL AFTER id');
                DB::statement('ALTER TABLE ratings_review ADD CONSTRAINT ratings_review_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
                $this->info('✓ Added user_id column');
            }
            
            if (!$hasAppointmentId) {
                DB::statement('ALTER TABLE ratings_review ADD COLUMN appointment_id BIGINT UNSIGNED NULL AFTER ' . ($hasUserId ? 'user_id' : 'id'));
                DB::statement('ALTER TABLE ratings_review ADD CONSTRAINT ratings_review_appointment_id_foreign FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE');
                $this->info('✓ Added appointment_id column');
            }
            
            $this->info('✓ Successfully fixed ratings_review table!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}
