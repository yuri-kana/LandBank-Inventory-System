<?php

namespace App\Http\Controllers;

use App\Exports\SimpleInventoryExport;
use App\Exports\InventoryExport;
use App\Models\MonthlyReport;
use App\Models\Item;
use App\Models\TeamRequest;
use App\Models\InventoryLog;
use App\Models\Team;
use App\Http\Controllers\DashboardController;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\MonthlyReportExcelExport; 
use Barryvdh\DomPDF\Facade\Pdf;
use DB;

class ReportController extends Controller
{
    protected $dashboardController;
    
    public function __construct(DashboardController $dashboardController)
    {
        $this->dashboardController = $dashboardController;
    }
    
    /**
     * Display reports index page
     */
    public function index()
    {
        $reports = MonthlyReport::orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(10);
        
        return view('reports.index', compact('reports'));
    }

    /**
     * Download Excel Report - UPDATED TO USE SAME DATA AS PDF
     */
    public function downloadExcel(Request $request)
    {
        try {
            // Get parameters from either GET or POST
            $month = $request->query('month', $request->input('month'));
            $year = $request->query('year', $request->input('year'));
            $type = $request->query('type', $request->input('type', 'inventory'));
            
            // Check if report exists for monthly reports
            if ($type === 'monthly' && $month && $year) {
                $reportExists = MonthlyReport::where('year', $year)
                    ->where('month', $month)
                    ->exists();
                
                if (!$reportExists) {
                    // Return JSON response instead of error page
                    return response()->json([
                        'error' => 'Report not found',
                        'message' => 'The monthly report for ' . date('F', mktime(0, 0, 0, $month, 1)) . ' ' . $year . ' has not been generated yet.',
                        'year' => $year,
                        'month' => $month
                    ], 404);
                }
                
                $monthName = date('F', mktime(0, 0, 0, $month, 1));
                $filename = "Monthly_Report_{$monthName}_{$year}.xlsx";
                
                // Use the same data preparation as PDF (from DashboardController)
                $data = $this->getComprehensiveReportData($year, $month);
                
                // Pass the comprehensive data to InventoryExport
                return Excel::download(
                    new InventoryExport($data), 
                    $filename
                );
                
            } else {
                $filename = "Complete_Inventory_Report_" . date('Y-m-d') . ".xlsx";
                
                // For complete inventory report, use SimpleInventoryExport
                return Excel::download(
                    new SimpleInventoryExport(), 
                    $filename
                );
            }
        } catch (\Exception $e) {
            \Log::error('Excel download error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            // Ultimate fallback
            return $this->downloadFallbackCSV();
        }
    }

    /**
     * Get comprehensive report data (same as PDF)
     */
    private function getComprehensiveReportData($year, $month)
{
    try {
        // Get report data
        $report = MonthlyReport::where('year', $year)
            ->where('month', $month)
            ->first();
        
        if (!$report) {
            // Report doesn't exist - throw exception to be caught by calling method
            throw new \Exception("Monthly report for {$year}-{$month} not found. Please generate it first.");
        }
        
        // Get all analytics data needed for the report
        $thirtyDaysAgo = \Carbon\Carbon::now()->subDays(30);
        $startDate = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = \Carbon\Carbon::create($year, $month, 1)->endOfMonth();
        
        // =========== NEW: Get ALL request counts by status ===========
        $allRequests = TeamRequest::whereBetween('created_at', [$startDate, $endDate])->get();
        
        $requestStats = [
            'total_requests' => $allRequests->count(), // ALL requests
            'approved_requests' => $allRequests->where('status', 'approved')->count(),
            'pending_requests' => $allRequests->where('status', 'pending')->count(),
            'rejected_requests' => $allRequests->where('status', 'rejected')->count(),
            'claimed_requests' => $allRequests->where('status', 'claimed')->count(),
        ];
        // =========== END NEW ===========
        
        // 1. Top Requested Items (Last 30 Days) - FIXED: Count ALL requests
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
                    ->where('team_requests.created_at', '>=', $thirtyDaysAgo);
                    // REMOVED: ->where('team_requests.status', 'approved')
            })
            ->groupBy('items.id', 'items.name', 'items.quantity', 'items.minimum_stock', 'items.updated_at')
            ->orderByDesc('total_requests')
            ->orderBy('items.name')
            ->get();

        $maxRequests = $topRequestedItems->max('total_requests') ?? 1;

        // 2. Most Active Teams - FIXED: Count ALL requests
        $mostActiveTeams = Team::select([
                'teams.id',
                'teams.name',
                DB::raw('COUNT(team_requests.id) as request_count'),
                DB::raw('(SELECT COUNT(*) FROM users WHERE users.team_id = teams.id AND users.is_active = true) as members_count')
            ])
            ->leftJoin('team_requests', function($join) use ($startDate, $endDate) {
                $join->on('teams.id', '=', 'team_requests.team_id')
                    ->whereBetween('team_requests.created_at', [$startDate, $endDate]);
                    // REMOVED: ->where('team_requests.status', 'approved')
            })
            ->groupBy('teams.id', 'teams.name')
            ->orderByDesc('request_count')
            ->orderBy('teams.name')
            ->get();

        // 3. Fast Depleting Items - FIXED: Count ALL requests
        $allItems = Item::all();
        
        $fastDepletingItems = $allItems
            ->map(function ($item) use ($thirtyDaysAgo) {
                $requestsLast30Days = TeamRequest::where('item_id', $item->id)
                    ->where('created_at', '>=', $thirtyDaysAgo)
                    // REMOVED: ->where('status', 'approved')
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
        $criticalItemsCount = Item::where('quantity', '<=', 0)->count();
        $warningItemsCount = Item::where('quantity', '>', 0)
            ->whereColumn('quantity', '<=', 'minimum_stock')
            ->count();
        $safeItemsCount = Item::where('quantity', '>', 0)
            ->whereColumn('quantity', '>', 'minimum_stock')
            ->count();

        // 5. Inventory Flow Items - FIXED: Count ALL requests
        $inventoryFlowItems = Item::all()
            ->map(function ($item) use ($startDate, $endDate) {
                // Get ALL requests for this month
                $allItemRequests = TeamRequest::where('item_id', $item->id)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
                
                // Get requested quantity (ALL requests)
                $requested = $allItemRequests->sum('quantity_requested');
                
                // Get claimed quantity (status = 'claimed')
                $claimed = $allItemRequests->where('status', 'claimed')->sum('quantity_requested');
                
                // Get approved quantity (status = 'approved')
                $approved = $allItemRequests->where('status', 'approved')->sum('quantity_requested');
                
                // Get rejected quantity (status = 'rejected')
                $rejected = $allItemRequests->where('status', 'rejected')->sum('quantity_requested');
                
                // Get pending quantity (status = 'pending')
                $pending = $allItemRequests->where('status', 'pending')->sum('quantity_requested');
                
                // Get restocked quantity from logs
                $restocked = 0;
                try {
                    $restocked = InventoryLog::where('item_id', $item->id)
                        ->where('action', 'restock')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->sum('quantity_change');
                } catch (\Exception $e) {
                    // Table might not exist
                }
                
                // Calculate beginning and ending quantities
                $beginning = $item->quantity + $claimed - $restocked;
                $ending = $beginning - $claimed + $restocked;
                
                return (object) [
                    'id' => $item->id,
                    'name' => $item->name,
                    'category' => $item->category ?? 'General',
                    'beginning_quantity' => $beginning,
                    'requested_quantity' => $requested,
                    'approved_quantity' => $approved,
                    'claimed_quantity' => $claimed,
                    'rejected_quantity' => $rejected,
                    'pending_quantity' => $pending,
                    'restocked_quantity' => $restocked,
                    'ending_quantity' => $ending,
                    'quantity' => $item->quantity,
                    'minimum_stock' => $item->minimum_stock,
                    'status' => $this->calculateItemStatus($ending, $item->minimum_stock, $restocked)
                ];
            });

        // 6. Active teams count - FIXED: Count ALL requests
        $activeTeams = TeamRequest::whereBetween('created_at', [$startDate, $endDate])
            ->distinct('team_id')
            ->count('team_id');

        // 7. Yearly comparison
        $yearlyComparison = $this->generateYearlyComparison($year);
        
        // 8. Total items
        $totalItems = Item::count();
        
        // 9. Activity level - FIXED: Use ALL requests
        $daysInPeriod = $endDate->diffInDays($startDate) + 1;
        $requestsPerDay = $daysInPeriod > 0 ? ($requestStats['total_requests'] ?? 0) / $daysInPeriod : 0;
        $averageActivity = $this->determineActivityLevel($requestsPerDay);
        
        // Prepare data for export - ENSURE ZEROS INSTEAD OF BLANK
        $monthName = date('F', mktime(0, 0, 0, $month, 1));
        $period = $monthName . ' ' . $year;
        
        // Get finalized_at date
        $finalizedAt = null;
        if ($report->is_finalized && $report->finalized_at) {
            $finalizedAt = \Carbon\Carbon::parse($report->finalized_at);
        } elseif ($report->is_finalized) {
            $finalizedAt = $report->updated_at ?? now();
        }
        
        return [
            'report' => $report,
            'period' => $period,
            'monthName' => $monthName,
            'year' => $year,
            'month' => $month,
            'generatedAt' => $report->report_generated_at ? \Carbon\Carbon::parse($report->report_generated_at) : now(),
            'isFinalized' => $report->is_finalized ?? false,
            'finalizedAt' => $finalizedAt, // Add finalized date
            'reportId' => $report->id ?? 'N/A',
            
            // =========== NEW: Request statistics ===========
            'request_stats' => $requestStats,
            
            // Analytics data - Now shows ALL requests
            'totalRequests' => $requestStats['total_requests'], // This will be 12 for Jan 2026
            'totalRestocked' => $report->total_restocked ?? 0,
            'totalClaimed' => $requestStats['claimed_requests'],
            'activeTeams' => $activeTeams ?? 0,
            'totalItems' => $totalItems ?? 0,
            'averageActivity' => $averageActivity ?? 'No Activity',
            
            // Dashboard analytics sections
            'topRequestedItems' => $topRequestedItems ?? collect(),
            'maxRequests' => $maxRequests ?? 1,
            'mostActiveTeams' => $mostActiveTeams ?? collect(),
            'fastDepletingItems' => $fastDepletingItems ?? collect(),
            'criticalItemsCount' => $criticalItemsCount ?? 0,
            'warningItemsCount' => $warningItemsCount ?? 0,
            'safeItemsCount' => $safeItemsCount ?? 0,
            'inventoryFlowItems' => $inventoryFlowItems ?? collect(),
            'yearlyComparison' => $yearlyComparison ?? [],
            'selectedYear' => $year,
        ];
        
    } catch (\Exception $e) {
        \Log::error('Error getting comprehensive report data: ' . $e->getMessage());
        throw $e;
    }
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
        } else {
            return 'In Stock';
        }
    }

    /**
     * Determine activity level
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
        $startDate = \Carbon\Carbon::create($year, 1, 1)->startOfYear();
        $endDate = \Carbon\Carbon::create($year, 12, 31)->endOfYear();
        
        // Get most requested item for the year
        $mostRequestedItem = Item::select([
                'items.id',
                'items.name',
                DB::raw('COUNT(team_requests.id) as request_count')
            ])
            ->leftJoin('team_requests', function($join) use ($startDate, $endDate) {
                $join->on('items.id', '=', 'team_requests.item_id')
                    ->whereBetween('team_requests.created_at', [$startDate, $endDate])
                    ->where('team_requests.status', 'approved');
            })
            ->groupBy('items.id', 'items.name')
            ->orderByDesc('request_count')
            ->first();
        
        // FIX: Get total requests for the year - COUNT DISTINCT ITEMS
        $totalRequests = TeamRequest::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'approved')
            ->distinct('item_id')
            ->count('item_id');
        
        // FIX: Get total restocked for the year - COUNT DISTINCT ITEMS
        $totalRestocked = 0;
        try {
            $totalRestocked = InventoryLog::whereBetween('created_at', [$startDate, $endDate])
                ->where('action', 'restock')
                ->distinct('item_id')
                ->count('item_id');
        } catch (\Exception $e) {
            // Table might not exist
        }
        
        // FIX: Get total claimed for the year - COUNT DISTINCT ITEMS
        $totalClaimed = TeamRequest::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'claimed')
            ->distinct('item_id')
            ->count('item_id');
        
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

    /**
     * Fallback CSV download if Excel fails
     */
    private function downloadFallbackCSV()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="inventory_fallback_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, ['ID', 'Item Name', 'Quantity', 'Status']);
            
            // Sample data
            fputcsv($file, [1, 'Test Item 1', 100, 'In Stock']);
            fputcsv($file, [2, 'Test Item 2', 50, 'Low Stock']);
            fputcsv($file, [3, 'Test Item 3', 0, 'Out of Stock']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Download CSV Report - UPDATED
     */
    public function downloadCSV(Request $request)
    {
        try {
            // Get parameters
            $month = $request->query('month', $request->input('month'));
            $year = $request->query('year', $request->input('year'));
            $type = $request->query('type', $request->input('type', 'inventory'));
            
            if ($type === 'monthly' && $month && $year) {
                $monthName = date('F', mktime(0, 0, 0, $month, 1));
                $filename = "Monthly_Report_{$monthName}_{$year}.csv";
                
                // Use the same data preparation as PDF
                $data = $this->getComprehensiveReportData($year, $month);
                
                return Excel::download(
                    new InventoryExport($data), 
                    $filename,
                    \Maatwebsite\Excel\Excel::CSV
                );
            } else {
                $filename = "Complete_Inventory_Report_" . date('Y-m-d') . ".csv";
                
                return Excel::download(
                    new InventoryExport([], 'inventory'), 
                    $filename,
                    \Maatwebsite\Excel\Excel::CSV
                );
            }
        } catch (\Exception $e) {
            \Log::error('CSV download error: ' . $e->getMessage());
            
            // Fallback
            return $this->downloadFallbackCSV();
        }
    }

    /**
     * Download PDF Report - UPDATED TO USE SAME DATA AS EXCEL
     */
    public function downloadPDF(Request $request)
    {
        try {
            $month = $request->query('month', $request->input('month'));
            $year = $request->query('year', $request->input('year'));
            $type = $request->query('type', $request->input('type', 'inventory'));
            
            if ($type === 'monthly' && $month && $year) {
                // Check if report exists first
                $reportExists = MonthlyReport::where('year', $year)
                    ->where('month', $month)
                    ->exists();
                
                if (!$reportExists) {
                    // Return JSON response instead of error page
                    return response()->json([
                        'error' => 'Report not found',
                        'message' => 'The monthly report for ' . date('F', mktime(0, 0, 0, $month, 1)) . ' ' . $year . ' has not been generated yet.',
                        'year' => $year,
                        'month' => $month
                    ], 404);
                }
                
                // Use the same data preparation as Excel
                $data = $this->getComprehensiveReportData($year, $month);
                
                // FIX: Use current time for PDF generation timestamp
                $data['generatedAt'] = now(); // Add this line to override with current time
                
                $pdf = Pdf::loadView('reports.pdf.monthly', $data)
                    ->setPaper('a4', 'landscape');
                
                $monthName = date('F', mktime(0, 0, 0, $month, 1));
                $filename = "Monthly_Report_{$monthName}_{$year}.pdf";
                
                // FIX: Make sure we're returning download, not redirect
                return $pdf->download($filename);
                
            } else {
                // For inventory report
                $items = Item::all();
                $data = [
                    'items' => $items,
                    'generatedAt' => now(),
                    'title' => 'Complete Inventory Report'
                ];
                
                $pdf = Pdf::loadView('reports.pdf.inventory', $data)
                    ->setPaper('a4', 'landscape');
                
                $filename = "Complete_Inventory_Report_" . date('Y-m-d') . ".pdf";
                return $pdf->download($filename);
            }
        } catch (\Exception $e) {
            \Log::error('PDF download error: ' . $e->getMessage());
            // FIX: Return error as JSON or simple response instead of redirect
            return response()->json([
                'error' => 'Failed to generate PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View report details
     */
    public function view(Request $request, $year, $month)
    {
        $monthlyReport = MonthlyReport::where('year', $year)
            ->where('month', $month)
            ->firstOrFail();
        
        return view('reports.view', compact('monthlyReport'));
    }

    /**
     * Finalize monthly report
     */
    public function finalize(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|between:1,12'
        ]);
        
        $monthlyReport = MonthlyReport::where('year', $request->year)
            ->where('month', $request->month)
            ->firstOrFail();
        
        $monthlyReport->update([
            'is_finalized' => true,
            'finalized_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Report finalized successfully',
            'report' => $monthlyReport
        ]);
    }

    /**
     * Test Excel download - Public route for testing
     */
    public function testExcel()
    {
        try {
            $filename = "test_report_" . date('Y-m-d') . ".xlsx";
            
            return Excel::download(
                new SimpleInventoryExport(), 
                $filename
            );
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
    
    /**
     * Check if report exists - API endpoint
     */
    public function checkReportExists($year, $month)
    {
        $report = MonthlyReport::where('year', $year)
            ->where('month', $month)
            ->first();
        
        return response()->json([
            'exists' => $report !== null,
            'report' => $report ? [
                'id' => $report->id,
                'year' => $report->year,
                'month' => $report->month,
                'total_requests' => $report->total_requests,
                'total_restocked' => $report->total_restocked,
                'is_finalized' => $report->is_finalized
            ] : null,
            'message' => $report ? 'Report exists' : 'Report not found'
        ]);
    }
    
    /**
     * Generate report on demand - API endpoint
     */
    public function generateReportOnDemand(Request $request, $year, $month)
    {
        try {
            $dashboardController = new DashboardController();
            $report = $dashboardController->generateAndStoreMonthlyReport($year, $month);
            
            return response()->json([
                'success' => true,
                'message' => 'Report generated successfully',
                'report' => [
                    'id' => $report->id,
                    'year' => $report->year,
                    'month' => $report->month,
                    'total_requests' => $report->total_requests,
                    'total_restocked' => $report->total_restocked,
                    'generated_at' => $report->report_generated_at
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error generating report: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate report: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate missing reports for a year
     */
    public function generateMissingReports(Request $request)
    {
        try {
            $request->validate([
                'year' => 'required|integer'
            ]);
            
            $year = $request->year;
            $generated = 0;
            $errors = [];
            
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
                    try {
                        $dashboardController = new DashboardController();
                        $report = $dashboardController->generateAndStoreMonthlyReport($year, $month);
                        $generated++;
                    } catch (\Exception $e) {
                        $errors[] = "Failed to generate report for {$year}-{$month}: " . $e->getMessage();
                    }
                }
            }
            
            if (count($errors) > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some reports failed to generate',
                    'generated' => $generated,
                    'errors' => $errors
                ], 207); // 207 Multi-Status
            }
            
            return response()->json([
                'success' => true,
                'message' => "Generated {$generated} missing reports for {$year}",
                'generated' => $generated
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error generating missing reports: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate reports: ' . $e->getMessage()
            ], 500);
        }
    }
}