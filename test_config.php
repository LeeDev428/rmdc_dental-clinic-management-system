<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

// Test Redis connection
try {
    Redis::set('test_key', 'test_value');
    $redisTest = Redis::get('test_key');
    echo "Redis test: " . ($redisTest === 'test_value' ? 'PASSED' : 'FAILED') . "\n";
} catch (Exception $e) {
    echo "Redis test FAILED: " . $e->getMessage() . "\n";
}

// Test mail configuration
try {
    Mail::raw('This is a test email from RMDC system', function($message) {
        $message->to('test@example.com')
                ->subject('Test Email from RMDC');
    });
    echo "Mail test: PASSED\n";
} catch (Exception $e) {
    echo "Mail test FAILED: " . $e->getMessage() . "\n";
}

echo "Configuration test completed.\n";