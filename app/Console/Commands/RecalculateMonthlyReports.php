<?php

namespace App\Console\Commands;

use App\Models\MonthlyReport;
use App\Models\TeamRequest;
use App\Models\InventoryLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RecalculateMonthlyReports extends Command
{
    protected $signature = 'reports:recalculate {year? : Year to recalculate}';
    protected $description = 'Recalculate monthly reports with correct total requests';

    public function handle()
    {
        $year = $this->argument('year') ?? date('Y');
        
        $this->info("Recalculating monthly reports for {$year}...");
        
        for ($month = 1; $month <= 12; $month++) {
            // Count ALL requests for this month
            $totalRequests = TeamRequest::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();
            
            $totalRestocked = InventoryLog::where('action', 'restock')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('quantity_change');
            
            $totalClaimed = TeamRequest::where('status', 'claimed')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('quantity_requested');
            
            // Update the report
            MonthlyReport::updateOrCreate(
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
            
            $this->line("Month {$month}: {$totalRequests} total requests");
        }
        
        $this->info("Monthly reports for {$year} have been recalculated!");
    }
}