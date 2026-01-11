<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\MongoMessage;

$userId1 = 3;
$userId2 = 20;

echo "Testing conversation between user $userId1 and $userId2\n\n";

$messages = MongoMessage::conversation($userId1, $userId2)->get();

echo "Found {$messages->count()} messages:\n";
foreach ($messages as $message) {
    echo "- From {$message->sender_id} to {$message->recipient_id}: {$message->message}\n";
}
