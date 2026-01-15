<?php
// app/Exports/InventoryExport.php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
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
        return 'Complete Inventory Report';
    }

    public function headings(): array
    {
        if ($this->reportType === 'monthly' && isset($this->data['totalRequests'])) {
            $headings = [
                ['Land Bank Inventory System - Monthly Inventory Analysis Report'],
                [$this->data['period'] ?? ''],
                ['Generated on: ' . ($this->data['generatedAt'] instanceof \DateTime ? $this->data['generatedAt']->format('F d, Y h:i A') : date('F d, Y h:i A'))],
            ];
            
            // Add finalized status
            $isFinalized = $this->data['isFinalized'] ?? false;
            if ($isFinalized) {
                $headings[] = ['✓ FINALIZED REPORT'];
                if (isset($this->data['finalizedAt']) && $this->data['finalizedAt'] instanceof \DateTime) {
                    $headings[] = ['Finalized on: ' . $this->data['finalizedAt']->format('F d, Y h:i A')];
                }
            } else {
                $headings[] = ['⚠️ DRAFT REPORT'];
            }
            
            $headings[] = []; // Empty row for spacing
            return $headings;
        }
        
        return [
            ['Land Bank Inventory System - Complete Inventory Report'],
            ['Generated on: ' . date('F d, Y h:i A')],
            [], // Empty row for spacing
        ];
    }

    public function array(): array
    {
        if ($this->reportType === 'monthly' && !empty($this->data)) {
            return $this->generateMonthlyReportArray();
        }
        
        return $this->generateCompleteInventoryArray();
    }

    // ULTRA-AGGRESSIVE function to force 0
    private function forceZero($value)
    {
        // If value is exactly 0 or '0', return 0
        if ($value === 0 || $value === '0' || $value === 0.0) {
            return 0;
        }
        
        // If value is null, empty, false, or any empty-like value, return 0
        if ($value === null || $value === '' || $value === false || empty($value)) {
            return 0;
        }
        
        // If it's a string that contains only whitespace, return 0
        if (is_string($value) && trim($value) === '') {
            return 0;
        }
        
        // If it's already numeric, return it (but as int if it's a whole number)
        if (is_numeric($value)) {
            return $value == (int)$value ? (int)$value : (float)$value;
        }
        
        // For ANY other case, return 0
        return 0;
    }

    private function generateMonthlyReportArray(): array
    {
        $rows = [];
        
        // ========== EXECUTIVE SUMMARY ==========
        $rows[] = ['📊 Executive Summary'];
        $rows[] = ['Total Requests:', $this->forceZero($this->data['totalRequests'] ?? 0)];
        $rows[] = ['Items Restocked:', $this->forceZero($this->data['totalRestocked'] ?? 0)];
        $rows[] = ['Items Claimed:', $this->forceZero($this->data['totalClaimed'] ?? 0)];
        $rows[] = ['Active Teams:', $this->forceZero($this->data['activeTeams'] ?? 0)];
        $rows[] = ['Total Items:', $this->forceZero($this->data['totalItems'] ?? 0)];
        $rows[] = []; // Empty row
        
        // ========== INVENTORY FLOW ANALYSIS ==========
        $rows[] = ['📈 Inventory Flow Analysis'];
        $rows[] = ['Item', 'Beginning', 'Requested', 'Claimed', 'Restocked', 'Ending', 'Status'];
        
        $hasInventoryData = false;
        if (isset($this->data['inventoryFlowItems']) && !empty($this->data['inventoryFlowItems'])) {
            $itemCount = 0;
            if (is_countable($this->data['inventoryFlowItems'])) {
                $itemCount = count($this->data['inventoryFlowItems']);
            } elseif (method_exists($this->data['inventoryFlowItems'], 'count')) {
                $itemCount = $this->data['inventoryFlowItems']->count();
            }
            
            if ($itemCount > 0) {
                $hasInventoryData = true;
                
                // DEBUG: Log what we're getting
                // error_log("Processing " . $itemCount . " inventory items");
                
                foreach ($this->data['inventoryFlowItems'] as $index => $item) {
                    // Convert item to array for consistent access
                    if (is_object($item)) {
                        $itemArray = (array) $item;
                        // Handle Laravel model access
                        if (method_exists($item, 'toArray')) {
                            $itemArray = $item->toArray();
                        } else {
                            $itemArray = get_object_vars($item);
                        }
                    } else {
                        $itemArray = $item;
                    }
                    
                    // DEBUG: See what data we have
                    // error_log("Item $index: " . json_encode($itemArray));
                    
                    // FORCE 0 for ALL numeric fields - no exceptions!
                    $rows[] = [
                        $itemArray['name'] ?? $itemArray['item_name'] ?? 'N/A',
                        $this->forceZero($itemArray['beginning_quantity'] ?? $itemArray['beginning'] ?? 0),
                        $this->forceZero($itemArray['requested_quantity'] ?? $itemArray['requested'] ?? 0),
                        $this->forceZero($itemArray['claimed_quantity'] ?? $itemArray['claimed'] ?? 0),
                        $this->forceZero($itemArray['restocked_quantity'] ?? $itemArray['restocked'] ?? 0),
                        $this->forceZero($itemArray['ending_quantity'] ?? $itemArray['ending'] ?? 0),
                        $itemArray['status'] ?? $itemArray['stock_status'] ?? 0
                    ];
                }
                
                // Calculate totals
                $totalBeginning = 0;
                $totalRequested = 0;
                $totalClaimed = 0;
                $totalRestocked = 0;
                $totalEnding = 0;
                
                foreach ($this->data['inventoryFlowItems'] as $item) {
                    if (is_object($item)) {
                        $itemArray = (array) $item;
                        if (method_exists($item, 'toArray')) {
                            $itemArray = $item->toArray();
                        }
                    } else {
                        $itemArray = $item;
                    }
                    
                    $totalBeginning += $this->forceZero($itemArray['beginning_quantity'] ?? $itemArray['beginning'] ?? 0);
                    $totalRequested += $this->forceZero($itemArray['requested_quantity'] ?? $itemArray['requested'] ?? 0);
                    $totalClaimed += $this->forceZero($itemArray['claimed_quantity'] ?? $itemArray['claimed'] ?? 0);
                    $totalRestocked += $this->forceZero($itemArray['restocked_quantity'] ?? $itemArray['restocked'] ?? 0);
                    $totalEnding += $this->forceZero($itemArray['ending_quantity'] ?? $itemArray['ending'] ?? 0);
                }
                
                $rows[] = ['TOTAL:', $totalBeginning, $totalRequested, $totalClaimed, $totalRestocked, $totalEnding, 0];
            }
        }
        
        if (!$hasInventoryData) {
            $rows[] = ['No inventory items found', 0, 0, 0, 0, 0, 'N/A'];
        }
        $rows[] = []; // Empty row
        
        // ========== USAGE PATTERNS ==========
        $rows[] = ['📊 Usage Patterns - All Items by Request Count (Last 30 Days)'];
        $rows[] = ['Item', 'Current Stock', 'Minimum Stock', 'Total Requests', 'Avg Requests/Day', 'Demand Level'];
        
        $hasUsageData = false;
        if (isset($this->data['topRequestedItems']) && !empty($this->data['topRequestedItems'])) {
            $itemCount = 0;
            if (is_countable($this->data['topRequestedItems'])) {
                $itemCount = count($this->data['topRequestedItems']);
            } elseif (method_exists($this->data['topRequestedItems'], 'count')) {
                $itemCount = $this->data['topRequestedItems']->count();
            }
            
            if ($itemCount > 0) {
                $hasUsageData = true;
                foreach ($this->data['topRequestedItems'] as $item) {
                    if (is_object($item)) {
                        $itemArray = (array) $item;
                        if (method_exists($item, 'toArray')) {
                            $itemArray = $item->toArray();
                        }
                    } else {
                        $itemArray = $item;
                    }
                    
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
                        $this->forceZero($itemArray['quantity'] ?? $itemArray['current_stock'] ?? 0),
                        $this->forceZero($itemArray['minimum_stock'] ?? 0),
                        $totalRequests,
                        number_format($this->forceZero($itemArray['avg_requests_per_day'] ?? 0), 2),
                        $demandLevel
                    ];
                }
            }
        }
        
        if (!$hasUsageData) {
            $rows[] = ['No usage data available', 0, 0, 0, '0.00', 'No Demand'];
        }
        $rows[] = []; // Empty row
        
        // ========== MOST ACTIVE TEAMS ==========
        $rows[] = ['👥 Most Teams Requested'];
        $rows[] = ['Team', 'Members', 'Request Count', 'Percentage'];
        
        $hasTeamData = false;
        if (isset($this->data['mostActiveTeams']) && !empty($this->data['mostActiveTeams'])) {
            $teamCount = 0;
            if (is_countable($this->data['mostActiveTeams'])) {
                $teamCount = count($this->data['mostActiveTeams']);
            } elseif (method_exists($this->data['mostActiveTeams'], 'count')) {
                $teamCount = $this->data['mostActiveTeams']->count();
            }
            
            if ($teamCount > 0) {
                $hasTeamData = true;
                $maxRequests = 1;
                foreach ($this->data['mostActiveTeams'] as $team) {
                    if (is_object($team)) {
                        $teamArray = (array) $team;
                        if (method_exists($team, 'toArray')) {
                            $teamArray = $team->toArray();
                        }
                    } else {
                        $teamArray = $team;
                    }
                    
                    $requestCount = $this->forceZero($teamArray['request_count'] ?? 0);
                    if ($requestCount > $maxRequests) {
                        $maxRequests = $requestCount;
                    }
                }
                
                foreach ($this->data['mostActiveTeams'] as $team) {
                    if (is_object($team)) {
                        $teamArray = (array) $team;
                        if (method_exists($team, 'toArray')) {
                            $teamArray = $team->toArray();
                        }
                    } else {
                        $teamArray = $team;
                    }
                    
                    $teamRequestCount = $this->forceZero($teamArray['request_count'] ?? 0);
                    $percentage = ($teamRequestCount / $maxRequests) * 100;
                    
                    $rows[] = [
                        $teamArray['name'] ?? 'N/A',
                        $this->forceZero($teamArray['members_count'] ?? 0),
                        $teamRequestCount,
                        number_format($percentage, 1) . '%'
                    ];
                }
            }
        }
        
        if (!$hasTeamData) {
            $rows[] = ['No team request data available', 0, 0, '0.0%'];
        }
        $rows[] = []; // Empty row
        
        // ========== FASTEST DEPLETING ITEMS ==========
        $rows[] = ['⚡ Fastest Depleting Items'];
        $rows[] = ['Item', 'Current Stock', 'Minimum Stock', 'Requests (30 Days)', 'Depletion Rate', 'Days to Depletion', 'Status'];
        
        $hasDepletionData = false;
        if (isset($this->data['fastDepletingItems']) && !empty($this->data['fastDepletingItems'])) {
            $itemCount = 0;
            if (is_countable($this->data['fastDepletingItems'])) {
                $itemCount = count($this->data['fastDepletingItems']);
            } elseif (method_exists($this->data['fastDepletingItems'], 'count')) {
                $itemCount = $this->data['fastDepletingItems']->count();
            }
            
            if ($itemCount > 0) {
                $hasDepletionData = true;
                foreach ($this->data['fastDepletingItems'] as $item) {
                    if (is_object($item)) {
                        $itemArray = (array) $item;
                        if (method_exists($item, 'toArray')) {
                            $itemArray = $item->toArray();
                        }
                    } else {
                        $itemArray = $item;
                    }
                    
                    $quantity = $this->forceZero($itemArray['quantity'] ?? 0);
                    $minimumStock = $this->forceZero($itemArray['minimum_stock'] ?? 0);
                    $daysToDepletion = $this->forceZero($itemArray['days_to_depletion'] ?? 999);
                    
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
                    
                    $rows[] = [
                        $itemArray['name'] ?? 'N/A',
                        $quantity,
                        $minimumStock,
                        $this->forceZero($itemArray['requests_last_30_days'] ?? 0),
                        number_format($this->forceZero($itemArray['depletion_rate'] ?? 0), 1) . '%',
                        ($daysToDepletion == 999 || $daysToDepletion >= 999) ? '∞ days' : $daysToDepletion . ' days',
                        $status
                    ];
                }
            }
        }
        
        if (!$hasDepletionData) {
            $rows[] = ['No depletion data available', 0, 0, 0, '0.0%', '∞ days', 'Normal'];
        }
        $rows[] = []; // Empty row
        
        // ========== STOCK STATUS ==========
        $rows[] = ['📉 Depletion Analysis - Stock Level Predictions'];
        $rows[] = ['Out of Stock', 'Low Stock', 'In Stock'];
        $rows[] = [
            $this->forceZero($this->data['criticalItemsCount'] ?? 0),
            $this->forceZero($this->data['warningItemsCount'] ?? 0),
            $this->forceZero($this->data['safeItemsCount'] ?? 0)
        ];
        $rows[] = []; // Empty row
        
        // ========== RECOMMENDATIONS ==========
        $rows[] = ['💡 Recommendations & Action Items'];
        
        $recommendations = [];
        $criticalCount = $this->forceZero($this->data['criticalItemsCount'] ?? 0);
        $warningCount = $this->forceZero($this->data['warningItemsCount'] ?? 0);
        
        if ($criticalCount > 0) {
            $recommendations[] = "Immediate restocking needed for {$criticalCount} out-of-stock items";
        }
        if ($warningCount > 0) {
            $recommendations[] = "Schedule restocking for {$warningCount} low-stock items";
        }
        
        // Check for fast depleting items
        if (isset($this->data['fastDepletingItems']) && $hasDepletionData) {
            $criticalDepletion = 0;
            foreach ($this->data['fastDepletingItems'] as $item) {
                if (is_object($item)) {
                    $itemArray = (array) $item;
                    if (method_exists($item, 'toArray')) {
                        $itemArray = $item->toArray();
                    }
                } else {
                    $itemArray = $item;
                }
                
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
        if (isset($this->data['topRequestedItems']) && $hasUsageData) {
            $highDemand = 0;
            foreach ($this->data['topRequestedItems'] as $item) {
                if (is_object($item)) {
                    $itemArray = (array) $item;
                    if (method_exists($item, 'toArray')) {
                        $itemArray = $item->toArray();
                    }
                } else {
                    $itemArray = $item;
                }
                
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
            $rows[] = [($index + 1) . ". " . $rec];
        }
        
        return $rows;
    }

    private function generateCompleteInventoryArray(): array
    {
        // For complete inventory report (when type is not 'monthly')
        $items = \App\Models\Item::where('is_available', true)->get();
        
        $rows = [];
        $rows[] = ['Complete Inventory Report - Generated on: ' . date('F d, Y h:i A')];
        $rows[] = [];
        $rows[] = ['ID', 'Item Name', 'Description', 'Category', 'Current Quantity', 'Minimum Stock', 'Unit Price', 'Total Value', 'Stock Status'];
        
        foreach ($items as $item) {
            $totalValue = $item->quantity * ($item->unit_price ?? 0);
            $stockStatus = 'Unknown';
            
            if ($item->quantity <= 0) {
                $stockStatus = 'Out of Stock';
            } elseif ($item->quantity <= $item->minimum_stock) {
                $stockStatus = 'Low Stock';
            } else {
                $stockStatus = 'In Stock';
            }
            
            $rows[] = [
                $item->id,
                $item->name,
                $item->description ?? 'N/A',
                $item->category ?? 'Uncategorized',
                $this->forceZero($item->quantity),
                $this->forceZero($item->minimum_stock ?? 0),
                '₱' . number_format($this->forceZero($item->unit_price ?? 0), 2),
                '₱' . number_format($totalValue, 2),
                $stockStatus
            ];
        }
        
        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Style the title rows
                $sheet->getStyle('A1:E1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => 'center'],
                ]);
                
                $sheet->mergeCells('A1:E1');
                
                $sheet->getStyle('A2:E2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => 'center'],
                ]);
                
                $sheet->mergeCells('A2:E2');
                
                // Style finalized status rows
                $isFinalized = $this->data['isFinalized'] ?? false;
                if ($isFinalized) {
                    $sheet->getStyle('A3')->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => '155724']],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'color' => ['rgb' => 'D4EDDA']
                        ]
                    ]);
                    $sheet->mergeCells('A3:E3');
                    
                    // If there's a finalized date row
                    if (isset($this->data['finalizedAt']) && $this->data['finalizedAt'] instanceof \DateTime) {
                        $sheet->getStyle('A4')->applyFromArray([
                            'font' => ['italic' => true, 'size' => 10],
                        ]);
                        $sheet->mergeCells('A4:E4');
                    }
                } else {
                    // Draft report styling
                    $sheet->getStyle('A3')->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => '856404']],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'color' => ['rgb' => 'FFF3CD']
                        ]
                    ]);
                    $sheet->mergeCells('A3:E3');
                }
                
                // Style section headers
                $lastRow = $sheet->getHighestRow();
                
                for ($row = 1; $row <= $lastRow; $row++) {
                    $cellValue = $sheet->getCell('A' . $row)->getValue();
                    
                    // Check if this is a section header (starts with 📊, 📈, 👥, ⚡, 📉, 💡)
                    if (is_string($cellValue) && preg_match('/^[📊📈👥⚡📉💡]/', $cellValue)) {
                        $sheet->getStyle('A' . $row)->applyFromArray([
                            'font' => ['bold' => true, 'size' => 11],
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['rgb' => 'E8E8E8']
                            ]
                        ]);
                    }
                    
                    // Style table headers (row after section header)
                    if (is_string($cellValue) && preg_match('/^[📊📈👥⚡📉💡]/', $sheet->getCell('A' . ($row - 1))->getValue() ?? '')) {
                        $sheet->getStyle('A' . $row . ':Z' . $row)->applyFromArray([
                            'font' => ['bold' => true],
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['rgb' => 'F2F2F2']
                            ]
                        ]);
                    }
                }
                
                // Auto-size all columns
                foreach (range('A', $sheet->getHighestColumn()) as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}