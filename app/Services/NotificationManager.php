<?php

namespace App\Services;

use App\Models\User;
use App\Models\TeamRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class NotificationManager
{
    /**
     * Mark "New Inventory Request" notifications as read when request is processed
     */
    public static function markNewRequestAsRead(TeamRequest $teamRequest)
    {
        try {
            Log::info("Marking new request notifications as read for request #{$teamRequest->id}");
            
            // Get all admin users
            $adminUsers = User::where('role', 'admin')->get();
            $markedCount = 0;
            
            foreach ($adminUsers as $admin) {
                // Find notifications for this specific request
                $notifications = $admin->notifications()
                    ->where(function($query) {
                        $query->where('type', 'new_request')
                              ->orWhere('type', 'App\Notifications\NewRequestNotification');
                    })
                    ->where(function($query) use ($teamRequest) {
                        $query->where('data', 'like', '%"request_id":' . $teamRequest->id . '%')
                              ->orWhere('data', 'like', '%"request_id":"' . $teamRequest->id . '"%')
                              ->orWhere('message', 'like', '%"request_id":' . $teamRequest->id . '%')
                              ->orWhere('message', 'like', '%request_id":"' . $teamRequest->id . '"%');
                    })
                    ->whereNull('read_at')
                    ->get();
                
                foreach ($notifications as $notification) {
                    $notification->update([
                        'read_at' => now(),
                        'is_read' => 1
                    ]);
                    $markedCount++;
                    
                    Log::debug('Marked new request notification as read', [
                        'notification_id' => $notification->id,
                        'request_id' => $teamRequest->id,
                        'admin' => $admin->email
                    ]);
                }
            }
            
            Log::info('Marked new request notifications as read', [
                'request_id' => $teamRequest->id,
                'marked_count' => $markedCount
            ]);
            
            return $markedCount;
            
        } catch (\Exception $e) {
            Log::error('Failed to mark new request notifications as read', [
                'error' => $e->getMessage(),
                'request_id' => $teamRequest->id
            ]);
            return 0;
        }
    }
    
    /**
     * Mark "Request Approved" notifications as read when items are claimed
     */
    public static function markApprovedRequestAsRead(TeamRequest $teamRequest)
    {
        try {
            Log::info("Marking approved notifications as read for request #{$teamRequest->id}");
            
            // Get all admin users
            $adminUsers = User::where('role', 'admin')->get();
            $markedCount = 0;
            
            foreach ($adminUsers as $admin) {
                // Find "request_status" notifications for this specific request with approved status
                $notifications = $admin->notifications()
                    ->where(function($query) {
                        $query->where('type', 'request_status')
                              ->orWhere('type', 'App\Notifications\RequestStatusNotification');
                    })
                    ->where(function($query) use ($teamRequest) {
                        $query->where('data', 'like', '%"request_id":' . $teamRequest->id . '%')
                              ->orWhere('data', 'like', '%"request_id":"' . $teamRequest->id . '"%')
                              ->orWhere('message', 'like', '%"request_id":' . $teamRequest->id . '%')
                              ->orWhere('message', 'like', '%request_id":"' . $teamRequest->id . '"%');
                    })
                    ->where(function($query) {
                        $query->where('data', 'like', '%"status":"approved"%')
                              ->orWhere('message', 'like', '%"status":"approved"%');
                    })
                    ->whereNull('read_at')
                    ->get();
                
                foreach ($notifications as $notification) {
                    $notification->update([
                        'read_at' => now(),
                        'is_read' => 1
                    ]);
                    $markedCount++;
                    
                    Log::debug('Marked approved notification as read', [
                        'notification_id' => $notification->id,
                        'request_id' => $teamRequest->id,
                        'admin' => $admin->email
                    ]);
                }
            }
            
            Log::info('Marked approved notifications as read', [
                'request_id' => $teamRequest->id,
                'marked_count' => $markedCount
            ]);
            
            return $markedCount;
            
        } catch (\Exception $e) {
            Log::error('Failed to mark approved notifications as read', [
                'error' => $e->getMessage(),
                'request_id' => $teamRequest->id
            ]);
            return 0;
        }
    }
    
    /**
     * Simple direct database update method (more efficient)
     */
    public static function markNewRequestNotificationsDirect(TeamRequest $teamRequest)
    {
        try {
            $updated = DB::table('notifications')
                ->where(function($query) {
                    $query->where('type', 'new_request')
                          ->orWhere('type', 'App\Notifications\NewRequestNotification');
                })
                ->where('notifiable_type', 'App\Models\User')
                ->whereIn('notifiable_id', function($query) {
                    $query->select('id')
                          ->from('users')
                          ->where('role', 'admin');
                })
                ->where(function($query) use ($teamRequest) {
                    $query->where('data', 'like', '%"request_id":' . $teamRequest->id . '%')
                          ->orWhere('data', 'like', '%"request_id":"' . $teamRequest->id . '"%')
                          ->orWhere('message', 'like', '%"request_id":' . $teamRequest->id . '%')
                          ->orWhere('message', 'like', '%request_id":"' . $teamRequest->id . '"%');
                })
                ->whereNull('read_at')
                ->update([
                    'read_at' => now(),
                    'is_read' => 1
                ]);
            
            Log::info('Direct update marked new request notifications as read', [
                'request_id' => $teamRequest->id,
                'updated_count' => $updated
            ]);
            
            return $updated;
            
        } catch (\Exception $e) {
            Log::error('Failed direct update of new request notifications', [
                'error' => $e->getMessage(),
                'request_id' => $teamRequest->id
            ]);
            return 0;
        }
    }
    
    /**
     * Direct database update for approved notifications
     */
    public static function markApprovedNotificationsDirect(TeamRequest $teamRequest)
    {
        try {
            $updated = DB::table('notifications')
                ->where(function($query) {
                    $query->where('type', 'request_status')
                          ->orWhere('type', 'App\Notifications\RequestStatusNotification');
                })
                ->where('notifiable_type', 'App\Models\User')
                ->whereIn('notifiable_id', function($query) {
                    $query->select('id')
                          ->from('users')
                          ->where('role', 'admin');
                })
                ->where(function($query) use ($teamRequest) {
                    $query->where('data', 'like', '%"request_id":' . $teamRequest->id . '%')
                          ->orWhere('data', 'like', '%"request_id":"' . $teamRequest->id . '"%')
                          ->orWhere('message', 'like', '%"request_id":' . $teamRequest->id . '%')
                          ->orWhere('message', 'like', '%request_id":"' . $teamRequest->id . '"%');
                })
                ->where(function($query) {
                    $query->where('data', 'like', '%"status":"approved"%')
                          ->orWhere('message', 'like', '%"status":"approved"%');
                })
                ->whereNull('read_at')
                ->update([
                    'read_at' => now(),
                    'is_read' => 1
                ]);
            
            Log::info('Direct update marked approved notifications as read', [
                'request_id' => $teamRequest->id,
                'updated_count' => $updated
            ]);
            
            return $updated;
            
        } catch (\Exception $e) {
            Log::error('Failed direct update of approved notifications', [
                'error' => $e->getMessage(),
                'request_id' => $teamRequest->id
            ]);
            return 0;
        }
    }
}