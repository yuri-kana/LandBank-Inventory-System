<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Monthly Inventory Report - {{ $period }}</title>
</head>
<body>
    <h1>Land Bank Inventory System</h1>
    <h2>Monthly Inventory Analysis Report</h2>
    <h3>{{ $period }}</h3>
    <p><strong>Generated on:</strong> {{ $generatedAt->format('F d, Y h:i A') }}</p>
    @if($isFinalized)<p><strong>Status:</strong> FINALIZED REPORT</p>@endif
    
    <hr>
    
    <h2>📊 Executive Summary</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <td><strong>Total Requests:</strong> {{ number_format($totalRequests) }}</td>
            <td><strong>Items Restocked:</strong> {{ number_format($totalRestocked) }}</td>
            <td><strong>Items Claimed:</strong> {{ number_format($totalClaimed) }}</td>
        </tr>
        <tr>
            <td><strong>Active Teams:</strong> {{ number_format($activeTeams) }}</td>
            <td><strong>Active Items:</strong> {{ number_format($totalItems) }}</td>
            <td><strong>Activity Level:</strong> {{ $averageActivity }}</td>
        </tr>
    </table>
    
    <h2>📈 Inventory Flow Analysis</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>ITEM</th>
                <th>BEGINNING</th>
                <th>REQUESTED</th>
                <th>CLAIMED</th>
                <th>RESTOCKED</th>
                <th>ENDING</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventoryFlowItems as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td align="right">{{ number_format($item->beginning_quantity) }}</td>
                <td align="right">{{ number_format($item->requested_quantity) }}</td>
                <td align="right" style="color: red;">-{{ number_format($item->claimed_quantity) }}</td>
                <td align="right" style="color: green;">+{{ number_format($item->restocked_quantity) }}</td>
                <td align="right">{{ number_format($item->ending_quantity) }}</td>
                <td>{{ $item->status }}</td>
            </tr>
            @empty
            <tr><td colspan="7" align="center">No inventory flow data available</td></tr>
            @endforelse
        </tbody>
    </table>
    
    <h2>📊 Usage Patterns - All Items by Request Count (Last 30 Days)</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr style="background-color: #f2f2f2;">
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
                <td align="right">{{ number_format($item->quantity) }}</td>
                <td align="right">{{ number_format($item->minimum_stock) }}</td>
                <td align="right">{{ number_format($item->total_requests) }}</td>
                <td align="right">{{ number_format($item->avg_requests_per_day, 2) }}</td>
                <td>
                    @if($item->total_requests > 20) High Demand
                    @elseif($item->total_requests > 10) Medium Demand
                    @elseif($item->total_requests > 0) Normal Demand
                    @else No Demand
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6" align="center">No usage data available</td></tr>
            @endforelse
        </tbody>
    </table>
    
    <h2>👥 Most Teams Requested</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>TEAM</th>
                <th>MEMBERS</th>
                <th>REQUEST COUNT</th>
                <th>PERCENTAGE</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mostActiveTeams as $team)
            <tr>
                <td>{{ $team->name }}</td>
                <td align="right">{{ number_format($team->members_count) }}</td>
                <td align="right">{{ number_format($team->request_count) }}</td>
                <td align="right">
                    @php
                        $maxRequests = $mostActiveTeams->max('request_count');
                        $percentage = $maxRequests > 0 ? ($team->request_count / $maxRequests) * 100 : 0;
                    @endphp
                    {{ number_format($percentage, 1) }}%
                </td>
            </tr>
            @empty
            <tr><td colspan="4" align="center">No team request data available</td></tr>
            @endforelse
        </tbody>
    </table>
    
    <h2>⚡ Fastest Depleting Items</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr style="background-color: #f2f2f2;">
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
            <tr>
                <td>{{ $item->name }}</td>
                <td align="right">{{ number_format($item->quantity) }}</td>
                <td align="right">{{ number_format($item->minimum_stock) }}</td>
                <td align="right">{{ number_format($item->requests_last_30_days) }}</td>
                <td align="right">{{ number_format($item->depletion_rate, 1) }}%</td>
                <td align="right">
                    @if($item->days_to_depletion == 999 || $item->days_to_depletion >= 999)
                        ∞ days
                    @else
                        {{ $item->days_to_depletion }} days
                    @endif
                </td>
                <td>
                    @if($item->quantity <= 0) Out of Stock
                    @elseif($item->quantity <= $item->minimum_stock) Low Stock
                    @elseif($item->days_to_depletion <= 7) Critical
                    @elseif($item->days_to_depletion <= 14) Warning
                    @else Normal
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" align="center">No depletion data available</td></tr>
            @endforelse
        </tbody>
    </table>
    
    <h2>📉 Depletion Analysis</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <td align="center" style="background-color: #f8d7da;">
                <strong>{{ $criticalItemsCount }}</strong><br>Out of Stock
            </td>
            <td align="center" style="background-color: #fff3cd;">
                <strong>{{ $warningItemsCount }}</strong><br>Low Stock
            </td>
            <td align="center" style="background-color: #d4edda;">
                <strong>{{ $safeItemsCount }}</strong><br>In Stock
            </td>
        </tr>
    </table>
    
    @if(isset($yearlyComparison))
    <h2>📅 Yearly Comparison</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <td align="center">{{ $yearlyComparison['previous_year']['year'] ?? ($selectedYear - 1) }}<br>
                <strong>{{ number_format($yearlyComparison['previous_year']['total_requests'] ?? 0) }}</strong>
            </td>
            <td align="center">Comparison<br>
                <strong>
                    @if(($yearlyComparison['previous_year']['total_requests'] ?? 0) > 0)
                        {{ $yearlyComparison['comparison']['direction'] == 'up' ? '↑' : ($yearlyComparison['comparison']['direction'] == 'down' ? '↓' : '→') }}
                        {{ number_format($yearlyComparison['comparison']['change'] ?? 0, 1) }}%
                    @else
                        N/A
                    @endif
                </strong>
            </td>
            <td align="center">{{ $selectedYear }} YTD<br>
                <strong>{{ number_format($yearlyComparison['current_year']['total_requests'] ?? 0) }}</strong>
            </td>
        </tr>
    </table>
    @endif
    
    <h2>💡 Recommendations</h2>
    <ul>
        @php
            $recommendations = [];
            if ($criticalItemsCount > 0) $recommendations[] = "Immediate restocking needed for {$criticalItemsCount} out-of-stock items";
            if ($warningItemsCount > 0) $recommendations[] = "Schedule restocking for {$warningItemsCount} low-stock items";
            $criticalDepletion = $fastDepletingItems->where('days_to_depletion', '<=', 7)->count();
            if ($criticalDepletion > 0) $recommendations[] = "Review procurement schedule for {$criticalDepletion} critically depleting items";
            $highDemand = $topRequestedItems->where('total_requests', '>', 20)->count();
            if ($highDemand > 0) $recommendations[] = "Consider increasing stock levels for {$highDemand} high-demand items";
            if (empty($recommendations)) $recommendations[] = "Inventory levels are optimal";
        @endphp
        
        @foreach($recommendations as $rec)
        <li>{{ $rec }}</li>
        @endforeach
    </ul>
    
    <hr>
    <p><em>Report generated by Land Bank Inventory System</em></p>
</body>
</html>