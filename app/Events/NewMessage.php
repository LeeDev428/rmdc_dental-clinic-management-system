<?php

namespace App\Events;

use App\Models\MongoMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(MongoMessage $message)
    {
        // Don't load relationships - they'll be attached by the controller
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return [
            new Channel('messages.' . $this->message->recipient_id),
        ];
    }

    public function broadcastAs()
    {
        return 'new.message';
    }

    public function broadcastWith()
    {
        // Load sender data from MySQL
        $sender = \App\Models\User::find($this->message->sender_id);
        
        return [
            'id' => (string) $this->message->_id,
            'message' => $this->message->message,
            'sender_id' => $this->message->sender_id,
            'sender_name' => $sender ? $sender->name : 'Unknown',
            'sender_avatar' => $sender ? $sender->avatar_url : null,
            'sender_type' => $this->message->sender_type,
            'is_read' => $this->message->is_read,
            'created_at' => $this->message->created_at->toIso8601String(),
        ];
    }
}
