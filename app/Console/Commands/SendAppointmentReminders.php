<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Mail\AppointmentReminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders for appointments scheduled in 4 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for appointments that need reminders...');
        
        // Get current time and 4 hours from now
        $now = Carbon::now();
        $fourHoursLater = $now->copy()->addHours(4);
        
        // Find appointments that:
        // 1. Start time is between now+4hours and now+4hours+15minutes (15min window)
        // 2. Status is 'accepted' (confirmed by admin)
        // 3. Haven't been sent a reminder yet (we'll mark them)
        $appointments = Appointment::whereBetween('start', [
                $fourHoursLater,
                $fourHoursLater->copy()->addMinutes(15)
            ])
            ->where('status', 'accepted')
            ->whereNull('reminder_sent_at') // Only send once
            ->with('user')
            ->get();
        
        if ($appointments->isEmpty()) {
            $this->info('No appointments found that need reminders.');
            return 0;
        }
        
        $this->info("Found {$appointments->count()} appointment(s) to send reminders for.");
        
        $successCount = 0;
        $failCount = 0;
        
        foreach ($appointments as $appointment) {
            try {
                // Send reminder email
                Mail::to($appointment->user->email)->send(new AppointmentReminder($appointment));
                
                // Mark as sent
                $appointment->update(['reminder_sent_at' => now()]);
                
                $this->info("✓ Reminder sent to {$appointment->user->email} for appointment #{$appointment->id}");
                Log::info('Appointment reminder sent', [
                    'appointment_id' => $appointment->id,
                    'user_email' => $appointment->user->email,
                    'appointment_time' => $appointment->start
                ]);
                
                $successCount++;
            } catch (\Exception $e) {
                $this->error("✗ Failed to send reminder for appointment #{$appointment->id}: {$e->getMessage()}");
                Log::error('Failed to send appointment reminder', [
                    'appointment_id' => $appointment->id,
                    'error' => $e->getMessage()
                ]);
                
                $failCount++;
            }
        }
        
        $this->info("\nSummary:");
        $this->info("✓ Successfully sent: {$successCount}");
        if ($failCount > 0) {
            $this->warn("✗ Failed: {$failCount}");
        }
        
        return 0;
    }
}
