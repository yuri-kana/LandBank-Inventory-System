<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\TeamRequest;

class RequestActionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $teamRequest;
    protected $action; // 'approved' or 'rejected'
    protected $actionBy;

    public function __construct(TeamRequest $teamRequest, $action, $actionBy)
    {
        $this->teamRequest = $teamRequest;
        $this->action = $action;
        $this->actionBy = $actionBy;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $item = $this->teamRequest->item;
        $team = $this->teamRequest->team;
        
        // Get team number
        $teamNumber = $this->extractTeamNumber($team);
        $teamDisplay = $teamNumber ? 'Team ' . $teamNumber : ($team->name ?? 'Team');
        
        $actionText = $this->action === 'approved' ? 'approved' : 'rejected';
        $actionIcon = $this->action === 'approved' ? '✅' : '❌';
        
        return [
            'title' => 'Request ' . ucfirst($actionText),
            'message' => $this->actionBy . ' has ' . $actionText . ' your request for ' . 
                        $this->teamRequest->quantity_requested . ' ' . $item->name,
            'body' => 'Your request has been ' . $actionText . ' by ' . $this->actionBy,
            'items' => [
                $item->name . ' (Quantity: ' . $this->teamRequest->quantity_requested . ')',
            ],
            'team_id' => $team->id ?? null,
            'team_name' => $team->name ?? 'Team',
            'team_number' => $teamNumber,
            'team_display' => $teamDisplay,
            'action_by' => $this->actionBy,
            'user_name' => $this->teamRequest->user->name ?? 'User',
            'user_id' => $this->teamRequest->user_id,
            'request_id' => $this->teamRequest->id,
            'item_name' => $item->name,
            'quantity' => $this->teamRequest->quantity_requested,
            'action' => $this->action,
            'action_icon' => $actionIcon,
            'action_text' => ucfirst($actionText),
            'notification_type' => 'request_action',
            'url' => route('requests.index'),
            'type' => 'request_action',
            'created_at' => now()->format('M d, Y h:i A'),
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
    
    private function extractTeamNumber($team)
    {
        if (!$team) {
            return null;
        }
        
        if (isset($team->team_number) && !empty($team->team_number)) {
            return $team->team_number;
        }
        
        if (isset($team->name)) {
            if (preg_match('/Team\s+(\d+)/i', $team->name, $matches)) {
                return (int) $matches[1];
            }
            
            if (is_numeric($team->name)) {
                return (int) $team->name;
            }
            
            if (preg_match('/(\d+)/', $team->name, $matches)) {
                return (int) $matches[1];
            }
        }
        
        return null;
    }
}