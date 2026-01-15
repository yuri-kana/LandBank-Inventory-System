<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\TeamRequest;

class NewRequestNotification extends Notification
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
        
        return [
            'title' => 'New Inventory Request',
            'message' => $this->requestedBy . ' from ' . ($team->name ?? 'Team') . 
                        ' requested ' . $this->teamRequest->quantity_requested . ' unit(s) of ' . $item->name,
            'body' => $this->requestedBy . ' has submitted a new request for ' . $item->name,
            'items' => [
                $item->name . ' (Quantity: ' . $this->teamRequest->quantity_requested . ')',
            ],
            'team_id' => $team->id ?? null,
            'team_name' => $team->name ?? 'Team',
            'team_number' => $team->team_number ?? null,
            'department' => $team->name ?? 'Team',
            'requested_by' => $this->requestedBy,
            'user_name' => $this->requestedBy,
            'user_id' => auth()->id(),
            'request_id' => $this->teamRequest->id,
            'item_name' => $item->name,
            'quantity' => $this->teamRequest->quantity_requested,
            'url' => route('requests.index'),
            'type' => 'new_request',
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}