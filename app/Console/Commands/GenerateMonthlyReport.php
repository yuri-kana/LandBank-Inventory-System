<?php

namespace App\Console\Commands;

use App\Models\MonthlyReport;
use App\Models\TeamRequest;
use App\Models\Item;
use App\Models\InventoryLog;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateMonthlyReport extends Command
{
    protected $signature = 'reports:generate-monthly 
                            {--year= : The year to generate report for (default: previous month\'s year)}
                            {--month= : The month to generate report for (default: previous month)}
                            {--finalize : Mark the report as finalized}
                            {--all : Generate reports for all months of the year}
                            {--yearly : Generate yearly report from monthly data}';

    protected $description = 'Generate monthly inventory reports';

    public function handle()
    {
        // Generate for specific month or all months
        if ($this->option('all')) {
            $this->generateAllMonthsForYear();
            return;
        }

        if ($this->option('yearly')) {
            $this->generateYearlyReport();
            return;
        }

        // Generate single month report
        $year = $this->option('year') ?? Carbon::now()->subMonth()->year;
        $month = $this->option('month') ?? Carbon::now()->subMonth()->month;
        $finalize = $this->option('finalize');

        $this->generateSingleMonthReport($year, $month, $finalize);
    }

    private function generateSingleMonthReport($year, $month, $finalize = false)
    {
        $this->info("Generating report for {$year}-{$month}...");

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        // Calculate beginning stock value (sum of all items' quantity at start of month)
        $beginningStockValue = $this->calculateInventoryValueAtDate($startDate);

        // Calculate total approved requests
        $totalRequests = TeamRequest::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'approved')
            ->count();

        // Calculate total restocked
        $totalRestocked = InventoryLog::where('action', 'restock')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('quantity_change');

        // Calculate total claimed
        $totalClaimed = TeamRequest::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'claimed')
            ->sum('quantity_requested');

        // Calculate ending stock value
        $endingStockValue = $this->calculateInventoryValueAtDate($endDate);

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
            ->limit(10)
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

        // Get fast depleting items (items that went below minimum stock)
        $fastDepletingItems = Item::whereHas('inventoryLogs', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->with(['inventoryLogs' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->get()
            ->filter(function($item) {
                return $item->quantity <= $item->minimum_stock;
            })
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'current_quantity' => $item->quantity,
                    'minimum_stock' => $item->minimum_stock,
                    'depletion_rate' => $item->quantity > 0 ? 
                        (($item->minimum_stock - $item->quantity) / $item->minimum_stock) * 100 : 100
                ];
            })
            ->sortByDesc('depletion_rate')
            ->values()
            ->take(10)
            ->toArray();

        // Get active teams for the month
        $activeTeams = Team::whereHas('requests', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'approved');
            })
            ->withCount(['requests' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'approved');
            }])
            ->orderByDesc('requests_count')
            ->limit(10)
            ->get()
            ->map(function($team) {
                return [
                    'id' => $team->id,
                    'name' => $team->name,
                    'request_count' => $team->requests_count
                ];
            })
            ->toArray();

        // Create or update the monthly report
        $report = MonthlyReport::updateOrCreate(
            [
                'year' => $year,
                'month' => $month,
            ],
            [
                'beginning_stock_value' => $beginningStockValue,
                'total_requests' => $totalRequests,
                'total_restocked' => $totalRestocked,
                'total_claimed' => $totalClaimed,
                'ending_stock_value' => $endingStockValue,
                'most_requested_items' => json_encode([
                    'items' => $mostRequestedItems,
                    'active_teams' => $activeTeams
                ]),
                'fast_depleting_items' => json_encode($fastDepletingItems),
                'report_generated_at' => now(),
                'is_finalized' => $finalize,
            ]
        );

        $this->info("✅ Report generated successfully!");
        $this->info("📊 Summary:");
        $this->info("   - Total Requests: {$totalRequests}");
        $this->info("   - Total Restocked: {$totalRestocked}");
        $this->info("   - Total Claimed: {$totalClaimed}");
        $this->info("   - Beginning Stock Value: {$beginningStockValue}");
        $this->info("   - Ending Stock Value: {$endingStockValue}");
        
        if ($finalize) {
            $this->info("   - Status: ✅ Finalized");
        }
    }

    private function generateAllMonthsForYear()
    {
        $year = $this->option('year') ?? Carbon::now()->year;
        
        $this->info("Generating reports for all months of {$year}...");
        
        for ($month = 1; $month <= 12; $month++) {
            // Only generate past months
            if ($year == Carbon::now()->year && $month > Carbon::now()->month) {
                break;
            }
            
            $this->generateSingleMonthReport($year, $month, true);
        }
        
        $this->info("✅ All monthly reports for {$year} generated!");
    }

    private function generateYearlyReport()
    {
        $year = $this->option('year') ?? Carbon::now()->subYear()->year;
        
        $this->info("Generating yearly report for {$year}...");
        
        // Get all monthly reports for the year
        $monthlyReports = MonthlyReport::where('year', $year)
            ->where('is_finalized', true)
            ->get();
        
        if ($monthlyReports->isEmpty()) {
            $this->error("No finalized monthly reports found for {$year}. Generate monthly reports first.");
            return;
        }
        
        $yearlyData = [
            'year' => $year,
            'total_requests' => $monthlyReports->sum('total_requests'),
            'total_restocked' => $monthlyReports->sum('total_restocked'),
            'total_claimed' => $monthlyReports->sum('total_claimed'),
            'average_monthly_requests' => round($monthlyReports->avg('total_requests'), 2),
            'peak_month' => $monthlyReports->sortByDesc('total_requests')->first()->month,
            'monthly_breakdown' => $monthlyReports->map(function($report) {
                return [
                    'month' => $report->month,
                    'month_name' => $report->month_name,
                    'total_requests' => $report->total_requests,
                    'total_restocked' => $report->total_restocked,
                    'total_claimed' => $report->total_claimed,
                ];
            })->toArray(),
            'generated_at' => now(),
        ];
        
        // Store yearly report in database (you can create a yearly_reports table if needed)
        // For now, we'll just output the data
        
        $this->info("✅ Yearly report generated for {$year}!");
        $this->info(json_encode($yearlyData, JSON_PRETTY_PRINT));
    }

    private function calculateInventoryValueAtDate($date)
    {
        // For simplicity, we'll use quantity as value
        // If you have prices, use: DB::raw('SUM(quantity * price)')
        return Item::where('created_at', '<=', $date)
            ->sum('quantity');
    }
}