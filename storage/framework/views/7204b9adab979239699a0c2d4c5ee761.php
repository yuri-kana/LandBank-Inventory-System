<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Usage Patterns Table - Container Made Larger -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-md min-h-[600px]">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
            <h4 class="font-medium text-gray-700">All Items by Request Count</h4>
            <p class="text-sm text-gray-500 mt-1">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($period_info)): ?>
                    Current Month Only (<?php echo e($period_info['month_name']); ?> <?php echo e($period_info['year']); ?>)
                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded ml-2">
                        Monthly Reset • Day <?php echo e($period_info['days_elapsed']); ?>/<?php echo e($period_info['days_in_month']); ?>

                    </span>
                <?php else: ?>
                    Current Month Only (Resets on 1st of each month)
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </p>
        </div>
        <div class="divide-y divide-gray-200 h-[500px] overflow-y-auto">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $topRequestedItems ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-4 hover:bg-gray-50 transition duration-150">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center flex-1">
                            <div class="p-2 rounded-lg bg-blue-100 text-blue-600 mr-3">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-medium text-gray-900 truncate"><?php echo e($item->name); ?></div>
                                <div class="text-sm text-gray-500">
                                    <?php echo e($item->total_requests); ?> requests • <?php echo e($item->teams_info ?? 'No teams'); ?>

                                </div>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium <?php echo e($item->demand_color ?? 'text-gray-500'); ?>">
                                <i class="fas fa-chart-bar mr-1"></i> <?php echo e($item->demand_level ?? 'No Demand'); ?>

                            </div>
                        </div>
                    </div>
                    
                    <!-- Detailed Stats -->
                    <div class="grid grid-cols-3 gap-2 text-xs text-gray-600 mb-3">
                        <div class="bg-gray-50 p-2 rounded">
                            <div class="font-medium">Current Stock</div>
                            <div><?php echo e($item->quantity ?? 0); ?></div>
                        </div>
                        <div class="bg-gray-50 p-2 rounded">
                            <div class="font-medium">Min. Stock</div>
                            <div><?php echo e($item->minimum_stock ?? 0); ?></div>
                        </div>
                        <div class="bg-gray-50 p-2 rounded">
                            <div class="font-medium">Total Qty</div>
                            <div><?php echo e($item->total_quantity ?? 0); ?></div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>Avg. Requests/Day (Monthly)</span>
                            <span class="font-medium"><?php echo e($item->avg_display ?? '0.00/day'); ?></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: <?php echo e(min(($item->total_requests / max($maxRequests, 1)) * 100, 100)); ?>%"></div>
                        </div>
                        <div class="text-xs text-gray-400 mt-1">
                            <?php echo e($item->period_info ?? ''); ?>

                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="p-6 text-center text-gray-500">
                    <i class="fas fa-chart-line text-2xl text-gray-300 mb-2"></i>
                    <p>No usage data available for this month</p>
                    <p class="text-xs text-gray-400 mt-1">Data resets on 1st of each month</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <!-- Most Active Teams - Container Made Larger -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-md min-h-[600px]">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
            <h4 class="font-medium text-gray-700">Most Active Teams</h4>
            <p class="text-sm text-gray-500 mt-1">
                Team request patterns 
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($period_info)): ?>
                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded ml-2">
                        <?php echo e($period_info['month_name']); ?> <?php echo e($period_info['year']); ?>

                    </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </p>
        </div>
        <div class="h-[500px] overflow-y-auto p-6">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($mostActiveTeams) && count($mostActiveTeams) > 0): ?>
                <div class="space-y-6">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $mostActiveTeams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <div>
                                    <div class="font-medium text-gray-900"><?php echo e($team->name); ?></div>
                                    <div class="text-xs text-gray-500">
                                        <?php echo e($team->members_count); ?> members • 
                                        <?php echo e($team->item_count ?? 0); ?> different items
                                    </div>
                                </div>
                                <div class="text-sm font-medium text-blue-600">
                                    <?php echo e($team->request_count); ?> requests
                                </div>
                            </div>
                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($team->requested_items): ?>
                                <div class="text-xs text-gray-600 mb-2">
                                    <span class="font-medium">Items: </span>
                                    <?php echo e(Str::limit($team->requested_items, 60)); ?>

                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <?php
                                    $maxRequests = $mostActiveTeams->max('request_count');
                                    $percentage = $maxRequests > 0 ? ($team->request_count / $maxRequests) * 100 : 0;
                                ?>
                                <div class="bg-blue-500 h-2 rounded-full" style="width: <?php echo e($percentage); ?>%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span><?php echo e($team->total_quantity ?? 0); ?> total quantity</span>
                                <span>Avg: <?php echo e($team->request_count > 0 ? round($team->total_quantity / $team->request_count, 1) : 0); ?>/request</span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-users text-2xl text-gray-300 mb-2"></i>
                    <p class="text-gray-500">No team request data available this month</p>
                    <p class="text-xs text-gray-400 mt-1">Data resets on 1st of each month</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div><?php /**PATH C:\Users\Jhon Rhey\Downloads\Land Bank Inventory System\Land Bank Inventory System\inventory-system\resources\views/tabs/usage-patterns.blade.php ENDPATH**/ ?>