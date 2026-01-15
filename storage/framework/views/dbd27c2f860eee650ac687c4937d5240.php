<div class="bg-white rounded-lg border border-gray-200">
    <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
        <div class="flex justify-between items-center">
            <div>
                <h4 class="font-medium text-gray-700">Inventory Flow & Restock Management</h4>
                <p class="text-sm text-gray-500 mt-1">Track inventory movement through the restocking process (Last 30 Days)</p>
                <div class="flex items-center space-x-4 mt-2 text-xs">
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-orange-500 mr-1"></span>
                        <span class="text-orange-600">Pending Approval</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-blue-500 mr-1"></span>
                        <span class="text-blue-600">Approved (Reserved)</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-purple-500 mr-1"></span>
                        <span class="text-purple-600">Claimed (Taken)</span>
                    </div>
                </div>
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
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ITEM
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        BEGINNING
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        REQUESTED<br>
                        <span class="text-xs font-normal text-orange-600">(Pending Approval)</span>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        APPROVED<br>
                        <span class="text-xs font-normal text-blue-600">(Reserved)</span>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        CLAIMED
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        RESTOCKED
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ENDING<br>
                        <span class="text-xs font-normal">(Available Stock)</span>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        STATUS
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="restock-table-body">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $inventoryFlowItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        // Get the separated values
                        $pendingRequests = $item->pending_quantity ?? 0;
                        $approvedRequests = $item->approved_quantity ?? 0;
                        $claimedRequests = $item->claimed_quantity ?? 0;
                        $declinedRequests = $item->declined_quantity ?? 0;
                        $restockedAmount = $item->restocked_quantity ?? 0;
                        
                        // Calculate AVAILABLE STOCK: Total Quantity - Approved Requests ONLY
                        // Pending requests don't affect available stock until approved
                        $availableStock = max(0, $item->quantity - $approvedRequests);
                        
                        // Calculate if there are pending requests
                        $hasPending = $pendingRequests > 0;
                        $hasApproved = $approvedRequests > 0;
                        $hasDeclined = $declinedRequests > 0;
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
                            'Declined' => 'bg-gray-200 text-gray-800'
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
                    ?>
                    
                    <tr class="transition-colors <?php echo e($rowBgClass); ?>">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900"><?php echo e($item->name); ?></div>
                            <div class="text-xs text-gray-500"><?php echo e($item->category ?? 'General'); ?></div>
                            <div class="text-xs text-gray-500"><?php echo e($item->unit ?? 'units'); ?></div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasDeclined): ?>
                            <div class="text-xs text-red-500 mt-1">
                                <i class="fas fa-times mr-1"></i><?php echo e($declinedRequests); ?> declined
                            </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        
                        <!-- BEGINNING QUANTITY (30 days ago) -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <?php echo e($item->beginning_quantity ?? 0); ?>

                            </div>
                        </td>
                        
                        <!-- REQUESTED QUANTITY (PENDING APPROVAL) -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasPending): ?>
                            <div class="text-sm">
                                <div class="text-orange-600 font-medium"><?php echo e($pendingRequests); ?></div>
                                <div class="text-xs text-gray-500 mt-1">
                                    Waiting admin approval
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="text-sm text-gray-400">0</div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        
                        <!-- APPROVED QUANTITY (RESERVED FROM STOCK) -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasApproved): ?>
                            <div class="text-sm">
                                <div class="text-blue-600 font-medium"><?php echo e($approvedRequests); ?></div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasClaimed): ?>
                                    (<?php echo e($approvedRequests - $claimedRequests); ?> waiting, <?php echo e($claimedRequests); ?> claimed)
                                    <?php else: ?>
                                    (All <?php echo e($approvedRequests); ?> waiting to be claimed)
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="text-sm text-gray-400">0</div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        
                        <!-- CLAIMED QUANTITY (ACTUALLY TAKEN FROM STOCK) -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasClaimed): ?>
                            <div class="text-sm text-purple-600 font-medium">
                                <?php echo e($claimedRequests); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($approvedRequests > 0): ?>
                                <div class="text-xs text-gray-500 mt-1">
                                    <?php echo e(number_format(($claimedRequests / $approvedRequests) * 100, 0)); ?>% of approved
                                </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <?php else: ?>
                            <div class="text-sm text-gray-400">0</div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        
                        <!-- RESTOCKED QUANTITY -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasRestocked): ?>
                            <div class="text-sm text-green-600 font-medium">
                                +<?php echo e($restockedAmount); ?>

                            </div>
                            <?php else: ?>
                            <div class="text-sm text-gray-400">0</div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        
                        <!-- ENDING QUANTITY (AVAILABLE STOCK = TOTAL - APPROVED) -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold <?php echo e($endingColorClass); ?>">
                                <?php echo e($availableStock); ?>

                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->minimum_stock > 0): ?>
                            <div class="text-xs text-gray-500">Min: <?php echo e($item->minimum_stock); ?></div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <div class="text-xs text-gray-500 mt-1">
                                <?php echo e($item->quantity); ?> total - <?php echo e($approvedRequests); ?> approved
                            </div>
                        </td>
                        
                        <!-- STATUS -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="relative group">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($statusColor); ?>">
                                    <?php echo e($item->status); ?>

                                </span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasPending || $hasApproved || $hasClaimed || $hasRestocked || $hasDeclined): ?>
                                <div class="absolute left-0 bottom-full mb-2 hidden group-hover:block bg-gray-800 text-white text-xs rounded py-2 px-3 z-10 min-w-[300px]">
                                    <div class="font-medium mb-1 border-b border-gray-700 pb-1">Activity Summary (30 days):</div>
                                    
                                    <!-- Physical Stock -->
                                    <div class="flex justify-between mb-1">
                                        <span class="text-gray-300">Physical Stock:</span>
                                        <span class="font-medium"><?php echo e($item->quantity); ?></span>
                                    </div>
                                    
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingRequests > 0): ?>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-orange-300">Pending Requests:</span>
                                        <span class="font-medium"><?php echo e($pendingRequests); ?></span>
                                    </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($approvedRequests > 0): ?>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-blue-300">Approved (Reserved):</span>
                                        <span class="font-medium"><?php echo e($approvedRequests); ?></span>
                                    </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($declinedRequests > 0): ?>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-red-300">Declined Requests:</span>
                                        <span class="font-medium"><?php echo e($declinedRequests); ?></span>
                                    </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($claimedRequests > 0): ?>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-purple-300">Actually Claimed:</span>
                                        <span class="font-medium"><?php echo e($claimedRequests); ?></span>
                                    </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($restockedAmount > 0): ?>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-green-300">Restocked:</span>
                                        <span class="font-medium">+<?php echo e($restockedAmount); ?></span>
                                    </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    
                                    <!-- Available Stock Calculation -->
                                    <div class="mt-2 pt-2 border-t border-gray-700">
                                        <div class="text-gray-300 mb-1">Available Stock Calculation:</div>
                                        <div class="text-sm">
                                            <div><?php echo e($item->quantity); ?> (Total) - <?php echo e($approvedRequests); ?> (Approved) = <?php echo e($availableStock); ?> (Available)</div>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->beginning_quantity): ?>
                                            <div class="mt-1">Flow Formula: <?php echo e($item->beginning_quantity); ?> - <?php echo e($claimedRequests); ?> + <?php echo e($restockedAmount); ?> = <?php echo e($item->beginning_quantity - $claimedRequests + $restockedAmount); ?></div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="absolute left-3 top-full -mt-1 border-4 border-transparent border-t-gray-800"></div>
                                </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-exchange-alt text-2xl text-gray-300 mb-2"></i>
                        <p>No inventory flow activity in the last 30 days</p>
                    </td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-200 bg-gray-50">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                <i class="fas fa-info-circle mr-1"></i>
                <span class="font-medium">Note:</span> 
                <span class="text-orange-600">"Requested"</span> = Pending approval (doesn't affect available stock). 
                <span class="text-blue-600">"Approved"</span> = Reserved stock (reduces available stock). 
                <span class="text-purple-600">"Claimed"</span> = Actually taken from stock.
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($inventoryFlowItems) && count($inventoryFlowItems) > 0): ?>
            <div class="text-sm text-gray-600">
                <?php
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
                ?>
                <span class="font-medium">Summary:</span> 
                <span class="mx-2 text-orange-600"><?php echo e($totalPending); ?> pending</span> •
                <span class="mx-2 text-blue-600"><?php echo e($totalApproved); ?> approved</span> •
                <span class="mx-2 text-purple-600"><?php echo e($totalClaimed); ?> claimed</span> •
                <span class="mx-2 text-green-600">+<?php echo e($totalRestocked); ?> restocked</span>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($totalApproved > 0): ?>
                <span class="mx-2">(<?php echo e($claimRate); ?>% claim rate)</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($totalPending > 0): ?>
                <span class="mx-2">(<?php echo e($approvalRate); ?>% approval rate)</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>

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

// Add CSS for tooltips
const style = document.createElement('style');
style.textContent = `
    .relative.group:hover .hidden {
        display: block !important;
    }
    .bg-orange-50 {
        background-color: #fff7ed;
    }
    .bg-blue-50 {
        background-color: #eff6ff;
    }
`;
document.head.appendChild(style);
</script><?php /**PATH C:\Users\Jhon Rhey\Downloads\inventory-system\resources\views/tabs/restock-management.blade.php ENDPATH**/ ?>