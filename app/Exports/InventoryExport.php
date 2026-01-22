<?php
// app/Exports/InventoryExport.php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventoryExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithEvents
{
    protected $data;
    protected $reportType;

    public function __construct($data = [], $reportType = 'monthly')
    {
        $this->data = $data;
        $this->reportType = $reportType;
    }

    public function title(): string
    {
        if ($this->reportType === 'monthly' && isset($this->data['period'])) {
            return "Monthly Report - {$this->data['period']}";
        }
        return 'Inventory Report';
    }

    public function headings(): array
    {
        if ($this->reportType === 'monthly' && isset($this->data['period'])) {
            return [
                ['Land Bank Inventory System - Monthly Inventory Analysis Report'],
                [$this->data['period'] ?? ''],
                ['Generated on: ' . ($this->data['generatedAt'] instanceof \DateTime ? $this->data['generatedAt']->format('F d, Y h:i A') : date('F d, Y h:i A'))],
            ];
        }
        
        return [
            ['Land Bank Inventory System - Inventory Report'],
            ['Generated on: ' . date('F d, Y h:i A')],
        ];
    }

    public function array(): array
    {
        if ($this->reportType === 'monthly' && !empty($this->data)) {
            return $this->generateMonthlyReportArray();
        }
        
        return $this->generateCompleteInventoryArray();
    }

    private function forceZero($value)
    {
        if ($value === 0 || $value === '0' || $value === 0.0) {
            return 0;
        }
        
        if ($value === null || $value === '' || $value === false || empty($value)) {
            return 0;
        }
        
        if (is_string($value) && trim($value) === '') {
            return 0;
        }
        
        if (is_numeric($value)) {
            return $value == (int)$value ? (int)$value : (float)$value;
        }
        
        return 0;
    }

    private function generateMonthlyReportArray(): array
    {
        $rows = [];
        
        // ========== REPORT HEADER ==========
        $rows[] = []; // Empty row after headings
        
        // Finalized status
        $isFinalized = $this->data['isFinalized'] ?? false;
        if ($isFinalized) {
            $rows[] = ['✓ FINALIZED REPORT'];
            if (isset($this->data['finalizedAt']) && $this->data['finalizedAt'] instanceof \DateTime) {
                $rows[] = ['Finalized on: ' . $this->data['finalizedAt']->format('F d, Y h:i A')];
            }
        } else {
            $rows[] = ['⚠️ DRAFT REPORT'];
        }
        
        $rows[] = []; // Empty row
        
        // ========== EXECUTIVE SUMMARY ==========
        $rows[] = ['📊 Executive Summary'];
        $rows[] = ['Total Items:', $this->forceZero($this->data['totalItems'] ?? 0)];
        $rows[] = ['Items Restocked:', $this->forceZero($this->data['totalRestocked'] ?? 0)];
        $rows[] = ['Items Claimed:', $this->forceZero($this->data['totalClaimed'] ?? 0)];
        $rows[] = []; // Empty row
        
        // ========== REQUEST STATUS BREAKDOWN ==========
        $rows[] = ['📊 Request Status Breakdown'];
        $rows[] = ['Status', 'Count', 'Percentage'];
        
        $requestStats = $this->data['request_stats'] ?? [];
        $totalRequests = $this->forceZero($requestStats['total_requests'] ?? 0);
        
        $rows[] = ['Total Requests', $totalRequests, '100%'];
        $rows[] = ['✅ Approved', $this->forceZero($requestStats['approved_requests'] ?? 0), 
            $totalRequests > 0 ? number_format((($requestStats['approved_requests'] ?? 0) / $totalRequests) * 100, 1) . '%' : '0%'];
        $rows[] = ['❌ Rejected', $this->forceZero($requestStats['rejected_requests'] ?? 0),
            $totalRequests > 0 ? number_format((($requestStats['rejected_requests'] ?? 0) / $totalRequests) * 100, 1) . '%' : '0%'];
        $rows[] = ['📦 Claimed', $this->forceZero($requestStats['claimed_requests'] ?? 0),
            $totalRequests > 0 ? number_format((($requestStats['claimed_requests'] ?? 0) / $totalRequests) * 100, 1) . '%' : '0%'];
        $rows[] = ['⏳ Pending', $this->forceZero($requestStats['pending_requests'] ?? 0),
            $totalRequests > 0 ? number_format((($requestStats['pending_requests'] ?? 0) / $totalRequests) * 100, 1) . '%' : '0%'];
        
        $rows[] = []; // Empty row
        
        // ========== INVENTORY FLOW ANALYSIS ==========
        $rows[] = ['📈 Inventory Flow Analysis'];
        $rows[] = ['Item', 'Beginning', 'Requested', 'Claimed', 'Restocked', 'Ending', 'Status'];
        
        $inventoryFlowItems = $this->data['inventoryFlowItems'] ?? collect();
        $hasInventoryData = false;
        
        if ($inventoryFlowItems && (is_countable($inventoryFlowItems) ? count($inventoryFlowItems) : 0) > 0) {
            $hasInventoryData = true;
            
            foreach ($inventoryFlowItems as $item) {
                $itemArray = $this->convertToArray($item);
                
                $rows[] = [
                    $itemArray['name'] ?? 'N/A',
                    $this->forceZero($itemArray['beginning_quantity'] ?? 0),
                    $this->forceZero($itemArray['requested_quantity'] ?? 0),
                    $this->forceZero($itemArray['claimed_quantity'] ?? 0),
                    $this->forceZero($itemArray['restocked_quantity'] ?? 0),
                    $this->forceZero($itemArray['ending_quantity'] ?? 0),
                    $itemArray['status'] ?? 'N/A'
                ];
            }
            
            // Calculate totals
            $totalBeginning = 0;
            $totalRequested = 0;
            $totalClaimed = 0;
            $totalRestocked = 0;
            $totalEnding = 0;
            $needsRestock = 0;
            $outOfStock = 0;
            
            foreach ($inventoryFlowItems as $item) {
                $itemArray = $this->convertToArray($item);
                $totalBeginning += $this->forceZero($itemArray['beginning_quantity'] ?? 0);
                $totalRequested += $this->forceZero($itemArray['requested_quantity'] ?? 0);
                $totalClaimed += $this->forceZero($itemArray['claimed_quantity'] ?? 0);
                $totalRestocked += $this->forceZero($itemArray['restocked_quantity'] ?? 0);
                $totalEnding += $this->forceZero($itemArray['ending_quantity'] ?? 0);
                
                if (($itemArray['status'] ?? '') === 'Needs Restock') $needsRestock++;
                if (($itemArray['status'] ?? '') === 'Out of Stock') $outOfStock++;
            }
            
            $rows[] = ['TOTAL', $totalBeginning, $totalRequested, $totalClaimed, $totalRestocked, $totalEnding, 
                ($outOfStock > 0 ? $outOfStock . ' out of stock' : '') . 
                ($outOfStock > 0 && $needsRestock > 0 ? ', ' : '') . 
                ($needsRestock > 0 ? $needsRestock . ' need restock' : '')
            ];
        } else {
            $rows[] = ['No inventory flow data available', '', '', '', '', '', ''];
        }
        
        $rows[] = []; // Empty row
        
        // Flow Summary
        $rows[] = ['Flow Summary:'];
        if ($hasInventoryData) {
            $rows[] = ['Total Beginning Value:', $totalBeginning . ' units'];
            $netChange = $totalRestocked - $totalClaimed;
            $netChangeText = $netChange . ' units';
            if ($netChange > 0) {
                $netChangeText = '+' . $netChangeText . ' (Increase)';
            } elseif ($netChange < 0) {
                $netChangeText .= ' (Decrease)';
            } else {
                $netChangeText .= ' (No Change)';
            }
            $rows[] = ['Net Change:', $netChangeText];
            $rows[] = ['Items Needing Restock:', $needsRestock];
            $rows[] = ['Items Out of Stock:', $outOfStock];
        }
        
        $rows[] = []; // Empty row
        
        // ========== USAGE PATTERNS ==========
        $rows[] = ['📊 Usage Patterns - All Items by Request Count (Last 30 Days)'];
        $rows[] = ['Item', 'Current Stock', 'Minimum Stock', 'Total Requests', 'Avg Requests/Day', 'Demand Level'];
        
        $topRequestedItems = $this->data['topRequestedItems'] ?? collect();
        $hasUsageData = false;
        
        if ($topRequestedItems && (is_countable($topRequestedItems) ? count($topRequestedItems) : 0) > 0) {
            $hasUsageData = true;
            
            foreach ($topRequestedItems as $item) {
                $itemArray = $this->convertToArray($item);
                $totalRequests = $this->forceZero($itemArray['total_requests'] ?? 0);
                
                $demandLevel = 'No Demand';
                if ($totalRequests > 20) {
                    $demandLevel = 'High Demand';
                } elseif ($totalRequests > 10) {
                    $demandLevel = 'Medium Demand';
                } elseif ($totalRequests > 0) {
                    $demandLevel = 'Normal Demand';
                }
                
                $rows[] = [
                    $itemArray['name'] ?? 'N/A',
                    $this->forceZero($itemArray['quantity'] ?? 0),
                    $this->forceZero($itemArray['minimum_stock'] ?? 0),
                    $totalRequests,
                    number_format($this->forceZero($itemArray['avg_requests_per_day'] ?? 0), 2),
                    $demandLevel
                ];
            }
            
            // Calculate totals
            $totalStock = 0;
            $totalMinStock = 0;
            $totalRequestsSum = 0;
            $avgRequestsPerDay = 0;
            $count = 0;
            
            foreach ($topRequestedItems as $item) {
                $itemArray = $this->convertToArray($item);
                $totalStock += $this->forceZero($itemArray['quantity'] ?? 0);
                $totalMinStock += $this->forceZero($itemArray['minimum_stock'] ?? 0);
                $totalRequestsSum += $this->forceZero($itemArray['total_requests'] ?? 0);
                $avgRequestsPerDay += $this->forceZero($itemArray['avg_requests_per_day'] ?? 0);
                $count++;
            }
            
            $avgRequestsPerDayAvg = $count > 0 ? $avgRequestsPerDay / $count : 0;
            
            $rows[] = ['TOTAL', $totalStock, $totalMinStock, $totalRequestsSum, 
                number_format($avgRequestsPerDayAvg, 2), ''];
        } else {
            $rows[] = ['No usage data available', '', '', '', '', ''];
        }
        
        $rows[] = []; // Empty row
        
        // ========== MOST ACTIVE TEAMS ==========
        $rows[] = ['👥 Most Active Teams'];
        $rows[] = ['Team', 'Members', 'Request Count', 'Percentage'];
        
        $mostActiveTeams = $this->data['mostActiveTeams'] ?? collect();
        $hasTeamData = false;
        
        if ($mostActiveTeams && (is_countable($mostActiveTeams) ? count($mostActiveTeams) : 0) > 0) {
            $hasTeamData = true;
            
            // Sort teams
            $sortedTeams = collect($mostActiveTeams)->sortBy(function($team) {
                $name = is_object($team) ? ($team->name ?? '') : ($team['name'] ?? '');
                preg_match('/\d+/', $name, $matches);
                return isset($matches[0]) ? (int)$matches[0] : 999;
            })->values();
            
            $maxRequests = $sortedTeams->max(function($team) {
                return is_object($team) ? ($team->request_count ?? 0) : ($team['request_count'] ?? 0);
            }) ?? 1;
            
            $totalMembers = 0;
            $totalRequests = 0;
            
            foreach ($sortedTeams as $team) {
                $teamArray = $this->convertToArray($team);
                $teamName = $teamArray['name'] ?? 'N/A';
                $members = $this->forceZero($teamArray['members_count'] ?? $teamArray['members_count'] ?? 0);
                $requestCount = $this->forceZero($teamArray['request_count'] ?? $teamArray['request_count'] ?? 0);
                
                $percentage = $maxRequests > 0 ? ($requestCount / $maxRequests) * 100 : 0;
                
                $rows[] = [
                    $teamName,
                    $members,
                    $requestCount,
                    number_format($percentage, 1) . '%'
                ];
                
                $totalMembers += $members;
                $totalRequests += $requestCount;
            }
            
            $teamCount = count($sortedTeams);
            $avgPercentage = $maxRequests > 0 ? ($totalRequests / ($maxRequests * $teamCount)) * 100 : 0;
            
            $rows[] = ['TOTAL', $totalMembers, $totalRequests, number_format($avgPercentage, 1) . '% (avg)'];
        } else {
            $rows[] = ['No team request data available', '', '', ''];
        }
        
        $rows[] = []; // Empty row
        
        // ========== FASTEST DEPLETING ITEMS ==========
        $rows[] = ['⚡ Fastest Depleting Items'];
        $rows[] = ['Item', 'Current Stock', 'Minimum Stock', 'Requests (30 Days)', 'Depletion Rate', 'Days to Depletion', 'Status'];
        
        $fastDepletingItems = $this->data['fastDepletingItems'] ?? collect();
        $hasDepletionData = false;
        
        if ($fastDepletingItems && (is_countable($fastDepletingItems) ? count($fastDepletingItems) : 0) > 0) {
            $hasDepletionData = true;
            
            $criticalItems = 0;
            $warningItems = 0;
            $normalItems = 0;
            $totalStock = 0;
            $totalMinStock = 0;
            $totalRequests30Days = 0;
            $totalDepletionRate = 0;
            $count = 0;
            
            foreach ($fastDepletingItems as $item) {
                $itemArray = $this->convertToArray($item);
                $quantity = $this->forceZero($itemArray['quantity'] ?? 0);
                $minimumStock = $this->forceZero($itemArray['minimum_stock'] ?? 0);
                $requests30Days = $this->forceZero($itemArray['requests_last_30_days'] ?? 0);
                $depletionRate = $this->forceZero($itemArray['depletion_rate'] ?? 0);
                $daysToDepletion = $this->forceZero($itemArray['days_to_depletion'] ?? 999);
                
                // Determine status
                $status = 'Normal';
                if ($quantity <= 0) {
                    $status = 'Out of Stock';
                } elseif ($quantity <= $minimumStock) {
                    $status = 'Low Stock';
                } elseif ($daysToDepletion <= 7) {
                    $status = 'Critical';
                } elseif ($daysToDepletion <= 14) {
                    $status = 'Warning';
                }
                
                // Count by category
                if ($status === 'Out of Stock') $criticalItems++;
                elseif ($status === 'Critical') $criticalItems++;
                elseif ($status === 'Low Stock' || $status === 'Warning') $warningItems++;
                else $normalItems++;
                
                $rows[] = [
                    $itemArray['name'] ?? 'N/A',
                    $quantity,
                    $minimumStock,
                    $requests30Days,
                    number_format($depletionRate, 1) . '%',
                    ($daysToDepletion == 999 || $daysToDepletion >= 999) ? '∞ days' : $daysToDepletion . ' days',
                    $status
                ];
                
                $totalStock += $quantity;
                $totalMinStock += $minimumStock;
                $totalRequests30Days += $requests30Days;
                $totalDepletionRate += $depletionRate;
                $count++;
            }
            
            // Add summary row
            $avgDepletionRate = $count > 0 ? $totalDepletionRate / $count : 0;
            $totalDailyUsage = $totalRequests30Days / 30;
            $avgDaysToDepletion = $totalDailyUsage > 0 ? round($totalStock / $totalDailyUsage) : 999;
            
            $rows[] = ['SUMMARY', $totalStock, $totalMinStock, $totalRequests30Days, 
                number_format($avgDepletionRate, 1) . '%',
                $avgDaysToDepletion == 999 ? '∞ days' : $avgDaysToDepletion . ' days',
                'C:' . $criticalItems . ' W:' . $warningItems . ' N:' . $normalItems];
        } else {
            $rows[] = ['No depletion data available', '', '', '', '', '', ''];
        }
        
        $rows[] = []; // Empty row
        
        // ========== DEPLETION ANALYSIS ==========
        $rows[] = ['📉 Depletion Analysis - Stock Level Predictions'];
        $rows[] = ['Out of Stock', 'Low Stock', 'In Stock'];
        
        $criticalItemsCount = $this->forceZero($this->data['criticalItemsCount'] ?? 0);
        $warningItemsCount = $this->forceZero($this->data['warningItemsCount'] ?? 0);
        $safeItemsCount = $this->forceZero($this->data['safeItemsCount'] ?? 0);
        
        $rows[] = [$criticalItemsCount, $warningItemsCount, $safeItemsCount];
        
        // Status message
        if ($criticalItemsCount > 0) {
            $rows[] = ['⚠️ Urgent Attention Needed:', $criticalItemsCount . ' items are out of stock and need immediate restocking.'];
        } elseif ($warningItemsCount > 0) {
            $rows[] = ['⚠️ Low Stock Warning:', $warningItemsCount . ' items are below minimum stock levels.'];
        } else {
            $rows[] = ['✓ Stock Levels Good:', 'All items are at or above minimum stock levels.'];
        }
        
        $rows[] = []; // Empty row
        
        // ========== YEARLY COMPARISON ==========
        if (isset($this->data['yearlyComparison'])) {
            $rows[] = ['📅 Yearly Comparison Summary'];
            $rows[] = [$this->data['yearlyComparison']['previous_year']['year'] ?? ($this->data['selectedYear'] ?? date('Y')) - 1,
                     'Comparison',
                     $this->data['selectedYear'] ?? date('Y') . ' YTD'];
            
            $prevYearRequests = $this->forceZero($this->data['yearlyComparison']['previous_year']['total_requests'] ?? 0);
            $currentYearRequests = $this->forceZero($this->data['yearlyComparison']['current_year']['total_requests'] ?? 0);
            $change = $prevYearRequests > 0 ? (($currentYearRequests - $prevYearRequests) / $prevYearRequests) * 100 : 0;
            $direction = $change > 0 ? '↑' : ($change < 0 ? '↓' : '→');
            
            $rows[] = [$prevYearRequests, 
                     $prevYearRequests > 0 ? $direction . ' ' . number_format(abs($change), 1) . '%' : 'N/A',
                     $currentYearRequests];
            $rows[] = ['Total Requests', 'Year-over-Year Change', 'Year-to-Date'];
            
            // Most requested item
            if (isset($this->data['yearlyComparison']['current_year']['most_requested_item'])) {
                $mostReqItem = $this->data['yearlyComparison']['current_year']['most_requested_item'];
                $rows[] = ['Most Requested Item in ' . ($this->data['selectedYear'] ?? date('Y')) . ':',
                         $mostReqItem['name'] . ' (' . $mostReqItem['request_count'] . ' requests)'];
            }
            
            $rows[] = []; // Empty row
        }
        
        // ========== RECOMMENDATIONS ==========
        $rows[] = ['💡 Recommendations & Action Items'];
        
        $recommendations = [];
        
        if ($criticalItemsCount > 0) {
            $recommendations[] = "Immediate restocking needed for {$criticalItemsCount} out-of-stock items";
        }
        
        if ($warningItemsCount > 0) {
            $recommendations[] = "Schedule restocking for {$warningItemsCount} low-stock items";
        }
        
        // Check for fast depleting items
        if ($hasDepletionData) {
            $criticalDepletion = 0;
            foreach ($fastDepletingItems as $item) {
                $itemArray = $this->convertToArray($item);
                $days = $this->forceZero($itemArray['days_to_depletion'] ?? 999);
                if ($days <= 7) {
                    $criticalDepletion++;
                }
            }
            
            if ($criticalDepletion > 0) {
                $recommendations[] = "Review procurement schedule for {$criticalDepletion} critically depleting items";
            }
        }
        
        // Check for high demand items
        if ($hasUsageData) {
            $highDemand = 0;
            foreach ($topRequestedItems as $item) {
                $itemArray = $this->convertToArray($item);
                $requests = $this->forceZero($itemArray['total_requests'] ?? 0);
                if ($requests > 20) {
                    $highDemand++;
                }
            }
            
            if ($highDemand > 0) {
                $recommendations[] = "Consider increasing stock levels for {$highDemand} high-demand items";
            }
        }
        
        if (empty($recommendations)) {
            $recommendations[] = "Inventory levels are optimal. Continue current restocking schedule";
        }
        
        foreach ($recommendations as $index => $rec) {
            $rows[] = [($index + 1) . '. ' . $rec];
        }
        
        $rows[] = []; // Empty row
        
        // ========== FOOTER ==========
        $rows[] = ['Report ID:', $this->data['reportId'] ?? 'N/A'];
        $rows[] = ['Report Status:', $isFinalized ? 'FINALIZED' : 'DRAFT'];
        $rows[] = ['Generated by: Land Bank Inventory System v1.0'];
        $rows[] = ['This is a system-generated report. For questions, contact the inventory department.'];
        
        return $rows;
    }

    private function generateCompleteInventoryArray(): array
    {
        // This would be your existing complete inventory report
        $rows = [];
        $rows[] = ['Complete Inventory Report'];
        $rows[] = []; // Empty row
        // ... your existing complete inventory logic ...
        
        return $rows;
    }

    private function convertToArray($item)
    {
        if (is_object($item)) {
            if (method_exists($item, 'toArray')) {
                return $item->toArray();
            }
            return get_object_vars($item);
        }
        return $item;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Apply styling
                $this->applyStyling($sheet);
            },
        ];
    }

    private function applyStyling($sheet)
    {
        // Style main title
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => 'center'],
        ]);
        
        // Style period
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => 'center'],
        ]);
        
        // Style generated date
        $sheet->mergeCells('A3:G3');
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['italic' => true, 'size' => 10],
            'alignment' => ['horizontal' => 'center'],
        ]);
        
        // Style finalized status
        $isFinalized = $this->data['isFinalized'] ?? false;
        $statusRow = 4;
        
        if ($isFinalized) {
            $sheet->mergeCells("A{$statusRow}:G{$statusRow}");
            $sheet->getStyle("A{$statusRow}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => '155724']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'D4EDDA']],
                'alignment' => ['horizontal' => 'center'],
            ]);
            
            if (isset($this->data['finalizedAt']) && $this->data['finalizedAt'] instanceof \DateTime) {
                $sheet->mergeCells("A" . ($statusRow + 1) . ":G" . ($statusRow + 1));
                $sheet->getStyle("A" . ($statusRow + 1))->applyFromArray([
                    'font' => ['italic' => true, 'size' => 9],
                    'alignment' => ['horizontal' => 'center'],
                ]);
            }
        } else {
            $sheet->mergeCells("A{$statusRow}:G{$statusRow}");
            $sheet->getStyle("A{$statusRow}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => '856404']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'FFF3CD']],
                'alignment' => ['horizontal' => 'center'],
            ]);
        }
        
        // Style section headers
        $lastRow = $sheet->getHighestRow();
        
        for ($row = 1; $row <= $lastRow; $row++) {
            $cellValue = $sheet->getCell('A' . $row)->getValue();
            
            // Section headers (with emojis)
            if (is_string($cellValue) && preg_match('/^[📊📈👥⚡📉💡📅]/u', $cellValue)) {
                $sheet->getStyle('A' . $row)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'F0F0F0']],
                ]);
                
                // Apply borders for section headers
                $highestCol = $sheet->getHighestColumn();
                $sheet->getStyle('A' . $row . ':' . $highestCol . $row)->applyFromArray([
                    'borders' => [
                        'bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '333333']],
                    ],
                ]);
            }
            
            // Table headers (row after section header)
            $prevCellValue = $row > 1 ? $sheet->getCell('A' . ($row - 1))->getValue() : '';
            if (is_string($prevCellValue) && preg_match('/^[📊📈👥⚡📉💡📅]/u', $prevCellValue)) {
                $highestCol = $sheet->getHighestColumn();
                $sheet->getStyle('A' . $row . ':' . $highestCol . $row)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'F8F9FA']],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'DEE2E6']],
                    ],
                ]);
            }
            
            // Style numeric columns with thousand separators
            $cellValueB = $sheet->getCell('B' . $row)->getValue();
            if (is_numeric($cellValueB)) {
                $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
            }
            
            // Style TOTAL rows
            if (is_string($cellValue) && strtoupper($cellValue) === 'TOTAL') {
                $highestCol = $sheet->getHighestColumn();
                $sheet->getStyle('A' . $row . ':' . $highestCol . $row)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'F8F9FA']],
                    'borders' => [
                        'top' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '333333']],
                    ],
                ]);
            }
        }
        
        // Auto-size all columns
        foreach (range('A', $sheet->getHighestColumn()) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Set column widths for better readability
        $sheet->getColumnDimension('A')->setWidth(30); // Item/Team names
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(20);
    }
}