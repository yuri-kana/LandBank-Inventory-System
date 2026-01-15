<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // ... existing commands
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Sync current month's report every hour to ensure data is fresh
        $schedule->call(function () {
            $year = date('Y');
            $month = date('n');
            
            // Calculate fresh data
            $totalRequests = \App\Models\TeamRequest::where('status', 'approved')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();
            
            $totalRestocked = 0;
            if (\Illuminate\Support\Facades\Schema::hasTable('inventory_logs')) {
                $totalRestocked = \App\Models\InventoryLog::where('action', 'restock')
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->sum('quantity_change');
            }
            
            $totalClaimed = \App\Models\TeamRequest::where('status', 'claimed')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('quantity_requested');
            
            // Update the report
            \App\Models\MonthlyReport::updateOrCreate(
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
            
            \Log::info("Monthly report for {$year}-{$month} synced automatically.");
        })->hourly();
        
        // Also sync all reports daily at midnight
        $schedule->call(function () {
            // Sync current year's months
            $year = date('Y');
            for ($month = 1; $month <= 12; $month++) {
                $totalRequests = \App\Models\TeamRequest::where('status', 'approved')
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->count();
                
                $totalRestocked = 0;
                if (\Illuminate\Support\Facades\Schema::hasTable('inventory_logs')) {
                    $totalRestocked = \App\Models\InventoryLog::where('action', 'restock')
                        ->whereYear('created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->sum('quantity_change');
                }
                
                $totalClaimed = \App\Models\TeamRequest::where('status', 'claimed')
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->sum('quantity_requested');
                
                \App\Models\MonthlyReport::updateOrCreate(
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
            }
            \Log::info("All monthly reports for {$year} synced.");
        })->dailyAt('00:00');

        // Generate monthly report on the 1st day of each month at 2:00 AM for previous month
        $schedule->command('reports:generate-monthly --finalize')
            ->monthlyOn(1, '02:00')
            ->timezone('Asia/Manila');

        // Generate yearly report on January 1st at 3:00 AM
        $schedule->command('reports:generate-monthly --yearly')
            ->yearlyOn(1, 1, '03:00')
            ->timezone('Asia/Manila');

        // Optional: Backup reports daily at midnight
        $schedule->command('backup:run')->dailyAt('00:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}