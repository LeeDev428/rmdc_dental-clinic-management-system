<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use App\Models\Message;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'auth_provider', // Added auth_provider
        'auth_provider_id', // Added auth_provider_id
        'bio', // Added bio
        'avatar', // Added avatar
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_email_verified' => 'integer', // Cast as integer
            'password' => 'hashed',
        ];
    }

    /**
     * Get the URL of the avatar.
     *
     * @return string
     */
    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar ? Storage::url($this->avatar) : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email))) . '?d=mp';
    }

        // Relationship with Notification model
        public function notifications()
        {
            return $this->hasMany(Notification::class);
        }

        // Get only unread notifications
        public function unreadNotifications()
        {
            return $this->notifications()->where('status', 'unread');
        }

        // Mark all notifications as read
        public function markAllNotificationsAsRead()
        {
            $this->notifications()->where('status', 'unread')->update(['status' => 'read']);
        }
        public function messages()
        {
            return $this->hasMany(Message::class);
        }
}
