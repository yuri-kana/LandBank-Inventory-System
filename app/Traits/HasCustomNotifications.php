<?php

namespace App\Traits;

use App\Notifications\NewRequestNotification;
use App\Notifications\RequestStatusNotification;

trait HasCustomNotifications
{
    public function getFormattedNotifications()
    {
        return $this->notifications->map(function ($notification) {
            $data = json_decode($notification->message, true);
            
            return [
                'id' => $notification->id,
                'title' => $data['title'] ?? 'Notification',
                'message' => $data['body'] ?? $notification->message,
                'url' => $data['url'] ?? '#',
                'type' => $notification->type,
                'read_at' => $notification->read_at,
                'is_read' => $notification->is_read,
                'created_at' => $notification->created_at,
                'data' => $data,
            ];
        });
    }
    
    public function getUnreadNotifications()
    {
        return $this->notifications()
            ->where('is_read', 0)
            ->orWhereNull('read_at')
            ->get()
            ->map(function ($notification) {
                $data = json_decode($notification->message, true);
                
                return [
                    'id' => $notification->id,
                    'title' => $data['title'] ?? 'Notification',
                    'message' => $data['body'] ?? $notification->message,
                    'url' => $data['url'] ?? '#',
                    'type' => $notification->type,
                    'read_at' => $notification->read_at,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at,
                    'data' => $data,
                ];
            });
    }
    
    public function getUnreadNotificationsCount()
    {
        return $this->notifications()
            ->where('is_read', 0)
            ->orWhereNull('read_at')
            ->count();
    }
    
    public function markNotificationAsRead($id)
    {
        $notification = $this->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->update([
                'is_read' => 1,
                'read_at' => now(),
            ]);
            return true;
        }
        
        return false;
    }
    
    public function markAllNotificationsAsRead()
    {
        $this->notifications()
            ->where('is_read', 0)
            ->orWhereNull('read_at')
            ->update([
                'is_read' => 1,
                'read_at' => now(),
            ]);
            
        return true;
    }
}