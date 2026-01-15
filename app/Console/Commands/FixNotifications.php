<?php

namespace App\Console\Commands;

use App\Models\TeamRequest;
use App\Models\User;
use App\Notifications\NewRequestNotification;
use App\Notifications\RequestStatusNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixNotifications extends Command
{
    protected $signature = 'notifications:fix 
                            {--all : Fix all processed requests} 
                            {--request= : Specific request ID}
                            {--create-missing : Create missing notifications for existing requests}
                            {--list : List all requests with notification status}';
    
    protected $description = 'Fix notification issues - mark processed notifications as read and create missing ones';

    public function handle()
    {
        $this->info('Fixing notification issues...');
        
        if ($this->option('list')) {
            $this->listAllRequests();
            return 0;
        }
        
        if ($this->option('create-missing')) {
            $this->createMissingNotifications();
            return 0;
        }
        
        if ($this->option('request')) {
            $requestId = $this->option('request');
            $teamRequest = TeamRequest::find($requestId);
            
            if (!$teamRequest) {
                $this->error("Request #{$requestId} not found!");
                return 1;
            }
            
            $this->fixSingleRequest($teamRequest);
        } elseif ($this->option('all')) {
            $this->fixAllProcessedRequests();
        } else {
            $this->showHelp();
        }
        
        return 0;
    }
    
    private function showHelp()
    {
        $this->info('Available options:');
        $this->info('  --all                Fix all processed requests (mark as read)');
        $this->info('  --request=<id>       Fix specific request (mark as read)');
        $this->info('  --create-missing     Create missing notifications for existing requests');
        $this->info('  --list               List all requests with notification status');
        $this->info('');
        $this->info('Examples:');
        $this->info('  php artisan notifications:fix --all');
        $this->info('  php artisan notifications:fix --request=6');
        $this->info('  php artisan notifications:fix --create-missing');
        $this->info('  php artisan notifications:fix --list');
    }
    
    private function fixSingleRequest(TeamRequest $teamRequest)
    {
        $this->info("Fixing notifications for request #{$teamRequest->id}");
        $this->line("Item: " . ($teamRequest->item->name ?? 'N/A'));
        $this->line("Quantity: {$teamRequest->quantity_requested}");
        $this->line("Status: {$teamRequest->status}");
        $this->line("Team: " . ($teamRequest->team->name ?? 'N/A'));
        $this->line("");
        
        $totalFixed = 0;
        
        // 1. First check if notifications exist for this request
        $adminUsers = User::where('role', 'admin')->get();
        $notificationsExist = false;
        
        foreach ($adminUsers as $admin) {
            $hasNotification = $admin->notifications()
                ->where(function($query) use ($teamRequest) {
                    $query->where('data', 'like', '%"request_id":' . $teamRequest->id . '%')
                          ->orWhere('data', 'like', '%"request_id":"' . $teamRequest->id . '"%');
                })
                ->exists();
            
            if ($hasNotification) {
                $notificationsExist = true;
                break;
            }
        }
        
        if (!$notificationsExist && $teamRequest->status === 'pending') {
            $this->warn("⚠️ No notifications found for this pending request!");
            $create = $this->confirm('Create missing notifications for admins?', true);
            
            if ($create) {
                $created = $this->createNotificationForRequest($teamRequest);
                $this->info("✓ Created {$created} new notifications");
            }
        }
        
        // 2. Mark existing notifications as read if request is processed
        if (in_array($teamRequest->status, ['approved', 'rejected', 'claimed'])) {
            $markedRead = $this->markNotificationsAsRead($teamRequest);
            $totalFixed += $markedRead;
            $this->info("✓ Marked {$markedRead} notifications as read");
        }
        
        $this->info("✓ Done! Fixed {$totalFixed} notifications for request #{$teamRequest->id}");
        
        Log::info('Fixed notifications for request', [
            'request_id' => $teamRequest->id,
            'total_fixed' => $totalFixed
        ]);
    }
    
    private function markNotificationsAsRead(TeamRequest $teamRequest)
    {
        $fixedCount = 0;
        
        // Mark all notification types as read for this request
        $fixedCount += DB::table('notifications')
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
        
        return $fixedCount;
    }
    
    private function createNotificationForRequest(TeamRequest $teamRequest)
    {
        $createdCount = 0;
        $adminUsers = User::where('role', 'admin')->get();
        
        // Get the user who made the request
        $requestedBy = $teamRequest->teamMember ? $teamRequest->teamMember->name : 'Team Member';
        
        foreach ($adminUsers as $admin) {
            // Check if admin already has notification for this request
            $exists = $admin->notifications()
                ->where('type', 'App\Notifications\NewRequestNotification')
                ->where('data', 'like', '%"request_id":' . $teamRequest->id . '%')
                ->exists();
            
            if (!$exists) {
                // Create new notification
                $admin->notify(new NewRequestNotification(
                    $teamRequest,
                    $requestedBy
                ));
                
                $createdCount++;
                $this->line("  Created notification for admin: {$admin->email}");
            }
        }
        
        return $createdCount;
    }
    
    private function fixAllProcessedRequests()
    {
        $this->info('Finding all processed requests...');
        
        $processedRequests = TeamRequest::whereIn('status', ['approved', 'rejected', 'claimed'])
            ->with(['item', 'team'])
            ->get();
        
        $this->info("Found {$processedRequests->count()} processed requests");
        
        $totalFixed = 0;
        $processedCount = 0;
        
        foreach ($processedRequests as $request) {
            $processedCount++;
            $this->line("Processing {$processedCount}/{$processedRequests->count()}: Request #{$request->id}");
            
            $fixed = $this->markNotificationsAsRead($request);
            $totalFixed += $fixed;
            
            if ($fixed > 0) {
                $this->line("  ✓ Marked {$fixed} notifications as read");
            } else {
                $this->line("  ✓ No notifications to mark as read");
            }
        }
        
        $this->info("✓ Total: Fixed {$totalFixed} notifications across all requests");
        
        Log::info('Fixed all processed request notifications', [
            'total_requests' => $processedRequests->count(),
            'total_fixed' => $totalFixed
        ]);
    }
    
    private function createMissingNotifications()
    {
        $this->info('Creating missing notifications for all pending requests...');
        
        // Get all pending requests
        $pendingRequests = TeamRequest::where('status', 'pending')
            ->with(['item', 'team', 'teamMember'])
            ->get();
        
        $this->info("Found {$pendingRequests->count()} pending requests");
        
        $totalCreated = 0;
        $processedCount = 0;
        
        foreach ($pendingRequests as $request) {
            $processedCount++;
            $this->line("Processing {$processedCount}/{$pendingRequests->count()}: Request #{$request->id}");
            
            $created = $this->createNotificationForRequest($request);
            $totalCreated += $created;
            
            if ($created > 0) {
                $this->line("  ✓ Created {$created} new notifications");
            } else {
                $this->line("  ✓ Notifications already exist");
            }
        }
        
        $this->info("✓ Total: Created {$totalCreated} new notifications");
        
        // Show summary
        $this->showNotificationSummary();
        
        Log::info('Created missing notifications', [
            'total_requests' => $pendingRequests->count(),
            'total_created' => $totalCreated
        ]);
    }
    
    private function listAllRequests()
    {
        $this->info('Listing all requests with notification status...');
        
        $requests = TeamRequest::with(['item', 'team', 'teamMember'])
            ->orderBy('id')
            ->get();
        
        $headers = ['ID', 'Item', 'Quantity', 'Status', 'Team', 'Requested By', 'Created At', 'Admin Notifications'];
        
        $rows = [];
        
        foreach ($requests as $request) {
            // Count admin users who have notifications for this request
            $adminUsers = User::where('role', 'admin')->get();
            $notificationCount = 0;
            
            foreach ($adminUsers as $admin) {
                $hasNotification = $admin->notifications()
                    ->where(function($query) use ($request) {
                        $query->where('data', 'like', '%"request_id":' . $request->id . '%')
                              ->orWhere('data', 'like', '%"request_id":"' . $request->id . '"%');
                    })
                    ->exists();
                
                if ($hasNotification) {
                    $notificationCount++;
                }
            }
            
            $notificationStatus = "{$notificationCount}/{$adminUsers->count()} admins";
            
            if ($notificationCount === 0 && $request->status === 'pending') {
                $notificationStatus = "⚠️ MISSING";
            } elseif ($notificationCount > 0 && in_array($request->status, ['approved', 'rejected', 'claimed'])) {
                $notificationStatus .= " (should be read)";
            }
            
            $rows[] = [
                $request->id,
                $request->item->name ?? 'N/A',
                $request->quantity_requested,
                $request->status,
                $request->team->name ?? 'N/A',
                $request->teamMember->email ?? 'N/A',
                $request->created_at->format('Y-m-d H:i'),
                $notificationStatus
            ];
        }
        
        $this->table($headers, $rows);
        
        // Show summary
        $this->showNotificationSummary();
    }
    
    private function showNotificationSummary()
    {
        $this->info("\nNotification Summary:");
        
        $adminCount = User::where('role', 'admin')->count();
        $this->line("Total Admin Users: {$adminCount}");
        
        if ($adminCount > 0) {
            $admins = User::where('role', 'admin')->get();
            
            foreach ($admins as $admin) {
                $total = $admin->notifications()->count();
                $unread = $admin->unreadNotifications()->count();
                $this->line("  {$admin->email}: {$total} total, {$unread} unread");
            }
        }
        
        // Check for requests without notifications
        $pendingRequests = TeamRequest::where('status', 'pending')->count();
        $pendingWithoutNotifications = 0;
        
        $allPending = TeamRequest::where('status', 'pending')->get();
        foreach ($allPending as $request) {
            $hasAnyNotification = DB::table('notifications')
                ->where('notifiable_type', 'App\Models\User')
                ->whereIn('notifiable_id', function($query) {
                    $query->select('id')
                          ->from('users')
                          ->where('role', 'admin');
                })
                ->where(function($query) use ($request) {
                    $query->where('data', 'like', '%"request_id":' . $request->id . '%')
                          ->orWhere('data', 'like', '%"request_id":"' . $request->id . '"%');
                })
                ->exists();
            
            if (!$hasAnyNotification) {
                $pendingWithoutNotifications++;
            }
        }
        
        $this->line("\nRequests needing attention:");
        $this->line("  Pending requests: {$pendingRequests}");
        $this->line("  Pending without notifications: {$pendingWithoutNotifications}");
        
        if ($pendingWithoutNotifications > 0) {
            $this->warn("⚠️  Run: php artisan notifications:fix --create-missing");
        }
    }
}