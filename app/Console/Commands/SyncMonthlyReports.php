<?php

namespace App\Console\Commands;

use App\Models\MonthlyReport;
use App\Models\TeamRequest;
use App\Models\InventoryLog;
use Illuminate\Console\Command;

class SyncMonthlyReports extends Command
{
    protected $signature = 'reports:sync {year?} {month?} {--all}';
    protected $description = 'Sync monthly reports with actual data';

    public function handle()
    {
        $year = $this->argument('year') ?? date('Y');
        $month = $this->argument('month');
        
        if ($this->option('all')) {
            // Sync all years
            $this->syncAllYears();
        } elseif ($month) {
            $this->syncMonth($year, $month);
        } else {
            // Sync all months of the specified year
            for ($m = 1; $m <= 12; $m++) {
                $this->syncMonth($year, $m);
            }
        }
        
        $this->info('✅ Monthly reports synced successfully!');
    }
    
    private function syncMonth($year, $month)
    {
        $totalRequests = TeamRequest::where('status', 'approved')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();
        
        $totalRestocked = 0;
        if (\Illuminate\Support\Facades\Schema::hasTable('inventory_logs')) {
            $totalRestocked = InventoryLog::where('action', 'restock')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('quantity_change');
        }
        
        $totalClaimed = TeamRequest::where('status', 'claimed')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('quantity_requested');
        
        $report = MonthlyReport::updateOrCreate(
            [
                'year' => $year,
                'month' => $month,
            ],
            [
                'total_requests' => $totalRequests,
                'total_restocked' => $totalRestocked,
                'total_claimed' => $totalClaimed,
                'updated_at' => now(),
            ]
        );
        
        $this->info("Synced {$year}-{$month}: {$totalRequests} requests, {$totalRestocked} restocked, {$totalClaimed} claimed");
        
        return $report;
    }
    
    private function syncAllYears()
    {
        // Get all unique years from team requests
        $years = TeamRequest::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->pluck('year');
        
        foreach ($years as $year) {
            $this->info("Syncing year {$year}...");
            for ($month = 1; $month <= 12; $month++) {
                $this->syncMonth($year, $month);
            }
        }
    }
}