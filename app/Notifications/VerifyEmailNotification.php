<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        parent::__construct(); // Add parent constructor call
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        \Log::info('=== VERIFICATION EMAIL START ===', [
            'user_id' => $notifiable->id,
            'email' => $notifiable->email,
        ]);
        
        // Generate verification URL
        $verificationUrl = $this->verificationUrl($notifiable);
        
        \Log::info('=== EMAIL URL READY ===', [
            'user_id' => $notifiable->id,
            'url_preview' => substr($verificationUrl, 0, 100),
        ]);

        // Generate team-based password
        $teamNumber = $notifiable->team_id ?? 1;
        $currentYear = date('Y');
        $defaultPassword = "Inventory-Team{$teamNumber}@{$currentYear}";

        return (new MailMessage)
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
    }
    
    /**
     * Generate the verification URL - CORRECTED VERSION
     */
    protected function verificationUrl($notifiable)
    {
        \Log::info('=== GENERATING VERIFICATION URL ===', [
            'user_id' => $notifiable->id,
            'email' => $notifiable->email,
            'token' => $this->token,
        ]);
        
        // Generate signed URL WITH token included in the signature
        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addHours(24),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
                'token' => $this->token, // Add token INSIDE the signed parameters
            ]
        );
        
        \Log::info('=== GENERATED URL ===', [
            'url' => $url,
            'url_length' => strlen($url),
        ]);
        
        // Log the query parameters
        $parsed = parse_url($url);
        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $params);
            \Log::info('=== URL PARAMETERS ===', [
                'parameters' => array_keys($params),
                'has_expires' => isset($params['expires']),
                'has_signature' => isset($params['signature']),
                'has_token' => isset($params['token']),
            ]);
        }
        
        return $url;
    }

    public function previewText($notifiable)
    {
        return 'Verify your email address for LandBank Inventory System';
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}