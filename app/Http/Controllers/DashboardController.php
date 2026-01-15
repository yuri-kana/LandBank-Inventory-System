<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\TeamRequest;
use App\Models\Team;
use App\Models\User;
use App\Models\InventoryLog;
use App\Models\MonthlyReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Main dashboard with tab support
     * FIX: Make sure this method can handle BOTH with and without tab parameter
     */
    public function dashboard(Request $request, $tab = 'usage-pattern')
    {
        $validTabs = ['usage-pattern', 'depletion', 'restock-management', 'inventory-records'];
        
        // Ensure tab is valid
        if (!in_array($tab, $validTabs)) {
            $tab = 'usage-pattern';
        }
        
        $user = Auth::user();
        $selectedMonth = $request->input('month', 'all');
        $selectedYear = $request->input('year', date('Y'));
        
        if ($user->isAdmin()) {
            return $this->adminDashboardWithTabs($user, $selectedMonth, $selectedYear, $tab);
        } else {
            return $this->teamMemberDashboardWithTabs($user, $selectedMonth, $selectedYear, $tab);
        }
    }

    /**
     * Admin dashboard with tab support
     */
    private function adminDashboardWithTabs($user, $selectedMonth, $selectedYear, $tab)
    {
        // Get available years for the filter dropdown
        $availableYears = $this->getAvailableYears();
        
        // Determine date range based on selected year
        if ($selectedYear === 'all') {
            $startDate = null; // No date filtering
            $endDate = null;
        } else {
            $startDate = Carbon::create($selectedYear, 1, 1)->startOfYear();
            $endDate = Carbon::create($selectedYear, 12, 31)->endOfYear();
        }
        
        // Prepare base data array - FIXED: Use available stock calculation
        $totalItems = Item::count();
        
        // Calculate low stock and out-of-stock based on AVAILABLE stock (quantity - approved)
        $lowStockCount = 0;
        $outOfStockCount = 0;
        
        // Get all items with their approved requests
        $items = Item::with(['requests' => function($query) {
            $query->where('status', 'approved');
        }])->get();
        
        foreach ($items as $item) {
            // Calculate approved quantity for this item
            $approvedQuantity = $item->requests->sum('quantity_requested');
            
            // Calculate available stock
            $availableStock = max(0, $item->quantity - $approvedQuantity);
            
            // Check if out of stock (available stock <= 0)
            if ($availableStock <= 0) {
                $outOfStockCount++;
            } 
            // Check if low stock (available stock > 0 but <= minimum stock)
            elseif ($availableStock > 0 && $availableStock <= $item->minimum_stock) {
                $lowStockCount++;
            }
        }
        
        $data = [
            'tab' => $tab,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'availableYears' => $availableYears,
            'totalItems' => $totalItems,
            'lowStockItems' => $lowStockCount,
            'outOfStockItems' => $outOfStockCount,
        ];
        
        // ===========================================
        // 1. USAGE PATTERNS TAB DATA - FIXED (MONTHLY RESET)
        // ===========================================
        if ($tab === 'usage-pattern') {
            // Get current date for month-to-date calculation
            $currentDate = now();
            $currentYear = $currentDate->year;
            $currentMonth = $currentDate->month;
            $daysInMonth = $currentDate->daysInMonth; // FIXED: Property, not method!
            $daysElapsed = $currentDate->day; // Days elapsed including today
            
            // Most requested items - CURRENT MONTH ONLY (monthly reset)
            $startOfMonth = $currentDate->copy()->startOfMonth();
            
            $query = Item::select([
                    'items.id',
                    'items.name',
                    'items.quantity',
                    'items.minimum_stock',
                    'items.updated_at',
                    DB::raw('COALESCE(COUNT(team_requests.id), 0) as total_requests'),
                    DB::raw('COALESCE(SUM(team_requests.quantity_requested), 0) as total_quantity'),
                    // Get teams that requested this item (Current Month)
                    DB::raw('GROUP_CONCAT(DISTINCT teams.name ORDER BY teams.name SEPARATOR ", ") as requesting_teams'),
                    DB::raw('COUNT(DISTINCT teams.id) as team_count'),
                    // Calculate avg requests/day based on WHOLE MONTH
                    DB::raw('ROUND(COALESCE(COUNT(team_requests.id), 0) / ' . $daysInMonth . ', 2) as avg_requests_per_day')
                ])
                ->leftJoin('team_requests', function($join) use ($startOfMonth) {
                    $join->on('items.id', '=', 'team_requests.item_id')
                        ->where('team_requests.created_at', '>=', $startOfMonth); // CURRENT MONTH ONLY
                })
                ->leftJoin('teams', 'team_requests.team_id', '=', 'teams.id')
                ->groupBy('items.id', 'items.name', 'items.quantity', 'items.minimum_stock', 'items.updated_at')
                ->orderByDesc('total_requests')
                ->orderBy('items.name');
            
            $data['topRequestedItems'] = $query->get();
            $data['maxRequests'] = $data['topRequestedItems']->max('total_requests') ?? 1;
            
            // Add additional calculated fields for the view
            foreach ($data['topRequestedItems'] as $item) {
                // Determine demand level based on avg requests per day
                $avg = $item->avg_requests_per_day;
                if ($avg >= 1.0) {
                    $item->demand_level = 'High Demand';
                    $item->demand_color = 'text-red-600';
                } elseif ($avg >= 0.5) {
                    $item->demand_level = 'Medium Demand';
                    $item->demand_color = 'text-amber-600';
                } elseif ($avg > 0) {
                    $item->demand_level = 'Normal Demand';
                    $item->demand_color = 'text-emerald-600';
                } else {
                    $item->demand_level = 'No Demand';
                    $item->demand_color = 'text-gray-500';
                }
                
                // Format teams info
                $item->teams_info = $item->team_count > 0 
                    ? $item->requesting_teams . " ({$item->team_count} teams)" 
                    : 'No teams';
                
                // Format avg requests per day (based on whole month)
                $item->avg_display = number_format($item->avg_requests_per_day, 2) . '/day';
                $item->period_info = "Monthly Avg ({$daysInMonth}-day month)";
            }

            // Most active teams - CURRENT MONTH ONLY
            $teamQuery = Team::select([
                    'teams.id',
                    'teams.name',
                    DB::raw('COUNT(team_requests.id) as request_count'),
                    DB::raw('SUM(team_requests.quantity_requested) as total_quantity'),
                    DB::raw('(SELECT COUNT(*) FROM users WHERE users.team_id = teams.id AND users.is_active = true) as members_count'),
                    // Get items requested by this team
                    DB::raw('GROUP_CONCAT(DISTINCT items.name ORDER BY items.name SEPARATOR ", ") as requested_items'),
                    DB::raw('COUNT(DISTINCT items.id) as item_count')
                ])
                ->leftJoin('team_requests', function($join) use ($startOfMonth) {
                    $join->on('teams.id', '=', 'team_requests.team_id')
                        ->where('team_requests.created_at', '>=', $startOfMonth); // CURRENT MONTH ONLY
                })
                ->leftJoin('items', 'team_requests.item_id', '=', 'items.id')
                ->groupBy('teams.id', 'teams.name')
                ->orderByDesc('request_count')
                ->orderBy('teams.name');
            
            $data['mostActiveTeams'] = $teamQuery->get();

            // If no teams have requests, show all teams with 0 requests
            if ($data['mostActiveTeams']->isEmpty()) {
                $data['mostActiveTeams'] = Team::select([
                        'teams.id',
                        'teams.name',
                        DB::raw('0 as request_count'),
                        DB::raw('0 as total_quantity'),
                        DB::raw('(SELECT COUNT(*) FROM users WHERE users.team_id = teams.id AND users.is_active = true) as members_count'),
                        DB::raw('NULL as requested_items'),
                        DB::raw('0 as item_count')
                    ])
                    ->orderBy('teams.name')
                    ->get();
            }
            
            // Add period info to view data
            $data['period_info'] = [
                'days_elapsed' => $daysElapsed,
                'days_in_month' => $daysInMonth,
                'month_name' => $currentDate->format('F'),
                'year' => $currentYear,
                'period_text' => "Current Month Only - Resets on 1st of each month"
            ];
        }
        
        // ===========================================
// 2. DEPLETION RATE TAB DATA - FIXED (MONTHLY RESET)
// ===========================================
if ($tab === 'depletion') {
    // Get current date for month calculation
    $currentDate = now();
    $currentYear = $currentDate->year;
    $currentMonth = $currentDate->month;
    $daysInMonth = $currentDate->daysInMonth;
    
    // Get start of current month
    $startOfMonth = $currentDate->copy()->startOfMonth();   
    
    $data['fastDepletingItems'] = Item::all()
        ->map(function ($item) use ($startOfMonth, $daysInMonth) {
            // Get CLAIMED quantity for CURRENT MONTH (actual consumption)
            $claimedThisMonth = TeamRequest::where('item_id', $item->id)
                ->where('status', 'claimed')
                ->where('created_at', '>=', $startOfMonth)
                ->sum('quantity_requested');
            
            // ADD THIS: Get APPROVED quantity (reserved stock) for CURRENT MONTH
            $approvedQuantity = TeamRequest::where('item_id', $item->id)
                ->where('status', 'approved')
                ->where('created_at', '>=', $startOfMonth)
                ->sum('quantity_requested');
            
            // Calculate depletion rate based on CURRENT MONTH consumption
            if ($item->quantity > 0) {
                // Monthly depletion rate: (claimed this month ÷ current stock) × 100
                $depletionRate = ($claimedThisMonth / $item->quantity) * 100;
            } else {
                $depletionRate = 100; // Out of stock
            }
            
            // Days to depletion estimate based on CURRENT MONTH rate
            if ($item->quantity <= 0) {
                $daysToDepletion = 0;
            } elseif ($claimedThisMonth > 0) {
                // Calculate daily usage based on current month
                $daysElapsed = now()->day; // Days elapsed this month
                $dailyUsage = $daysElapsed > 0 ? $claimedThisMonth / $daysElapsed : 0;
                
                if ($dailyUsage > 0) {
                    $daysToDepletion = round($item->quantity / $dailyUsage);
                } else {
                    $daysToDepletion = 999;
                }
            } else {
                $daysToDepletion = 999; // No consumption this month
            }
            
            // Determine depletion category
            $depletionCategory = 'normal';
            if ($item->quantity <= 0) {
                $depletionCategory = 'critical';
            } elseif ($depletionRate >= 50) {
                $depletionCategory = 'critical';
            } elseif ($depletionRate >= 20) {
                $depletionCategory = 'warning';
            } else {
                $depletionCategory = 'normal';
            }
            
            return (object) [
                'id' => $item->id,
                'name' => $item->name,
                'quantity' => $item->quantity,
                'minimum_stock' => $item->minimum_stock,
                'depletion_rate' => min($depletionRate, 100),
                'days_to_depletion' => $daysToDepletion,
                'claimed_this_month' => $claimedThisMonth,
                'approved_quantity' => $approvedQuantity, // ADD THIS LINE!
                'depletion_category' => $depletionCategory,
                'period_info' => "Current Month Consumption"
            ];
        })
        ->sortByDesc('depletion_rate')
        ->values();

            // Stock status counts (always current, not period-based)
            $data['criticalItemsCount'] = $outOfStockCount;
            $data['warningItemsCount'] = $lowStockCount;
            $data['safeItemsCount'] = max(0, $totalItems - ($lowStockCount + $outOfStockCount));
            
            // Add period info for view
            $data['depletion_period_info'] = [
                'month_name' => $currentDate->format('F'),
                'year' => $currentYear,
                'days_in_month' => $daysInMonth,
                'days_elapsed' => $currentDate->day,
                'period_text' => "Current Month Only (Resets on 1st of each month)"
            ];
        }
        
        // ===========================================
        // 3. RESTOCK MANAGEMENT TAB DATA
        // ===========================================
        if ($tab === 'restock-management') {
    // Get items with their separated request counts - SHOW ALL ITEMS
    $inventoryFlowItems = Item::with(['requests'])->get()
        ->map(function ($item) use ($startDate, $endDate) {
            // Filter requests by date if needed
            $requestsQuery = $item->requests();
            if ($startDate && $endDate) {
                $requestsQuery->whereBetween('created_at', [$startDate, $endDate]);
            }
            
            $allRequests = $requestsQuery->get();
            
            // Count by status (separated)
            $pending = $allRequests->where('status', 'pending')->sum('quantity_requested');
            $approved = $allRequests->where('status', 'approved')->sum('quantity_requested');
            $claimed = $allRequests->where('status', 'claimed')->sum('quantity_requested');
            $declined = $allRequests->where('status', 'rejected')->sum('quantity_requested');
            
            // Get restocked quantity
            $restockedQuery = InventoryLog::where('item_id', $item->id)
                ->where('action', 'restock');
            
            if ($startDate && $endDate) {
                $restockedQuery->whereBetween('created_at', [$startDate, $endDate]);
            }
            
            $restocked = $restockedQuery->sum('quantity_change');
            
            // Calculate beginning and ending quantities
            $beginning = $item->quantity + $claimed - $restocked;
            $ending = max(0, $item->quantity - $approved); // Available stock = Total - Approved
            
            // Determine status based on available stock
            $status = $this->calculateItemStatus($ending, $item->minimum_stock, $restocked);
            
            // Format for display like your image
            $displayEnding = $ending . " Min: " . $item->minimum_stock . " " . 
                            $item->quantity . " total - " . $approved . " approved";
            
            return (object) [
                'id' => $item->id,
                'name' => $item->name,
                'category' => $item->category ?? 'General',
                'unit' => $item->unit ?? 'units',
                'beginning_quantity' => $beginning,
                'pending_quantity' => $pending,       // Pending approval
                'approved_quantity' => $approved,     // Approved (reserved)
                'claimed_quantity' => $claimed,       // Actually claimed
                'declined_quantity' => $declined,     // Rejected requests
                'restocked_quantity' => $restocked,   // Restocked items
                'ending_quantity' => $ending,         // Available stock (Total - Approved)
                'quantity' => $item->quantity,        // Total physical stock
                'minimum_stock' => $item->minimum_stock,
                'status' => $status,
                'display_ending' => $displayEnding,   // Formatted like your image
                'has_pending' => $pending > 0,
                'has_approved' => $approved > 0,
                'has_claimed' => $claimed > 0,
                'has_restocked' => $restocked > 0
            ];
        })
        // REMOVE THE FILTER - SHOW ALL ITEMS
        ->sortBy('name')  // Sort alphabetically by name
        ->values();

    $data['inventoryFlowItems'] = $inventoryFlowItems;
    
    // Calculate summary statistics
    $data['summary'] = [
        'total_pending' => $inventoryFlowItems->sum('pending_quantity'),
        'total_approved' => $inventoryFlowItems->sum('approved_quantity'),
        'total_claimed' => $inventoryFlowItems->sum('claimed_quantity'),
        'total_restocked' => $inventoryFlowItems->sum('restocked_quantity'),
        'total_items' => $inventoryFlowItems->count(),
        'items_with_pending' => $inventoryFlowItems->where('has_pending')->count(),
        'items_with_approved' => $inventoryFlowItems->where('has_approved')->count(),
    ];
}
        
        // ===========================================
        // 4. INVENTORY RECORDS TAB DATA
        // ===========================================
        if ($tab === 'inventory-records') {
    // Get available years
    $availableYears = $this->getAvailableYears();
    
    // Validate selected year
    if (!in_array($selectedYear, $availableYears)) {
        $selectedYear = date('Y');
    }
    
    // Auto-generate missing monthly reports for selected year
    if ($selectedYear !== 'all') {
        $this->autoGenerateMonthlyReports($selectedYear);
    }
    
    // Get monthly reports - FIXED: Counts ALL requests
    $monthlyReports = $this->getMonthlyReports($selectedYear, $selectedMonth);
    
    // Get current period stats - FIXED: Counts ALL requests
    $currentPeriodStats = $this->getCurrentPeriodStats($selectedYear, $selectedMonth);
    
    // Yearly comparison
    $yearlyComparison = $this->generateYearlyComparison($selectedYear);
    
    // Summary statistics
    $data['totalRequests'] = $currentPeriodStats['total_requests'];
    $data['totalRestocked'] = $currentPeriodStats['total_restocked'];
    $data['totalClaimed'] = $currentPeriodStats['total_claimed'];
    $data['averageActivity'] = $currentPeriodStats['average_activity'];
    
    // Calculate year-over-year change
    if ($selectedYear !== 'all') {
        $prevYear = $selectedYear - 1;
        $prevYearStats = $this->getCurrentPeriodStats($prevYear, 'all');
        
        $data['yearOverYearChange'] = [
            'requests' => $prevYearStats['total_requests'] > 0 ? 
                round((($data['totalRequests'] - $prevYearStats['total_requests']) / $prevYearStats['total_requests']) * 100, 1) : 
                ($data['totalRequests'] > 0 ? 100 : 0),
            'direction' => $data['totalRequests'] > $prevYearStats['total_requests'] ? 'up' : 
                        ($data['totalRequests'] < $prevYearStats['total_requests'] ? 'down' : 'stable')
        ];
    } else {
        $data['yearOverYearChange'] = [
            'requests' => 0,
            'direction' => 'stable'
        ];
    }
    
    // Pass all data to view
    $data['monthlyReports'] = $monthlyReports;
    $data['yearlyComparison'] = $yearlyComparison;
    $data['availableYears'] = $availableYears;
    $data['selectedYear'] = $selectedYear;
    $data['selectedMonth'] = $selectedMonth;
    
    // Add debug info (remove in production)
    $data['debug'] = [
        'monthly_reports_count' => $monthlyReports->count(),
        'current_year' => $selectedYear,
        'current_month' => $selectedMonth,
    ];
}
        
        return view('dashboard', $data);
    }

    /**
     * Get all available years with data for filter dropdown
     */
    private function getAvailableYears()
    {
        // Get years from team requests
        $requestYears = TeamRequest::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
        
        // Get years from inventory logs
        $logYears = [];
        try {
            if (Schema::hasTable('inventory_logs')) {
                $logYears = InventoryLog::selectRaw('YEAR(created_at) as year')
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->pluck('year')
                    ->toArray();
            }
        } catch (\Exception $e) {
            // Table might not exist, continue without it
        }
        
        // Get years from monthly reports
        $reportYears = [];
        try {
            if (class_exists(MonthlyReport::class)) {
                $reportYears = MonthlyReport::selectRaw('year')
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->pluck('year')
                    ->toArray();
            }
        } catch (\Exception $e) {
            // Table might not exist
        }
        
        // Combine all years and remove duplicates
        $allYears = array_unique(array_merge($requestYears, $logYears, $reportYears));
        
        // Sort in descending order
        rsort($allYears);
        
          array_unshift($allYears, 'all');
        
        // Ensure current year is included
        $currentYear = date('Y');
        if (!in_array($currentYear, $allYears)) {
            $allYears[] = $currentYear;
            rsort($allYears); // Re-sort after adding
        }
        
        return $allYears;
    }

    /**
     * Team member dashboard with tab support
     */
    private function teamMemberDashboardWithTabs($user, $selectedMonth, $selectedYear, $tab)
    {
        $team = $user->team;
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        
        // Prepare base data - FIXED: Use available stock calculation
        $totalItems = Item::count();
        
        // Calculate low stock and out-of-stock based on AVAILABLE stock
        $lowStockCount = 0;
        $outOfStockCount = 0;
        $inStockCount = 0;
        
        // Get all items with their approved requests
        $items = Item::with(['requests' => function($query) {
            $query->where('status', 'approved');
        }])->get();
        
        foreach ($items as $item) {
            // Calculate approved quantity for this item
            $approvedQuantity = $item->requests->sum('quantity_requested');
            
            // Calculate available stock
            $availableStock = max(0, $item->quantity - $approvedQuantity);
            
            // Check stock status
            if ($availableStock <= 0) {
                $outOfStockCount++;
            } elseif ($availableStock > 0 && $availableStock <= $item->minimum_stock) {
                $lowStockCount++;
            } else {
                $inStockCount++;
            }
        }
        
        $data = [
            'tab' => $tab,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'totalItems' => $totalItems,
            'lowStockItems' => $lowStockCount,
            'outOfStockItems' => $outOfStockCount,
            'inStockItems' => $inStockCount,
        ];
        
        // Team-specific statistics
        if ($team) {
            $data['teamRequests'] = $team->requests()->where('created_at', '>=', $thirtyDaysAgo)->count();
            $data['pendingTeamRequests'] = $team->requests()->where('status', 'pending')->count();
            $data['approvedTeamRequests'] = $team->requests()->where('status', 'approved')->count();
            $data['rejectedTeamRequests'] = $team->requests()->where('status', 'rejected')->count();
            
            $data['recentTeamRequests'] = $team->requests()
                ->with('item')
                ->where('created_at', '>=', $thirtyDaysAgo)
                ->latest()
                ->take(5)
                ->get();
        } else {
            $data['teamRequests'] = 0;
            $data['pendingTeamRequests'] = 0;
            $data['approvedTeamRequests'] = 0;
            $data['rejectedTeamRequests'] = 0;
            $data['recentTeamRequests'] = collect();
        }
        
        // Available items for team members
        $data['availableItems'] = Item::where('quantity', '>', 0)
            ->orderBy('name')
            ->get();
        $data['availableItemsCount'] = $data['availableItems']->count();
        
        // Request statistics
        $data['pendingRequests'] = TeamRequest::where('status', 'pending')->count();
        $data['approvedRequests'] = TeamRequest::where('status', 'approved')->count();
        $data['rejectedRequests'] = TeamRequest::where('status', 'rejected')->count();
        $data['totalRequests'] = TeamRequest::count();
        
        // Recent items
        $data['recentItems'] = Item::where('quantity', '>', 0)
            ->latest()
            ->take(10)
            ->get();
        
        $data['team'] = $team;
        
        return view('dashboard', $data);
    }

    /**
     * Legacy index method for backward compatibility - FIXED
     */
    public function index(Request $request)
    {
        // Redirect to the new dashboard with default tab
        return redirect()->route('dashboard', 'usage-pattern');
    }

    /**
 * Auto-generate missing monthly reports
 */
private function autoGenerateMonthlyReports($year)
{
    try {
        if (!class_exists(MonthlyReport::class)) {
            return;
        }
        
        // Get current year and month
        $currentYear = date('Y');
        $currentMonth = date('n');
        
        // Generate reports for selected year
        for ($month = 1; $month <= 12; $month++) {
            // Only generate past months for current year
            if ($year == $currentYear && $month > $currentMonth) {
                break;
            }
            
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();
            
            // Use real-time calculation
            $monthlyData = $this->calculateRealTimeMonthlyData($year, $month, $startDate, $endDate);
            
            // Check if report already exists
            $existingReport = MonthlyReport::where('year', $year)
                ->where('month', $month)
                ->first();
            
            if (!$existingReport) {
                // Create new report with real-time data
                MonthlyReport::create([
                    'year' => $year,
                    'month' => $month,
                    'total_requests' => $monthlyData['total_requests'],
                    'total_restocked' => $monthlyData['total_restocked'],
                    'total_claimed' => $monthlyData['total_claimed'],
                    'beginning_stock_value' => 0,
                    'ending_stock_value' => 0,
                    'report_generated_at' => now(),
                    'is_finalized' => false,
                ]);
            }
        }
        
    } catch (\Exception $e) {
        \Log::error('Error auto-generating monthly reports: ' . $e->getMessage());
    }
}

/**
 * Calculate real-time data for a specific month
 */
private function calculateRealTimeMonthlyData($year, $month, $startDate, $endDate)
{
    // Adjust these queries based on your actual model names
    // If your request model is called something else, update this
    $totalRequests = TeamRequest::whereBetween('created_at', [$startDate, $endDate])->count();
    
    // Assuming you have models for inventory logs
    $totalRestocked = 0;
    if (Schema::hasTable('inventory_logs')) {
        $totalRestocked = InventoryLog::where('action', 'restock')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('quantity_change');
    }
    
    $totalClaimed = TeamRequest::where('status', 'claimed')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();
    
    return [
        'total_requests' => $totalRequests,
        'total_restocked' => $totalRestocked,
        'total_claimed' => $totalClaimed,
    ];
}

/**
 * Calculate month-over-month change using real-time data
 */
private function calculateMonthOverMonthChange($year, $month)
{
    $currentMonthData = $this->calculateRealTimeMonthlyData(
        $year, 
        $month, 
        Carbon::create($year, $month, 1)->startOfMonth(),
        Carbon::create($year, $month, 1)->endOfMonth()
    );
    
    // Calculate previous month
    if ($month == 1) {
        $prevYear = $year - 1;
        $prevMonth = 12;
    } else {
        $prevYear = $year;
        $prevMonth = $month - 1;
    }
    
    $prevMonthData = $this->calculateRealTimeMonthlyData(
        $prevYear,
        $prevMonth,
        Carbon::create($prevYear, $prevMonth, 1)->startOfMonth(),
        Carbon::create($prevYear, $prevMonth, 1)->endOfMonth()
    );
    
    if ($prevMonthData['total_requests'] > 0) {
        $change = (($currentMonthData['total_requests'] - $prevMonthData['total_requests']) / $prevMonthData['total_requests']) * 100;
        return round($change, 2);
    }
    
    return 0;
}

    /**
 * Get monthly reports for display - REAL-TIME VERSION
 */
private function getMonthlyReports($year, $month = 'all')
{
    $reports = collect();
    
    // Determine which months to show
    if ($month !== 'all') {
        $months = [$month];
    } else {
        // Show all months of the year
        $months = range(1, 12);
    }
    
    foreach ($months as $monthNum) {
        $startDate = Carbon::create($year, $monthNum, 1)->startOfMonth();
        $endDate = Carbon::create($year, $monthNum, 1)->endOfMonth();
        $currentDate = Carbon::now();
        
        // Check if month has ended
        $hasMonthEnded = $currentDate->greaterThan($endDate);
        
        // REAL-TIME CALCULATION - Always calculate fresh data
        $monthlyData = $this->calculateRealTimeMonthlyData($year, $monthNum, $startDate, $endDate);
        
        // Get or create the MonthlyReport record (for storage/reference only)
        $monthlyReport = MonthlyReport::updateOrCreate(
            [
                'year' => $year,
                'month' => $monthNum,
            ],
            [
                'total_requests' => $monthlyData['total_requests'],
                'total_restocked' => $monthlyData['total_restocked'],
                'total_claimed' => $monthlyData['total_claimed'],
                'report_generated_at' => now(),
                // Keep existing is_finalized value if exists, otherwise false
                'is_finalized' => false,
            ]
        );
        
        // Get additional stats - COUNT ALL REQUESTS (real-time)
        $activeTeams = TeamRequest::whereBetween('created_at', [$startDate, $endDate])
            ->distinct('team_id')
            ->count('team_id');
        
        // Determine status based on activity - USE ALL REQUESTS
        $status = $this->determineReportStatus(
            $monthlyData['total_requests'],
            $monthlyData['total_claimed']
        );
        
        // Get month-over-month change (using real-time data for previous month too)
        $changeFromPrevious = $this->calculateMonthOverMonthChange($year, $monthNum);
        
        $reports->push((object) [
            'id' => $monthlyReport->id,
            'year' => $year,
            'month' => $monthNum,
            'month_name' => date('F', mktime(0, 0, 0, $monthNum, 1)),
            'total_requests' => $monthlyData['total_requests'],
            'total_restocked' => $monthlyData['total_restocked'],
            'total_claimed' => $monthlyData['total_claimed'],
            'active_teams' => $activeTeams,
            'status' => $status,
            'change_from_previous' => $changeFromPrevious,
            'is_finalized' => $monthlyReport->is_finalized ?? false,
            'report_generated_at' => $monthlyReport->report_generated_at ?? now(),
            'period' => date('F Y', mktime(0, 0, 0, $monthNum, 1, $year)),
            'has_month_ended' => $hasMonthEnded,
            'month_end_date' => $endDate->format('Y-m-d'),
        ]);
    }
    
    return $reports;
}

    /**
     * Generate monthly report data on the fly
     */
    private function generateMonthlyReportData($year, $month)
{
    // FIX: Count ALL requests, not just approved
    $totalRequests = TeamRequest::whereYear('created_at', $year)
        ->whereMonth('created_at', $month)
        ->count(); // ALL requests regardless of status
    
    $totalRestocked = 0;
    if (Schema::hasTable('inventory_logs')) {
        $totalRestocked = InventoryLog::where('action', 'restock')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('quantity_change');
    }
    
    $totalClaimed = TeamRequest::where('status', 'claimed')
        ->whereYear('created_at', $year)
        ->whereMonth('created_at', $month)
        ->sum('quantity_requested');
    
    // Determine status based on total requests (not just approved)
    $status = $this->determineReportStatus($totalRequests, $totalClaimed);
    
    // Get most requested items for this month
    $mostRequestedItems = Item::select([
            'items.id',
            'items.name',
            DB::raw('COUNT(team_requests.id) as request_count'),
            DB::raw('SUM(team_requests.quantity_requested) as total_quantity')
        ])
        ->leftJoin('team_requests', function($join) use ($year, $month) {
            $join->on('items.id', '=', 'team_requests.item_id')
                ->whereYear('team_requests.created_at', $year)
                ->whereMonth('team_requests.created_at', $month);
        })
        ->groupBy('items.id', 'items.name')
        ->orderByDesc('request_count')
        ->limit(5)
        ->get()
        ->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'request_count' => $item->request_count,
                'total_quantity' => $item->total_quantity
            ];
        })
        ->toArray();
    
    // Get fast depleting items
    $fastDepletingItems = Item::where('quantity', '<=', DB::raw('minimum_stock'))
        ->where('quantity', '>', 0)
        ->limit(5)
        ->get()
        ->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'quantity' => $item->quantity,
                'minimum_stock' => $item->minimum_stock
            ];
        })
        ->toArray();
    
    // Update the database to keep it in sync with fresh data
    $report = MonthlyReport::updateOrCreate(
        [
            'year' => $year,
            'month' => $month,
        ],
        [
            'total_requests' => $totalRequests,
            'total_restocked' => $totalRestocked,
            'total_claimed' => $totalClaimed,
            'most_requested_items' => json_encode($mostRequestedItems),
            'fast_depleting_items' => json_encode($fastDepletingItems),
            'status' => $status, // Add status field
            'report_generated_at' => now(),
            'updated_at' => now(),
        ]
    );
    
    return $report;
}


    /**
     * Get stats for the current period (for stats cards)
     */
    private function getCurrentPeriodStats($year, $month = 'all')
{
    // Determine date range
    if ($year === 'all') {
        // No date filtering for "All Time"
        $startDate = null;
        $endDate = null;
    } elseif ($month !== 'all') {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();
    } else {
        $startDate = Carbon::create($year, 1, 1)->startOfYear();
        $endDate = Carbon::create($year, 12, 31)->endOfYear();
    }
    
    // FIX: Calculate stats with ALL requests (not just approved)
    $query = TeamRequest::query(); // REMOVED: ->where('status', 'approved')
    if ($startDate && $endDate) {
        $query->whereYear('created_at', $startDate->year);
        if ($month !== 'all') {
            $query->whereMonth('created_at', $month);
        }
    }
    $totalRequests = $query->count();
        
        $totalRestocked = 0;
        if (Schema::hasTable('inventory_logs')) {
            $logQuery = InventoryLog::where('action', 'restock');
            if ($startDate && $endDate) {
                $logQuery->whereYear('created_at', $startDate->year);
                if ($month !== 'all') {
                    $logQuery->whereMonth('created_at', $month);
                }
            }
            $totalRestocked = $logQuery->sum('quantity_change');
        }
        
        $claimedQuery = TeamRequest::where('status', 'claimed');
        if ($startDate && $endDate) {
            $claimedQuery->whereYear('created_at', $startDate->year);
            if ($month !== 'all') {
                $claimedQuery->whereMonth('created_at', $month);
            }
        }
        $totalClaimed = $claimedQuery->sum('quantity_requested');
        
        // Calculate average activity
        $daysInPeriod = 365; // Default for yearly view
        if ($year !== 'all') {
            if ($month !== 'all') {
                $daysInPeriod = Carbon::create($year, $month, 1)->daysInMonth;
            } else {
                $daysInPeriod = Carbon::create($year, 1, 1)->isLeapYear() ? 366 : 365;
            }
        } else {
            // For "All Time", use 365 days as baseline
            $daysInPeriod = 365;
        }
        
        $requestsPerDay = $daysInPeriod > 0 ? $totalRequests / $daysInPeriod : 0;
        $averageActivity = $this->determineActivityLevel($requestsPerDay);
        
        return [
            'total_requests' => $totalRequests,
            'total_restocked' => $totalRestocked,
            'total_claimed' => $totalClaimed,
            'average_activity' => $averageActivity,
        ];
    }

    /**
     * Determine report status based on activity
     */
    private function determineReportStatus($totalRequests, $totalClaimed)
{
    $totalActivity = $totalRequests + $totalClaimed;
    
    if ($totalActivity >= 20) {
        return 'High Activity';
    } elseif ($totalActivity >= 10) {
        return 'Medium Activity';
    } elseif ($totalActivity > 0) {
        return 'Normal Activity';
    } else {
        return 'No Activity';
    }
}


    /**
     * Determine activity level for stats cards
     */
    private function determineActivityLevel($requestsPerDay)
    {
        if ($requestsPerDay >= 10) {
            return 'High Activity';
        } elseif ($requestsPerDay >= 5) {
            return 'Medium Activity';
        } elseif ($requestsPerDay > 0) {
            return 'Normal Activity';
        } else {
            return 'No Activity';
        }
    }

    /**
     * Generate yearly comparison data
     */
    private function generateYearlyComparison($year)
    {
        $currentYear = $year;
        $previousYear = $year - 1;
        
        $currentYearData = $this->getYearlyData($currentYear);
        $previousYearData = $this->getYearlyData($previousYear);
        
        return [
            'current_year' => $currentYearData,
            'previous_year' => $previousYearData,
            'comparison' => $this->calculateYearComparison($currentYearData, $previousYearData)
        ];
    }

    private function getYearlyData($year)
{
    $startDate = Carbon::create($year, 1, 1)->startOfYear();
    $endDate = Carbon::create($year, 12, 31)->endOfYear();
    
    // Get most requested item for the year
    $mostRequestedItem = Item::select([
            'items.id',
            'items.name',
            DB::raw('COUNT(team_requests.id) as request_count')
        ])
        ->leftJoin('team_requests', function($join) use ($startDate, $endDate) {
            $join->on('items.id', '=', 'team_requests.item_id')
                ->whereBetween('team_requests.created_at', [$startDate, $endDate]);
        })
        ->groupBy('items.id', 'items.name')
        ->orderByDesc('request_count')
        ->first();
    
    // Get total requests for the year - COUNT ALL REQUESTS
    $totalRequests = TeamRequest::whereBetween('created_at', [$startDate, $endDate])
        ->count(); // REMOVED: ->where('status', 'approved')
    
    // Get total restocked for the year
    $totalRestocked = 0;
    if (Schema::hasTable('inventory_logs')) {
        $totalRestocked = InventoryLog::whereBetween('created_at', [$startDate, $endDate])
            ->where('action', 'restock')
            ->sum('quantity_change');
    }
    
    // Get total claimed for the year
    $totalClaimed = TeamRequest::whereBetween('created_at', [$startDate, $endDate])
        ->where('status', 'claimed')
        ->sum('quantity_requested');
    
    // Get total items
    $totalItems = Item::count();
    
    return [
        'total_requests' => $totalRequests,
        'total_restocked' => $totalRestocked,
        'total_claimed' => $totalClaimed,
        'total_items' => $totalItems,
        'most_requested_item' => $mostRequestedItem,
    ];
}

    private function calculateYearComparison($current, $previous)
    {
        if ($previous['total_requests'] == 0) {
            return [
                'change' => $current['total_requests'] > 0 ? 100 : 0,
                'direction' => $current['total_requests'] > 0 ? 'up' : 'stable'
            ];
        }
        
        $change = (($current['total_requests'] - $previous['total_requests']) / $previous['total_requests']) * 100;
        
        return [
            'change' => abs($change),
            'direction' => $change > 0 ? 'up' : ($change < 0 ? 'down' : 'stable')
        ];
    }

    public function restock(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();
        
        try {
            $restockedItems = [];
            
            foreach ($request->items as $itemData) {
                $item = Item::find($itemData['id']);
                $oldQuantity = $item->quantity;
                $item->quantity += $itemData['quantity'];
                $item->save();
                
                // Create inventory log
                try {
                    if (Schema::hasTable('inventory_logs')) {
                        InventoryLog::create([
                            'item_id' => $item->id,
                            'action' => 'restock',
                            'quantity_change' => $itemData['quantity'],
                            'beginning_quantity' => $oldQuantity,
                            'ending_quantity' => $item->quantity,
                            'user_id' => $request->user()->id,
                            'notes' => 'Restocked via dashboard'
                        ]);
                    }
                } catch (\Exception $e) {
                    // Table might not exist yet, continue without logging
                }
                
                $restockedItems[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'added' => $itemData['quantity'],
                    'new_total' => $item->quantity
                ];
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Items restocked successfully',
                'items' => $restockedItems
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to restock items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate missing reports for a year
     */
    public function generateMissingReports(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $generated = 0;
        
        try {
            for ($month = 1; $month <= 12; $month++) {
                // Only generate past months for current year
                if ($year == date('Y') && $month > date('n')) {
                    break;
                }
                
                // Check if report already exists
                $existingReport = MonthlyReport::where('year', $year)
                    ->where('month', $month)
                    ->first();
                
                if (!$existingReport) {
                    $this->generateAndStoreMonthlyReport($year, $month);
                    $generated++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Generated {$generated} missing reports for {$year}",
                'generated' => $generated
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate reports: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate and store a monthly report
     */
    private function generateAndStoreMonthlyReport($year, $month, $finalize = false)
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();
        
        // Calculate statistics
        $totalRequests = TeamRequest::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'approved')
            ->count();
        
        $totalRestocked = 0;
        if (Schema::hasTable('inventory_logs')) {
            $totalRestocked = InventoryLog::where('action', 'restock')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('quantity_change');
        }
        
        $totalClaimed = TeamRequest::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'claimed')
            ->sum('quantity_requested');
        
        // Get most requested items
        $mostRequestedItems = Item::select([
                'items.id',
                'items.name',
                DB::raw('COUNT(team_requests.id) as request_count'),
                DB::raw('SUM(team_requests.quantity_requested) as total_quantity')
            ])
            ->leftJoin('team_requests', function($join) use ($startDate, $endDate) {
                $join->on('items.id', '=', 'team_requests.item_id')
                    ->whereBetween('team_requests.created_at', [$startDate, $endDate])
                    ->where('team_requests.status', 'approved');
            })
            ->groupBy('items.id', 'items.name')
            ->orderByDesc('request_count')
            ->limit(5)
            ->get()
            ->toArray();
        
        // Get fast depleting items
        $fastDepletingItems = Item::where('quantity', '<=', DB::raw('minimum_stock'))
            ->where('quantity', '>', 0)
            ->limit(5)
            ->get()
            ->toArray();
        
        // Create or update the report
        $report = MonthlyReport::updateOrCreate(
            [
                'year' => $year,
                'month' => $month,
            ],
            [
                'beginning_stock_value' => 0, // You can implement this if you track item values
                'total_requests' => $totalRequests,
                'total_restocked' => $totalRestocked,
                'total_claimed' => $totalClaimed,
                'ending_stock_value' => 0, // You can implement this if you track item values
                'most_requested_items' => json_encode($mostRequestedItems),
                'fast_depleting_items' => json_encode($fastDepletingItems),
                'report_generated_at' => now(),
                'is_finalized' => $finalize,
            ]
        );
        
        return $report;
    }

    /**
     * Finalize a monthly report
     */
    public function finalizeReport(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|between:1,12',
        ]);
        
        try {
            $report = MonthlyReport::where('year', $request->year)
                ->where('month', $request->month)
                ->firstOrFail();
            
            $report->update([
                'is_finalized' => true,
                'report_generated_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Report finalized successfully',
                'report' => $report
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to finalize report: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download comprehensive monthly report
     */
    public function downloadReport($year, $month, $format)
    {
        try {
            // Get report data
            $report = MonthlyReport::where('year', $year)
                ->where('month', $month)
                ->first();
            
            if (!$report) {
                // Generate report on the fly if it doesn't exist
                $report = $this->generateAndStoreMonthlyReport($year, $month);
            }
            
            // Get all analytics data needed for the report
            $thirtyDaysAgo = Carbon::now()->subDays(30);
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();
            
            // 1. Top Requested Items (Last 30 Days)
            $topRequestedItems = Item::select([
                    'items.id',
                    'items.name',
                    'items.quantity',
                    'items.minimum_stock',
                    'items.updated_at',
                    DB::raw('COALESCE(COUNT(team_requests.id), 0) as total_requests'),
                    DB::raw('COALESCE(COUNT(team_requests.id) / 30.0, 0) as avg_requests_per_day')
                ])
                ->leftJoin('team_requests', function($join) use ($thirtyDaysAgo) {
                    $join->on('items.id', '=', 'team_requests.item_id')
                        ->where('team_requests.created_at', '>=', $thirtyDaysAgo)
                        ->where('team_requests.status', 'approved');
                })
                ->groupBy('items.id', 'items.name', 'items.quantity', 'items.minimum_stock', 'items.updated_at')
                ->orderByDesc('total_requests')
                ->orderBy('items.name')
                ->get();

            $maxRequests = $topRequestedItems->max('total_requests') ?? 1;

            // 2. Most Active Teams
            $mostActiveTeams = Team::select([
                    'teams.id',
                    'teams.name',
                    DB::raw('COUNT(team_requests.id) as request_count'),
                    DB::raw('(SELECT COUNT(*) FROM users WHERE users.team_id = teams.id AND users.is_active = true) as members_count')
                ])
                ->leftJoin('team_requests', function($join) use ($startDate, $endDate) {
                    $join->on('teams.id', '=', 'team_requests.team_id')
                        ->whereBetween('team_requests.created_at', [$startDate, $endDate])
                        ->where('team_requests.status', 'approved');
                })
                ->groupBy('teams.id', 'teams.name')
                ->orderByDesc('request_count')
                ->orderBy('teams.name')
                ->get();

            // 3. Fast Depleting Items
            $allItems = Item::all();
            
            $fastDepletingItems = $allItems
                ->map(function ($item) use ($thirtyDaysAgo) {
                    $requestsLast30Days = TeamRequest::where('item_id', $item->id)
                        ->where('status', 'approved')
                        ->where('created_at', '>=', $thirtyDaysAgo)
                        ->sum('quantity_requested');
                    
                    if ($item->quantity > 0) {
                        $depletionRate = ($requestsLast30Days / $item->quantity) * 100;
                    } else {
                        $depletionRate = 100;
                    }
                    
                    if ($item->quantity <= 0) {
                        $daysToDepletion = 0;
                    } elseif ($requestsLast30Days > 0) {
                        $dailyUsage = $requestsLast30Days / 30;
                        $daysToDepletion = $dailyUsage > 0 ? round($item->quantity / $dailyUsage) : 999;
                    } else {
                        $daysToDepletion = 999;
                    }
                    
                    return (object) [
                        'id' => $item->id,
                        'name' => $item->name,
                        'quantity' => $item->quantity,
                        'minimum_stock' => $item->minimum_stock,
                        'depletion_rate' => min($depletionRate, 100),
                        'days_to_depletion' => $daysToDepletion,
                        'requests_last_30_days' => $requestsLast30Days
                    ];
                })
                ->sortByDesc('depletion_rate')
                ->values();

            // 4. Stock Status Counts
            $criticalItemsCount = $outOfStockCount ?? 0;
            $warningItemsCount = $lowStockCount ?? 0;
            $safeItemsCount = max(0, Item::count() - ($criticalItemsCount + $warningItemsCount));

            // 5. INVENTORY FLOW ITEMS - ALL ITEMS (Last 30 Days)
            $inventoryFlowItems = Item::with(['teamRequests'])->get()
            ->map(function ($item) use ($thirtyDaysAgo) {
                // Get ALL requests in last 30 days
                $allRequests = $item->teamRequests()
                    ->where('created_at', '>=', $thirtyDaysAgo)
                    ->get();
                
                // Group by status
                $pending = $allRequests->where('status', 'pending')->sum('quantity_requested');
                $approved = $allRequests->where('status', 'approved')->sum('quantity_requested');
                $rejected = $allRequests->where('status', 'rejected')->sum('quantity_requested');
                $claimed = $allRequests->where('status', 'claimed')->sum('quantity_requested');
                
                // Get restocked quantity from logs (last 30 days)
                $restocked = 0;
                if (Schema::hasTable('inventory_logs')) {
                    $restocked = InventoryLog::where('item_id', $item->id)
                        ->where('action', 'restock')
                        ->where('created_at', '>=', $thirtyDaysAgo)
                        ->sum('quantity_change');
                }
                
                // Calculate net change
                $netChange = $restocked - $claimed;
                
                // Calculate status based on current stock and activity
                $status = 'Normal';
                if ($item->quantity <= 0) {
                    $status = 'Out of Stock';
                } elseif ($item->quantity <= $item->minimum_stock) {
                    $status = 'Low Stock';
                } elseif ($restocked > 0) {
                    $status = 'Restocked';
                } elseif ($claimed > 0) {
                    $status = 'Claimed';
                } elseif ($approved > 0) {
                    $status = 'Requested';
                }
                
                return (object) [
                    'id' => $item->id,
                    'name' => $item->name,
                    'category' => $item->category ?? 'General',
                    'beginning_quantity' => $item->quantity + $claimed - $restocked, // Reverse calculate
                    'pending_quantity' => $pending,
                    'approved_quantity' => $approved,
                    'rejected_quantity' => $rejected,
                    'claimed_quantity' => $claimed,
                    'restocked_quantity' => $restocked,
                    'ending_quantity' => $item->quantity,
                    'net_change' => $netChange,
                    'quantity' => $item->quantity,
                    'minimum_stock' => $item->minimum_stock,
                    'status' => $status,
                    'has_activity' => ($pending + $approved + $claimed + $restocked) > 0
                ];
            })
            ->filter(function ($item) {
                // Only show items with activity OR low/out of stock
                return $item->has_activity || 
                    $item->quantity <= $item->minimum_stock || 
                    $item->quantity <= 0;
            })
            ->sortByDesc('net_change') // Sort by most activity first
            ->values();

            // 6. Active teams count
            $activeTeams = TeamRequest::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'approved')
                ->distinct('team_id')
                ->count('team_id');

            // 7. Yearly comparison
            $yearlyComparison = $this->generateYearlyComparison($year);
            
            // 8. Total items
            $totalItems = Item::count();
            
            // 9. Current period stats
            $currentPeriodStats = $this->getCurrentPeriodStats($year, $month);

            // Prepare data for view
            $monthName = date('F', mktime(0, 0, 0, $month, 1));
            $period = $monthName . ' ' . $year;
            
            $data = [
                'report' => $report,
                'period' => $period,
                'monthName' => $monthName,
                'year' => $year,
                'month' => $month,
                'generatedAt' => $report->report_generated_at ?? now(),
                'isFinalized' => $report->is_finalized ?? false,
                'reportId' => $report->id ?? 'N/A',
                
                // Analytics data
                'totalRequests' => $report->total_requests,
                'totalRestocked' => $report->total_restocked,
                'totalClaimed' => $report->total_claimed,
                'activeTeams' => $activeTeams,
                'totalItems' => $totalItems,
                'averageActivity' => $this->determineActivityLevel($report->total_requests / $endDate->diffInDays($startDate)),
                
                // Dashboard analytics sections
                'topRequestedItems' => $topRequestedItems,
                'maxRequests' => $maxRequests,
                'mostActiveTeams' => $mostActiveTeams,
                'fastDepletingItems' => $fastDepletingItems,
                'criticalItemsCount' => $criticalItemsCount,
                'warningItemsCount' => $warningItemsCount,
                'safeItemsCount' => $safeItemsCount,
                'inventoryFlowItems' => $inventoryFlowItems,
                'yearlyComparison' => $yearlyComparison,
                'selectedYear' => $year,
            ];

            if ($format === 'pdf') {
                return $this->downloadPdfReport($data);
            } elseif ($format === 'excel') {
                return $this->downloadExcelReport($data);
            } else {
                abort(400, 'Invalid format specified');
            }
            
        } catch (\Exception $e) {
            \Log::error('Error downloading report: ' . $e->getMessage());
            abort(500, 'Failed to generate report: ' . $e->getMessage());
        }
    }

    /**
     * Download PDF report
     */
    private function downloadPdfReport($data)
    {
        // Load the PDF view with all data
        $pdf = \PDF::loadView('reports.pdf.monthly', $data);
        
        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');
        
        $filename = "Inventory_Report_{$data['monthName']}_{$data['year']}.pdf";
        
        return $pdf->download($filename);
    }

    /**
     * Download Excel/HTML report
     */
    private function downloadExcelReport($data)
    {
        // For now, we'll return HTML that can be saved as Excel
        // You can install Laravel Excel package for actual Excel files
        
        $html = view('reports.excel.monthly', $data)->render();
        
        $filename = "Inventory_Report_{$data['monthName']}_{$data['year']}.html";
        
        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Calculate item status
     */
    private function calculateItemStatus($endingQuantity, $minimumStock, $restockedQuantity)
    {
        if ($endingQuantity <= 0) {
            return 'Out of Stock';
        } elseif ($endingQuantity <= $minimumStock) {
            return 'Needs Restock';
        } elseif ($restockedQuantity > 0) {
            return 'Restocked';
        } elseif ($endingQuantity > $minimumStock) {
            return 'In Stock';
        } else {
            return 'In Stock';
        }
    }

    /**
     * Get items for restock
     */
    public function getItemsForRestock()
{
    try {
        // Get items with pending/approved requests count (for reserved stock)
        $items = Item::withCount(['pendingRequests as reserved_quantity_sum' => function($query) {
            $query->select(DB::raw('COALESCE(SUM(quantity_requested), 0)'));
        }])->orderBy('name')->get()
        ->map(function($item) {
            // Total physical quantity
            $totalQuantity = $item->quantity;
            
            // Reserved = pending + approved requests
            $reservedQuantity = $item->reserved_quantity_sum ?? 0;
            
            // Available = total - reserved
            $availableStock = max(0, $totalQuantity - $reservedQuantity);
            
            // For your original fields (keep for compatibility)
            $claimed = 0; // If you still need claimed separately
            
            return [
                'id' => $item->id,
                'name' => $item->name,
                'category' => $item->category ?? 'General',
                'quantity' => $totalQuantity, // Total physical stock
                'available_stock' => $availableStock, // Available after reserved
                'reserved_quantity' => $reservedQuantity, // Pending + Approved
                'claimed_quantity' => $claimed, // For backward compatibility
                'minimum_stock' => $item->minimum_stock,
                'unit' => $item->unit ?? 'units',
                // IMPORTANT: Include pending_quantity_sum for your JavaScript
                'pending_quantity_sum' => $reservedQuantity
            ];
        });
        
        return response()->json([
            'success' => true,
            'items' => $items
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to load items'
        ], 500);
    }
}

    /**
     * Get detailed report data for modal/view
     */
    public function getReportDetails($year, $month)
    {
        try {
            // Get or generate the report
            $report = MonthlyReport::where('year', $year)
                ->where('month', $month)
                ->first();
            
            if (!$report) {
                // Generate on the fly if doesn't exist
                $report = $this->generateAndStoreMonthlyReport($year, $month);
            }
            
            // Get additional detailed data
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();
            
            // Get item-level details
            $itemDetails = Item::select([
                    'items.id',
                    'items.name',
                    'items.category',
                    'items.quantity',
                    'items.minimum_stock',
                    DB::raw('COALESCE(COUNT(team_requests.id), 0) as request_count'),
                    DB::raw('COALESCE(SUM(team_requests.quantity_requested), 0) as total_requested'),
                    DB::raw('(SELECT COALESCE(SUM(quantity_change), 0) FROM inventory_logs WHERE item_id = items.id AND action = "restock" AND created_at BETWEEN "' . $startDate->format('Y-m-d') . '" AND "' . $endDate->format('Y-m-d') . '") as total_restocked')
                ])
                ->leftJoin('team_requests', function($join) use ($startDate, $endDate) {
                    $join->on('items.id', '=', 'team_requests.item_id')
                        ->whereBetween('team_requests.created_at', [$startDate, $endDate])
                        ->where('team_requests.status', 'approved');
                })
                ->groupBy('items.id', 'items.name', 'items.category', 'items.quantity', 'items.minimum_stock')
                ->orderByDesc('request_count')
                ->get();
            
            // Get team-level details
            $teamDetails = Team::select([
                    'teams.id',
                    'teams.name',
                    DB::raw('COUNT(team_requests.id) as request_count'),
                    DB::raw('COALESCE(SUM(team_requests.quantity_requested), 0) as total_quantity')
                ])
                ->leftJoin('team_requests', function($join) use ($startDate, $endDate) {
                    $join->on('teams.id', '=', 'team_requests.team_id')
                        ->whereBetween('team_requests.created_at', [$startDate, $endDate])
                        ->where('team_requests.status', 'approved');
                })
                ->groupBy('teams.id', 'teams.name')
                ->orderByDesc('request_count')
                ->get();
            
            // Get activity timeline
            $activityTimeline = [];
            $currentDay = $startDate->copy();
            while ($currentDay <= $endDate) {
                $day = $currentDay->format('Y-m-d');
                $activityTimeline[$day] = [
                    'date' => $currentDay->format('M j'),
                    'requests' => TeamRequest::whereDate('created_at', $day)
                        ->where('status', 'approved')
                        ->count(),
                    'restocked' => 0
                ];
                
                // Check if inventory_logs table exists
                if (Schema::hasTable('inventory_logs')) {
                    $activityTimeline[$day]['restocked'] = InventoryLog::whereDate('created_at', $day)
                        ->where('action', 'restock')
                        ->sum('quantity_change');
                }
                
                $currentDay->addDay();
            }
            
            // Get status summary - FIXED: Use available stock calculation
            $allItems = Item::with(['requests' => function($query) {
                $query->where('status', 'approved');
            }])->get();
            
            $criticalItems = 0;
            $lowStockItems = 0;
            $inStockItems = 0;
            
            foreach ($allItems as $item) {
                $approvedQuantity = $item->requests->sum('quantity_requested');
                $availableStock = max(0, $item->quantity - $approvedQuantity);
                
                if ($availableStock <= 0) {
                    $criticalItems++;
                } elseif ($availableStock > 0 && $availableStock <= $item->minimum_stock) {
                    $lowStockItems++;
                } else {
                    $inStockItems++;
                }
            }
            
            $statusSummary = [
                'critical_items' => $criticalItems,
                'low_stock_items' => $lowStockItems,
                'in_stock_items' => $inStockItems,
            ];
            
            $data = [
                'success' => true,
                'report' => $report,
                'item_details' => $itemDetails,
                'team_details' => $teamDetails,
                'activity_timeline' => array_values($activityTimeline),
                'status_summary' => $statusSummary,
                'period' => Carbon::create($year, $month, 1)->format('F Y'),
                'days_in_month' => $startDate->daysInMonth
            ];
            
            return response()->json($data);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load report details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get ranked items by priority
     */
    private function getRankedItems()
    {
        $items = Item::with(['requests' => function($query) {
            $query->whereIn('status', ['pending', 'approved']);
        }])->get();
        
        // Calculate priority scores for each item
        $rankedItems = $items->map(function($item) {
            // Calculate consumption rate for current month
            $currentMonth = now()->startOfMonth();
            $claimedThisMonth = TeamRequest::where('item_id', $item->id)
                ->where('status', 'claimed')
                ->where('created_at', '>=', $currentMonth)
                ->sum('quantity_requested');
            
            $consumptionRate = $item->quantity > 0 ? 
                ($claimedThisMonth / $item->quantity) * 100 : 100;
            
            // Calculate reserved percentage
            $reservedPercentage = $item->quantity > 0 ? 
                ($item->reserved_stock / $item->quantity) * 100 : 100;
            
            // Get pending requests count
            $pendingRequests = $item->requests->where('status', 'pending')->sum('quantity_requested');
            
            // Calculate priority score manually (same logic as model)
            $score = 0;
            
            // Stock status
            if ($item->quantity <= 0) {
                $score += 100 * 0.5; // Out of stock
            } elseif ($item->quantity <= $item->minimum_stock) {
                $score += 70 * 0.5; // Low stock
            } elseif ($item->available_stock <= $item->minimum_stock) {
                $score += 60 * 0.5; // Available stock low
            } else {
                $score += 10 * 0.5; // In stock
            }
            
            // Consumption rate
            $score += min($consumptionRate, 100) * 0.25;
            
            // Reserved stock
            $score += min($reservedPercentage, 100) * 0.15;
            
            // Pending requests
            $score += ($pendingRequests > 0 ? 50 : 0) * 0.1;
            
            $score = min($score, 100);
            
            // Determine priority level
            $priorityLevel = match(true) {
                $score >= 80 => 'Critical',
                $score >= 60 => 'High',
                $score >= 40 => 'Medium',
                $score >= 20 => 'Low',
                default => 'Normal'
            };
            
            return (object) [
                'id' => $item->id,
                'name' => $item->name,
                'quantity' => $item->quantity,
                'available_stock' => $item->available_stock,
                'minimum_stock' => $item->minimum_stock,
                'reserved_stock' => $item->reserved_stock,
                'consumption_rate' => min($consumptionRate, 100),
                'reserved_percentage' => min($reservedPercentage, 100),
                'pending_requests' => $pendingRequests,
                'priority_score' => round($score, 1),
                'priority_level' => $priorityLevel,
                'priority_color' => match($priorityLevel) {
                    'Critical' => 'bg-red-100 text-red-800 border-red-200',
                    'High' => 'bg-orange-100 text-orange-800 border-orange-200',
                    'Medium' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                    'Low' => 'bg-blue-100 text-blue-800 border-blue-200',
                    default => 'bg-gray-100 text-gray-800 border-gray-200'
                },
                'priority_icon' => match($priorityLevel) {
                    'Critical' => 'fas fa-exclamation-triangle',
                    'High' => 'fas fa-exclamation-circle',
                    'Medium' => 'fas fa-arrow-up',
                    'Low' => 'fas fa-arrow-right',
                    default => 'fas fa-check'
                }
            ];
        })->sortByDesc('priority_score')->values();
        
        return $rankedItems;
    }
}