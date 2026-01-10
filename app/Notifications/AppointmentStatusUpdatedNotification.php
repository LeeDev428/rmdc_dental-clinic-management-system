<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Appointment;

class AppointmentStatusUpdatedNotification extends Notification
{
    use Queueable;

    public $appointment;
    public $action;

    public function __construct(Appointment $appointment, string $action)
    {
        $this->appointment = $appointment;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $subject = $this->action === 'accepted' 
            ? 'Appointment Approved - RMDC' 
            : 'Appointment Update - RMDC';
            
        return (new MailMessage)
            ->from('rmdc.c.moncayo@gmail.com', 'RMDC')
            ->subject($subject)
            ->view('emails.appointment-status-updated', [
                'appointment' => $this->appointment,
                'action' => $this->action
            ]);
    }
}
