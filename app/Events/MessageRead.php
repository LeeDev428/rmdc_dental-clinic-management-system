<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageRead implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messageId;
    public $userId;

    public function __construct($messageId, $userId)
    {
        $this->messageId = $messageId;
        $this->userId = $userId;
    }

    public function broadcastOn()
    {
        return [
            new Channel('messages.' . $this->userId),
        ];
    }

    public function broadcastAs()
    {
        return 'message.read';
    }

    public function broadcastWith()
    {
        return [
            'message_id' => $this->messageId,
        ];
    }
}
