<!-- Analytics Modal -->
<div id="analytics-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-7xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <!-- TOP SECTION WITH TITLE, DATE FILTERS, AND BUTTONS -->
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    <i class="fas fa-chart-pie mr-2 text-blue-600"></i> Inventory Analytics
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">Strategic insights for inventory management</p>
                            </div>
                            <div class="flex space-x-4 items-center">
                                <!-- DATE FILTERS -->
                                <div class="flex space-x-2">
                                    <div>
                                        <select id="month-filter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="all">All Months</option>
                                            <option value="01">January</option>
                                            <option value="02">February</option>
                                            <option value="03">March</option>
                                            <option value="04">April</option>
                                            <option value="05">May</option>
                                            <option value="06">June</option>
                                            <option value="07">July</option>
                                            <option value="08">August</option>
                                            <option value="09">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>
                                        </select>
                                    </div>
                                    <div>
                                        <select id="year-filter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="2024">2024</option>
                                            <option value="2023">2023</option>
                                            <option value="2022">2022</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- BUTTONS -->
                                <div class="flex space-x-2">
                                    <!-- Start Restocking Button -->
                                    <div class="relative">
                                        <button id="start-restocking-btn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center shadow-md transition duration-200">
                                            <i class="fas fa-play-circle mr-2"></i> Start Restocking
                                            <i class="fas fa-chevron-down ml-2 text-sm"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Download Reports Button -->
                                    <div class="relative">
                                        <button id="download-reports-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center shadow-md transition duration-200">
                                            <i class="fas fa-download mr-2"></i> Download Reports
                                            <i class="fas fa-chevron-down ml-2 text-sm"></i>
                                        </button>
                                        
                                        <!-- Download Reports Dropdown -->
                                        <div id="download-reports-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 z-10 hidden">
                                            <div class="py-1">
                                                <a href="#" class="download-complete-report flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700" data-format="pdf">
                                                    <i class="fas fa-file-pdf text-red-500 mr-3"></i>
                                                    <div>
                                                        <div class="font-medium">Download Complete Report (PDF)</div>
                                                        <div class="text-xs text-gray-500">All-in-one PDF with 8 sections</div>
                                                    </div>
                                                </a>
                                                <a href="#" class="download-complete-report flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700" data-format="excel">
                                                    <i class="fas fa-file-excel text-green-500 mr-3"></i>
                                                    <div>
                                                        <div class="font-medium">Download Complete Report (Excel)</div>
                                                        <div class="text-xs text-gray-500">All-in-one Excel workbook</div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <!-- Key Analytics Sections -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 hover:shadow-md transition duration-200">
                                    <div class="flex items-center mb-3">
                                        <div class="p-2 rounded-lg bg-blue-100 text-blue-600">
                                            <i class="fas fa-fire"></i>
                                        </div>
                                        <h4 class="ml-3 font-medium text-gray-900">Most Used Items</h4>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4">Identify which items are requested most frequently</p>
                                    <button class="view-top-10-btn text-xs text-blue-600 font-medium hover:text-blue-800">
                                        <i class="fas fa-arrow-right mr-1"></i> View Top 10
                                    </button>
                                </div>
                                
                                <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 hover:shadow-md transition duration-200">
                                    <div class="flex items-center mb-3">
                                        <div class="p-2 rounded-lg bg-amber-100 text-amber-600">
                                            <i class="fas fa-bolt"></i>
                                        </div>
                                        <h4 class="ml-3 font-medium text-gray-900">Fast Depleting Items</h4>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4">Track items that run out quickly and need frequent restocking</p>
                                    <button class="restock-alerts-btn text-xs text-amber-600 font-medium hover:text-amber-800">
                                        <i class="fas fa-arrow-right mr-1"></i> Restock Alerts
                                    </button>
                                </div>
                                
                                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5 hover:shadow-md transition duration-200">
                                    <div class="flex items-center mb-3">
                                        <div class="p-2 rounded-lg bg-emerald-100 text-emerald-600">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <h4 class="ml-3 font-medium text-gray-900">Restock Management</h4>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4">Track inventory flow and manage restocking</p>
                                    <button class="schedule-view-btn text-xs text-emerald-600 font-medium hover:text-emerald-800">
                                        <i class="fas fa-arrow-right mr-1"></i> View Inventory Flow
                                    </button>
                                </div>
                            </div>

                            <!-- Tabs for different analytics views -->
                            <div class="border-b border-gray-200">
                                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                    <button id="usage-tab" class="analytics-tab border-b-2 border-blue-500 text-blue-600 px-1 py-3 text-sm font-medium active">
                                        <i class="fas fa-chart-line mr-2"></i> Usage Patterns
                                    </button>
                                    <button id="depletion-tab" class="analytics-tab border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 px-1 py-3 text-sm font-medium">
                                        <i class="fas fa-tachometer-alt mr-2"></i> Depletion Rate
                                    </button>
                                    <button id="restock-tab" class="analytics-tab border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 px-1 py-3 text-sm font-medium">
                                        <i class="fas fa-calendar-check mr-2"></i> Restock Management
                                    </button>
                                    <button id="inventory-tab" class="analytics-tab border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 px-1 py-3 text-sm font-medium">
                                        <i class="fas fa-boxes mr-2"></i> Full Inventory
                                    </button>
                                </nav>
                            </div>

                            <!-- Usage Patterns Tab Content -->
                            <div id="usage-content" class="analytics-tab-content pt-6">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                    <!-- Top Requested Items -->
                                    <div class="bg-white rounded-lg border border-gray-200">
                                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                            <h4 class="font-medium text-gray-700">Most Requested Items (Last 30 Days)</h4>
                                            <p class="text-sm text-gray-500 mt-1">Items with highest request frequency</p>
                                        </div>
                                        <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $topRequestedItems ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <div class="p-4 hover:bg-gray-50 transition duration-150">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center flex-1">
                                                            <div class="p-2 rounded-lg bg-blue-100 text-blue-600 mr-3">
                                                                <i class="fas fa-chart-line"></i>
                                                            </div>
                                                            <div class="flex-1 min-w-0">
                                                                <div class="font-medium text-gray-900 truncate"><?php echo e($item->name); ?></div>
                                                                <div class="text-sm text-gray-500"><?php echo e($item->total_requests); ?> requests</div>
                                                            </div>
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium <?php echo e($item->total_requests > 20 ? 'text-red-600' : ($item->total_requests > 10 ? 'text-amber-600' : 'text-emerald-600')); ?>">
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->total_requests > 20): ?>
                                                                    <i class="fas fa-fire mr-1"></i> High Demand
                                                                <?php elseif($item->total_requests > 10): ?>
                                                                    <i class="fas fa-arrow-up mr-1"></i> Medium Demand
                                                                <?php else: ?>
                                                                    <i class="fas fa-check mr-1"></i> Normal Demand
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3">
                                                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                                                            <span>Request Rate</span>
                                                            <span><?php echo e(number_format($item->avg_requests_per_day, 1)); ?>/day</span>
                                                        </div>
                                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                                            <div class="bg-blue-500 h-2 rounded-full" style="width: <?php echo e(min(($item->total_requests / max($maxRequests, 1)) * 100, 100)); ?>%"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <div class="p-6 text-center text-gray-500">
                                                    <i class="fas fa-chart-line text-2xl text-gray-300 mb-2"></i>
                                                    <p>No usage data available</p>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Usage Trends -->
                                    <div class="bg-white rounded-lg border border-gray-200">
                                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                            <h4 class="font-medium text-gray-700">Usage Trends Analysis</h4>
                                            <p class="text-sm text-gray-500 mt-1">Weekly request patterns</p>
                                        </div>
                                        <div class="p-6">
                                            <div class="space-y-6">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $usageTrends ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trend): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div>
                                                        <div class="flex justify-between items-center mb-2">
                                                            <div class="font-medium text-gray-900"><?php echo e($trend['category']); ?></div>
                                                            <div class="text-sm font-medium <?php echo e($trend['trend'] === 'increasing' ? 'text-red-600' : ($trend['trend'] === 'decreasing' ? 'text-emerald-600' : 'text-gray-600')); ?>">
                                                                <?php echo e($trend['percentage']); ?>% <?php echo e($trend['trend']); ?>

                                                            </div>
                                                        </div>
                                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                                            <div class="bg-blue-500 h-2 rounded-full" style="width: <?php echo e($trend['percentage']); ?>%"></div>
                                                        </div>
                                                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                                                            <span>Last week: <?php echo e($trend['last_week']); ?> requests</span>
                                                            <span>This week: <?php echo e($trend['this_week']); ?> requests</span>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                                                <div class="flex items-center">
                                                    <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                                                    <p class="text-sm text-gray-700">
                                                        <strong>Insight:</strong> <?php echo e($usageInsight ?? 'Analyze request patterns to optimize inventory levels'); ?>

                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Depletion Rate Tab Content -->
                            <div id="depletion-content" class="analytics-tab-content pt-6 hidden">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                    <!-- Fast Depleting Items -->
                                    <div class="bg-white rounded-lg border border-gray-200">
                                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                            <h4 class="font-medium text-gray-700">Fastest Depleting Items</h4>
                                            <p class="text-sm text-gray-500 mt-1">Items that run out quickly</p>
                                        </div>
                                        <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $fastDepletingItems ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <div class="p-4 hover:bg-gray-50 transition duration-150">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center">
                                                            <div class="p-2 rounded-lg <?php echo e($item->depletion_rate > 50 ? 'bg-red-100 text-red-600' : 'bg-amber-100 text-amber-600'); ?> mr-3">
                                                                <i class="fas fa-bolt"></i>
                                                            </div>
                                                            <div>
                                                                <div class="font-medium text-gray-900"><?php echo e($item->name); ?></div>
                                                                <div class="text-sm text-gray-500">Current: <?php echo e($item->quantity); ?> | Min: <?php echo e($item->minimum_stock); ?></div>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="text-lg font-bold <?php echo e($item->depletion_rate > 50 ? 'text-red-600' : 'text-amber-600'); ?>">
                                                                <?php echo e(number_format($item->depletion_rate, 1)); ?>%
                                                            </div>
                                                            <div class="text-xs text-gray-500">depletion rate</div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3">
                                                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                                                            <span>Days to depletion</span>
                                                            <span class="font-medium <?php echo e($item->days_to_depletion <= 7 ? 'text-red-600' : ($item->days_to_depletion <= 14 ? 'text-amber-600' : 'text-emerald-600')); ?>">
                                                                <?php echo e($item->days_to_depletion); ?> days
                                                            </span>
                                                        </div>
                                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                                            <div class="<?php echo e($item->depletion_rate > 50 ? 'bg-red-500' : 'bg-amber-500'); ?> h-2 rounded-full" style="width: <?php echo e(min($item->depletion_rate, 100)); ?>%"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <div class="p-6 text-center text-gray-500">
                                                    <i class="fas fa-bolt text-2xl text-gray-300 mb-2"></i>
                                                    <p>No depletion data available</p>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Depletion Analysis -->
                                    <div class="bg-white rounded-lg border border-gray-200">
                                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                            <h4 class="font-medium text-gray-700">Depletion Analysis</h4>
                                            <p class="text-sm text-gray-500 mt-1">Stock level predictions</p>
                                        </div>
                                        <div class="p-6">
                                            <div class="mb-6">
                                                <h5 class="font-medium text-gray-700 mb-3">Stock Status Categories</h5>
                                                <div class="grid grid-cols-3 gap-4">
                                                    <div class="text-center p-3 bg-red-50 rounded-lg">
                                                        <div class="text-lg font-bold text-red-600"><?php echo e($criticalItemsCount ?? 0); ?></div>
                                                        <div class="text-xs text-red-600 mt-1">Critical (＜7 days)</div>
                                                    </div>
                                                    <div class="text-center p-3 bg-amber-50 rounded-lg">
                                                        <div class="text-lg font-bold text-amber-600"><?php echo e($warningItemsCount ?? 0); ?></div>
                                                        <div class="text-xs text-amber-600 mt-1">Warning (7-14 days)</div>
                                                    </div>
                                                    <div class="text-center p-3 bg-emerald-50 rounded-lg">
                                                        <div class="text-lg font-bold text-emerald-600"><?php echo e($safeItemsCount ?? 0); ?></div>
                                                        <div class="text-xs text-emerald-600 mt-1">Safe (>14 days)</div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="p-4 bg-blue-50 rounded-lg">
                                                <div class="flex items-start">
                                                    <i class="fas fa-exclamation-triangle text-amber-500 mt-1 mr-2"></i>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900 mb-1">Urgent Attention Needed</p>
                                                        <p class="text-xs text-gray-600">
                                                            <?php echo e($criticalItemsCount ?? 0); ?> items will run out within a week based on current usage rates.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Restock Management Tab Content -->
                            <div id="restock-content" class="analytics-tab-content pt-6 hidden">
                                <div class="bg-white rounded-lg border border-gray-200">
                                    <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                        <h4 class="font-medium text-gray-700">Inventory Flow & Restock Management</h4>
                                        <p class="text-sm text-gray-500 mt-1">Track inventory movement through the restocking process</p>
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
                                                        REQUESTED
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        CLAIMED
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        RESTOCKED
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        ENDING
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        STATUS
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200" id="restock-table-body">
                                                <!-- Sample Data - In real app, this would come from backend -->
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="font-medium text-gray-900">Printer Paper</div>
                                                        <div class="text-xs text-gray-500">Office Supplies</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-gray-900">100</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-gray-900">30</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-red-600 font-medium">-20</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-green-600 font-medium">+0</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-gray-900">80</div>
                                                        <div class="text-xs text-gray-500">100 - 20 + 0</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">
                                                            Needs Restock
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="font-medium text-gray-900">Black Pens</div>
                                                        <div class="text-xs text-gray-500">Office Supplies</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-gray-900">200</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-gray-900">50</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-red-600 font-medium">-30</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-green-600 font-medium">+0</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-gray-900">170</div>
                                                        <div class="text-xs text-gray-500">200 - 30 + 0</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">
                                                            Needs Restock
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="font-medium text-gray-900">Office Chairs</div>
                                                        <div class="text-xs text-gray-500">Furniture</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-gray-900">25</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-gray-900">10</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-red-600 font-medium">-5</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-green-600 font-medium">+10</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-gray-900">30</div>
                                                        <div class="text-xs text-gray-500">25 - 5 + 10</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                                            ✅ Restocked
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="p-4 border-t border-gray-200 bg-gray-50">
                                        <div class="flex items-center justify-between">
                                            <div class="text-sm text-gray-600">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Ending = Beginning - Claimed + Restocked
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Full Inventory Tab Content -->
                            <div id="inventory-content" class="analytics-tab-content pt-6 hidden">
                                <div class="bg-white rounded-lg border border-gray-200">
                                    <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                                        <div>
                                            <h4 class="font-medium text-gray-700">Complete Inventory Analysis</h4>
                                            <p class="text-sm text-gray-500 mt-1"><?php echo e($totalItems ?? 0); ?> items • <?php echo e($totalValue ?? 0); ?> total value</p>
                                        </div>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Item
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Stock
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Status
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Requests (30d)
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Turnover Rate
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Last Restock
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200" id="inventory-table-body">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $allItems ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $stockStatus = '';
                                                        if ($item->quantity <= 0) {
                                                            $stockStatus = 'out-of-stock';
                                                        } elseif ($item->quantity <= $item->minimum_stock) {
                                                            $stockStatus = 'low-stock';
                                                        } else {
                                                            $stockStatus = 'in-stock';
                                                        }
                                                        
                                                        $requestsCount = $item->requests()->whereDate('created_at', '>=', now()->subDays(30))->count();
                                                        $turnoverRate = $item->quantity > 0 ? ($requestsCount / $item->quantity) * 100 : 0;
                                                    ?>
                                                    <tr class="inventory-row hover:bg-gray-50" data-status="<?php echo e($stockStatus); ?>">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="flex items-center">
                                                                <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                                                    <i class="fas fa-box text-gray-400"></i>
                                                                </div>
                                                                <div class="ml-4">
                                                                    <div class="text-sm font-medium text-gray-900"><?php echo e($item->name); ?></div>
                                                                    <div class="text-xs text-gray-500"><?php echo e($item->category ?? 'General'); ?></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-900"><?php echo e($item->quantity); ?></div>
                                                            <div class="text-xs text-gray-500">Min: <?php echo e($item->minimum_stock); ?></div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stockStatus === 'out-of-stock'): ?>
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                                    Out of Stock
                                                                </span>
                                                            <?php elseif($stockStatus === 'low-stock'): ?>
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">
                                                                    Low Stock
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                                                    In Stock
                                                                </span>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-900"><?php echo e($requestsCount); ?></div>
                                                            <div class="text-xs <?php echo e($requestsCount > 20 ? 'text-red-600' : ($requestsCount > 10 ? 'text-amber-600' : 'text-gray-500')); ?>">
                                                                <?php if($requestsCount > 20): ?>
                                                                    High demand
                                                                <?php elseif($requestsCount > 10): ?>
                                                                    Medium demand
                                                                <?php else: ?>
                                                                    Low demand
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="flex items-center">
                                                                <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                                                                    <div class="bg-blue-500 h-2 rounded-full" style="width: <?php echo e(min($turnoverRate, 100)); ?>%"></div>
                                                                </div>
                                                                <span class="text-sm text-gray-900"><?php echo e(number_format($turnoverRate, 1)); ?>%</span>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            <?php echo e($item->updated_at->format('M d, Y')); ?>

                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($allItems)): ?>
                                        <div class="text-center py-12">
                                            <i class="fas fa-boxes text-3xl text-gray-300 mb-3"></i>
                                            <p class="text-gray-500">No inventory items found</p>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="close-analytics-modal" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Start Restocking Modal -->
<div id="start-restocking-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="restock-modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="restock-modal-title">
                                    <i class="fas fa-play-circle mr-2 text-green-600"></i> Add Stock to Inventory
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">Current stock is shown AFTER claims have been deducted</p>
                            </div>
                            <button type="button" id="close-restock-modal" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        
                        <div class="mt-4">
                            <div class="bg-white rounded-lg border border-gray-200">
                                <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                    <h4 class="font-medium text-gray-700">Add quantities to restock items</h4>
                                    <p class="text-sm text-gray-500 mt-1">Enter how many units to add for each item</p>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    ITEM NAME
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    CURRENT STOCK<br><span class="text-xs font-normal">(After Claims)</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    ADD QUANTITY
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    NEW TOTAL
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200" id="restock-items-table">
                                            <!-- Items will be populated by JavaScript -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="p-4 border-t border-gray-200 bg-gray-50">
                                    <div class="flex justify-between items-center">
                                        <div class="text-sm text-gray-600">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            New Total = Current Stock + Add Quantity
                                        </div>
                                        <div class="flex space-x-3">
                                            <button type="button" id="cancel-restock" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                                Cancel
                                            </button>
                                            <button type="button" id="confirm-restock" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                                Confirm Restock
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Restock Confirmation Modal -->
<div id="restock-confirmation-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="confirmation-modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="confirmation-modal-title">
                                    <i class="fas fa-check-circle mr-2 text-green-600"></i> Restock Confirmed
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">The following items have been added to inventory</p>
                            </div>
                            <button type="button" id="close-confirmation-modal" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        
                        <div class="mt-4">
                            <div class="bg-white rounded-lg border border-gray-200">
                                <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                    <h4 class="font-medium text-gray-700">Restock Summary</h4>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    ITEM NAME
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    QUANTITY ADDED
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    NEW TOTAL STOCK
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200" id="confirmation-summary-table">
                                            <!-- Summary will be populated by JavaScript -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="p-4 border-t border-gray-200 bg-green-50">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900" id="restock-summary-text"></p>
                                        </div>
                                        <button type="button" id="close-summary" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab elements
        const usageTab = document.getElementById('usage-tab');
        const depletionTab = document.getElementById('depletion-tab');
        const restockTab = document.getElementById('restock-tab');
        const inventoryTab = document.getElementById('inventory-tab');
        
        const usageContent = document.getElementById('usage-content');
        const depletionContent = document.getElementById('depletion-content');
        const restockContent = document.getElementById('restock-content');
        const inventoryContent = document.getElementById('inventory-content');

        // Button elements
        const startRestockingBtn = document.getElementById('start-restocking-btn');
        const downloadReportsBtn = document.getElementById('download-reports-btn');
        const downloadReportsDropdown = document.getElementById('download-reports-dropdown');
        
        // Modal elements
        const startRestockingModal = document.getElementById('start-restocking-modal');
        const restockConfirmationModal = document.getElementById('restock-confirmation-modal');
        const closeRestockModal = document.getElementById('close-restock-modal');
        const closeConfirmationModal = document.getElementById('close-confirmation-modal');
        const closeSummary = document.getElementById('close-summary');
        const cancelRestock = document.getElementById('cancel-restock');
        const confirmRestock = document.getElementById('confirm-restock');
        
        // Table elements
        const restockItemsTable = document.getElementById('restock-items-table');
        const confirmationSummaryTable = document.getElementById('confirmation-summary-table');
        const restockSummaryText = document.getElementById('restock-summary-text');
        
        // Date filter elements
        const monthFilter = document.getElementById('month-filter');
        const yearFilter = document.getElementById('year-filter');

        // Quick action buttons
        const viewTop10Btn = document.querySelector('.view-top-10-btn');
        const restockAlertsBtn = document.querySelector('.restock-alerts-btn');
        const scheduleViewBtn = document.querySelector('.schedule-view-btn');

        // Tab switching functionality
        function switchTab(tabName) {
            // Remove active class from all tabs
            document.querySelectorAll('.analytics-tab').forEach(tab => {
                tab.classList.remove('active', 'border-blue-500', 'text-blue-600');
                tab.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Hide all tab contents
            document.querySelectorAll('.analytics-tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Show selected tab content
            const tabContent = document.getElementById(`${tabName}-content`);
            if (tabContent) {
                tabContent.classList.remove('hidden');
            }
            
            // Activate selected tab
            const tab = document.getElementById(`${tabName}-tab`);
            if (tab) {
                tab.classList.add('active', 'border-blue-500', 'text-blue-600');
                tab.classList.remove('border-transparent', 'text-gray-500');
            }
            
            // Close dropdowns when switching tabs
            if (downloadReportsDropdown) {
                downloadReportsDropdown.classList.add('hidden');
            }
        }

        // Tab click events
        if (usageTab) usageTab.addEventListener('click', () => switchTab('usage'));
        if (depletionTab) depletionTab.addEventListener('click', () => switchTab('depletion'));
        if (restockTab) restockTab.addEventListener('click', () => switchTab('restock'));
        if (inventoryTab) inventoryTab.addEventListener('click', () => switchTab('inventory'));

        // Toggle download reports dropdown
        if (downloadReportsBtn && downloadReportsDropdown) {
            downloadReportsBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                downloadReportsDropdown.classList.toggle('hidden');
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (downloadReportsDropdown && !downloadReportsDropdown.contains(e.target) && 
                downloadReportsBtn && !downloadReportsBtn.contains(e.target)) {
                downloadReportsDropdown.classList.add('hidden');
            }
        });

        // Start Restocking Modal
        if (startRestockingBtn) {
            startRestockingBtn.addEventListener('click', function() {
                openRestockingModal();
            });
        }

        function openRestockingModal() {
            // Get data from the restock management table
            const rows = document.querySelectorAll('#restock-table-body tr');
            let itemsHTML = '';
            
            rows.forEach((row, index) => {
                const itemName = row.querySelector('td:nth-child(1) .font-medium').textContent;
                const beginning = parseInt(row.querySelector('td:nth-child(2) .text-gray-900').textContent);
                const claimed = parseInt(row.querySelector('td:nth-child(4) .text-red-600').textContent);
                const restocked = parseInt(row.querySelector('td:nth-child(5) .text-green-600').textContent.replace('+', ''));
                
                // Current Stock = Beginning - Claimed
                const currentStock = beginning - Math.abs(claimed);
                
                itemsHTML += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">${itemName}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-gray-900 font-medium">${currentStock}</div>
                            <div class="text-xs text-gray-500">${beginning} beginning - ${Math.abs(claimed)} claimed</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="number" min="0" value="0" 
                                   class="restock-quantity w-20 border border-gray-300 rounded-lg px-3 py-1 text-center"
                                   data-item="${itemName}"
                                   data-current="${currentStock}"
                                   data-beginning="${beginning}"
                                   data-claimed="${claimed}">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-gray-900 font-medium" id="new-total-${index}">${currentStock}</div>
                            <div class="text-xs text-gray-500">Current + Added</div>
                        </td>
                    </tr>
                `;
            });
            
            restockItemsTable.innerHTML = itemsHTML;
            
            // Add event listeners to quantity inputs
            document.querySelectorAll('.restock-quantity').forEach(input => {
                input.addEventListener('input', function() {
                    const currentStock = parseInt(this.getAttribute('data-current'));
                    const added = parseInt(this.value) || 0;
                    const index = Array.from(this.parentNode.parentNode.parentNode.children).indexOf(this.parentNode.parentNode);
                    const newTotalElement = document.getElementById(`new-total-${index}`);
                    newTotalElement.textContent = currentStock + added;
                });
            });
            
            // Show the modal
            startRestockingModal.classList.remove('hidden');
        }

        // Close modals
        if (closeRestockModal) {
            closeRestockModal.addEventListener('click', () => {
                startRestockingModal.classList.add('hidden');
            });
        }

        if (closeConfirmationModal) {
            closeConfirmationModal.addEventListener('click', () => {
                restockConfirmationModal.classList.add('hidden');
            });
        }

        if (closeSummary) {
            closeSummary.addEventListener('click', () => {
                restockConfirmationModal.classList.add('hidden');
            });
        }

        if (cancelRestock) {
            cancelRestock.addEventListener('click', () => {
                startRestockingModal.classList.add('hidden');
            });
        }

        // Confirm restock
        if (confirmRestock) {
            confirmRestock.addEventListener('click', () => {
                const items = [];
                let totalAdded = 0;
                let itemsAdded = 0;
                
                // Collect all items with added quantities
                document.querySelectorAll('.restock-quantity').forEach(input => {
                    const added = parseInt(input.value) || 0;
                    if (added > 0) {
                        const itemName = input.getAttribute('data-item');
                        const currentStock = parseInt(input.getAttribute('data-current'));
                        const newTotal = currentStock + added;
                        
                        items.push({
                            name: itemName,
                            added: added,
                            newTotal: newTotal
                        });
                        
                        totalAdded += added;
                        itemsAdded++;
                    }
                });
                
                if (items.length === 0) {
                    alert('Please add quantities for at least one item.');
                    return;
                }
                
                // Show confirmation modal
                showConfirmationModal(items, totalAdded, itemsAdded);
                
                // Close the restocking modal
                startRestockingModal.classList.add('hidden');
            });
        }

        function showConfirmationModal(items, totalAdded, itemsAdded) {
            let summaryHTML = '';
            
            items.forEach(item => {
                summaryHTML += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">${item.name}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-green-600 font-medium">+${item.added}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-gray-900 font-medium">${item.newTotal}</div>
                        </td>
                    </tr>
                `;
            });
            
            confirmationSummaryTable.innerHTML = summaryHTML;
            restockSummaryText.textContent = `Total Items Restocked: ${itemsAdded} • Total Quantity Added: ${totalAdded}`;
            
            restockConfirmationModal.classList.remove('hidden');
            
            // Update the main restock management table
            updateRestockTable(items);
        }

        function updateRestockTable(restockedItems) {
            const rows = document.querySelectorAll('#restock-table-body tr');
            
            restockedItems.forEach(restockedItem => {
                rows.forEach(row => {
                    const itemName = row.querySelector('td:nth-child(1) .font-medium').textContent;
                    
                    if (itemName === restockedItem.name) {
                        // Update the Restocked column
                        const restockedCell = row.querySelector('td:nth-child(5) .text-green-600');
                        const currentRestocked = parseInt(restockedCell.textContent.replace('+', '')) || 0;
                        const newRestocked = currentRestocked + restockedItem.added;
                        restockedCell.textContent = `+${newRestocked}`;
                        
                        // Update the Ending column
                        const beginning = parseInt(row.querySelector('td:nth-child(2) .text-gray-900').textContent);
                        const claimed = parseInt(row.querySelector('td:nth-child(4) .text-red-600').textContent);
                        const ending = beginning - Math.abs(claimed) + newRestocked;
                        
                        const endingCell = row.querySelector('td:nth-child(6) .text-gray-900');
                        const endingCalcCell = row.querySelector('td:nth-child(6) .text-xs');
                        
                        endingCell.textContent = ending;
                        endingCalcCell.textContent = `${beginning} - ${Math.abs(claimed)} + ${newRestocked}`;
                        
                        // Update the status
                        const statusCell = row.querySelector('td:nth-child(7) span');
                        statusCell.className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800';
                        statusCell.textContent = '✅ Restocked';
                    }
                });
            });
        }

        // Download complete reports
        document.querySelectorAll('.download-complete-report').forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                const format = this.getAttribute('data-format');
                
                // Close dropdown
                downloadReportsDropdown.classList.add('hidden');
                
                // Show loading state
                const originalText = downloadReportsBtn.innerHTML;
                downloadReportsBtn.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i> Preparing...`;
                downloadReportsBtn.disabled = true;
                
                const reportName = `Complete Inventory Report`;
                const fileExtension = format === 'pdf' ? '.pdf' : '..xlsx';
                const filename = `Inventory_Complete_Report_${new Date().toISOString().split('T')[0]}${fileExtension}`;
                
                // Simulate download with delay
                setTimeout(() => {
                    if (format === 'pdf') {
                        alert(`📚 Complete PDF Report Generated!\n\n"${reportName}" has been created as a multi-page PDF with all sections.\n\nFile: ${filename}`);
                    } else {
                        alert(`📊 Complete Excel Report Generated!\n\n"${reportName}" has been created as a multi-sheet Excel workbook with all data.\n\nFile: ${filename}`);
                    }
                    
                    // Simulate the download
                    simulateDownload(filename, reportName);
                    
                    // Reset button state
                    downloadReportsBtn.innerHTML = originalText;
                    downloadReportsBtn.disabled = false;
                }, 1500);
            });
        });

        // Date filter functionality
        if (monthFilter && yearFilter) {
            monthFilter.addEventListener('change', applyDateFilters);
            yearFilter.addEventListener('change', applyDateFilters);
        }

        function applyDateFilters() {
            const month = monthFilter.value;
            const year = yearFilter.value;
            
            // In a real application, this would make an API call to filter data
            // For now, we'll just show a message
            console.log(`Applying filters: Month=${month}, Year=${year}`);
            
            // Update any displayed data (in a real app, this would reload data)
            // For this example, we'll just update a message
            const activeTab = document.querySelector('.analytics-tab.active');
            if (activeTab) {
                const tabId = activeTab.id.replace('-tab', '');
                console.log(`Refreshing ${tabId} data with filters...`);
            }
        }

        // Quick action buttons
        if (viewTop10Btn) {
            viewTop10Btn.addEventListener('click', () => {
                switchTab('usage');
            });
        }

        if (restockAlertsBtn) {
            restockAlertsBtn.addEventListener('click', () => {
                switchTab('depletion');
            });
        }

        if (scheduleViewBtn) {
            scheduleViewBtn.addEventListener('click', () => {
                switchTab('restock');
            });
        }

        // Function to simulate file download
        function simulateDownload(filename, content) {
            // Create a temporary link for download simulation
            const link = document.createElement('a');
            link.style.display = 'none';
            document.body.appendChild(link);
            
            // For simulation, we'll create a simple text file
            const blob = new Blob([`This is a simulation of: ${filename}\n\nGenerated on: ${new Date().toLocaleString()}`], { type: 'text/plain' });
            const url = window.URL.createObjectURL(blob);
            link.href = url;
            link.download = filename;
            
            // Trigger download
            link.click();
            
            // Cleanup
            window.URL.revokeObjectURL(url);
            document.body.removeChild(link);
        }
    });
</script><?php /**PATH C:\Users\Jhon Rhey\Downloads\Land Bank Inventory System\Land Bank Inventory System\inventory-system\resources\views\components\analytics-modal.blade.php ENDPATH**/ ?>