<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\TeamRequest;

class NewRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $teamRequest;
    protected $requestedBy;

    public function __construct(TeamRequest $teamRequest, $requestedBy)
    {
        $this->teamRequest = $teamRequest;
        $this->requestedBy = $requestedBy;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $item = $this->teamRequest->item;
        $team = $this->teamRequest->team;
        
        // Get team number consistently
        $teamNumber = $this->extractTeamNumber($team);
        $teamDisplay = $teamNumber ? 'Team ' . $teamNumber : ($team->name ?? 'Team');
        
        return [
            'title' => 'New Inventory Request',
            'message' => $this->requestedBy . ' from ' . $teamDisplay . 
                        ' requested ' . $this->teamRequest->quantity_requested . ' unit(s) of ' . $item->name,
            'body' => $this->requestedBy . ' has submitted a new request for ' . $item->name,
            'items' => [
                $item->name . ' (Quantity: ' . $this->teamRequest->quantity_requested . ')',
            ],
            'team_id' => $team->id ?? null,
            'team_name' => $team->name ?? 'Team',
            'team_number' => $teamNumber,
            'team_display' => $teamDisplay, // NEW: Consistent team display
            'department' => $team->name ?? 'Team',
            'requested_by' => $this->requestedBy,
            'user_name' => $this->requestedBy,
            'user_id' => $this->teamRequest->user_id ?? auth()->id(), // FIXED: Use request's user_id
            'request_id' => $this->teamRequest->id,
            'item_name' => $item->name,
            'quantity' => $this->teamRequest->quantity_requested,
            'status' => 'pending', // NEW: Add status field
            'status_icon' => '⏳', // NEW: Add icon
            'status_text' => 'Pending', // NEW: Add status text
            'is_resolved' => false, // NEW: Track if resolved
            'url' => route('requests.index'),
            'type' => 'new_request',
            'created_at' => now()->format('M d, Y h:i A'), // NEW: Add timestamp
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
    
    /**
     * Extract team number from team
     */
    private function extractTeamNumber($team)
    {
        if (!$team) {
            return null;
        }
        
        // If team has team_number field
        if (isset($team->team_number) && !empty($team->team_number)) {
            return $team->team_number;
        }
        
        // Try to extract from name
        if (isset($team->name)) {
            // Check if it's already "Team X" format
            if (preg_match('/Team\s+(\d+)/i', $team->name, $matches)) {
                return (int) $matches[1];
            }
            
            // Check if it's just a number
            if (is_numeric($team->name)) {
                return (int) $team->name;
            }
            
            // Check if it contains a number
            if (preg_match('/(\d+)/', $team->name, $matches)) {
                return (int) $matches[1];
            }
        }
        
        return null;
    }
}