<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Carbon\Carbon;

class MongoMessage extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'messages';

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'message',
        'is_read',
        'read_at',
        'sender_type', // 'admin' or 'user'
        'attachments',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships removed - MongoDB can't directly join with MySQL
    // User data should be manually attached by controllers when needed

    // Mark as read
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => Carbon::now(),
            ]);
        }
    }

    // Scope for unread messages
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // Scope for conversation between two users
    public function scopeConversation($query, $userId1, $userId2)
    {
        // Cast to integers to ensure type matching
        $userId1 = (int)$userId1;
        $userId2 = (int)$userId2;
        
        return $query->where(function ($q) use ($userId1, $userId2) {
            $q->where(function ($q2) use ($userId1, $userId2) {
                $q2->where('sender_id', $userId1)->where('recipient_id', $userId2);
            })->orWhere(function ($q2) use ($userId1, $userId2) {
                $q2->where('sender_id', $userId2)->where('recipient_id', $userId1);
            });
        });
    }
}
