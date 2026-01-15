<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Fast Depleting Items - UPDATED TO SHOW CLAIMED & APPROVED -->
    <div class="bg-white rounded-lg border border-gray-200">
        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
            <h4 class="font-medium text-gray-700">Consumption & Reserved Stock Analysis</h4>
            <p class="text-sm text-gray-500 mt-1">
                @if(isset($depletion_period_info))
                    Current Month Only ({{ $depletion_period_info['month_name'] }} {{ $depletion_period_info['year'] }})
                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded ml-2">
                        Monthly Reset • Shows Claimed & Approved Items
                    </span>
                @else
                    Current Month Consumption & Reservation Analysis
                @endif
            </p>
        </div>
        <div class="divide-y divide-gray-200 max-h-[600px] overflow-y-auto">
            @forelse($fastDepletingItems ?? [] as $item)
                @php
                    // Calculate available stock after approved requests
                    $approvedQuantity = $item->approved_quantity ?? 0;
                    $availableStock = max(0, $item->quantity - $approvedQuantity);
                    $reservedPercentage = $item->quantity > 0 ? ($approvedQuantity / $item->quantity) * 100 : 0;
                @endphp
                <div class="p-4 hover:bg-gray-50 transition duration-150">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg 
                                {{ $item->depletion_category === 'critical' ? 'bg-red-100 text-red-600' : 
                                   ($item->depletion_category === 'warning' ? 'bg-amber-100 text-amber-600' : 'bg-gray-100 text-gray-600') }} 
                                mr-3">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">{{ $item->name }}</div>
                                <div class="text-sm text-gray-500">
                                    Total: {{ $item->quantity }} | Min: {{ $item->minimum_stock }}
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold 
                                {{ $item->depletion_category === 'critical' ? 'text-red-600' : 
                                   ($item->depletion_category === 'warning' ? 'text-amber-600' : 'text-gray-600') }}">
                                {{ number_format($item->depletion_rate, 1) }}%
                            </div>
                            <div class="text-xs text-gray-500">monthly consumption</div>
                        </div>
                    </div>
                    
                    <!-- Stock Status Breakdown -->
                    <div class="mt-3 grid grid-cols-2 gap-3 text-xs">
                        <div class="bg-green-50 p-2 rounded">
                            <div class="font-medium text-green-700">Available Stock</div>
                            <div class="text-sm font-bold text-green-800">{{ $availableStock }}</div>
                            <div class="text-gray-500">After approved requests</div>
                        </div>
                        <div class="bg-blue-50 p-2 rounded">
                            <div class="font-medium text-blue-700">Reserved</div>
                            <div class="text-sm font-bold text-blue-800">{{ $approvedQuantity }}</div>
                            <div class="text-gray-500">Approved to claim</div>
                        </div>
                    </div>
                    
                    <!-- Usage Breakdown -->
                    <div class="mt-3">
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <div>
                                <span class="font-medium">This Month:</span>
                                <span class="ml-2 text-blue-600">
                                    {{ $item->claimed_this_month ?? 0 }} claimed
                                </span>
                            </div>
                            <span class="font-medium 
                                {{ $item->days_to_depletion <= 7 ? 'text-red-600' : 
                                   ($item->days_to_depletion <= 14 ? 'text-amber-600' : 'text-emerald-600') }}">
                                @if($item->days_to_depletion == 999 || $item->days_to_depletion >= 999)
                                    ∞ days left
                                @elseif($item->days_to_depletion == 0)
                                    Out of Stock
                                @else
                                    {{ $item->days_to_depletion }} days left
                                @endif
                            </span>
                        </div>
                        
                        <!-- Consumption Progress Bar -->
                        <div class="mb-2">
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Consumption Rate</span>
                                <span>{{ number_format($item->depletion_rate, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="{{ $item->depletion_category === 'critical' ? 'bg-red-500' : 
                                             ($item->depletion_category === 'warning' ? 'bg-amber-500' : 'bg-gray-300') }} 
                                             h-2 rounded-full" 
                                     style="width: {{ min($item->depletion_rate, 100) }}%">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Reservation Progress Bar -->
                        @if($approvedQuantity > 0)
                        <div>
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Reserved Stock</span>
                                <span>{{ number_format($reservedPercentage, 1) }}% reserved</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" 
                                     style="width: {{ min($reservedPercentage, 100) }}%">
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($item->period_info)
                            <div class="text-xs text-gray-400 mt-2">
                                {{ $item->period_info }}
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    <i class="fas fa-bolt text-2xl text-gray-300 mb-2"></i>
                    <p>No consumption data available this month</p>
                    <p class="text-sm mt-2 text-gray-400">
                        @if(isset($depletion_period_info))
                            Data based on {{ $depletion_period_info['month_name'] }} activity
                        @else
                            Try making some claims to see consumption analysis
                        @endif
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Data resets on 1st of each month</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Depletion Analysis - UPDATED WITH RESERVED STOCK INFO -->
    <div class="bg-white rounded-lg border border-gray-200">
        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
            <h4 class="font-medium text-gray-700">Stock & Reservation Analysis</h4>
            <p class="text-sm text-gray-500 mt-1">
                Stock level predictions with reservation impact
                @if(isset($depletion_period_info))
                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded ml-2">
                        {{ $depletion_period_info['month_name'] }} {{ $depletion_period_info['year'] }}
                    </span>
                @endif
            </p>
        </div>
        <div class="p-6 pb-8"> <!-- Added pb-8 for extra bottom padding -->
            <!-- Current Month Summary -->
            @if(isset($fastDepletingItems) && count($fastDepletingItems) > 0)
                @php
                    $criticalDepleting = $fastDepletingItems->where('depletion_category', 'critical')->count();
                    $warningDepleting = $fastDepletingItems->where('depletion_category', 'warning')->count();
                    $normalDepleting = $fastDepletingItems->where('depletion_category', 'normal')->count();
                    
                    // Calculate total reserved stock
                    $totalReservedStock = 0;
                    $itemsWithReservations = 0;
                    foreach($fastDepletingItems as $item) {
                        $approvedQuantity = $item->approved_quantity ?? 0;
                        if($approvedQuantity > 0) {
                            $totalReservedStock += $approvedQuantity;
                            $itemsWithReservations++;
                        }
                    }
                @endphp
                <div class="mb-6">
                    <h5 class="font-medium text-gray-700 mb-3">Monthly Consumption Status</h5>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-center p-3 bg-red-50 rounded-lg">
                            <div class="text-lg font-bold text-red-600">{{ $criticalDepleting }}</div>
                            <div class="text-xs text-red-600 mt-1">Critical</div>
                            <div class="text-xs text-gray-500 mt-1">&gt;50% monthly</div>
                        </div>
                        <div class="text-center p-3 bg-amber-50 rounded-lg">
                            <div class="text-lg font-bold text-amber-600">{{ $warningDepleting }}</div>
                            <div class="text-xs text-amber-600 mt-1">Warning</div>
                            <div class="text-xs text-gray-500 mt-1">20-50% monthly</div>
                        </div>
                        <div class="text-center p-3 bg-emerald-50 rounded-lg">
                            <div class="text-lg font-bold text-emerald-600">{{ $normalDepleting }}</div>
                            <div class="text-xs text-emerald-600 mt-1">Normal</div>
                            <div class="text-xs text-gray-500 mt-1">&lt;20% monthly</div>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Current Stock Status - UPDATED WITH RESERVATIONS -->
            <div class="mb-6">
                <h5 class="font-medium text-gray-700 mb-3">Current Stock Status</h5>
                <div class="grid grid-cols-3 gap-4 mb-3">
                    <div class="text-center p-3 bg-red-50 rounded-lg">
                        <div class="text-lg font-bold text-red-600">{{ $criticalItemsCount ?? 0 }}</div>
                        <div class="text-xs text-red-600 mt-1">Out of Stock</div>
                    </div>
                    <div class="text-center p-3 bg-amber-50 rounded-lg">
                        <div class="text-lg font-bold text-amber-600">{{ $warningItemsCount ?? 0 }}</div>
                        <div class="text-xs text-amber-600 mt-1">Low Stock</div>
                    </div>
                    <div class="text-center p-3 bg-emerald-50 rounded-lg">
                        <div class="text-lg font-bold text-emerald-600">{{ $safeItemsCount ?? 0 }}</div>
                        <div class="text-xs text-emerald-600 mt-1">In Stock</div>
                    </div>
                </div>
                
                <!-- Reserved Stock Summary -->
                @if(isset($totalReservedStock) && $totalReservedStock > 0)
                <div class="p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-full mr-3">
                                <i class="fas fa-clock text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Items Awaiting Claim</p>
                                <p class="text-xs text-gray-600">
                                    {{ $itemsWithReservations }} items with {{ $totalReservedStock }} units reserved
                                </p>
                            </div>
                        </div>
                        <span class="text-sm font-medium text-blue-600">
                            {{ $itemsWithReservations }} items
                        </span>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Alerts with Reservation Context -->
            @if(($criticalItemsCount ?? 0) > 0)
            <div class="p-4 bg-red-50 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-red-500 mt-1 mr-2"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-900 mb-1">Urgent Attention Needed</p>
                        <p class="text-xs text-gray-600">
                            {{ $criticalItemsCount }} items are out of stock and need immediate restocking.
                            @if(isset($itemsWithReservations) && $itemsWithReservations > 0)
                                <span class="font-medium">Note: {{ $itemsWithReservations }} items have approved requests waiting to be claimed.</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            @elseif(($warningItemsCount ?? 0) > 0)
            <div class="p-4 bg-amber-50 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-amber-500 mt-1 mr-2"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-900 mb-1">Low Stock Warning</p>
                        <p class="text-xs text-gray-600">
                            {{ $warningItemsCount }} items are below minimum stock levels.
                            @if(isset($itemsWithReservations) && $itemsWithReservations > 0)
                                <span class="font-medium">Monitor: {{ $itemsWithReservations }} items have approved requests.</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            @else
            <div class="p-4 bg-emerald-50 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-emerald-500 mt-1 mr-2"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-900 mb-1">Stock Levels Good</p>
                        <p class="text-xs text-gray-600">
                            All items are at or above minimum stock levels.
                            @if(isset($itemsWithReservations) && $itemsWithReservations > 0)
                                <span class="font-medium">{{ $itemsWithReservations }} items have approved requests ready for claim.</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            @endif
            
            
            
            <!-- Month Info -->
            @if(isset($depletion_period_info))
                <div class="p-3 bg-blue-50 rounded-lg mt-4">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                        <div class="text-xs text-gray-600">
                            <span class="font-medium">Period:</span> {{ $depletion_period_info['period_text'] }}
                            <br>
                            <span class="font-medium">Month Progress:</span> Day {{ $depletion_period_info['days_elapsed'] }}/{{ $depletion_period_info['days_in_month'] }}
                            <br>
                            <span class="font-medium">Data Shows:</span> Claimed items (consumption) & Approved items (reserved)
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>