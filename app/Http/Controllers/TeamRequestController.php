<?php

namespace App\Http\Controllers;

use App\Models\TeamRequest;
use App\Models\Item;
use App\Models\User;
use App\Models\Team; // Added this import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Services\NotificationManager;
use App\Notifications\TeamNotification;
use App\Notifications\AdminNotification;

class TeamRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Build query with eager loading
        $query = TeamRequest::with(['team', 'item', 'approver', 'claimer'])
            ->latest();
        
        // Apply status filter if provided
        if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'rejected', 'claimed'])) {
            $query->where('status', $request->status);
        }
        
        // Apply team filter if provided and user is admin
        if ($user->isAdmin() && $request->has('team')) {
            $query->where('team_id', $request->team);
        }
        
        // Admin sees all requests, team members see only their team's requests
        if ($user->isAdmin()) {
            $requests = $query->paginate(15);
        } else {
            if (!$user->team_id) {
                return redirect()->route('dashboard')
                    ->with('warning', 'You are not assigned to any team. Please contact Inventory Head.');
            }
            
            $requests = $query->where('team_id', $user->team_id)->paginate(15);
        }
        
        // Get counts for filter tabs
        if ($user->isAdmin()) {
            $counts = [
                'all' => TeamRequest::count(),
                'pending' => TeamRequest::pending()->count(),
                'approved' => TeamRequest::approved()->count(),
                'rejected' => TeamRequest::rejected()->count(),
                'claimed' => TeamRequest::claimed()->count(),
            ];
        } else {
            $counts = [
                'all' => $user->team ? $user->team->requests()->count() : 0,
                'pending' => $user->team ? $user->team->requests()->pending()->count() : 0,
                'approved' => $user->team ? $user->team->requests()->approved()->count() : 0,
                'rejected' => $user->team ? $user->team->requests()->rejected()->count() : 0,
                'claimed' => $user->team ? $user->team->requests()->claimed()->count() : 0,
            ];
        }
        
        // Get teams for admin filter dropdown
        $teams = $user->isAdmin() ? Team::orderBy('name')->get() : collect();
        
        return view('requests.index', compact('requests', 'counts', 'teams'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        
        // Check if user has a team and is verified
        if (!$user->team_id) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not assigned to any team. Please contact Inventory Head.');
        }

        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('warning', 'Please verify your email address before making requests.');
        }

        // Get items with available stock for new requests
        $items = Item::where('is_available', true)
            ->where('quantity', '>', 0)
            ->withCount(['pendingRequests as pending_quantity_sum' => function($query) {
                $query->select(DB::raw('COALESCE(SUM(quantity_requested), 0)'));
            }])
            ->orderBy('name')
            ->get()
            ->map(function($item) {
                $item->available_for_request = $item->quantity - $item->pending_quantity_sum;
                return $item;
            })
            ->filter(function($item) {
                return $item->available_for_request > 0;
            });

        if ($items->isEmpty()) {
            return redirect()->route('requests.index')
                ->with('info', 'No items are currently available for request.');
        }

        // Get the selected item ID from the query string (if provided)
        $selectedItemId = $request->get('item_id');
        $selectedItem = null;
        
        // Validate and get the selected item
        if ($selectedItemId) {
            $selectedItem = Item::find($selectedItemId);
            
            // Check if the selected item is in the available items list
            if (!$selectedItem || !$items->contains('id', $selectedItemId)) {
                $selectedItem = null;
            }
        }

        return view('requests.create', compact('items', 'selectedItem'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Check if user has a team
        if (!$user->team_id) {
            return back()->with('error', 'You are not assigned to any team. Please contact Inventory Head.')
                        ->withInput();
        }

        $item = Item::findOrFail($request->item_id);
        
        // Check if item is available
        if (!$item->is_available) {
            return back()->with('error', 'This item is currently unavailable.')
                        ->withInput();
        }

        // Calculate available stock considering pending requests
        $pendingQuantity = $item->pendingRequests()->sum('quantity_requested');
        $availableForRequest = $item->quantity - $pendingQuantity;

        // Check if enough AVAILABLE stock
        if ($request->quantity_requested > $availableForRequest) {
            return back()->with('error', "Only {$availableForRequest} units available for request. " . 
                        ($pendingQuantity > 0 ? "There are {$pendingQuantity} units pending in other requests." : ""))
                        ->withInput();
        }

        try {
            DB::beginTransaction();

            // Create the request
            $teamRequest = TeamRequest::create([
                'team_id' => $user->team_id,
                'item_id' => $request->item_id,
                'quantity_requested' => $request->quantity_requested,
                'status' => 'pending',
            ]);

            // Update available stock
            $item->calculateAvailableStock();

            // Send notifications to all admins using Notification class
            $adminUsers = User::where('role', 'admin')->get();
            $teamRequest->load(['item', 'team']);

            foreach ($adminUsers as $admin) {
                $admin->notify(new \App\Notifications\NewRequestNotification(
                    $teamRequest,
                    $user->name
                ));
            }

            // ALSO send notification to all team members using TeamNotification
            $team = $teamRequest->team;
            if ($team) {
                // Get team display name
                $teamDisplayName = $this->getTeamDisplayName($team);
                
                // Create notification data
                $notificationData = [
                    'title' => 'Request Submitted',
                    'message' => $user->name . ' from ' . $teamDisplayName . ' submitted a request for ' . 
                                $teamRequest->quantity_requested . ' ' . $teamRequest->item->name,
                    'body' => 'A team member has submitted a new request',
                    'items' => [$teamRequest->item->name . ' (Quantity: ' . $teamRequest->quantity_requested . ')'],
                    'team_id' => $team->id,
                    'team_name' => $teamDisplayName,
                    'team_number' => $team->team_number ?? $this->extractTeamNumber($team->name),
                    'requested_by' => $user->name,
                    'user_name' => $user->name,
                    'user_id' => $user->id,
                    'request_id' => $teamRequest->id,
                    'item_name' => $teamRequest->item->name,
                    'quantity' => $teamRequest->quantity_requested,
                    'url' => route('requests.index'),
                    'type' => 'team_request_submitted',
                ];

                // Create TeamNotification instance
                $teamNotification = new TeamNotification($notificationData);
                
                // Send to all team members (including the requester)
                foreach ($team->activeUsers()->get() as $member) {
                    $member->notify($teamNotification);
                }
            }

            DB::commit();

            Log::info('Request created successfully', [
                'request_id' => $teamRequest->id,
                'team' => $teamRequest->team->name,
                'team_number' => $teamRequest->team->team_number,
                'item' => $teamRequest->item->name,
                'quantity' => $teamRequest->quantity_requested,
                'user' => $user->email
            ]);

            return redirect()->route('requests.index')
                ->with('success', 'Request submitted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Request creation failed', [
                'error' => $e->getMessage(),
                'user' => $user->email,
                'item_id' => $request->item_id,
                'quantity' => $request->quantity_requested
            ]);

            return back()->with('error', 'Failed to submit request. Please try again.')
                        ->withInput();
        }
    }

    public function updateStatus(Request $request, TeamRequest $teamRequest)
    {
        // Ensure only admins can update status
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow status update for pending requests
        if (!$teamRequest->isPending()) {
            return redirect()->back()->with('error', 'Cannot update status of a processed request.');
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            if ($request->status === 'approved') {
                $teamRequest->approve(auth()->id(), $request->admin_notes);
            } else {
                $teamRequest->reject(auth()->id(), $request->admin_notes);
            }

            // Send notification to ALL team members using the Notification class
            $team = $teamRequest->team;
            if ($team) {
                $notification = new \App\Notifications\RequestStatusNotification(
                    $teamRequest,
                    $request->status,
                    auth()->user()->name
                );
                
                // Send to all active team members
                $team->notifyAllMembers($notification);
            }

            // AUTO MARK AS READ: Mark the "New Inventory Request" notification as read
            $markedCount = NotificationManager::markNewRequestNotificationsDirect($teamRequest);
            
            Log::info('Auto-marked notifications as read', [
                'request_id' => $teamRequest->id,
                'marked_count' => $markedCount,
                'action' => 'update_status'
            ]);

            DB::commit();

            Log::info('Request status updated', [
                'request_id' => $teamRequest->id,
                'status' => $request->status,
                'admin' => auth()->user()->email,
                'notifications_marked_read' => $markedCount
            ]);

            return redirect()->route('requests.index')
                ->with('success', 'Request ' . $request->status . ' successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to update request status', [
                'error' => $e->getMessage(),
                'request_id' => $teamRequest->id,
                'status' => $request->status
            ]);

            return redirect()->back()->with('error', 'Failed to update request status. Please try again.');
        }
    }

    public function claim(Request $request, TeamRequest $teamRequest)
    {
        // Ensure only admins can claim items
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action. Only administrators can claim items.');
        }

        // Check if request can be claimed
        if (!$teamRequest->canBeClaimed()) {
            $reason = 'Cannot claim this request. ';
            
            if (!$teamRequest->isApproved()) {
                $reason .= 'Request is not approved.';
            } elseif (!$teamRequest->item) {
                $reason .= 'Item not found.';
            } else {
                $reason .= 'Insufficient stock. Available: ' . $teamRequest->item->quantity . 
                        ', Requested: ' . $teamRequest->quantity_requested;
            }
            
            return back()->with('error', $reason);
        }

        try {
            DB::beginTransaction();

            // Mark as claimed (this deducts physical stock)
            $teamRequest->markAsClaimed(auth()->id());

            // Send notification about claim
            $teamRequest->load(['item', 'team.users']);
            
            // Get team display name
            $team = $teamRequest->team;
            $teamDisplayName = $this->getTeamDisplayName($team);
            
            // Create admin notification data
            $adminNotificationData = [
                'title' => 'Items has been claimed',
                'message' => $teamRequest->quantity_requested . ' ' . $teamRequest->item->name . 
                            ' has been claimed ',
                'body' => 'Items have been claimed from inventory by administrator',
                'items' => [$teamRequest->item->name . ' (Quantity: ' . $teamRequest->quantity_requested . ')'],
                'team_id' => $team->id,
                'team_name' => $teamDisplayName,
                'team_number' => $team->team_number ?? $this->extractTeamNumber($team->name),
                'request_id' => $teamRequest->id,
                'item_name' => $teamRequest->item->name,
                'quantity' => $teamRequest->quantity_requested,
                'claimed_by' => auth()->user()->name,
                'user_name' => auth()->user()->name,
                'user_id' => auth()->id(),
                'url' => route('requests.index'),
                'type' => 'items_claimed',
            ];

            // Notify all admins using AdminNotification
            $adminUsers = User::where('role', 'admin')->get();
            $adminNotification = new AdminNotification($adminNotificationData);
            
            foreach ($adminUsers as $admin) {
                $admin->notify($adminNotification);
            }

            // Also notify the team that requested it using TeamNotification
            if ($team) {
                $teamNotificationData = [
                    'title' => 'Your Request Has Been Fulfilled',
                    'message' => $teamRequest->quantity_requested . ' ' . $teamRequest->item->name . 
                                ' has been claimed ',
                    'body' => 'Your requested items are now ready for pickup',
                    'items' => [$teamRequest->item->name . ' (Quantity: ' . $teamRequest->quantity_requested . ')'],
                    'team_id' => $team->id,
                    'team_name' => $teamDisplayName,
                    'team_number' => $team->team_number ?? $this->extractTeamNumber($team->name),
                    'claimed_by' => auth()->user()->name,
                    'user_name' => auth()->user()->name,
                    'user_id' => auth()->id(),
                    'request_id' => $teamRequest->id,
                    'item_name' => $teamRequest->item->name,
                    'quantity' => $teamRequest->quantity_requested,
                    'url' => route('requests.index'),
                    'type' => 'request_fulfilled',
                ];
                
                $teamNotification = new TeamNotification($teamNotificationData);
                
                foreach ($team->users as $member) {
                    $member->notify($teamNotification);
                }
            }

            // AUTO MARK AS READ: Mark the "Request Approved" notification as read
            $markedCount = NotificationManager::markApprovedNotificationsDirect($teamRequest);
            
            Log::info('Auto-marked approved notifications as read', [
                'request_id' => $teamRequest->id,
                'marked_count' => $markedCount,
                'action' => 'claim'
            ]);

            DB::commit();

            Log::info('Admin claimed items', [
                'request_id' => $teamRequest->id,
                'item' => $teamRequest->item->name,
                'quantity' => $teamRequest->quantity_requested,
                'claimed_by' => auth()->user()->email,
                'remaining_stock' => $teamRequest->item->quantity,
                'notifications_marked_read' => $markedCount
            ]);

            return redirect()->route('requests.index')
                ->with('success', 'Items claimed successfully! Stock has been deducted from inventory.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to claim request', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_id' => $teamRequest->id,
                'user' => auth()->user()->email,
                'item_id' => $teamRequest->item_id,
                'item_stock' => $teamRequest->item ? $teamRequest->item->quantity : 'N/A',
                'requested_quantity' => $teamRequest->quantity_requested
            ]);

            return back()->with('error', 'Failed to claim items: ' . $e->getMessage());
        }
    }

    public function destroy(TeamRequest $teamRequest)
    {
        $user = auth()->user();
        
        // Only allow deletion of pending requests by the same team or admin
        if ($teamRequest->isPending() && 
            ($user->isAdmin() || $teamRequest->team_id === $user->team_id)) {
            
            try {
                // If request was approved, release reserved stock
                if ($teamRequest->status === 'approved') {
                    $teamRequest->item->releaseReservedStock($teamRequest->quantity_requested);
                }
                
                $teamRequest->delete();
                
                Log::info('Request deleted', [
                    'request_id' => $teamRequest->id,
                    'user' => $user->email
                ]);
                
                return redirect()->route('requests.index')
                    ->with('success', 'Request deleted successfully.');
                    
            } catch (\Exception $e) {
                Log::error('Failed to delete request', [
                    'error' => $e->getMessage(),
                    'request_id' => $teamRequest->id
                ]);
                
                return back()->with('error', 'Failed to delete request. Please try again.');
            }
        }

        abort(403, 'Unauthorized action.');
    }

    /**
     * Get team display name with team number
     */
    private function getTeamDisplayName($team): string
    {
        if (!$team) {
            return 'Team';
        }
        
        // If team has team_number field
        if (isset($team->team_number) && !empty($team->team_number)) {
            return 'Team ' . $team->team_number;
        }
        
        // Try to extract team number from name
        $teamNumber = $this->extractTeamNumber($team->name);
        if ($teamNumber) {
            return 'Team ' . $teamNumber;
        }
        
        // If no number found, just return the name
        return $team->name;
    }

    /**
     * Extract team number from team name
     */
    private function extractTeamNumber($teamName): ?int
    {
        if (empty($teamName)) {
            return null;
        }
        
        // Check if it's already "Team X" format
        if (preg_match('/Team\s+(\d+)/i', $teamName, $matches)) {
            return (int) $matches[1];
        }
        
        // Check if it's just a number
        if (is_numeric($teamName)) {
            return (int) $teamName;
        }
        
        // Check if it contains a number
        if (preg_match('/(\d+)/', $teamName, $matches)) {
            return (int) $matches[1];
        }
        
        return null;
    }
}