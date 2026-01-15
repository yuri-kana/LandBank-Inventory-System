<?php $__env->startSection('title', 'Dashboard - Inventory System'); ?>

<?php if (isset($component)) { $__componentOriginal4619374cef299e94fd7263111d0abc69 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4619374cef299e94fd7263111d0abc69 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-black leading-tight">
                <?php echo e(__('Dashboard')); ?>

            </h2>
        </div>
     <?php $__env->endSlot(); ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->check() && auth()->user()->isAdmin()): ?>
        <!-- =========================================== -->
        <!-- STRATEGY & ANALYTICS DASHBOARD (Admin Users) -->
        <!-- =========================================== -->
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Analytics Header Section -->
                <div class="mb-8">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-center justify-between">
                            <div class="flex items-center mb-4 md:mb-0">
                                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                    <i class="fas fa-chart-pie text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Inventory Analytics Dashboard</h3>
                                    <p class="text-gray-600">Comprehensive insights and management tools</p>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>

                
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($totalItems) && $totalItems > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-6 shadow-md hover:shadow-lg transition duration-300">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-200 text-blue-700">
                                <i class="fas fa-boxes text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-blue-900">Total Items</p>
                                <p class="text-2xl font-bold text-blue-700"><?php echo e($totalItems); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200 rounded-lg p-6 shadow-md hover:shadow-lg transition duration-300">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-200 text-yellow-700">
                                <i class="fas fa-exclamation-triangle text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-yellow-900">Low Stock Items</p>
                                <p class="text-2xl font-bold text-yellow-700">
                                    <?php echo e($lowStockItems); ?>

                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-lg p-6 shadow-md hover:shadow-lg transition duration-300">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-red-200 text-red-700">
                                <i class="fas fa-times-circle text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-red-900">Out of Stock</p>
                                <p class="text-2xl font-bold text-red-700">
                                    <?php echo e($outOfStockItems); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <!-- Tabs Navigation with URL Support -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs" id="dashboard-tabs">
                        <a href="<?php echo e(route('dashboard', 'usage-pattern')); ?>" 
                           class="tab-link border-b-2 px-1 py-3 text-sm font-medium flex items-center transition-colors duration-200 
                                  <?php echo e($tab === 'usage-pattern' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?>">
                            <i class="fas fa-chart-line mr-2"></i> Usage Patterns
                        </a>
                        
                        <a href="<?php echo e(route('dashboard', 'depletion')); ?>" 
                           class="tab-link border-b-2 px-1 py-3 text-sm font-medium flex items-center transition-colors duration-200 
                                  <?php echo e($tab === 'depletion' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?>">
                            <i class="fas fa-tachometer-alt mr-2"></i> Depletion Rate
                        </a>
                        
                        <a href="<?php echo e(route('dashboard', 'restock-management')); ?>" 
                           class="tab-link border-b-2 px-1 py-3 text-sm font-medium flex items-center transition-colors duration-200 
                                  <?php echo e($tab === 'restock-management' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?>">
                            <i class="fas fa-calendar-check mr-2"></i> Restock Management
                        </a>
                        
                        <a href="<?php echo e(route('dashboard', 'inventory-records')); ?>" 
                           class="tab-link border-b-2 px-1 py-3 text-sm font-medium flex items-center transition-colors duration-200 
                                  <?php echo e($tab === 'inventory-records' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?>">
                            <i class="fas fa-boxes mr-2"></i> Inventory Records
                        </a>
                    </nav>
                </div>

                <!-- Tab Contents -->
                <div class="tab-content-container">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tab === 'usage-pattern'): ?>
                        <div id="usage-pattern-content" class="tab-content active">
                            <?php echo $__env->make('tabs.usage-patterns', [
                                'topRequestedItems' => $topRequestedItems ?? [],
                                'mostActiveTeams' => $mostActiveTeams ?? collect(),
                                'maxRequests' => $maxRequests ?? 1
                            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tab === 'depletion'): ?>
                        <div id="depletion-content" class="tab-content active">
                            <?php echo $__env->make('tabs.depletion-rate', [
                                'fastDepletingItems' => $fastDepletingItems ?? [],
                                'criticalItemsCount' => $criticalItemsCount,
                                'warningItemsCount' => $warningItemsCount,
                                'safeItemsCount' => $safeItemsCount
                            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tab === 'restock-management'): ?>
                        <div id="restock-management-content" class="tab-content active">
                            <?php echo $__env->make('tabs.restock-management', [
                                'inventoryFlowItems' => $inventoryFlowItems ?? []
                            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tab === 'inventory-records'): ?>
                        <div id="inventory-records-content" class="tab-content active">
                            <?php echo $__env->make('tabs.inventory-records', [
                                'monthlyReports' => $monthlyReports ?? [],
                                'yearlyComparison' => $yearlyComparison ?? [
                                    'previous_year' => ['year' => date('Y') - 1, 'total_requests' => 0],
                                    'current_year' => ['year' => date('Y'), 'total_requests' => 0, 'total_claimed' => 0],
                                    'comparison' => ['direction' => 'neutral', 'change' => 0]
                                ],
                                'selectedYear' => $selectedYear ?? date('Y'),
                                'totalRequests' => $totalRequests ?? 0,
                                'totalRestocked' => $totalRestocked ?? 0,
                                'totalClaimed' => $totalClaimed ?? 0,
                                'averageActivity' => $averageActivity ?? 'Normal'
                            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

            </div>
        </div>

    <?php else: ?>
        <!-- ============================================= -->
        <!-- EXECUTION & DAILY TASKS DASHBOARD (Team Members) -->
        <!-- ============================================= -->
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Team Action Header -->
                <div class="mb-8">
                    <div class="bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 rounded-xl p-6 shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-center justify-between">
                            <div class="flex items-center mb-4 md:mb-0">
                                <div class="p-3 rounded-full bg-emerald-100 text-emerald-600">
                                    <i class="fas fa-users text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Your Team: <?php echo e(auth()->user()->team->name ?? 'No Team Assigned'); ?></h3>
                                    <p class="text-gray-600">Complete daily tasks and manage requests</p>
                                </div>
                            </div>
                            
                            <?php if(auth()->user()->team): ?>
                            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                                <a href="<?php echo e(route('requests.create')); ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg flex items-center justify-center shadow-md transition duration-200 group">
                                    <i class="fas fa-plus-circle mr-2 group-hover:scale-110 transition-transform duration-200"></i> 
                                    <span class="font-medium">New Request</span>
                                </a>
                                <a href="<?php echo e(route('requests.index')); ?>" class="bg-white hover:bg-gray-50 text-gray-800 border border-gray-300 px-6 py-3 rounded-lg flex items-center justify-center shadow-sm transition duration-200 group">
                                    <i class="fas fa-list-alt mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                                    <span class="font-medium">View My Requests</span>
                                </a>
                            </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Request Item Card -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 group">
                        <div class="flex items-center mb-4">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 group-hover:bg-yellow-200 transition duration-300">
                                <i class="fas fa-plus-circle text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">Request Items</h4>
                                <p class="text-sm text-gray-600">Submit new item requests</p>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-4">Quickly request items needed by your team for daily operations.</p>
                        <a href="<?php echo e(route('requests.create')); ?>" class="inline-flex items-center text-yellow-600 hover:text-yellow-700 font-medium">
                            Start Request <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>

                    <!-- Track Requests Card -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 group">
                        <div class="flex items-center mb-4">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600 group-hover:bg-blue-200 transition duration-300">
                                <i class="fas fa-clipboard-list text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">Track Requests</h4>
                                <p class="text-sm text-gray-600">Monitor request status</p>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-4">Check the status of your submitted requests and take action when needed.</p>
                        <a href="<?php echo e(route('requests.index')); ?>" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                            View All Requests <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>

                    <!-- Team Status Card -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 group">
                        <div class="flex items-center mb-4">
                            <div class="p-3 rounded-full bg-emerald-100 text-emerald-600 group-hover:bg-emerald-200 transition duration-300">
                                <i class="fas fa-chart-pie text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">Team Status</h4>
                                <p class="text-sm text-gray-600">View team metrics</p>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-4">See how your team is performing with current request statistics.</p>
                        <a href="#team-stats" class="inline-flex items-center text-emerald-600 hover:text-emerald-700 font-medium">
                            View Stats <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                <!-- Team Statistics -->
                <div id="team-stats" class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 text-center">
                        <div class="text-2xl font-bold text-yellow-500"><?php echo e($pendingTeamRequests ?? 0); ?></div>
                        <div class="text-sm text-gray-600 mt-1">Pending</div>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 text-center">
                        <div class="text-2xl font-bold text-emerald-600"><?php echo e($approvedTeamRequests ?? 0); ?></div>
                        <div class="text-sm text-gray-600 mt-1">Approved</div>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 text-center">
                        <div class="text-2xl font-bold text-red-600"><?php echo e($rejectedTeamRequests ?? 0); ?></div>
                        <div class="text-sm text-gray-600 mt-1">Declined</div>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 text-center">
                        <div class="text-2xl font-bold text-gray-600"><?php echo e($teamRequests ?? 0); ?></div>
                        <div class="text-sm text-gray-600 mt-1">Total</div>
                    </div>
                </div>

                <!-- Recent Activity & Inventory -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Recent Team Requests -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100">
                        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Team Requests</h3>
                            <a href="<?php echo e(route('requests.index')); ?>" class="text-sm text-emerald-600 hover:text-emerald-800 flex items-center">
                                View All <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                        <div class="divide-y divide-gray-100">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = ($recentTeamRequests ?? collect())->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="p-4 hover:bg-gray-50 transition duration-150">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-medium text-gray-900"><?php echo e($request->item->name ?? 'Unknown Item'); ?></p>
                                            <p class="text-sm text-gray-500 mt-1">
                                                Qty: <?php echo e($request->quantity_requested); ?> • 
                                                <?php echo e($request->created_at->diffForHumans()); ?>

                                            </p>
                                        </div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($request->status === 'approved'): ?>
                                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                Approved
                                            </span>
                                        <?php elseif($request->status === 'rejected'): ?>
                                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Declined
                                            </span>
                                        <?php else: ?>
                                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="p-6 text-center text-gray-500">
                                    <i class="fas fa-inbox text-2xl text-gray-300 mb-2"></i>
                                    <p>No team requests yet</p>
                                    <a href="<?php echo e(route('requests.create')); ?>" class="text-sm text-emerald-600 hover:text-emerald-800 mt-2 inline-block">
                                        Make your first request
                                    </a>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <!-- Available Inventory - UPDATED TO SHOW ALL ITEMS -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 sm:mb-0">Available Inventory</h3>
                            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                                <div class="text-sm text-gray-500"><?php echo e($availableItemsCount ?? 0); ?> items in stock</div>
                                <div class="relative">
                                    <input type="text" 
                                           id="inventory-search" 
                                           placeholder="Search items..." 
                                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full sm:w-64"
                                           onkeyup="filterInventoryItems()">
                                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        <!-- Scrollable Inventory Section - SHOW ALL AVAILABLE ITEMS -->
                        <div class="max-h-[500px] overflow-y-auto divide-y divide-gray-100" id="inventory-scroll-container">
                            <?php
                                // Get all available items (not just recent)
                                $availableItems = $availableItems ?? \App\Models\Item::where('quantity', '>', 0)
                                    ->orderBy('name')
                                    ->get();
                                $availableItemsCount = $availableItems->count();
                            ?>
                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $availableItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="p-4 hover:bg-gray-50 transition duration-150 inventory-item">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-medium text-gray-900"><?php echo e($item->name); ?></p>
                                            <p class="text-sm text-gray-500 mt-1">Available: <?php echo e($item->quantity); ?> units</p>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->minimum_stock > 0): ?>
                                                <p class="text-xs text-gray-400 mt-1">Min. Stock: <?php echo e($item->minimum_stock); ?></p>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->quantity <= 0): ?>
                                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Out of Stock
                                            </span>
                                        <?php elseif($item->quantity <= $item->minimum_stock): ?>
                                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Low Stock
                                            </span>
                                        <?php else: ?>
                                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                Available
                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->quantity > 0): ?>
                                        <div class="mt-3">
                                            <a href="<?php echo e(route('requests.create')); ?>?item_id=<?php echo e($item->id); ?>" 
                                               class="text-sm bg-emerald-50 hover:bg-emerald-100 text-emerald-700 px-3 py-1 rounded-lg transition duration-200 inline-flex items-center">
                                                <i class="fas fa-cart-plus mr-1"></i> Request This Item
                                            </a>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="p-6 text-center text-gray-500">
                                    <i class="fas fa-boxes text-2xl text-gray-300 mb-2"></i>
                                    <p>No items available in inventory</p>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            
                            <!-- Scroll indicator for many items -->
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($availableItemsCount > 8): ?>
                                <div class="p-4 text-center text-gray-400 text-sm border-t border-gray-100 bg-gray-50 sticky bottom-0">
                                    <i class="fas fa-chevron-down mr-1 animate-bounce"></i>
                                    Scroll to see more items (<?php echo e($availableItemsCount); ?> total)
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Action Panel -->
                <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg border border-blue-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Need Help?</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-question-circle text-blue-500 mr-2"></i>
                                <span class="font-medium text-gray-900">How to make a request?</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">Click "New Request" button, select items, specify quantity, and submit for approval.</p>
                            <a href="<?php echo e(route('requests.create')); ?>" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                Start a request →
                            </a>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-clock text-blue-500 mr-2"></i>
                                <span class="font-medium text-gray-900">Check request status</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">View all your requests and their current status in the requests section.</p>
                            <a href="<?php echo e(route('requests.index')); ?>" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                View my requests →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $attributes = $__attributesOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__attributesOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $component = $__componentOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__componentOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>

<!-- Add CSS Styles -->
<style>
.tab-content {
    display: none;
    animation: fadeIn 0.3s ease-in-out;
}

.tab-content.active {
    display: block;
}

.tab-link.active {
    border-bottom-color: #3b82f6;
    color: #2563eb;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<!-- JavaScript Section -->
<script>
// ===========================================
// GLOBAL FUNCTIONS
// ===========================================

// Filter inventory items based on search
function filterInventoryItems() {
    const searchTerm = document.getElementById('inventory-search').value.toLowerCase();
    const items = document.querySelectorAll('.inventory-item');
    let visibleCount = 0;
    
    items.forEach(item => {
        const itemName = item.querySelector('.font-medium')?.textContent.toLowerCase() || '';
        const itemDescription = item.querySelector('.text-sm.text-gray-500')?.textContent.toLowerCase() || '';
        
        if (itemName.includes(searchTerm) || itemDescription.includes(searchTerm)) {
            item.style.display = 'block';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });
    
    // Show/hide empty state
    const scrollContainer = document.getElementById('inventory-scroll-container');
    const emptyState = scrollContainer.querySelector('.p-6.text-center');
    
    if (visibleCount === 0 && searchTerm !== '') {
        if (!emptyState) {
            const emptyDiv = document.createElement('div');
            emptyDiv.className = 'p-6 text-center text-gray-500';
            emptyDiv.innerHTML = `
                <i class="fas fa-search text-2xl text-gray-300 mb-2"></i>
                <p>No items found matching "${searchTerm}"</p>
                <button onclick="clearInventorySearch()" class="text-sm text-emerald-600 hover:text-emerald-800 mt-2 inline-block">
                    Clear search
                </button>
            `;
            scrollContainer.appendChild(emptyDiv);
        } else {
            emptyState.innerHTML = `
                <i class="fas fa-search text-2xl text-gray-300 mb-2"></i>
                <p>No items found matching "${searchTerm}"</p>
                <button onclick="clearInventorySearch()" class="text-sm text-emerald-600 hover:text-emerald-800 mt-2 inline-block">
                    Clear search
                </button>
            `;
        }
    } else if (emptyState && searchTerm === '') {
        emptyState.remove();
    }
}

// Clear inventory search
function clearInventorySearch() {
    document.getElementById('inventory-search').value = '';
    const items = document.querySelectorAll('.inventory-item');
    items.forEach(item => {
        item.style.display = 'block';
    });
    const emptyState = document.querySelector('#inventory-scroll-container .p-6.text-center');
    if (emptyState) {
        emptyState.remove();
    }
}

// Start Restocking function
function startRestocking() {
    console.log('🔄 Start Restocking clicked');
    const modal = document.getElementById('start-restocking-modal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        console.log('✅ Modal shown');
    } else {
        console.error('❌ Modal not found!');
        alert('Restocking modal not available. Please try again.');
    }
}

// Close modal function
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        console.log(`✅ Modal closed: ${modalId}`);
    }
}

// Initialize specific tab functionality
function initializeTab(tabName) {
    console.log(`🔄 Initializing tab: ${tabName}`);
    switch(tabName) {
        case 'inventory':
            initializeInventoryRecordsTab();
            break;
        case 'restock':
            initializeRestockTab();
            break;
        // Add other tab initializations as needed
    }
}

// ===========================================
// INVENTORY RECORDS TAB INITIALIZATION
// ===========================================

function initializeInventoryRecordsTab() {
    console.log('📊 Initializing Inventory Records Tab');
    
    // Debug: Check if elements exist
    const downloadButtons = document.querySelectorAll('.monthly-report-download-btn');
    console.log(`Found ${downloadButtons.length} monthly report download buttons`);
    
    // Monthly report dropdown functionality
    document.querySelectorAll('.monthly-report-download-btn').forEach(button => {
        // Remove any existing event listeners first
        const newButton = button.cloneNode(true);
        button.parentNode.replaceChild(newButton, button);
        button = newButton;
        
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            console.log('📥 Download button clicked');
            const dropdown = this.nextElementSibling;
            
            // Close all other dropdowns
            document.querySelectorAll('.monthly-report-dropdown').forEach(d => {
                if (d !== dropdown) {
                    d.classList.add('hidden');
                }
            });
            
            // Toggle current dropdown
            dropdown.classList.toggle('hidden');
        });
    });
    
    // Re-select buttons after cloning
    document.querySelectorAll('.download-pdf-btn').forEach(button => {
        button.addEventListener('click', function() {
            const year = this.getAttribute('data-year');
            const month = this.getAttribute('data-month');
            const monthName = this.getAttribute('data-month-name');
            console.log(`📄 Download PDF: ${monthName} ${year}`);
            
            // Use enhanced download function with modal check
            downloadReportWithModal('pdf', year, month, monthName);
        });
    });
    
    document.querySelectorAll('.download-excel-btn').forEach(button => {
        button.addEventListener('click', function() {
            const year = this.getAttribute('data-year');
            const month = this.getAttribute('data-month');
            const monthName = this.getAttribute('data-month-name');
            console.log(`📊 Download Excel: ${monthName} ${year}`);
            
            // Use enhanced download function with modal check
            downloadReportWithModal('excel', year, month, monthName);
        });
    });
    
    // View Report Details
    document.querySelectorAll('.view-report-details').forEach(button => {
        button.addEventListener('click', function() {
            const year = this.getAttribute('data-year');
            const month = this.getAttribute('data-month');
            console.log(`📋 View report details: ${year}-${month}`);
            showReportDetailsModal(year, month);
        });
    });
    
    // Finalize Report
    document.querySelectorAll('.finalize-report').forEach(button => {
        button.addEventListener('click', function() {
            const year = this.getAttribute('data-year');
            const month = this.getAttribute('data-month');
            console.log(`🔒 Finalize report: ${year}-${month}`);
            finalizeReport(year, month);
        });
    });
    
    // Generate Reports Button
    const generateReportsBtn = document.getElementById('generate-reports-btn');
    if (generateReportsBtn) {
        generateReportsBtn.addEventListener('click', function() {
            console.log('✨ Generate missing reports');
            generateMissingReports();
        });
    }
    
    // Refresh Button
    const refreshBtn = document.getElementById('refresh-inventory-btn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            console.log('🔄 Refreshing page');
            location.reload();
        });
    }
}

// Restock Tab Initialization
function initializeRestockTab() {
    console.log('📦 Initializing Restock Tab');
    
    // Add event listener to start restocking button
    const startRestockingBtn = document.getElementById('start-restocking-btn');
    if (startRestockingBtn) {
        startRestockingBtn.addEventListener('click', startRestocking);
    }
}

// ===========================================
// ENHANCED DOWNLOAD FUNCTION WITH MODAL
// ===========================================

// Store current download info
let currentDownloadInfo = null;

// Enhanced download function that checks for report existence first
async function downloadReportWithModal(format, year, month, monthName) {
    console.log(`📥 Attempting to download ${format} report for ${monthName} ${year}`);
    
    try {
        // First, check if report exists
        showLoading(`Checking report for ${monthName} ${year}...`);
        
        const checkResponse = await fetch(`/admin/reports/check/${year}/${month}`);
        const checkData = await checkResponse.json();
        
        hideLoading();
        
        if (checkData.exists) {
            // Report exists, proceed with download
            console.log('✅ Report exists, downloading...');
            downloadReportDirect(format, year, month, monthName);
        } else {
            // Report doesn't exist, show modal
            console.log('❌ Report does not exist, showing modal');
            showReportNotGeneratedModal(year, month, monthName, format);
        }
    } catch (error) {
        hideLoading();
        console.error('Error checking report:', error);
        showToast('error', 'Failed to check report: ' + error.message);
    }
}

// Direct download function (used when report exists)
function downloadReportDirect(format, year, month, monthName) {
    showLoading(`Downloading ${format.toUpperCase()} report...`);
    
    // Close dropdown if open
    document.querySelectorAll('.monthly-report-dropdown').forEach(dropdown => {
        dropdown.classList.add('hidden');
    });
    
    // Construct download URL
    let url;
    let filename;
    
    if (format === 'excel') {
        url = `/admin/reports/download/excel?year=${year}&month=${month}&type=monthly`;
        filename = `Monthly_Report_${monthName}_${year}.xlsx`;
    } else if (format === 'pdf') {
        url = `/admin/reports/download/pdf?year=${year}&month=${month}&type=monthly`;
        filename = `Monthly_Report_${monthName}_${year}.pdf`;
    } else if (format === 'csv') {
        url = `/admin/reports/download/csv?year=${year}&month=${month}&type=monthly`;
        filename = `Monthly_Report_${monthName}_${year}.csv`;
    }
    
    console.log('Download URL:', url);
    
    // Create a temporary anchor element for download
    const a = document.createElement('a');
    a.href = url;
    a.target = '_blank';
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    
    hideLoading();
    showToast('success', `${format.toUpperCase()} download started!`);
}

// Show modal when report is not generated
function showReportNotGeneratedModal(year, month, monthName, format) {
    // Store download info
    currentDownloadInfo = { year, month, monthName, format };
    
    // Get or create modal
    let modal = document.getElementById('report-not-generated-modal');
    
    if (!modal) {
        // The modal should already be in the HTML from the blade component
        console.error('Report not generated modal not found in DOM');
        return;
    }
    
    // Update modal content
    const periodElement = modal.querySelector('#report-period');
    if (periodElement) {
        periodElement.textContent = `${monthName} ${year}`;
    }
    
    // Setup modal buttons
    const generateBtn = modal.querySelector('#generate-report-btn');
    const cancelBtn = modal.querySelector('#cancel-report-btn');
    
    if (generateBtn) {
        // Remove existing event listeners
        const newGenerateBtn = generateBtn.cloneNode(true);
        generateBtn.parentNode.replaceChild(newGenerateBtn, generateBtn);
        
        newGenerateBtn.addEventListener('click', function() {
            generateReportOnDemand(year, month, monthName, format);
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });
    }
    
    if (cancelBtn) {
        // Remove existing event listeners
        const newCancelBtn = cancelBtn.cloneNode(true);
        cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);
        
        newCancelBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            currentDownloadInfo = null;
        });
    }
    
    // Show modal
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    
    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            currentDownloadInfo = null;
        }
    });
}

// Generate report on demand
async function generateReportOnDemand(year, month, monthName, format) {
    console.log(`✨ Generating report for ${monthName} ${year}`);
    
    showLoading(`Generating report for ${monthName} ${year}...`);
    
    try {
        const response = await fetch(`/admin/reports/generate/${year}/${month}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        hideLoading();
        
        if (data.success) {
            console.log('✅ Report generated successfully');
            showToast('success', 'Report generated successfully! Download will start now...');
            
            // Download the report after generation
            setTimeout(() => {
                downloadReportDirect(format, year, month, monthName);
            }, 1500);
        } else {
            console.error('❌ Failed to generate report:', data.message);
            showToast('error', 'Failed to generate report: ' + data.message);
        }
    } catch (error) {
        hideLoading();
        console.error('❌ Error generating report:', error);
        showToast('error', 'Error generating report: ' + error.message);
    }
}

// ===========================================
// REPORT FUNCTIONS
// ===========================================

function showReportDetailsModal(year, month) {
    let modal = document.getElementById('report-details-modal');
    
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'report-details-modal';
        modal.className = 'fixed inset-0 z-50 overflow-y-auto hidden modal-container';
        modal.innerHTML = `
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity modal-backdrop" aria-hidden="true"></div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <div class="flex justify-between items-center mb-6">
                                    <div>
                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="report-modal-title">
                                            <i class="fas fa-file-alt mr-2 text-blue-600"></i> Report Details
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-1" id="report-period"></p>
                                    </div>
                                    <button type="button" class="close-report-modal text-gray-400 hover:text-gray-500">
                                        <i class="fas fa-times text-xl"></i>
                                    </button>
                                </div>
                                <div class="mt-4" id="report-details-content">
                                    <div class="text-center py-8">
                                        <i class="fas fa-spinner fa-spin text-2xl text-gray-300 mb-2"></i>
                                        <p>Loading report details...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" class="close-report-modal mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        
        // Add close handlers
        modal.querySelectorAll('.close-report-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            });
        });
    }
    
    // Show modal
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    
    // Set period text
    const monthName = new Date(year, month - 1).toLocaleString('default', { month: 'long' });
    modal.querySelector('#report-period').textContent = `${monthName} ${year}`;
    
    // Fetch report details
    fetchReportDetails(year, month);
}

async function fetchReportDetails(year, month) {
    const contentDiv = document.getElementById('report-details-content');
    
    try {
        const response = await fetch(`/admin/reports/view/${year}/${month}`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        
        // Adjust based on actual response structure
        renderReportDetails(data);
    } catch (error) {
        console.error('Error:', error);
        contentDiv.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-exclamation-triangle text-2xl text-red-300 mb-2"></i>
                <p class="text-red-600">Error loading report: ${error.message}</p>
            </div>
        `;
    }
}

function renderReportDetails(data) {
    const contentDiv = document.getElementById('report-details-content');
    
    // Check if data is the report object or contains report
    const report = data.report || data;
    
    let html = `
        <div class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="text-sm text-blue-600 font-medium mb-1">Total Requests</div>
                    <div class="text-2xl font-bold text-gray-900">${report.total_requests || 0}</div>
                    <div class="text-xs text-gray-500 mt-1">Approved requests</div>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="text-sm text-green-600 font-medium mb-1">Items Restocked</div>
                    <div class="text-2xl font-bold text-gray-900">${report.total_restocked || 0}</div>
                    <div class="text-xs text-gray-500 mt-1">Total quantity added</div>
                </div>
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <div class="text-sm text-purple-600 font-medium mb-1">Items Claimed</div>
                    <div class="text-2xl font-bold text-gray-900">${report.total_claimed || 0}</div>
                    <div class="text-xs text-gray-500 mt-1">Successfully claimed</div>
                </div>
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <div class="text-sm text-amber-600 font-medium mb-1">Status</div>
                    <div class="text-2xl font-bold text-gray-900">${report.is_finalized ? 'Finalized' : 'Preliminary'}</div>
                    <div class="text-xs text-gray-500 mt-1">Report status</div>
                </div>
            </div>
        </div>
    `;
    
    contentDiv.innerHTML = html;
}

function generateMissingReports() {
    const year = new Date().getFullYear();
    
    if (!confirm(`Generate missing reports for ${year}? This may take a moment.`)) {
        return;
    }
    
    showLoading('Generating missing reports...');
    
    fetch(`/admin/reports/generate-missing`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ year: year })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.success) {
            showToast('success', `Generated ${data.generated} reports successfully!`);
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            showToast('error', data.message || 'Failed to generate reports');
        }
    })
    .catch(error => {
        hideLoading();
        showToast('error', 'Error generating reports: ' + error.message);
    });
}

function finalizeReport(year, month) {
    const monthName = new Date(year, month - 1).toLocaleString('default', { month: 'long' });
    
    if (!confirm(`Are you sure you want to finalize the report for ${monthName} ${year}? This action cannot be undone.`)) {
        return;
    }
    
    showLoading('Finalizing report...');
    
    fetch(`/admin/reports/finalize`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ year, month })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.success) {
            showToast('success', 'Report finalized successfully!');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showToast('error', data.message || 'Failed to finalize report');
        }
    })
    .catch(error => {
        hideLoading();
        showToast('error', 'Error finalizing report: ' + error.message);
    });
}

// ===========================================
// UTILITY FUNCTIONS
// ===========================================

function showLoading(message) {
    // Remove existing loading overlay
    const existingOverlay = document.getElementById('loading-overlay');
    if (existingOverlay) {
        existingOverlay.remove();
    }
    
    // Create loading overlay
    const loadingOverlay = document.createElement('div');
    loadingOverlay.id = 'loading-overlay';
    loadingOverlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    loadingOverlay.innerHTML = `
        <div class="bg-white rounded-lg p-6 shadow-xl">
            <div class="flex items-center">
                <i class="fas fa-spinner fa-spin text-blue-500 text-2xl mr-3"></i>
                <span class="text-gray-700">${message}</span>
            </div>
        </div>
    `;
    document.body.appendChild(loadingOverlay);
}

function hideLoading() {
    const loadingOverlay = document.getElementById('loading-overlay');
    if (loadingOverlay) {
        loadingOverlay.remove();
    }
}

function showToast(type, message) {
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.toast-notification');
    existingToasts.forEach(toast => {
        if (toast.parentElement) {
            toast.remove();
        }
    });
    
    // Create toast notification
    const toast = document.createElement('div');
    toast.className = `toast-notification fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-100 border border-green-300 text-green-700' :
        type === 'error' ? 'bg-red-100 border border-red-300 text-red-700' :
        'bg-blue-100 border border-blue-300 text-blue-700'
    }`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-3"></i>
            <span class="flex-1">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    document.body.appendChild(toast);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (toast.parentElement) {
            toast.remove();
        }
    }, 5000);
}

// Close dropdowns when clicking outside
function setupDropdownClose() {
    document.addEventListener('click', function(e) {
        // Close monthly report dropdowns
        if (!e.target.closest('.monthly-report-download-btn') && !e.target.closest('.monthly-report-dropdown')) {
            document.querySelectorAll('.monthly-report-dropdown').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }
        
        // Close modals when clicking backdrop
        if (e.target.classList.contains('modal-backdrop')) {
            const modal = e.target.closest('.modal-container');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }
    });
}

// ===========================================
// INITIALIZATION
// ===========================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('📊 Dashboard loaded');
    
    // Setup dropdown close functionality
    setupDropdownClose();
    
    // Initialize inventory search functionality for team members
    <?php if(auth()->check() && !auth()->user()->isAdmin()): ?>
    const searchInput = document.getElementById('inventory-search');
    if (searchInput) {
        searchInput.addEventListener('input', filterInventoryItems);
        console.log('🔍 Inventory search initialized');
    }
    <?php endif; ?>
    
    // Initialize admin dashboard tabs
    <?php if(auth()->check() && auth()->user()->isAdmin()): ?>
    console.log('👑 Admin dashboard detected');
    
    // Initialize current tab functionality
    const currentTab = '<?php echo e($tab); ?>';
    if (currentTab) {
        console.log(`📍 Current tab: ${currentTab}`);
        initializeTab(currentTab);
    }
    
    // Add click handler for refresh analytics button
    const refreshBtn = document.getElementById('refresh-analytics');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            location.reload();
        });
    }
    <?php endif; ?>
});

// Make functions globally available
window.startRestocking = startRestocking;
window.closeModal = closeModal;
window.showReportDetailsModal = showReportDetailsModal;
window.finalizeReport = finalizeReport;
window.downloadReport = downloadReportDirect; // Legacy support
window.downloadReportWithModal = downloadReportWithModal; // New enhanced function
window.generateMissingReports = generateMissingReports;
window.filterInventoryItems = filterInventoryItems;
window.clearInventorySearch = clearInventorySearch;
</script>

<!-- Include Modals -->
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(View::exists('components.modals.start-restocking')): ?>
    <?php echo $__env->make('components.modals.start-restocking', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(View::exists('components.modals.restock-confirmation')): ?>
    <?php echo $__env->make('components.modals.restock-confirmation', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<!-- Include the report not generated modal -->
<?php echo $__env->make('components.modals.report-not-generated', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Jhon Rhey\Downloads\Land Bank Inventory System\Land Bank Inventory System\inventory-system\resources\views/dashboard.blade.php ENDPATH**/ ?>