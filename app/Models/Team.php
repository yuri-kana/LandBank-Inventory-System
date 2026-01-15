<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function requests()
    {
        return $this->hasMany(TeamRequest::class);
    }

    public function activeUsers()
    {
        return $this->users()->where('is_active', true)->whereNotNull('email_verified_at');
    }

    public function pendingUsers()
    {
        return $this->users()->where('is_active', false)->orWhereNull('email_verified_at');
    }

    public static function generateVerificationToken()
    {
        return Str::random(60);
    }

    /**
     * Send notification to ALL active team members
     */
    public function notifyAllMembers($notification, $exceptUserId = null)
    {
        $users = $this->activeUsers();
        
        if ($exceptUserId) {
            $users = $users->where('id', '!=', $exceptUserId);
        }
        
        foreach ($users->get() as $member) {
            try {
                $member->notify($notification);
                Log::debug('Notification sent to team member', [
                    'team_id' => $this->id,
                    'team_name' => $this->name,
                    'member_id' => $member->id,
                    'member_email' => $member->email,
                    'notification_type' => get_class($notification)
                ]);
            } catch (\Exception $e) {
                Log::error("Failed to notify user {$member->id}: " . $e->getMessage());
            }
        }
        
        return $this;
    }
    
    /**
     * Send notification to ALL users in team (including inactive)
     */
    public function notifyAllUsers($notification, $exceptUserId = null)
    {
        $users = $this->users;
        
        if ($exceptUserId) {
            $users = $users->reject(function($user) use ($exceptUserId) {
                return $user->id == $exceptUserId;
            });
        }
        
        foreach ($users as $member) {
            try {
                $member->notify($notification);
                Log::debug('Notification sent to all team users', [
                    'team_id' => $this->id,
                    'member_id' => $member->id,
                    'member_email' => $member->email
                ]);
            } catch (\Exception $e) {
                Log::error("Failed to notify user {$member->id}: " . $e->getMessage());
            }
        }
        
        return $this;
    }
    
    /**
     * Get all users in the team (not just active ones)
     */
    public function getAllUsers()
    {
        return $this->users()->get();
    }
    
    /**
     * Check if a user is an active member of this team
     */
    public function hasActiveMember($userId)
    {
        return $this->activeUsers()->where('id', $userId)->exists();
    }
}