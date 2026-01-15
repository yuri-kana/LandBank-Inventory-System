<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\TeamRequest;

class RequestStatusNotification extends Notification
{
    use Queueable;

    protected $teamRequest;
    protected $status;
    protected $processedBy;

    public function __construct(TeamRequest $teamRequest, $status, $processedBy)
    {
        $this->teamRequest = $teamRequest;
        $this->status = $status;
        $this->processedBy = $processedBy;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $item = $this->teamRequest->item;
        $team = $this->teamRequest->team;
        
        $statusText = $this->status === 'approved' ? 'approved' : 'rejected';
        
        return [
            'title' => 'Request ' . ucfirst($statusText),
            'message' => $this->processedBy . ' has ' . $statusText . ' your request for ' . 
                        $this->teamRequest->quantity_requested . ' ' . $item->name . ' from ' . ($team->name ?? 'Team'),
            'body' => 'Your request for ' . $item->name . ' has been ' . $statusText,
            'items' => [
                $item->name . ' (Quantity: ' . $this->teamRequest->quantity_requested . ')',
            ],
            'team_id' => $team->id ?? null,
            'team_name' => $team->name ?? 'Team',
            'team_number' => $team->team_number ?? null,
            'department' => $team->name ?? 'Team',
            'requested_by' => $team ? ($team->users->first()->name ?? 'Team Member') : 'Team Member',
            'processed_by' => $this->processedBy,
            'user_name' => $this->processedBy,
            'user_id' => auth()->id(),
            'request_id' => $this->teamRequest->id,
            'status' => $this->status,
            'item_name' => $item->name,
            'quantity' => $this->teamRequest->quantity_requested,
            'url' => route('requests.index'),
            'type' => 'request_status',
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}