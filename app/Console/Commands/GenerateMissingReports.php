<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MonthlyReport;
use App\Http\Controllers\DashboardController;
use Carbon\Carbon;

class GenerateMissingReports extends Command
{
    protected $signature = 'reports:generate-missing {year?} {--all}';
    protected $description = 'Generate missing monthly reports for specified year or all years';

    public function handle()
    {
        $years = [];
        
        if ($this->option('all')) {
            // Generate for all years from 2025 to current year
            $currentYear = date('Y');
            for ($year = 2025; $year <= $currentYear; $year++) {
                $years[] = $year;
            }
        } else {
            $year = $this->argument('year') ?: date('Y');
            $years = [$year];
        }
        
        $dashboardController = new DashboardController();
        $totalGenerated = 0;
        
        foreach ($years as $year) {
            $this->info("Generating missing reports for year: {$year}");
            
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
                    $this->info("Generating report for {$year}-{$month}...");
                    
                    try {
                        $report = $dashboardController->generateAndStoreMonthlyReport($year, $month);
                        $totalGenerated++;
                        $this->info("✓ Created report for " . date('F', mktime(0, 0, 0, $month, 1)) . " {$year}");
                    } catch (\Exception $e) {
                        $this->error("Failed to generate report for {$year}-{$month}: " . $e->getMessage());
                    }
                } else {
                    $this->line("Report for {$year}-{$month} already exists");
                }
            }
        }
        
        $this->info("✅ Done! Generated {$totalGenerated} missing reports.");
        
        return 0;
    }
}