<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class CleanupOldNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete notifications older than 7 weeks (49 days)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting notification cleanup...');
        
        $sevenWeeksAgo = Carbon::now()->subWeeks(7);
        
        // Delete old system notifications
        $deletedNotifications = DB::table('notifications')
            ->where('created_at', '<', $sevenWeeksAgo)
            ->delete();
        
        $this->info("Deleted {$deletedNotifications} system notifications older than 7 weeks.");
        
        // Delete old messages if messages table exists
        if (Schema::hasTable('messages')) {
            $deletedMessages = DB::table('messages')
                ->where('created_at', '<', $sevenWeeksAgo)
                ->delete();
            
            $this->info("Deleted {$deletedMessages} messages older than 7 weeks.");
        }
        
        // Clear cache for comprehensive notifications
        $this->info('Clearing notification cache...');
        $users = DB::table('users')->pluck('id');
        foreach ($users as $userId) {
            Cache::forget('user_comprehensive_notifications_' . $userId);
        }
        
        $this->info('Notification cleanup completed successfully!');
        
        return Command::SUCCESS;
    }
}
