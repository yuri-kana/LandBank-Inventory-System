<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class TeamInvitationNotification extends Notification
{
    use Queueable;

    public $teamName;

    public function __construct($teamName)
    {
        $this->teamName = $teamName;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addDays(7),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        return (new MailMessage)
            ->subject('Invitation to Join ' . $this->teamName . ' Team')
            ->greeting('Hello!')
            ->line('You have been invited to join the ' . $this->teamName . ' team.')
            ->line('Please click the button below to verify your email address and set up your account.')
            ->action('Set Up Account', $verificationUrl)
            ->line('This invitation link will expire in 7 days.')
            ->line('If you did not expect this invitation, you can safely ignore this email.');
    }
}