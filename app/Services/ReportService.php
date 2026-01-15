<?php

namespace App\Services;

use App\Models\Item;
use App\Models\TeamRequest;
use App\Models\Team;
use App\Models\InventoryLog;
use App\Models\MonthlyReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Get comprehensive monthly report data
     */
    public function getMonthlyReportData($year, $month)
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        
        return [
            // Basic report info
            'period' => date('F Y', mktime(0, 0, 0, $month, 1, $year)),
            'year' => $year,
            'month' => $month,
            'monthName' => date('F', mktime(0, 0, 0, $month, 1)),
            
            // Inventory flow data
            'inventoryFlow' => $this->getInventoryFlowData($startDate, $endDate),
            
            // Usage patterns
            'usagePatterns' => $this->getUsagePatternsData($thirtyDaysAgo),
            
            // Team activity
            'teamActivity' => $this->getTeamActivityData($startDate, $endDate),
            
            // Depletion analysis
            'depletionAnalysis' => $this->getDepletionAnalysisData($thirtyDaysAgo),
            
            // Stock status
            'stockStatus' => $this->getStockStatusData(),
            
            // Summary statistics
            'summary' => $this->getSummaryStatistics($startDate, $endDate),
        ];
    }
    
    /**
     * Get inventory flow data with all columns
     */
    private function getInventoryFlowData($startDate, $endDate)
    {
        $items = Item::with(['requests' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }, 'inventoryLogs' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->get()
            ->map(function ($item) use ($startDate, $endDate) {
                // Calculate beginning quantity (previous month's ending)
                $beginning = $this->calculateBeginningQuantity($item, $startDate);
                
                // Get requested quantity (approved requests)
                $requested = $item->requests
                    ->where('status', 'approved')
                    ->sum('quantity_requested');
                
                // Get claimed quantity
                $claimed = $item->requests
                    ->where('status', 'claimed')
                    ->sum('quantity_requested');
                
                // Get restocked quantity
                $restocked = $item->inventoryLogs
                    ->where('action', 'restock')
                    ->sum('quantity_change');
                
                // Calculate ending quantity
                $ending = $beginning - $claimed + $restocked;
                
                // Determine status
                $status = $this->determineItemStatus($ending, $item->minimum_stock, $restocked);
                
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'category' => $item->category ?? 'General',
                    'beginning_quantity' => $beginning,
                    'requested_quantity' => $requested,
                    'claimed_quantity' => $claimed,
                    'restocked_quantity' => $restocked,
                    'ending_quantity' => $ending,
                    'current_stock' => $item->quantity,
                    'minimum_stock' => $item->minimum_stock,
                    'status' => $status,
                    'status_color' => $this->getStatusColor($status),
                ];
            });
        
        return [
            'items' => $items,
            'summary' => [
                'total_beginning' => $items->sum('beginning_quantity'),
                'total_requested' => $items->sum('requested_quantity'),
                'total_claimed' => $items->sum('claimed_quantity'),
                'total_restocked' => $items->sum('restocked_quantity'),
                'total_ending' => $items->sum('ending_quantity'),
                'net_change' => $items->sum('restocked_quantity') - $items->sum('claimed_quantity'),
            ]
        ];
    }
    
    /**
     * Get usage patterns data
     */
    private function getUsagePatternsData($thirtyDaysAgo)
    {
        $items = Item::select([
                'items.id',
                'items.name',
                'items.quantity',
                'items.minimum_stock',
                DB::raw('COALESCE(COUNT(team_requests.id), 0) as total_requests'),
                DB::raw('COALESCE(SUM(team_requests.quantity_requested), 0) as total_quantity'),
                DB::raw('COALESCE(COUNT(team_requests.id) / 30.0, 0) as avg_requests_per_day'),
                DB::raw('COALESCE(SUM(team_requests.quantity_requested) / 30.0, 0) as avg_quantity_per_day')
            ])
            ->leftJoin('team_requests', function($join) use ($thirtyDaysAgo) {
                $join->on('items.id', '=', 'team_requests.item_id')
                    ->where('team_requests.created_at', '>=', $thirtyDaysAgo)
                    ->where('team_requests.status', 'approved');
            })
            ->groupBy('items.id', 'items.name', 'items.quantity', 'items.minimum_stock')
            ->orderByDesc('total_requests')
            ->get()
            ->map(function ($item) {
                $demandLevel = $this->determineDemandLevel($item->total_requests);
                
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'current_stock' => $item->quantity,
                    'minimum_stock' => $item->minimum_stock,
                    'total_requests' => $item->total_requests,
                    'total_quantity' => $item->total_quantity,
                    'avg_requests_per_day' => $item->avg_requests_per_day,
                    'avg_quantity_per_day' => $item->avg_quantity_per_day,
                    'demand_level' => $demandLevel,
                    'demand_color' => $this->getDemandColor($demandLevel),
                    'utilization_percentage' => $item->quantity > 0 ? 
                        min(($item->total_quantity / $item->quantity) * 100, 100) : 100,
                ];
            });
        
        $maxRequests = $items->max('total_requests') ?: 1;
        
        return [
            'items' => $items,
            'max_requests' => $maxRequests,
            'summary' => [
                'total_items' => $items->count(),
                'total_requests' => $items->sum('total_requests'),
                'total_quantity' => $items->sum('total_quantity'),
                'high_demand_items' => $items->where('demand_level', 'high')->count(),
                'medium_demand_items' => $items->where('demand_level', 'medium')->count(),
                'low_demand_items' => $items->where('demand_level', 'low')->count(),
                'no_demand_items' => $items->where('demand_level', 'none')->count(),
            ]
        ];
    }
    
    /**
     * Get team activity data
     */
    private function getTeamActivityData($startDate, $endDate)
    {
        $teams = Team::select([
                'teams.id',
                'teams.name',
                DB::raw('COUNT(DISTINCT team_requests.id) as request_count'),
                DB::raw('COALESCE(SUM(team_requests.quantity_requested), 0) as total_quantity'),
                DB::raw('(SELECT COUNT(*) FROM users WHERE users.team_id = teams.id AND users.is_active = true) as members_count')
            ])
            ->leftJoin('team_requests', function($join) use ($startDate, $endDate) {
                $join->on('teams.id', '=', 'team_requests.team_id')
                    ->whereBetween('team_requests.created_at', [$startDate, $endDate])
                    ->where('team_requests.status', 'approved');
            })
            ->groupBy('teams.id', 'teams.name')
            ->orderByDesc('request_count')
            ->get()
            ->map(function ($team) {
                return [
                    'id' => $team->id,
                    'name' => $team->name,
                    'request_count' => $team->request_count,
                    'total_quantity' => $team->total_quantity,
                    'members_count' => $team->members_count,
                    'requests_per_member' => $team->members_count > 0 ? 
                        $team->request_count / $team->members_count : 0,
                    'percentage' => 0, // Will be calculated after we have max
                ];
            });
        
        // Calculate percentages
        $maxRequests = $teams->max('request_count') ?: 1;
        $teams = $teams->map(function ($team) use ($maxRequests) {
            $team['percentage'] = ($team['request_count'] / $maxRequests) * 100;
            return $team;
        });
        
        return [
            'teams' => $teams,
            'summary' => [
                'total_teams' => $teams->count(),
                'total_requests' => $teams->sum('request_count'),
                'total_quantity' => $teams->sum('total_quantity'),
                'average_requests_per_team' => $teams->avg('request_count'),
                'most_active_team' => $teams->sortByDesc('request_count')->first(),
            ]
        ];
    }
    
    /**
     * Get depletion analysis data
     */
    private function getDepletionAnalysisData($thirtyDaysAgo)
    {
        $items = Item::all()
            ->map(function ($item) use ($thirtyDaysAgo) {
                $requestsLast30Days = TeamRequest::where('item_id', $item->id)
                    ->where('status', 'approved')
                    ->where('created_at', '>=', $thirtyDaysAgo)
                    ->sum('quantity_requested');
                
                // Calculate depletion rate
                if ($item->quantity > 0) {
                    $depletionRate = ($requestsLast30Days / $item->quantity) * 100;
                } else {
                    $depletionRate = 100;
                }
                
                // Calculate days to depletion
                if ($item->quantity <= 0) {
                    $daysToDepletion = 0;
                } elseif ($requestsLast30Days > 0) {
                    $dailyUsage = $requestsLast30Days / 30;
                    $daysToDepletion = $dailyUsage > 0 ? round($item->quantity / $dailyUsage) : 999;
                } else {
                    $daysToDepletion = 999;
                }
                
                // Determine depletion level
                $depletionLevel = $this->determineDepletionLevel($daysToDepletion, $item->quantity, $item->minimum_stock);
                
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'current_stock' => $item->quantity,
                    'minimum_stock' => $item->minimum_stock,
                    'requests_last_30_days' => $requestsLast30Days,
                    'depletion_rate' => min($depletionRate, 100),
                    'days_to_depletion' => $daysToDepletion,
                    'depletion_level' => $depletionLevel,
                    'depletion_color' => $this->getDepletionColor($depletionLevel),
                ];
            })
            ->sortByDesc('depletion_rate');
        
        return [
            'items' => $items,
            'summary' => [
                'total_items' => $items->count(),
                'critical_items' => $items->where('depletion_level', 'critical')->count(),
                'warning_items' => $items->where('depletion_level', 'warning')->count(),
                'normal_items' => $items->where('depletion_level', 'normal')->count(),
                'average_depletion_rate' => $items->avg('depletion_rate'),
                'fastest_depleting_item' => $items->first(),
            ]
        ];
    }
    
    /**
     * Get stock status data
     */
    private function getStockStatusData()
    {
        $criticalItemsCount = Item::where('quantity', '<=', 0)->count();
        $warningItemsCount = Item::where('quantity', '>', 0)
            ->whereColumn('quantity', '<=', 'minimum_stock')
            ->count();
        $safeItemsCount = Item::where('quantity', '>', 0)
            ->whereColumn('quantity', '>', 'minimum_stock')
            ->count();
        
        return [
            'critical' => $criticalItemsCount,
            'warning' => $warningItemsCount,
            'safe' => $safeItemsCount,
            'total' => $criticalItemsCount + $warningItemsCount + $safeItemsCount,
            'percentage_critical' => ($criticalItemsCount / ($criticalItemsCount + $warningItemsCount + $safeItemsCount)) * 100,
            'percentage_warning' => ($warningItemsCount / ($criticalItemsCount + $warningItemsCount + $safeItemsCount)) * 100,
            'percentage_safe' => ($safeItemsCount / ($criticalItemsCount + $warningItemsCount + $safeItemsCount)) * 100,
        ];
    }
    
    /**
     * Get summary statistics
     */
    private function getSummaryStatistics($startDate, $endDate)
    {
        $totalRequests = TeamRequest::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'approved')
            ->count();
        
        $totalRestocked = InventoryLog::where('action', 'restock')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('quantity_change');
        
        $totalClaimed = TeamRequest::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'claimed')
            ->sum('quantity_requested');
        
        $activeTeams = TeamRequest::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'approved')
            ->distinct('team_id')
            ->count('team_id');
        
        $totalItems = Item::count();
        
        $daysInPeriod = $startDate->diffInDays($endDate) + 1;
        $requestsPerDay = $daysInPeriod > 0 ? $totalRequests / $daysInPeriod : 0;
        $activityLevel = $this->determineActivityLevel($requestsPerDay);
        
        return [
            'total_requests' => $totalRequests,
            'total_restocked' => $totalRestocked,
            'total_claimed' => $totalClaimed,
            'active_teams' => $activeTeams,
            'total_items' => $totalItems,
            'requests_per_day' => $requestsPerDay,
            'activity_level' => $activityLevel,
            'period_days' => $daysInPeriod,
        ];
    }
    
    /**
     * Helper methods
     */
    private function calculateBeginningQuantity($item, $startDate)
    {
        // Simplified: Use current quantity for now
        // In a real system, you'd track historical stock levels
        return $item->quantity;
    }
    
    private function determineItemStatus($endingQuantity, $minimumStock, $restockedQuantity)
    {
        if ($endingQuantity <= 0) {
            return 'Out of Stock';
        } elseif ($endingQuantity <= $minimumStock) {
            return 'Needs Restock';
        } elseif ($restockedQuantity > 0) {
            return 'Restocked';
        } else {
            return 'In Stock';
        }
    }
    
    private function getStatusColor($status)
    {
        return match($status) {
            'Out of Stock' => '#dc3545',
            'Needs Restock' => '#ffc107',
            'Restocked' => '#28a745',
            default => '#28a745',
        };
    }
    
    private function determineDemandLevel($totalRequests)
    {
        if ($totalRequests > 20) return 'high';
        if ($totalRequests > 10) return 'medium';
        if ($totalRequests > 0) return 'low';
        return 'none';
    }
    
    private function getDemandColor($demandLevel)
    {
        return match($demandLevel) {
            'high' => '#dc3545',
            'medium' => '#ffc107',
            'low' => '#28a745',
            default => '#6c757d',
        };
    }
    
    private function determineDepletionLevel($daysToDepletion, $currentStock, $minimumStock)
    {
        if ($currentStock <= 0) return 'critical';
        if ($daysToDepletion <= 7) return 'critical';
        if ($daysToDepletion <= 14 || $currentStock <= $minimumStock) return 'warning';
        return 'normal';
    }
    
    private function getDepletionColor($depletionLevel)
    {
        return match($depletionLevel) {
            'critical' => '#dc3545',
            'warning' => '#ffc107',
            default => '#28a745',
        };
    }
    
    private function determineActivityLevel($requestsPerDay)
    {
        if ($requestsPerDay >= 10) return 'High Activity';
        if ($requestsPerDay >= 5) return 'Medium Activity';
        if ($requestsPerDay > 0) return 'Normal Activity';
        return 'No Activity';
    }
}