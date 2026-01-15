<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Team;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $formattedNotifications = $notifications->map(function ($notification) {
            // Get notification data
            $notificationData = $notification->data ?? [];
            
            // If data is empty, try to decode message
            if (empty($notificationData) && !empty($notification->message)) {
                if (is_string($notification->message)) {
                    $notificationData = json_decode($notification->message, true);
                } elseif (is_array($notification->message)) {
                    $notificationData = $notification->message;
                }
            }
            
            // If still empty, create fallback
            if (empty($notificationData)) {
                $notificationData = [
                    'title' => 'Notification',
                    'message' => 'You have a new notification',
                    'type' => 'general',
                ];
            }
            
            // Get team name with team number
            $teamName = $this->getTeamDisplayName($notificationData);
            
            // Get user name
            $userName = $this->getUserName($notificationData);
            
            // Extract request items
            $requestItems = $this->getRequestItems($notificationData);
            
            // Determine type
            $type = $this->getNotificationType($notification, $notificationData);
            
            // Determine title
            $title = $notificationData['title'] ?? 'Notification';
            
            // Determine message
            $message = $notificationData['message'] ?? 
                      $notificationData['body'] ?? 
                      'You have a new notification';
            
            // Determine URL
            $url = $this->getNotificationUrl($notificationData);
            
            return (object) [
                'id' => $notification->id,
                'title' => $title,
                'message' => $message,
                'url' => $url,
                'type' => $type,
                'read_at' => $notification->read_at,
                'is_read' => !is_null($notification->read_at),
                'created_at' => $notification->created_at,
                'data' => $notificationData,
                'request_items' => $requestItems,
                'team_name' => $teamName,
                'requested_by' => $userName,
                'user_name' => $userName,
                'request_id' => $notificationData['request_id'] ?? null,
                'status' => $notificationData['status'] ?? null,
                'item_name' => $notificationData['item_name'] ?? null,
                'quantity' => $notificationData['quantity'] ?? null,
                'user_id' => $notificationData['user_id'] ?? null,
            ];
        });
        
        // Group notifications by team
        $groupedNotifications = $formattedNotifications->groupBy('team_name');
        
        // Get unread count
        $unreadCount = Auth::user()->unreadNotifications()->count();
        
        return view('notifications.index', [
            'notifications' => $notifications,
            'formattedNotifications' => $formattedNotifications,
            'groupedNotifications' => $groupedNotifications,
            'unreadCount' => $unreadCount
        ]);
    }
    
    /**
     * Get team display name with team number
     */
    private function getTeamDisplayName(array $data): string
    {
        // First try to get team number directly
        if (isset($data['team_number'])) {
            return 'Team ' . $data['team_number'];
        }
        
        // If we have team_id, fetch team from database
        if (isset($data['team_id'])) {
            $team = Team::find($data['team_id']);
            if ($team) {
                return $team->team_number ? 'Team ' . $team->team_number : 'Team';
            }
        }
        
        // Try to extract team number from team_name
        if (isset($data['team_name'])) {
            $teamName = $data['team_name'];
            
            // Check if it's already "Team X" format
            if (preg_match('/Team\s+(\d+)/i', $teamName, $matches)) {
                return 'Team ' . $matches[1];
            }
            
            // Check if it's just a number
            if (is_numeric($teamName)) {
                return 'Team ' . $teamName;
            }
            
            // Check if it contains a number
            if (preg_match('/(\d+)/', $teamName, $matches)) {
                return 'Team ' . $matches[1];
            }
        }
        
        // Return default team name
        return 'Team';
    }
    
    /**
     * Get user name from notification data
     */
    private function getUserName(array $data): string
    {
        $userName = $data['user_name'] ?? 
                   $data['requested_by'] ?? 
                   $data['processed_by'] ?? 
                   $data['user'] ?? '';
        
        // If we have user_id but no name, fetch from database
        if (empty($userName) && isset($data['user_id'])) {
            $user = \App\Models\User::find($data['user_id']);
            if ($user) {
                return $user->name;
            }
        }
        
        return $userName ?: 'Team Member';
    }
    
    /**
     * Extract request items from notification data
     */
    private function getRequestItems(array $data): array
    {
        if (isset($data['items']) && is_array($data['items'])) {
            return $data['items'];
        }
        
        if (isset($data['item_name']) && isset($data['quantity'])) {
            $itemName = $data['item_name'];
            $quantity = $data['quantity'];
            return [$itemName . ' (Quantity: ' . $quantity . ')'];
        }
        
        return [];
    }
    
    /**
     * Determine notification type
     */
    private function getNotificationType($notification, array $data): string
    {
        if (isset($data['type'])) {
            return $data['type'];
        }
        
        if ($notification->type === 'App\Notifications\NewRequestNotification') {
            return 'new_request';
        }
        
        if ($notification->type === 'App\Notifications\RequestStatusNotification') {
            return 'request_status';
        }
        
        return 'general';
    }
    
    /**
     * Get notification URL
     */
    private function getNotificationUrl(array $data): string
    {
        $url = $data['url'] ?? '#';
        
        if ($url === '#' && isset($data['request_id'])) {
            return route('requests.show', ['teamRequest' => $data['request_id']]);
        }
        
        return $url;
    }
    
    // ... rest of your methods remain the same (markAsRead, markAllAsRead, etc.)
    
    public function markAsRead($id)
    {
        try {
            $notification = Auth::user()->notifications()->where('id', $id)->first();
            
            if ($notification && !$notification->read_at) {
                $notification->markAsRead();
                return response()->json(['success' => true]);
            }
            
            return response()->json(['success' => false, 'message' => 'Notification not found or already read']);
        } catch (\Exception $e) {
            \Log::error('Error marking notification as read: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
    
    public function markAllAsRead()
    {
        try {
            Auth::user()->unreadNotifications->markAsRead();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Error marking all notifications as read: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
    
    public function getCount()
    {
        try {
            $count = Auth::user()->unreadNotifications()->count();
            return response()->json(['count' => $count, 'success' => true]);
        } catch (\Exception $e) {
            \Log::error('Error getting notification count: ' . $e->getMessage());
            return response()->json(['count' => 0, 'success' => false], 500);
        }
    }
    
    public function debug()
    {
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        $debugData = [];
        
        foreach ($notifications as $notification) {
            $debugData[] = [
                'id' => $notification->id,
                'type' => $notification->type,
                'message' => $notification->message,
                'is_null' => is_null($notification->message),
                'is_string' => is_string($notification->message),
                'decoded' => is_string($notification->message) ? json_decode($notification->message, true) : $notification->message,
                'created_at' => $notification->created_at,
                'read_at' => $notification->read_at,
                'is_read' => !is_null($notification->read_at),
            ];
        }
        
        return response()->json(['debug_data' => $debugData]);
    }
}