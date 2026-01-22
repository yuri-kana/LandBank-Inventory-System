<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MonthlyReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GenerateYearlyReports extends Command
{
    protected $signature = 'reports:generate-yearly {year?}';
    protected $description = 'Generate monthly reports for a specific year';

    public function handle()
    {
        $year = $this->argument('year') ?? date('Y');
        $currentYear = date('Y');
        $currentMonth = date('n');
        
        $this->info("Generating monthly reports for {$year}...");
        
        // Determine max month
        $maxMonth = 12;
        if ($year == $currentYear) {
            $maxMonth = $currentMonth;
        } elseif ($year == $currentYear + 1) {
            $maxMonth = min($currentMonth, 12);
        }
        
        $generatedCount = 0;
        
        for ($month = 1; $month <= $maxMonth; $month++) {
            // Check if report exists
            $exists = MonthlyReport::where('year', $year)
                ->where('month', $month)
                ->exists();
            
            if (!$exists) {
                // Generate the report
                $this->call('reports:generate-monthly', [
                    'year' => $year,
                    'month' => $month,
                ]);
                
                $generatedCount++;
                $this->info("Generated report for {$year}-{$month}");
            }
        }
        
        $this->info("Completed! Generated {$generatedCount} new reports for {$year}");
        
        return 0;
    }
}