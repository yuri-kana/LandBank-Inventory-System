<?php

namespace App\Console\Commands;

use App\Models\MonthlyReport;
use App\Models\TeamRequest;
use App\Models\InventoryLog;
use App\Models\Item;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class FixMonthlyReportData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:fix-data 
                            {--year= : Fix reports for specific year (default: all years)}
                            {--month= : Fix reports for specific month (1-12)}
                            {--force : Force regenerate even if report exists}
                            {--dry-run : Show what would be fixed without making changes}
                            {--create-missing : Create missing reports for the year}
                            {--skip-existing : Skip existing reports, only create missing ones}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix monthly reports by recalculating totals from actual data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = $this->option('year');
        $month = $this->option('month');
        $force = $this->option('force');
        $dryRun = $this->option('dry-run');
        $createMissing = $this->option('create-missing');
        $skipExisting = $this->option('skip-existing');
        
        $this->info('🔧 Fixing Monthly Reports Data...');
        $this->info('===================================');
        
        if ($dryRun) {
            $this->info('⚠️ DRY RUN MODE: No changes will be made');
        }
        
        // Handle create-missing option
        if ($createMissing) {
            $this->createMissingReports($year, $month, $dryRun);
            return;
        }
        
        // Determine which reports to fix
        $query = MonthlyReport::query();
        
        if ($year) {
            $query->where('year', $year);
            $this->info("📅 Filtering by year: {$year}");
        }
        
        if ($month) {
            $query->where('month', $month);
            $this->info("📅 Filtering by month: {$month}");
        }
        
        $reports = $query->orderBy('year')->orderBy('month')->get();
        
        if ($reports->isEmpty()) {
            if ($year || $month) {
                $this->warn("No reports found for the specified criteria.");
                if ($this->confirm('Would you like to create missing reports instead?')) {
                    $this->createMissingReports($year, $month, $dryRun);
                }
                return;
            }
            
            $this->error("No monthly reports found in the database.");
            if ($this->confirm('Would you like to create reports for all years?')) {
                $this->createMissingReports(null, null, $dryRun);
            }
            return;
        }
        
        $this->info("Found {$reports->count()} report(s) to process");
        
        $updated = 0;
        $skipped = 0;
        
        foreach ($reports as $report) {
            $this->line("");
            $this->info("Processing {$report->year}-{$report->month}...");
            
            if ($skipExisting) {
                $this->line("⏭️ Skipping existing report (--skip-existing flag)");
                $skipped++;
                continue;
            }
            
            // Get current data from database
            $startDate = Carbon::create($report->year, $report->month, 1)->startOfMonth();
            $endDate = Carbon::create($report->year, $report->month, 1)->endOfMonth();
            
            // Count ALL requests for this month (not just approved)
            $totalRequests = TeamRequest::whereBetween('created_at', [$startDate, $endDate])
                ->count();
            
            // Get restocked quantity
            $totalRestocked = 0;
            if (Schema::hasTable('inventory_logs')) {
                $totalRestocked = InventoryLog::where('action', 'restock')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->sum('quantity_change');
            }
            
            // Get claimed quantity
            $totalClaimed = TeamRequest::where('status', 'claimed')
                ->whereBetween('created_at', [$startDate, $endDate])
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
                        ->whereBetween('team_requests.created_at', [$startDate, $endDate]);
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
            
            // Determine status
            $status = $this->determineStatus($totalRequests, $totalClaimed);
            
            // Check if report needs updating
            $needsUpdate = false;
            $changes = [];
            
            // Check for differences
            if ($report->total_requests != $totalRequests) {
                $needsUpdate = true;
                $changes[] = "Requests: {$report->total_requests} → {$totalRequests}";
            }
            
            if ($report->total_restocked != $totalRestocked) {
                $needsUpdate = true;
                $changes[] = "Restocked: {$report->total_restocked} → {$totalRestocked}";
            }
            
            if ($report->total_claimed != $totalClaimed) {
                $needsUpdate = true;
                $changes[] = "Claimed: {$report->total_claimed} → {$totalClaimed}";
            }
            
            // Check if status field exists in the report
            if (property_exists($report, 'status') && $report->status != $status) {
                $needsUpdate = true;
                $changes[] = "Status: {$report->status} → {$status}";
            }
            
            if ($needsUpdate || $force) {
                if ($dryRun) {
                    $this->warn("📝 Would update: {$report->year}-{$report->month}");
                    foreach ($changes as $change) {
                        $this->line("   {$change}");
                    }
                    $updated++;
                } else {
                    // Prepare update data
                    $updateData = [
                        'total_requests' => $totalRequests,
                        'total_restocked' => $totalRestocked,
                        'total_claimed' => $totalClaimed,
                        'most_requested_items' => json_encode($mostRequestedItems),
                        'fast_depleting_items' => json_encode($fastDepletingItems),
                        'updated_at' => now(),
                    ];
                    
                    // Add status if the column exists
                    if (Schema::hasColumn('monthly_reports', 'status')) {
                        $updateData['status'] = $status;
                    }
                    
                    // Update the report
                    $report->update($updateData);
                    
                    $this->info("✅ Updated: {$report->year}-{$report->month}");
                    foreach ($changes as $change) {
                        $this->line("   {$change}");
                    }
                    $updated++;
                }
            } else {
                $this->line("✓ No changes needed for {$report->year}-{$report->month}");
                $skipped++;
            }
        }
        
        $this->line("");
        $this->info("===================================");
        $this->info("Summary:");
        $this->info("  Updated: {$updated}");
        $this->info("  Skipped: {$skipped}");
        $this->info("  Total Reports: " . $reports->count());
        
        if (!$dryRun) {
            $this->info("✅ Reports data fixed successfully!");
            
            // Show sample of fixed data
            if ($updated > 0) {
                $this->line("");
                $this->info("📊 Sample of fixed reports:");
                $sampleReports = MonthlyReport::when($year, function($query) use ($year) {
                        $query->where('year', $year);
                    })
                    ->when($month, function($query) use ($month) {
                        $query->where('month', $month);
                    })
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->limit(3)
                    ->get();
                
                foreach ($sampleReports as $sample) {
                    $this->line("  {$sample->year}-{$sample->month}: {$sample->total_requests} requests, {$sample->total_restocked} restocked, {$sample->total_claimed} claimed");
                }
            }
        } else {
            $this->info("ℹ️  Dry run complete. No changes were made.");
        }
    }
    
    /**
     * Create missing reports for a year/month
     */
    private function createMissingReports($year = null, $month = null, $dryRun = false)
    {
        $this->info("➕ Creating missing monthly reports...");
        
        // Determine years to process
        if ($year) {
            $years = [$year];
        } else {
            // Get years from team requests
            $minYear = TeamRequest::min(DB::raw('YEAR(created_at)')) ?? date('Y') - 1;
            $maxYear = date('Y');
            $years = range($minYear, $maxYear);
        }
        
        $created = 0;
        $skipped = 0;
        
        foreach ($years as $yr) {
            // Determine months to process
            $months = $month ? [$month] : range(1, 12);
            
            foreach ($months as $mo) {
                // Only create reports for past months (or current month if it's finished)
                $currentDate = now();
                $monthEndDate = Carbon::create($yr, $mo, 1)->endOfMonth();
                
                if ($yr == $currentDate->year && $mo > $currentDate->month) {
                    $this->line("⏭️ Skipping future month: {$yr}-{$mo}");
                    $skipped++;
                    continue;
                }
                
                // Check if report already exists
                $exists = MonthlyReport::where('year', $yr)
                    ->where('month', $mo)
                    ->exists();
                
                if ($exists) {
                    $this->line("✓ Report already exists: {$yr}-{$mo}");
                    $skipped++;
                    continue;
                }
                
                // Calculate data for this month
                $startDate = Carbon::create($yr, $mo, 1)->startOfMonth();
                $endDate = Carbon::create($yr, $mo, 1)->endOfMonth();
                
                // Count ALL requests for this month
                $totalRequests = TeamRequest::whereBetween('created_at', [$startDate, $endDate])
                    ->count();
                
                // Get restocked quantity
                $totalRestocked = 0;
                if (Schema::hasTable('inventory_logs')) {
                    $totalRestocked = InventoryLog::where('action', 'restock')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->sum('quantity_change');
                }
                
                // Get claimed quantity
                $totalClaimed = TeamRequest::where('status', 'claimed')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->sum('quantity_requested');
                
                // Determine status
                $status = $this->determineStatus($totalRequests, $totalClaimed);
                
                // Get most requested items
                $mostRequestedItems = Item::select([
                        'items.id',
                        'items.name',
                        DB::raw('COUNT(team_requests.id) as request_count'),
                        DB::raw('SUM(team_requests.quantity_requested) as total_quantity')
                    ])
                    ->leftJoin('team_requests', function($join) use ($startDate, $endDate) {
                        $join->on('items.id', '=', 'team_requests.item_id')
                            ->whereBetween('team_requests.created_at', [$startDate, $endDate]);
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
                
                if ($dryRun) {
                    $this->warn("📝 Would create report for {$yr}-{$mo}:");
                    $this->line("   Total Requests: {$totalRequests}");
                    $this->line("   Total Restocked: {$totalRestocked}");
                    $this->line("   Total Claimed: {$totalClaimed}");
                    $this->line("   Status: {$status}");
                    $created++;
                } else {
                    // Create the report
                    $reportData = [
                        'year' => $yr,
                        'month' => $mo,
                        'total_requests' => $totalRequests,
                        'total_restocked' => $totalRestocked,
                        'total_claimed' => $totalClaimed,
                        'most_requested_items' => json_encode($mostRequestedItems),
                        'fast_depleting_items' => json_encode($fastDepletingItems),
                        'report_generated_at' => now(),
                    ];
                    
                    // Add status if the column exists
                    if (Schema::hasColumn('monthly_reports', 'status')) {
                        $reportData['status'] = $status;
                    }
                    
                    MonthlyReport::create($reportData);
                    
                    $this->info("✅ Created report for {$yr}-{$mo}: {$totalRequests} requests");
                    $created++;
                }
            }
        }
        
        $this->line("");
        $this->info("===================================");
        $this->info("Creation Summary:");
        $this->info("  Created: {$created}");
        $this->info("  Skipped: {$skipped}");
        
        if (!$dryRun) {
            $this->info("✅ Missing reports created successfully!");
        } else {
            $this->info("ℹ️  Dry run complete. No reports were created.");
        }
    }
    
    /**
     * Determine report status based on activity
     */
    private function determineStatus($totalRequests, $totalClaimed)
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
}