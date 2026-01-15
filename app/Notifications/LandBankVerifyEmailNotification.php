<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;

class LandBankVerifyEmailNotification extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    /**
     * The verification token.
     *
     * @var string
     */
    protected $token;

    /**
     * The team ID.
     *
     * @var int|null
     */
    protected $teamId;

    /**
     * Create a new notification instance.
     *
     * @param  string  $token
     * @param  int|null  $teamId
     * @return void
     */
    public function __construct($token, $teamId = null)
    {
        $this->token = $token;
        $this->teamId = $teamId;
        // Don't call parent constructor
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        Log::info('=== LAND BANK VERIFICATION EMAIL START ===', [
            'user_id' => $notifiable->id,
            'email' => $notifiable->email,
            'notification_class' => get_class($this),
            'team_id_from_notification' => $this->teamId,
            'team_id_from_user' => $notifiable->team_id,
        ]);
        
        // Generate verification URL using our custom method
        $verificationUrl = $this->landBankVerificationUrl($notifiable);
        
        Log::info('=== LAND BANK EMAIL URL READY ===', [
            'user_id' => $notifiable->id,
            'url_preview' => substr($verificationUrl, 0, 100),
        ]);

        // Generate team-based password - USE teamId from notification if available
        $teamNumber = $this->teamId  ?? $notifiable->team_id ?? 1;
        $currentYear = date('Y');
        $defaultPassword = "Inventory-Team{$teamNumber}@{$currentYear}";

        Log::info('=== PASSWORD GENERATION ===', [
            'team_number_used' => $teamNumber,
            'default_password' => $defaultPassword,
        ]);

        $mailMessage = (new MailMessage)
            ->subject('Verify Your Email Address - LandBank Inventory System')
            ->greeting('Welcome to LandBank Inventory System!')
            ->line('Hello ' . $notifiable->name . ',')
            ->line('Thank you for registering with the LandBank Inventory System. To complete your registration and access the asset management platform, please verify your email address.')
            
            // Add credentials section
            ->line('')
            ->line('**Your Login Credentials:**')
            ->line('**Email:** `' . $notifiable->email . '`')
            ->line('**Default Password:** `' . $defaultPassword . '`')
            ->line('')
            
            // Add important instructions
            ->line('**🔐 Important Instructions:**')
            ->line('')
            ->line('1. **Click the verification link below** to activate your account')
            ->line('2. **Login** using the credentials above')
            ->line('3. **Change your password immediately** after first login')
            ->line('4. This default password is **team-based** and shared with your team')
            
            ->action('✅ Verify Email Address', $verificationUrl)
            
            // Add plain text version of URL as backup
            ->line('')
            ->line('**If the button above doesn\'t work, copy and paste this URL into your browser:**')
            ->line('<span style="word-break: break-all; font-family: monospace; color: #2d3748; background-color: #f7fafc; padding: 8px; border-radius: 4px; display: block;">' . $verificationUrl . '</span>')
            
            ->line('**Important Security Notes:**')
            ->line('- You **must change your password immediately** after first login')
            ->line('- All initial access attempts are logged for security monitoring')
            ->line('- **DO NOT share this verification link** with anyone')
            
            ->line('**Important:** This verification link will expire in 24 hours.')
            ->line('For security reasons, please do not share this link with anyone.')
            ->line('If you did not create an account on the LandBank Inventory System, please disregard this email.')
            ->salutation('Best regards,<br>LandBank Inventory System Team<br>Asset Management Department');

        Log::info('=== LAND BANK EMAIL PREPARED ===', [
            'user_id' => $notifiable->id,
            'action_url' => $mailMessage->actionUrl,
        ]);

        return $mailMessage;
    }
    
    /**
     * Generate the LandBank-specific verification URL.
     * This method is PUBLIC to avoid protected method access issues.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    public function landBankVerificationUrl($notifiable)
    {
        Log::info('=== LAND BANK GENERATING VERIFICATION URL ===', [
            'user_id' => $notifiable->id,
            'email' => $notifiable->email,
            'token' => substr($this->token, 0, 20) . '...',
        ]);
        
        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addHours(24),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
                'token' => $this->token,
            ]
        );
        
        Log::info('=== LAND BANK GENERATED URL ===', [
            'url_length' => strlen($url),
            'has_expires' => str_contains($url, 'expires='),
            'has_signature' => str_contains($url, 'signature='),
            'has_token' => str_contains($url, 'token='),
        ]);
        
        return $url;
    }

    /**
     * Override the parent verificationUrl method to use our custom one.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        return $this->landBankVerificationUrl($notifiable);
    }

    /**
     * Get the preview text for the notification.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    public function previewText($notifiable)
    {
        return 'Verify your email address for LandBank Inventory System';
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'notification_type' => 'landbank_verification',
            'user_id' => $notifiable->id,
            'email' => $notifiable->email,
            'team_id' => $this->teamId,
        ];
    }
}