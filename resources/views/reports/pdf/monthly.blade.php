<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Monthly Inventory Report - {{ $period }}</title>
    <style>
        @page { margin: 20px; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .section { margin-bottom: 20px; page-break-inside: avoid; }
        .section-title { background-color: #f0f0f0; padding: 8px; font-weight: bold; border-left: 4px solid #333; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th { background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 8px; text-align: left; font-weight: bold; }
        td { border: 1px solid #dee2e6; padding: 8px; }
        .summary-box { border: 1px solid #dee2e6; padding: 15px; margin-bottom: 15px; background-color: #f8f9fa; }
        .highlight { background-color: #fff3cd; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
        .page-break { page-break-before: always; }
        .badge { padding: 3px 8px; border-radius: 12px; font-size: 10px; }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-warning { background-color: #fff3cd; color: #856404; }
        .badge-danger { background-color: #f8d7da; color: #721c24; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .finalized-badge { 
            display: inline-block; 
            padding: 8px 15px; 
            border-radius: 5px; 
            font-weight: bold;
            margin: 10px 0;
        }
        .finalized-yes { 
            background-color: #d4edda; 
            color: #155724; 
            border: 1px solid #c3e6cb;
        }
        .finalized-no { 
            background-color: #fff3cd; 
            color: #856404; 
            border: 1px solid #ffeaa7;
        }
        .total-row { 
            background-color: #f8f9fa; 
            font-weight: bold; 
            border-top: 2px solid #333;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <h1 style="margin-bottom: 5px;">Land Bank Inventory System</h1>
        <h2 style="margin-top: 0; color: #666;">Monthly Inventory Analysis Report</h2>
        <h3 style="color: #333;">{{ $period }}</h3>
        <p>Generated on: {{ $generatedAt->format('F d, Y h:i A') }}</p>
        
        <!-- FINALIZED STATUS BADGE -->
        <div class="finalized-badge {{ $isFinalized ? 'finalized-yes' : 'finalized-no' }}">
            @if($isFinalized)
                ✓ FINALIZED REPORT
                @if(isset($finalizedAt) && $finalizedAt)
                @endif
            @else
                ⚠️ DRAFT REPORT
            @endif
        </div>
    </div>

    <!-- Executive Summary -->
    <div class="section">
        <div class="section-title">📊 Executive Summary</div>
        <div class="summary-box">
            <table>
                <tr>
                    <td width="33%"><strong>Total Items:</strong> {{ number_format($totalItems) }}</td>
                    <td width="33%"><strong>Items Restocked:</strong> {{ number_format($totalRestocked) }}</td>
                    <td width="33%"><strong>Items Claimed:</strong> {{ number_format($totalClaimed) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Request Status Breakdown -->
<div class="section">
    <div class="section-title">📊 Request Status Breakdown</div>
    <table>
        <thead>
            <tr>
                <th>STATUS</th>
                <th class="text-right">COUNT</th>
                <th class="text-right">PERCENTAGE</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = $request_stats['total_requests'] ?? 0;
            @endphp
            <tr>
                <td><strong>Total Requests</strong></td>
                <td class="text-right"><strong>{{ number_format($total) }}</strong></td>
                <td class="text-right"><strong>100%</strong></td>
            </tr>
            <tr>
                <td>✅ Approved</td>
                <td class="text-right">{{ number_format($request_stats['approved_requests'] ?? 0) }}</td>
                <td class="text-right">{{ $total > 0 ? number_format((($request_stats['approved_requests'] ?? 0) / $total) * 100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td>❌ Rejected</td>
                <td class="text-right">{{ number_format($request_stats['rejected_requests'] ?? 0) }}</td>
                <td class="text-right">{{ $total > 0 ? number_format((($request_stats['rejected_requests'] ?? 0) / $total) * 100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td>📦 Claimed</td>
                <td class="text-right">{{ number_format($request_stats['claimed_requests'] ?? 0) }}</td>
                <td class="text-right">{{ $total > 0 ? number_format((($request_stats['claimed_requests'] ?? 0) / $total) * 100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td>⏳ Pending</td>
                <td class="text-right">{{ number_format($request_stats['pending_requests'] ?? 0) }}</td>
                <td class="text-right">{{ $total > 0 ? number_format((($request_stats['pending_requests'] ?? 0) / $total) * 100, 1) : 0 }}%</td>
            </tr>
        </tbody>
    </table>
</div>

    <!-- Inventory Flow Analysis -->
    <div class="section">
        <div class="section-title">📈 Inventory Flow Analysis</div>
        <table>
            <thead>
                <tr>
                    <th>ITEM</th>
                    <th class="text-right">BEGINNING</th>
                    <th class="text-right">REQUESTED</th>
                    <th class="text-right">CLAIMED</th>
                    <th class="text-right">RESTOCKED</th>
                    <th class="text-right">ENDING</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventoryFlowItems as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="text-right">{{ number_format($item->beginning_quantity) }}</td>
                    <td class="text-right">{{ number_format($item->requested_quantity) }}</td>
                    <td class="text-right" style="color: #dc3545;">-{{ number_format($item->claimed_quantity) }}</td>
                    <td class="text-right" style="color: #28a745;">+{{ number_format($item->restocked_quantity) }}</td>
                    <td class="text-right">{{ number_format($item->ending_quantity) }}</td>
                    <td>
                        @if($item->status === 'Out of Stock')
                            <span class="badge badge-danger">Out of Stock</span>
                        @elseif($item->status === 'Needs Restock')
                            <span class="badge badge-warning">Needs Restock</span>
                        @elseif($item->status === 'Restocked')
                            <span class="badge badge-success">Restocked</span>
                        @else
                            <span class="badge badge-success">In Stock</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No inventory flow data available</td>
                </tr>
                @endforelse
                
                <!-- TOTAL ROW -->
                @if($inventoryFlowItems->count() > 0)
                <tr class="total-row">
                    <td><strong>TOTAL</strong></td>
                    <td class="text-right"><strong>{{ number_format($inventoryFlowItems->sum('beginning_quantity')) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($inventoryFlowItems->sum('requested_quantity')) }}</strong></td>
                    <td class="text-right"><strong style="color: #dc3545;">-{{ number_format($inventoryFlowItems->sum('claimed_quantity')) }}</strong></td>
                    <td class="text-right"><strong style="color: #28a745;">+{{ number_format($inventoryFlowItems->sum('restocked_quantity')) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($inventoryFlowItems->sum('ending_quantity')) }}</strong></td>
                    <td>
                        @php
                            $totalItemsNeedingRestock = $inventoryFlowItems->where('status', 'Needs Restock')->count();
                            $totalItemsOutOfStock = $inventoryFlowItems->where('status', 'Out of Stock')->count();
                        @endphp
                        <small>
                            @if($totalItemsOutOfStock > 0)
                                {{ $totalItemsOutOfStock }} out of stock
                            @endif
                            @if($totalItemsNeedingRestock > 0)
                                {{ $totalItemsOutOfStock > 0 ? ', ' : '' }}{{ $totalItemsNeedingRestock }} need restock
                            @endif
                        </small>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
        
        <!-- Flow Summary -->
        <div class="summary-box">
            <h4>Flow Summary:</h4>
            <p><strong>Total Beginning Value:</strong> {{ number_format($inventoryFlowItems->sum('beginning_quantity')) }} units</p>
            <p><strong>Net Change:</strong> 
                @php
                    $netChange = $inventoryFlowItems->sum('restocked_quantity') - $inventoryFlowItems->sum('claimed_quantity');
                @endphp
                @if($netChange > 0)
                    <span style="color: #28a745;">+{{ number_format($netChange) }} units (Increase)</span>
                @elseif($netChange < 0)
                    <span style="color: #dc3545;">{{ number_format($netChange) }} units (Decrease)</span>
                @else
                    <span>{{ number_format($netChange) }} units (No Change)</span>
                @endif
            </p>
            <p><strong>Items Needing Restock:</strong> {{ $inventoryFlowItems->where('status', 'Needs Restock')->count() }}</p>
            <p><strong>Items Out of Stock:</strong> {{ $inventoryFlowItems->where('status', 'Out of Stock')->count() }}</p>
        </div>
    </div>

    <div class="page-break"></div>

    <!-- Usage Patterns -->
    <div class="section">
        <div class="section-title">📊 Usage Patterns - All Items by Request Count (Last 30 Days)</div>
        <table>
            <thead>
                <tr>
                    <th>ITEM</th>
                    <th>CURRENT STOCK</th>
                    <th>MINIMUM STOCK</th>
                    <th>TOTAL REQUESTS</th>
                    <th>AVG REQUESTS/DAY</th>
                    <th>DEMAND LEVEL</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topRequestedItems as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="text-right">{{ number_format($item->quantity) }}</td>
                    <td class="text-right">{{ number_format($item->minimum_stock) }}</td>
                    <td class="text-right">{{ number_format($item->total_requests) }}</td>
                    <td class="text-right">{{ number_format($item->avg_requests_per_day, 2) }}</td>
                    <td>
                        @if($item->total_requests > 20)
                            <span class="badge badge-danger">High Demand</span>
                        @elseif($item->total_requests > 10)
                            <span class="badge badge-warning">Medium Demand</span>
                        @elseif($item->total_requests > 0)
                            <span class="badge badge-success">Normal Demand</span>
                        @else
                            <span>No Demand</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No usage data available</td>
                </tr>
                @endforelse
                
                <!-- TOTAL ROW for Usage Patterns -->
                @if($topRequestedItems->count() > 0)
                <tr class="total-row">
                    <td><strong>TOTAL</strong></td>
                    <td class="text-right"><strong>{{ number_format($topRequestedItems->sum('quantity')) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($topRequestedItems->sum('minimum_stock')) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($topRequestedItems->sum('total_requests')) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($topRequestedItems->avg('avg_requests_per_day'), 2) }}</strong></td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Most Active Teams -->
    <div class="section">
        <div class="section-title">👥 Most Active Teams</div>
        <table>
            <thead>
                <tr>
                    <th>TEAM</th>
                    <th>MEMBERS</th>
                    <th>REQUEST COUNT</th>
                    <th>PERCENTAGE</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Sort teams alphabetically by name
                    $sortedTeams = collect($mostActiveTeams)->sortBy(function($team) {
                        // Extract numeric part for natural sorting (Team 1, Team 2, etc.)
                        $name = $team->name ?? $team['name'] ?? '';
                        preg_match('/\d+/', $name, $matches);
                        $number = isset($matches[0]) ? (int)$matches[0] : 999;
                        return $number;
                    })->values();
                    
                    $maxRequests = $sortedTeams->max('request_count') ?? 1;
                    $totalMembers = $sortedTeams->sum('members_count');
                    $totalRequests = $sortedTeams->sum('request_count');
                    $teamCount = $sortedTeams->count();
                @endphp
                
                @forelse($sortedTeams as $team)
                <tr>
                    <td>{{ $team->name ?? $team['name'] ?? 'N/A' }}</td>
                    <td class="text-right">{{ number_format($team->members_count ?? $team['members_count'] ?? 0) }}</td>
                    <td class="text-right">{{ number_format($team->request_count ?? $team['request_count'] ?? 0) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No team request data available</td>
                </tr>
                @endforelse
                
                <!-- TOTAL ROW -->
                @if($teamCount > 0)
                <tr class="total-row">
                    <td><strong>TOTAL</strong></td>
                    <td class="text-right"><strong>{{ number_format($totalMembers) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($totalRequests) }}</strong></td>
                    <td class="text-right">
                        @php
                            $avgPercentage = $maxRequests > 0 ? ($totalRequests / ($maxRequests * $teamCount)) * 100 : 0;
                        @endphp
                        <strong>{{ number_format($avgPercentage, 1) }}%</strong> (avg)
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- Fastest Depleting Items -->
    <div class="section">
        <div class="section-title">⚡ Fastest Depleting Items</div>
        <table>
            <thead>
                <tr>
                    <th>ITEM</th>
                    <th>CURRENT STOCK</th>
                    <th>MINIMUM STOCK</th>
                    <th>REQUESTS (30 DAYS)</th>
                    <th>DEPLETION RATE</th>
                    <th>DAYS TO DEPLETION</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fastDepletingItems as $item)
                <tr class="{{ $item->days_to_depletion <= 7 ? 'highlight' : '' }}">
                    <td>{{ $item->name }}</td>
                    <td class="text-right">{{ number_format($item->quantity) }}</td>
                    <td class="text-right">{{ number_format($item->minimum_stock) }}</td>
                    <td class="text-right">{{ number_format($item->requests_last_30_days) }}</td>
                    <td class="text-right">
                        <span style="color: {{ $item->depletion_rate > 50 ? '#dc3545' : ($item->depletion_rate > 0 ? '#ffc107' : '#28a745') }}">
                            {{ number_format($item->depletion_rate, 1) }}%
                        </span>
                    </td>
                    <td class="text-right">
                        @if($item->days_to_depletion == 999 || $item->days_to_depletion >= 999)
                            ∞ days
                        @else
                            <span style="color: {{ $item->days_to_depletion <= 7 ? '#dc3545' : ($item->days_to_depletion <= 14 ? '#ffc107' : '#28a745') }}">
                                {{ $item->days_to_depletion }} days
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($item->quantity <= 0)
                            <span class="badge badge-danger">Out of Stock</span>
                        @elseif($item->quantity <= $item->minimum_stock)
                            <span class="badge badge-warning">Low Stock</span>
                        @elseif($item->days_to_depletion <= 7)
                            <span class="badge badge-danger">Critical</span>
                        @elseif($item->days_to_depletion <= 14)
                            <span class="badge badge-warning">Warning</span>
                        @else
                            <span class="badge badge-success">Normal</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No depletion data available</td>
                </tr>
                @endforelse
                
                <!-- TOTAL/SUMMARY ROW for Fast Depleting Items -->
                @if($fastDepletingItems->count() > 0)
                @php
                    $totalCurrentStock = $fastDepletingItems->sum('quantity');
                    $totalMinimumStock = $fastDepletingItems->sum('minimum_stock');
                    $totalRequestsLast30Days = $fastDepletingItems->sum('requests_last_30_days');
                    $avgDepletionRate = $fastDepletingItems->avg('depletion_rate');
                    $criticalItems = $fastDepletingItems->where('days_to_depletion', '<=', 7)->count();
                    $warningItems = $fastDepletingItems->whereBetween('days_to_depletion', [8, 14])->count();
                    $normalItems = $fastDepletingItems->where('days_to_depletion', '>', 14)->count();
                @endphp
                <tr class="total-row">
                    <td><strong>SUMMARY</strong></td>
                    <td class="text-right"><strong>{{ number_format($totalCurrentStock) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($totalMinimumStock) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($totalRequestsLast30Days) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($avgDepletionRate, 1) }}%</strong></td>
                    <td class="text-right">
                        @if($totalCurrentStock > 0)
                            <strong>
                                @php
                                    $totalDailyUsage = $totalRequestsLast30Days / 30;
                                    $avgDaysToDepletion = $totalDailyUsage > 0 ? round($totalCurrentStock / $totalDailyUsage) : 999;
                                @endphp
                                {{ $avgDaysToDepletion == 999 ? '∞ days' : $avgDaysToDepletion . ' days' }}
                            </strong>
                        @else
                            <strong>0 days</strong>
                        @endif
                    </td>
                    <td>
                        <small>
                            C:{{ $criticalItems }} W:{{ $warningItems }} N:{{ $normalItems }}
                        </small>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Depletion Analysis -->
    <div class="section">
        <div class="section-title">📉 Depletion Analysis - Stock Level Predictions</div>
        <div class="summary-box">
            <h4>Stock Status Categories</h4>
            <table>
                <tr>
                    <td width="33%" class="text-center" style="background-color: #f8d7da; padding: 15px;">
                        <h3 style="margin: 0; color: #721c24;">{{ $criticalItemsCount }}</h3>
                        <p style="margin: 5px 0 0 0; color: #721c24;">Out of Stock</p>
                        <small>Immediate attention needed</small>
                    </td>
                    <td width="33%" class="text-center" style="background-color: #fff3cd; padding: 15px;">
                        <h3 style="margin: 0; color: #856404;">{{ $warningItemsCount }}</h3>
                        <p style="margin: 5px 0 0 0; color: #856404;">Low Stock</p>
                        <small>Below minimum levels</small>
                    </td>
                    <td width="33%" class="text-center" style="background-color: #d4edda; padding: 15px;">
                        <h3 style="margin: 0; color: #155724;">{{ $safeItemsCount }}</h3>
                        <p style="margin: 5px 0 0 0; color: #155724;">In Stock</p>
                        <small>Above minimum levels</small>
                    </td>
                </tr>
            </table>
            
            @if($criticalItemsCount > 0)
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-top: 15px; border-radius: 5px;">
                <strong>⚠️ Urgent Attention Needed:</strong> {{ $criticalItemsCount }} items are out of stock and need immediate restocking.
            </div>
            @elseif($warningItemsCount > 0)
            <div style="background-color: #fff3cd; color: #856404; padding: 10px; margin-top: 15px; border-radius: 5px;">
                <strong>⚠️ Low Stock Warning:</strong> {{ $warningItemsCount }} items are below minimum stock levels.
            </div>
            @else
            <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-top: 15px; border-radius: 5px;">
                <strong>✓ Stock Levels Good:</strong> All items are at or above minimum stock levels.
            </div>
            @endif
        </div>
    </div>

    <!-- Yearly Comparison -->
    @if(isset($yearlyComparison))
    <div class="section">
        <div class="section-title">📅 Yearly Comparison Summary</div>
        <div class="summary-box">
            <table>
                <tr>
                    <td style="background-color: #f8f9fa; padding: 15px; text-align: center;">
                        <h4 style="margin: 0;">{{ $yearlyComparison['previous_year']['year'] ?? ($selectedYear - 1) }}</h4>
                        <h2 style="margin: 10px 0; color: #6c757d;">{{ number_format($yearlyComparison['previous_year']['total_requests'] ?? 0) }}</h2>
                        <small>Total Requests</small>
                    </td>
                    <td style="background-color: #e9ecef; padding: 15px; text-align: center;">
                        <h4 style="margin: 0;">Comparison</h4>
                        <h2 style="margin: 10px 0; color: {{ $yearlyComparison['comparison']['direction'] == 'up' ? '#28a745' : ($yearlyComparison['comparison']['direction'] == 'down' ? '#dc3545' : '#6c757d') }};">
                            @if(($yearlyComparison['previous_year']['total_requests'] ?? 0) > 0)
                                {{ $yearlyComparison['comparison']['direction'] == 'up' ? '↑' : ($yearlyComparison['comparison']['direction'] == 'down' ? '↓' : '→') }}
                                {{ number_format($yearlyComparison['comparison']['change'] ?? 0, 1) }}%
                            @else
                                N/A
                            @endif
                        </h2>
                        <small>Year-over-Year Change</small>
                    </td>
                    <td style="background-color: #f8f9fa; padding: 15px; text-align: center;">
                        <h4 style="margin: 0;">{{ $selectedYear }} YTD</h4>
                        <h2 style="margin: 10px 0; color: #007bff;">{{ number_format($yearlyComparison['current_year']['total_requests'] ?? 0) }}</h2>
                        <small>Year-to-Date</small>
                    </td>
                </tr>
            </table>
            
            @if($yearlyComparison['current_year']['most_requested_item'] ?? false)
            <p style="margin-top: 15px;">
                <strong>Most Requested Item in {{ $selectedYear }}:</strong> 
                {{ $yearlyComparison['current_year']['most_requested_item']['name'] }} 
                ({{ $yearlyComparison['current_year']['most_requested_item']['request_count'] }} requests)
            </p>
            @endif
        </div>
    </div>
    @endif

    <!-- Recommendations -->
    <div class="section">
        <div class="section-title">💡 Recommendations & Action Items</div>
        <div class="summary-box">
            @php
                $recommendations = [];
                
                // Check for critical items
                if ($criticalItemsCount > 0) {
                    $recommendations[] = "Immediate restocking needed for {$criticalItemsCount} out-of-stock items";
                }
                
                // Check for low stock items
                if ($warningItemsCount > 0) {
                    $recommendations[] = "Schedule restocking for {$warningItemsCount} low-stock items";
                }
                
                // Check for fast depleting items
                $criticalDepletion = $fastDepletingItems->where('days_to_depletion', '<=', 7)->count();
                if ($criticalDepletion > 0) {
                    $recommendations[] = "Review procurement schedule for {$criticalDepletion} critically depleting items";
                }
                
                // Check for high demand items
                $highDemand = $topRequestedItems->where('total_requests', '>', 20)->count();
                if ($highDemand > 0) {
                    $recommendations[] = "Consider increasing stock levels for {$highDemand} high-demand items";
                }
                
                // If no issues
                if (empty($recommendations)) {
                    $recommendations[] = "Inventory levels are optimal. Continue current restocking schedule";
                }
            @endphp
            
            <ol style="margin: 10px 0; padding-left: 20px;">
                @foreach($recommendations as $rec)
                <li style="margin-bottom: 8px;">{{ $rec }}</li>
                @endforeach
            </ol>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Report ID: {{ $reportId ?? 'N/A' }}</p>
        <p>Report Status: {{ $isFinalized ? 'FINALIZED' : 'DRAFT' }}</p>
        <p>Generated by: Land Bank Inventory System v1.0</p>
        <p>This is a system-generated report. For questions, contact the inventory department.</p>
        <p>Page <pdf:pagenumber> of <pdf:pagecount></p>
    </div>
</body>
</html>