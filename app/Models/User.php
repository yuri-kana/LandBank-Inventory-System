<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Notifications\LandBankVerifyEmailNotification;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path',
        'role',
        'team_id',
        'email_verified_at',
        'verification_token',
        'verification_sent_at',
        'verification_required',
        'is_active',
        'password_changed_at',
        'password_change_required',
        'reset_token',
        'reset_token_expires_at',
        'login_count',
        'username',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
        'reset_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'verification_sent_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'reset_token_expires_at' => 'datetime',
        'is_active' => 'boolean',
        'verification_required' => 'boolean',
        'password_change_required' => 'boolean',
        'login_count' => 'integer',
    ];

    protected $appends = [
        'display_name', 
        'short_name', 
        'initials', 
        'password_status',
        'profile_photo_url'
    ];

    // =========================================================================
    // ===== RELATIONSHIPS =====
    // =========================================================================
    
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function teamRequests()
    {
        return $this->hasMany(TeamRequest::class, 'approved_by');
    }

    public function claimedRequests()
    {
        return $this->hasMany(TeamRequest::class, 'claimed_by');
    }

    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class);
    }

    // =========================================================================
    // ===== ROLE METHODS =====
    // =========================================================================
    
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTeamMember()
    {
        return $this->role === 'team_member';
    }

    // =========================================================================
    // ===== NOTIFICATION METHODS =====
    // =========================================================================
    
    /**
     * Get unread notifications count
     */
    public function getUnreadNotificationsCount()
    {
        try {
            return $this->unreadNotifications()->count();
        } catch (\Exception $e) {
            \Log::error('Error getting unread notifications count: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get unread notifications - Simplified and reliable
     */
    public function getUnreadNotifications()
    {
        try {
            return $this->unreadNotifications()
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get()
                ->map(function ($notification) {
                    try {
                        $data = $notification->data;
                        
                        // Handle both array and JSON string
                        if (is_string($data)) {
                            $data = json_decode($data, true) ?: [];
                        }
                        
                        if (!is_array($data)) {
                            $data = [];
                        }
                        
                        return [
                            'id' => $notification->id,
                            'title' => $data['title'] ?? 'Notification',
                            'message' => $data['message'] ?? $data['body'] ?? 'New notification',
                            'url' => $data['url'] ?? '#',
                            'is_read' => $notification->read_at !== null,
                            'created_at' => $notification->created_at,
                            'raw_data' => $data,
                        ];
                    } catch (\Exception $e) {
                        \Log::error('Error processing notification data: ' . $e->getMessage());
                        return [
                            'id' => $notification->id,
                            'title' => 'Notification',
                            'message' => 'Error processing notification',
                            'url' => '#',
                            'is_read' => $notification->read_at !== null,
                            'created_at' => $notification->created_at,
                        ];
                    }
                });
        } catch (\Exception $e) {
            \Log::error('Error getting unread notifications: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get all notifications with proper formatting
     */
    public function getAllNotifications()
    {
        try {
            return $this->notifications()
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($notification) {
                    $data = $notification->data;
                    
                    if (is_string($data)) {
                        $data = json_decode($data, true) ?: [];
                    }
                    
                    if (!is_array($data)) {
                        $data = [];
                    }
                    
                    return [
                        'id' => $notification->id,
                        'title' => $data['title'] ?? 'Notification',
                        'message' => $data['message'] ?? $data['body'] ?? 'New notification',
                        'url' => $data['url'] ?? '#',
                        'type' => $data['type'] ?? 'general',
                        'read_at' => $notification->read_at,
                        'is_read' => $notification->read_at !== null,
                        'created_at' => $notification->created_at,
                        'data' => $data,
                    ];
                });
        } catch (\Exception $e) {
            \Log::error('Error getting all notifications: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Mark a single notification as read
     */
    public function markNotificationAsRead($notificationId)
    {
        try {
            $notification = $this->notifications()->find($notificationId);
            
            if ($notification) {
                $notification->markAsRead();
                return true;
            }
            
            return false;
        } catch (\Exception $e) {
            Log::error('Error marking notification as read: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead()
    {
        try {
            $this->unreadNotifications->markAsRead();
            return true;
        } catch (\Exception $e) {
            Log::error('Error marking all notifications as read: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get notifications with is_read status
     */
    public function notificationsWithStatus()
    {
        return $this->notifications()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'data' => $notification->data,
                    'read_at' => $notification->read_at,
                    'is_read' => $notification->read_at !== null,
                    'created_at' => $notification->created_at,
                    'type' => $notification->type,
                ];
            });
    }

    // =========================================================================
    // ===== EMAIL VERIFICATION METHODS =====
    // =========================================================================
    
    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        // Generate a token
        $token = Str::random(60);
        
        // Store hashed token in database
        $this->verification_token = hash('sha256', $token);
        $this->verification_sent_at = now();
        $this->verification_required = true;
        $this->is_active = false; // User is NOT active until verified
        $this->email_verified_at = null; // Ensure this is null
        $this->save();
        
        \Log::info('=== SENDING VERIFICATION EMAIL ===', [
            'user_id' => $this->id,
            'email' => $this->email,
            'role' => $this->role,
            'team_id' => $this->team_id,
            'is_active' => $this->is_active,
            'email_verified_at' => $this->email_verified_at,
            'verification_required' => $this->verification_required,
        ]);
        
        // Send the new notification with the token
        $this->notify(new LandBankVerifyEmailNotification($token));
    }

    /**
     * Verify token (helper method for controller)
     */
    public function verifyToken($token)
    {
        return hash_equals($this->verification_token, hash('sha256', $token));
    }

    /**
     * Check if user has verified email
     */
    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Mark email as verified
     */
    public function markEmailAsVerified(): bool
    {
        try {
            $this->forceFill([
                'email_verified_at' => $this->freshTimestamp(),
                'verification_token' => null,
                'verification_required' => false,
                'is_active' => true,
            ])->save();
            
            \Log::info('Email marked as verified', [
                'user_id' => $this->id,
                'email' => $this->email,
                'verified_at' => $this->email_verified_at,
                'is_active' => $this->is_active,
            ]);
            
            event(new \Illuminate\Auth\Events\Verified($this));
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Error marking email as verified: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if user needs verification
     */
    public function needsVerification(): bool
    {
        return $this->verification_required && !$this->hasVerifiedEmail();
    }

    /**
     * Check if verification is expired
     */
    public function isVerificationExpired(): bool
    {
        if (!$this->verification_sent_at) {
            return true;
        }
        return $this->verification_sent_at->addHours(24)->isPast();
    }
    
    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Resend verification email
     */
    public function resendVerificationEmail()
    {
        if ($this->hasVerifiedEmail()) {
            throw new \Exception('User has already verified their email.');
        }
        
        // Reset verification status
        $this->verification_required = true;
        $this->is_active = false;
        $this->email_verified_at = null;
        $this->save();
        
        // Send verification email
        $this->sendEmailVerificationNotification();
        
        \Log::info('Verification email resent', [
            'user_id' => $this->id,
            'email' => $this->email,
            'resend_at' => now(),
        ]);
        
        return true;
    }

    // =========================================================================
    // ===== PASSWORD & SECURITY METHODS =====
    // =========================================================================
    
    /**
     * Check if user requires password change
     */
    public function requiresPasswordChange(): bool
    {
        // Admin never needs to change password on first login
        if ($this->isAdmin()) {
            return false;
        }
        
        // Team members need to change password if password_change_required is true
        // or if they haven't changed password yet (password_changed_at is null)
        return $this->password_change_required || empty($this->password_changed_at);
    }

    /**
     * Record user login
     */
    public function recordLogin()
    {
        $isFirstLogin = $this->login_count === 0;
        
        if ($isFirstLogin) {
            \Log::channel('security')->info('First login detected', [
                'user_id' => $this->id,
                'email' => $this->email,
            ]);
        }
        
        $this->login_count++;
        $this->save();
        
        return $isFirstLogin;
    }

    /**
     * Complete password change
     */
    public function completePasswordChange()
    {
        $this->password_change_required = false;
        $this->password_changed_at = now();
        $this->is_active = true; // Activate user after password is set
        
        \Log::channel('security')->info('Password changed successfully', [
            'user_id' => $this->id,
            'email' => $this->email,
            'is_active' => $this->is_active,
        ]);
        
        $this->save();
        
        return $this;
    }

    /**
     * Generate password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->forceFill([
            'reset_token' => Str::random(60),
            'reset_token_expires_at' => now()->addHours(1),
        ])->save();
    }

    /**
     * Clear password reset token
     */
    public function clearPasswordResetToken()
    {
        $this->forceFill([
            'reset_token' => null,
            'reset_token_expires_at' => null,
        ])->save();
    }

    /**
     * Check if reset token is valid
     */
    public function hasValidResetToken()
    {
        return $this->reset_token && 
               $this->reset_token_expires_at && 
               $this->reset_token_expires_at->isFuture();
    }

    // =========================================================================
    // ===== ACCESSOR METHODS =====
    // =========================================================================

    /**
     * Get display name
     */
    public function getDisplayNameAttribute()
    {
        if ($this->name && !Str::contains($this->name, 'Member')) {
            return $this->name;
        }
        
        return $this->team ? $this->team->name . ' Member' : $this->name;
    }

    /**
     * Get short name
     */
    public function getShortNameAttribute()
    {
        if ($this->name && !Str::contains($this->name, 'Member')) {
            $parts = explode(' ', $this->name);
            return $parts[0];
        }
        
        return $this->team ? $this->team->name : 'User';
    }

    /**
     * Get initials
     */
    public function getInitialsAttribute()
    {
        if ($this->name && !Str::contains($this->name, 'Member')) {
            $words = explode(' ', $this->name);
            $initials = '';
            foreach ($words as $word) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
            return substr($initials, 0, 2);
        }
        
        return $this->team ? strtoupper(substr($this->team->name, 0, 2)) : 'U';
    }

    /**
     * Get password status
     */
    public function getPasswordStatusAttribute()
    {
        if ($this->requiresPasswordChange()) {
            return 'requires_change';
        }
        
        return 'ok';
    }

    /**
     * Get profile photo URL
     */
    public function getProfilePhotoUrlAttribute()
    {
        // Check if profile_photo_path exists
        if ($this->profile_photo_path) {
            // Check if it's a full URL or a local path
            if (filter_var($this->profile_photo_path, FILTER_VALIDATE_URL)) {
                return $this->profile_photo_path;
            }
            
            // For local storage - assuming you're using the public disk
            return asset('storage/' . $this->profile_photo_path);
        }
        
        // Fallback to Gravatar based on email
        $email = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$email}?d=mp&s=128";
    }

    /**
     * Update name from team
     */
    public function updateNameFromTeam()
    {
        if ($this->team && Str::contains($this->name, 'Member')) {
            $username = explode('@', $this->email)[0];
            $this->name = $this->team->name . ' Member - ' . $username;
            $this->save();
        }
    }

    // =========================================================================
    // ===== STATIC METHODS =====
    // =========================================================================

    /**
     * Generate team password
     */
    public static function generateTeamPassword($teamNumber = null)
    {
        $currentYear = date('Y');
        $teamNumber = $teamNumber ?? 1;
        return "Inventory-Team{$teamNumber}@{$currentYear}";
    }

    /**
     * Create team member
     */
    public static function createTeamMember(array $data, Team $team)
    {
        $username = explode('@', $data['email'])[0];
        $teamPassword = self::generateTeamPassword($team->id);
        
        $user = self::create([
            'name' => $team->name . ' Member - ' . $username,
            'email' => $data['email'],
            'username' => $username,
            'password' => bcrypt($teamPassword),
            'team_id' => $team->id,
            'role' => 'team_member',
            'verification_token' => hash('sha256', Str::random(60)),
            'is_active' => false, // NOT active until verified
            'email_verified_at' => null, // NOT verified
            'verification_required' => true,
            'verification_sent_at' => now(), // Set when invitation is sent
            'password_change_required' => true, 
            'login_count' => 0,
            'profile_photo_path' => null,
        ]);
        
        \Log::info('Team member created', [
            'user_id' => $user->id,
            'email' => $user->email,
            'is_active' => $user->is_active,
            'email_verified_at' => $user->email_verified_at,
            'verification_required' => $user->verification_required,
        ]);
        
        return $user;
    }
    
    /**
     * Create admin user
     */
    public static function createAdmin(array $data)
    {
        $user = self::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => isset($data['username']) ? $data['username'] : explode('@', $data['email'])[0],
            'password' => bcrypt($data['password']),
            'role' => 'admin',
            'is_active' => true, // Admin is active immediately
            'email_verified_at' => now(), // Admin is verified immediately
            'password_change_required' => false, 
            'login_count' => 0,
            'profile_photo_path' => null,
        ]);
        
        \Log::info('Admin user created', [
            'user_id' => $user->id,
            'email' => $user->email,
            'is_active' => $user->is_active,
            'email_verified_at' => $user->email_verified_at,
        ]);
        
        return $user;
    }

    // =========================================================================
    // ===== BOOT METHOD (Event Listeners) =====
    // =========================================================================
    
    protected static function booted()
    {
        // When a user is created, set default values if not provided
        static::creating(function ($user) {
            if (!isset($user->username) && $user->email) {
                $user->username = explode('@', $user->email)[0];
            }
            
            if (!isset($user->login_count)) {
                $user->login_count = 0;
            }
            
            if (!isset($user->is_active)) {
                $user->is_active = $user->role === 'admin';
            }
            
            if (!isset($user->email_verified_at) && $user->role === 'admin') {
                $user->email_verified_at = now();
            }
        });
    }
}