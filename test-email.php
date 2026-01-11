<?php
// Test email sending using same configuration as Laravel
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentBooked;
use App\Models\User;
use App\Models\Appointment;

echo "Testing email configuration...\n\n";

// Get a real user and appointment from database
$user = User::find(20); // User ID from logs
if (!$user) {
    die("User not found\n");
}

echo "Found user: {$user->name} ({$user->email})\n";

// Get latest appointment
$appointment = Appointment::where('user_id', $user->id)->latest()->first();
if (!$appointment) {
    die("No appointment found\n");
}

echo "Found appointment: #{$appointment->id} - {$appointment->procedure}\n\n";

echo "Sending test email...\n";

try {
    Mail::to($user->email)->send(new AppointmentBooked($appointment));
    echo "✅ EMAIL SENT SUCCESSFULLY!\n";
    echo "Check your inbox: {$user->email}\n";
} catch (Exception $e) {
    echo "❌ EMAIL FAILED: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
