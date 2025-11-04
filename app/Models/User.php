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
        'usertype', // Added usertype
        'email_verified_at', // Added for OAuth
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
        if ($this->avatar) {
            // Check if it's the default image or a public path
            if (strpos($this->avatar, 'img/') === 0) {
                return asset($this->avatar); // Use asset() for public folder files
            }
            return Storage::url($this->avatar); // Use Storage for uploaded files
        }
        
        // Fallback to default avatar
        return asset('img/default-dp.jpg');
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

        // Relationship with Appointment model
        public function appointments()
        {
            return $this->hasMany(Appointment::class);
        }
}
