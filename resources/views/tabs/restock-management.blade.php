<div class="bg-white rounded-lg border border-gray-200">
    <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
        <div class="flex justify-between items-center">
            <div>
                <h4 class="font-medium text-gray-700">Inventory Flow & Restock Management</h4>
                <p class="text-sm text-gray-500 mt-1">Track inventory movement through the restocking process (Last 30 Days)</p>
            </div>
            <!-- Start Restocking Button - Using global function -->
            <div class="relative">
                <button onclick="startRestocking()" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center shadow-md transition duration-200">
                    <i class="fas fa-play-circle mr-2"></i> Start Restocking
                </button>
            </div>
        </div>
    </div>
    
    <!-- Table container without horizontal scroll -->
    <div class="w-full overflow-hidden">
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ITEM
                    </th>
                    <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        BEGINNING
                    </th>
                    <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        REQUESTED
                    </th>
                    <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        APPROVED
                    </th>
                    <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        CLAIMED
                    </th>
                    <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        RESTOCKED
                    </th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ENDING
                    </th>
                    <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        STATUS
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="restock-table-body">
                @forelse($inventoryFlowItems as $item)
                    @php
                        // Get the separated values
                        $pendingRequests = $item->pending_quantity ?? 0;
                        $approvedRequests = $item->approved_quantity ?? 0;
                        $claimedRequests = $item->claimed_quantity ?? 0;
                        $restockedAmount = $item->restocked_quantity ?? 0;
                        
                        // Calculate AVAILABLE STOCK: Total Quantity - Approved Requests ONLY
                        // Pending requests don't affect available stock until approved
                        $availableStock = max(0, $item->quantity - $approvedRequests);
                        
                        // Calculate if there are pending requests
                        $hasPending = $pendingRequests > 0;
                        $hasApproved = $approvedRequests > 0;
                        $hasClaimed = $claimedRequests > 0;
                        $hasRestocked = $restockedAmount > 0;
                        
                        // Status colors
                        $statusColors = [
                            'Out of Stock' => 'bg-red-100 text-red-800',
                            'Needs Restock' => 'bg-yellow-100 text-yellow-800',
                            'Restocked' => 'bg-green-100 text-green-800',
                            'In Stock' => 'bg-gray-100 text-gray-800',
                            'Approved' => 'bg-blue-100 text-blue-800',
                            'Pending' => 'bg-orange-100 text-orange-800',
                        ];
                        $statusColor = $statusColors[$item->status] ?? 'bg-gray-100 text-gray-800';
                        
                        // Color for ending stock column
                        $endingColorClass = $availableStock <= 0 ? 'text-red-600 font-bold' : 
                                           ($availableStock <= $item->minimum_stock ? 'text-yellow-600 font-semibold' : 'text-gray-900 font-semibold');
                        
                        // Highlight rows based on status
                        $rowBgClass = '';
                        if ($hasPending) {
                            $rowBgClass = 'bg-orange-50 hover:bg-orange-100';
                        } elseif ($hasApproved) {
                            $rowBgClass = 'bg-blue-50 hover:bg-blue-100';
                        } elseif ($hasClaimed || $hasRestocked) {
                            $rowBgClass = 'bg-blue-50 hover:bg-blue-100';
                        }
                    @endphp
                    
                    <tr class="transition-colors {{ $rowBgClass }}">
                        <!-- ITEM -->
                        <td class="px-3 py-4">
                            <div class="font-medium text-gray-900">{{ $item->name }}</div>
                            <div class="text-xs text-gray-500">{{ $item->category ?? 'General' }}</div>
                            <div class="text-xs text-gray-500">{{ $item->unit ?? 'units' }}</div>
                        </td>
                        
                        <!-- BEGINNING QUANTITY (30 days ago) -->
                        <td class="px-3 py-4 text-center">
                            <div class="text-sm text-gray-900">
                                {{ $item->beginning_quantity ?? 0 }}
                            </div>
                        </td>
                        
                        <!-- REQUESTED QUANTITY (PENDING APPROVAL) -->
                        <td class="px-3 py-4 text-center">
                            @if($hasPending)
                            <div class="text-sm">
                                <div class="text-orange-600 font-medium">{{ $pendingRequests }}</div>
                            </div>
                            @else
                            <div class="text-sm text-gray-400">0</div>
                            @endif
                        </td>
                        
                        <!-- APPROVED QUANTITY (RESERVED FROM STOCK) -->
                        <td class="px-3 py-4 text-center">
                            @if($hasApproved)
                            <div class="text-sm">
                                <div class="text-blue-600 font-medium">{{ $approvedRequests }}</div>
                            </div>
                            @else
                            <div class="text-sm text-gray-400">0</div>
                            @endif
                        </td>
                        
                        <!-- CLAIMED QUANTITY (ACTUALLY TAKEN FROM STOCK) -->
                        <td class="px-3 py-4 text-center">
                            @if($hasClaimed)
                            <div class="text-sm text-purple-600 font-medium">
                                {{ $claimedRequests }}
                            </div>
                            @else
                            <div class="text-sm text-gray-400">0</div>
                            @endif
                        </td>
                        
                        <!-- RESTOCKED QUANTITY -->
                        <td class="px-3 py-4 text-center">
                            @if($hasRestocked)
                            <div class="text-sm text-green-600 font-medium">
                                +{{ $restockedAmount }}
                            </div>
                            @else
                            <div class="text-sm text-gray-400">0</div>
                            @endif
                        </td>
                        
                        <!-- ENDING QUANTITY (AVAILABLE STOCK = TOTAL - APPROVED) -->
                        <td class="px-3 py-4">
                            <div class="text-sm font-semibold {{ $endingColorClass }}">
                                {{ $availableStock }}
                            </div>
                            @if($item->minimum_stock > 0)
                            <div class="text-xs text-gray-500">Min: {{ $item->minimum_stock }}</div>
                            @endif
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $item->quantity }} total - {{ $approvedRequests }} approved
                            </div>
                        </td>
                        
                        <!-- STATUS -->
                        <td class="px-3 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                {{ $item->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-exchange-alt text-2xl text-gray-300 mb-2"></i>
                        <p>No inventory flow activity in the last 30 days</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Footer with notes and summary -->
    <div class="p-4 border-t border-gray-200 bg-gray-50">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="text-sm text-gray-600">
                <i class="fas fa-info-circle mr-1"></i>
                <span class="font-medium">Note:</span> 
                <span class="text-orange-600">"Requested"</span> = Pending approval (doesn't affect available stock). 
                <span class="text-blue-600">"Approved"</span> = Reserved stock (reduces available stock). 
                <span class="text-purple-600">"Claimed"</span> = Actually taken from stock.
            </div>
            @if(isset($inventoryFlowItems) && count($inventoryFlowItems) > 0)
            <div class="text-sm text-gray-600">
                @php
                    $totalPending = 0;
                    $totalApproved = 0;
                    $totalClaimed = 0;
                    $totalRestocked = 0;
                    
                    foreach ($inventoryFlowItems as $item) {
                        $totalPending += $item->pending_quantity ?? 0;
                        $totalApproved += $item->approved_quantity ?? 0;
                        $totalClaimed += $item->claimed_quantity ?? 0;
                        $totalRestocked += $item->restocked_quantity ?? 0;
                    }
                    
                    $claimRate = $totalApproved > 0 ? number_format(($totalClaimed / $totalApproved) * 100, 1) : 0;
                    $approvalRate = $totalPending > 0 ? number_format(($totalApproved / $totalPending) * 100, 1) : 0;
                @endphp
                <span class="font-medium">Summary:</span> 
                <span class="mx-1 text-orange-600">{{ $totalPending }} pending</span> •
                <span class="mx-1 text-blue-600">{{ $totalApproved }} approved</span> •
                <span class="mx-1 text-purple-600">{{ $totalClaimed }} claimed</span> •
                <span class="mx-1 text-green-600">+{{ $totalRestocked }} restocked</span>
                @if($totalApproved > 0)
                <span class="ml-2 text-xs">({{ $claimRate }}% claim rate)</span>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add CSS to prevent horizontal scrolling -->
<style>
/* Ensure table fits within container without horizontal scroll */
.w-full.overflow-hidden {
    overflow-x: hidden;
    width: 100%;
}

/* Make table responsive */
table {
    width: 100%;
    table-layout: auto;
}

/* Adjust column widths */
th:nth-child(1), td:nth-child(1) { /* ITEM */
    min-width: 180px;
    max-width: 250px;
}

th:nth-child(2), td:nth-child(2), /* BEGINNING */
th:nth-child(3), td:nth-child(3), /* REQUESTED */
th:nth-child(4), td:nth-child(4), /* APPROVED */
th:nth-child(5), td:nth-child(5), /* CLAIMED */
th:nth-child(6), td:nth-child(6) { /* RESTOCKED */
    min-width: 70px;
    max-width: 90px;
}

th:nth-child(7), td:nth-child(7) { /* ENDING */
    min-width: 140px;
    max-width: 200px;
}

th:nth-child(8), td:nth-child(8) { /* STATUS */
    min-width: 100px;
    max-width: 120px;
}

/* Responsive adjustments */
@media (max-width: 1400px) {
    table {
        font-size: 0.875rem;
    }
    
    .px-3 {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
}

@media (max-width: 1200px) {
    th, td {
        white-space: nowrap;
    }
    
    .px-3 {
        padding-left: 0.25rem;
        padding-right: 0.25rem;
    }
}
</style>

<!-- Tab-specific initialization (optional) -->
<script>
// Listen for when this tab becomes visible
window.addEventListener('tab-restock-shown', function() {
    console.log('🎯 Restock management tab is now visible');
    
    // Test that startRestocking function is available
    const button = document.querySelector('button[onclick="startRestocking()"]');
    if (button) {
        console.log('✅ Start Restocking button found and ready');
        // Add visual feedback
        button.classList.add('animate-pulse');
        setTimeout(() => button.classList.remove('animate-pulse'), 1000);
    }
});
</script>