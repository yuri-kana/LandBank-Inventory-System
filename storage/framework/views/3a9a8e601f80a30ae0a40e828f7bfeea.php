<?php $__env->startSection('title', 'Requests - Inventory System'); ?>

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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e(auth()->user()->isAdmin() ? __('All Requests') : __('My Team Requests')); ?>

            </h2>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isTeamMember()): ?>
                <a href="<?php echo e(route('requests.create')); ?>" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                    <i class="fas fa-plus-circle mr-2"></i> New Request
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                <?php echo e(session('success')); ?>

                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <?php echo e(session('error')); ?>

                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Filter Section -->
                    <div class="mb-6 space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <!-- Filter Tabs -->
                            <div class="border-b border-gray-200 flex-1">
                                <nav class="-mb-px flex space-x-8 overflow-x-auto">
                                    <a href="<?php echo e(route('requests.index', array_merge(request()->except(['status', 'page']), ['status' => null]))); ?>" 
                                       class="<?php echo e(!request('status') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?> py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap">
                                        All Requests
                                        <span class="bg-gray-100 text-gray-900 ml-2 py-0.5 px-2.5 rounded-full text-xs">
                                            <?php echo e($counts['all'] ?? 0); ?>

                                        </span>
                                    </a>
                                    <a href="<?php echo e(route('requests.index', array_merge(request()->except(['status', 'page']), ['status' => 'pending']))); ?>" 
                                       class="<?php echo e(request('status') == 'pending' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?> py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap">
                                        Pending
                                        <span class="bg-yellow-100 text-yellow-900 ml-2 py-0.5 px-2.5 rounded-full text-xs">
                                            <?php echo e($counts['pending'] ?? 0); ?>

                                        </span>
                                    </a>
                                    <a href="<?php echo e(route('requests.index', array_merge(request()->except(['status', 'page']), ['status' => 'approved']))); ?>" 
                                       class="<?php echo e(request('status') == 'approved' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?> py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap">
                                        Approved
                                        <span class="bg-green-100 text-green-900 ml-2 py-0.5 px-2.5 rounded-full text-xs">
                                            <?php echo e($counts['approved'] ?? 0); ?>

                                        </span>
                                    </a>
                                    <a href="<?php echo e(route('requests.index', array_merge(request()->except(['status', 'page']), ['status' => 'rejected']))); ?>" 
                                       class="<?php echo e(request('status') == 'rejected' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?> py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap">
                                        Rejected
                                        <span class="bg-red-100 text-red-900 ml-2 py-0.5 px-2.5 rounded-full text-xs">
                                            <?php echo e($counts['rejected'] ?? 0); ?>

                                        </span>
                                    </a>
                                    <a href="<?php echo e(route('requests.index', array_merge(request()->except(['status', 'page']), ['status' => 'claimed']))); ?>" 
                                       class="<?php echo e(request('status') == 'claimed' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?> py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap">
                                        Claimed
                                        <span class="bg-blue-100 text-blue-900 ml-2 py-0.5 px-2.5 rounded-full text-xs">
                                            <?php echo e($counts['claimed'] ?? 0); ?>

                                        </span>
                                    </a>
                                </nav>
                            </div>

                            <!-- Team Filter Dropdown -->
                            <?php if(auth()->user()->isAdmin() && $teams->count() > 0): ?>
                                <div class="relative">
                                    <form method="GET" action="<?php echo e(route('requests.index')); ?>" class="flex items-center gap-2">
                                        <input type="hidden" name="status" value="<?php echo e(request('status')); ?>">
                                        
                                        <label for="team_filter" class="text-sm font-medium text-gray-700 whitespace-nowrap">
                                            <i class="fas fa-filter mr-1"></i> Filter by Team:
                                        </label>
                                        
                                        <select id="team_filter" 
                                                name="team" 
                                                onchange="this.form.submit()"
                                                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 min-w-[150px]">
                                            <option value="" disabled <?php echo e(!request('team') ? 'selected' : ''); ?> style="font-style: italic; color: #6b7280;">
                                                All Teams
                                            </option>
                                            <?php
                                                // Sort teams numerically by extracting the number from the name
                                                $sortedTeams = $teams->sort(function($a, $b) {
                                                    // Extract numbers from team names (e.g., "Team 6" -> 6)
                                                    preg_match('/\d+/', $a->name, $matchesA);
                                                    preg_match('/\d+/', $b->name, $matchesB);
                                                    
                                                    $numA = isset($matchesA[0]) ? (int)$matchesA[0] : PHP_INT_MAX;
                                                    $numB = isset($matchesB[0]) ? (int)$matchesB[0] : PHP_INT_MAX;
                                                    
                                                    return $numA <=> $numB;
                                                });
                                            ?>
                                            
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $sortedTeams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($team->id); ?>" 
                                                        <?php echo e(request('team') == $team->id ? 'selected' : ''); ?>>
                                                    <?php echo e($team->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </select>
                                        
                                        <!-- Clear Filter Button -->
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('team')): ?>
                                            <a href="<?php echo e(route('requests.index', request()->except(['team', 'page']))); ?>" 
                                               class="text-sm text-gray-600 hover:text-gray-900 px-2 py-1 rounded hover:bg-gray-100 transition">
                                                <i class="fas fa-times mr-1"></i> Clear
                                            </a>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </form>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <!-- Active Filters Info -->
                        <?php if(request('team') && auth()->user()->isAdmin()): ?>
                            <?php
                                $selectedTeam = $teams->firstWhere('id', request('team'));
                            ?>
                            <div class="flex items-center bg-blue-50 border border-blue-200 rounded-md p-3">
                                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                <span class="text-sm text-blue-700">
                                    Showing requests for: 
                                    <strong class="ml-1"><?php echo e($selectedTeam->name ?? 'Selected Team'); ?></strong>
                                </span>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Admin Notes Modal -->
                    <?php if(auth()->user()->isAdmin()): ?>
                    <div id="adminNotesModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden items-center justify-center z-50 p-4">
                        <div class="bg-white rounded-lg p-6 max-w-md w-full">
                            <h3 class="text-lg font-semibold mb-4" id="modalTitle">Add Admin Notes</h3>
                            <form id="adminNotesForm" method="POST" action="">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="status" id="modalStatus">
                                <div class="mb-4">
                                    <label for="modalAdminNotes" class="block text-sm font-medium text-gray-700 mb-2">
                                        Notes (Optional)
                                    </label>
                                    <textarea 
                                        name="admin_notes" 
                                        id="modalAdminNotes" 
                                        rows="3" 
                                        class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Add notes about this decision... Leave empty for 'None'"
                                    ></textarea>
                                    <p class="text-xs text-gray-500 mt-1">If no notes are added, it will show as "None"</p>
                                </div>
                                <div class="flex justify-end space-x-3">
                                    <button type="button" onclick="closeAdminNotesModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                        Confirm
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Reject Request Modal (NEW) -->
                    <?php if(auth()->user()->isAdmin()): ?>
                    <div id="rejectModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden items-center justify-center z-50 p-4">
                        <div class="bg-white rounded-lg p-6 max-w-md w-full">
                            <h3 class="text-lg font-semibold mb-4">Reject Request</h3>
                            <form id="rejectForm" method="POST" action="">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="status" value="rejected">
                                <div class="mb-4">
                                    <label for="rejectNotes" class="block text-sm font-medium text-gray-700 mb-2">
                                        Notes (Optional)
                                    </label>
                                    <textarea 
                                        name="admin_notes" 
                                        id="rejectNotes" 
                                        rows="3" 
                                        class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Add notes about why this request is being rejected..."
                                    ></textarea>
                                    <p class="text-xs text-gray-500 mt-1">If no notes are added, it will show as "None"</p>
                                </div>
                                <div class="flex justify-end space-x-3">
                                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                                        Reject Request
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Claim Confirmation Modal -->
                    <?php if(auth()->user()->isAdmin()): ?>
                    <div id="claimModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden items-center justify-center z-50 p-4">
                        <div class="bg-white rounded-lg p-6 max-w-md w-full">
                            <h3 class="text-lg font-semibold mb-4">Confirm Claim</h3>
                            <p id="claimMessage" class="text-gray-700 mb-6">
                                Are you sure you want to claim this item?
                            </p>
                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="closeClaimModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                                    Cancel
                                </button>
                                <form id="claimForm" method="POST" action="" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                        Claim Items
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Table Container -->
                    <div class="rounded-lg border border-gray-200 w-full">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <?php if(auth()->user()->isAdmin()): ?>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                            Team
                                        </th>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">
                                        Item
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                        Quantity
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-36">
                                        Status
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">
                                        Requested Date
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">
                                        Updated Date
                                    </th>
                                    <?php if(auth()->user()->isAdmin()): ?>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">
                                            Actions
                                        </th>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-64">
                                        Notes
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <?php if(auth()->user()->isAdmin()): ?>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                <div class="flex items-center">
                                                    <i class="fas fa-users mr-2 text-gray-400"></i>
                                                    <?php echo e($request->team->name ?? 'N/A'); ?>

                                                </div>
                                            </td>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-box mr-2 text-blue-400"></i>
                                                <a href="<?php echo e(auth()->user()->isAdmin() ? route('admin.items.show', $request->item) : route('items.show', $request->item)); ?>" 
                                                   class="text-blue-600 hover:text-blue-900 hover:underline transition truncate max-w-[180px]">
                                                    <?php echo e($request->item->name ?? 'N/A'); ?>

                                                </a>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex items-center">
                                                <span class="font-semibold text-gray-700"><?php echo e($request->quantity_requested); ?></span>
                                                <span class="text-xs text-gray-500 ml-1"><?php echo e($request->item->unit ?? 'units'); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span class="px-3 py-1 rounded-full text-xs font-medium inline-flex items-center justify-center min-w-[80px]
                                                    <?php echo e($request->status === 'claimed' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 
                                                       ($request->status === 'approved' ? 'bg-green-100 text-green-800 border border-green-200' : 
                                                       ($request->status === 'rejected' ? 'bg-red-100 text-red-800 border border-red-200' : 'bg-yellow-100 text-yellow-800 border border-yellow-200'))); ?>">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($request->status === 'claimed'): ?>
                                                        <i class="fas fa-check-double mr-1"></i>
                                                    <?php elseif($request->status === 'approved'): ?>
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                    <?php elseif($request->status === 'rejected'): ?>
                                                        <i class="fas fa-times-circle mr-1"></i>
                                                    <?php else: ?>
                                                        <i class="fas fa-clock mr-1"></i>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    <?php echo e(ucfirst($request->status)); ?>

                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex flex-col">
                                                <span><?php echo e($request->created_at->format('M d, Y')); ?></span>
                                                <span class="text-xs text-gray-500"><?php echo e($request->created_at->format('h:i A')); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex flex-col">
                                                <span><?php echo e($request->updated_at->format('M d, Y')); ?></span>
                                                <span class="text-xs text-gray-500"><?php echo e($request->updated_at->format('h:i A')); ?></span>
                                            </div>
                                        </td>
                                        <?php if(auth()->user()->isAdmin()): ?>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($request->status === 'pending'): ?>
                                                    <div class="flex flex-col sm:flex-row sm:space-x-2 space-y-1 sm:space-y-0">
                                                        <button onclick="showAdminNotesModal('<?php echo e($request->id); ?>', 'approved')" 
                                                                class="px-3 py-1 bg-green-100 text-green-700 rounded-md hover:bg-green-200 transition inline-flex items-center justify-center text-xs w-full sm:w-auto">
                                                            <i class="fas fa-check-circle mr-1"></i> Approve
                                                        </button>
                                                        <button onclick="showRejectModal('<?php echo e($request->id); ?>')" 
                                                                class="px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition inline-flex items-center justify-center text-xs w-full sm:w-auto">
                                                            <i class="fas fa-times-circle mr-1"></i> Reject
                                                        </button>
                                                    </div>
                                                <?php elseif($request->status === 'approved'): ?>
                                                    <button onclick="showClaimModal('<?php echo e($request->id); ?>', '<?php echo e($request->quantity_requested); ?>', '<?php echo e($request->item->unit ?? 'units'); ?>', '<?php echo e($request->item->name); ?>')" 
                                                            class="px-3 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition inline-flex items-center justify-center text-xs w-full">
                                                        <i class="fas fa-hand-holding mr-1"></i> Claim Items
                                                    </button>
                                                <?php elseif($request->status === 'claimed'): ?>
                                                    <div class="text-sm text-gray-600 italic text-center">
                                                        <i class="fas fa-check-double mr-1"></i> Claimed
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-gray-500 text-sm italic text-center">
                                                        <?php echo e(ucfirst($request->status)); ?>

                                                    </span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </td>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <td class="px-4 py-4 text-sm text-gray-500">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($request->status !== 'pending'): ?>
                                                <div class="text-sm">
                                                    <div class="text-xs text-gray-600 bg-gray-50 p-2 rounded border">
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($request->admin_notes && trim($request->admin_notes) !== ''): ?>
                                                            <i class="fas fa-sticky-note mr-1 text-gray-400"></i>
                                                            <span class="truncate block max-w-[200px]">
                                                                <?php echo e(Str::limit($request->admin_notes, 50)); ?>

                                                            </span>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(strlen($request->admin_notes) > 50): ?>
                                                                <span class="text-blue-500 cursor-help" title="<?php echo e($request->admin_notes); ?>">...</span>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        <?php else: ?>
                                                            <span class="text-gray-400 italic">
                                                                <i class="fas fa-minus mr-1"></i> None
                                                            </span>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </div>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($request->status === 'claimed' && $request->claimer): ?>
                                                        <div class="text-xs text-blue-600 mt-2">
                                                            <i class="fas fa-user-check mr-1"></i>
                                                            Claimed by: <?php echo e($request->claimer->name ?? 'Admin'); ?>

                                                        </div>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-gray-400 italic text-xs">
                                                    <i class="fas fa-clock mr-1"></i> Pending admin review...
                                                </span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="<?php echo e(auth()->user()->isAdmin() ? 9 : 7); ?>" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                                                <p class="text-lg text-gray-500 font-medium mb-2">No requests found</p>
                                                <p class="text-sm text-gray-400">
                                                    <?php if(request('team') && auth()->user()->isAdmin()): ?>
                                                        No requests found for the selected team.
                                                    <?php elseif(auth()->user()->isAdmin()): ?>
                                                        When team members make requests, they will appear here.
                                                    <?php else: ?>
                                                        Your team hasn't made any requests yet.
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </p>
                                                <?php if(request('team') && auth()->user()->isAdmin()): ?>
                                                    <a href="<?php echo e(route('requests.index', request()->except(['team']))); ?>" 
                                                       class="mt-3 text-sm text-blue-600 hover:text-blue-800 hover:underline">
                                                        <i class="fas fa-arrow-left mr-1"></i> Show all teams
                                                    </a>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($requests->hasPages()): ?>
                        <div class="mt-6">
                            <?php echo e($requests->appends(request()->except('page'))->links()); ?>

                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if(auth()->user()->isAdmin()): ?>
    <script>
    let currentRequestId = null;
    let currentStatus = null;
    
    function showAdminNotesModal(requestId, status) {
        console.log('Opening modal for request:', requestId, 'with status:', status);
        
        currentRequestId = requestId;
        currentStatus = status;
        const modal = document.getElementById('adminNotesModal');
        const statusInput = document.getElementById('modalStatus');
        const form = document.getElementById('adminNotesForm');
        const modalTitle = document.getElementById('modalTitle');
        
        // CORRECT: Use admin route with prefix
        const actionUrl = `/admin/requests/${requestId}/update-status`;
        console.log('Setting form action to:', actionUrl);
        form.action = actionUrl;
        
        // Set status
        statusInput.value = status;
        
        // Clear previous notes
        document.getElementById('modalAdminNotes').value = '';
        
        // Set modal title based on action
        modalTitle.textContent = status === 'approved' 
            ? 'Approve Request' 
            : 'Add Admin Notes';
        
        // Show modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Prevent body scrolling
        document.body.style.overflow = 'hidden';
    }
    
    function showRejectModal(requestId) {
        currentRequestId = requestId;
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        
        // Set the form action
        const actionUrl = `/admin/requests/${requestId}/update-status`;
        form.action = actionUrl;
        
        // Clear previous notes
        document.getElementById('rejectNotes').value = '';
        
        // Show modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Prevent body scrolling
        document.body.style.overflow = 'hidden';
    }
    
    function showClaimModal(requestId, quantity, unit, itemName) {
        const modal = document.getElementById('claimModal');
        const form = document.getElementById('claimForm');
        const message = document.getElementById('claimMessage');
        
        // Set the claim message exactly as in your image
        message.innerHTML = `Are you sure you want to claim ${quantity} ${unit} of ${itemName}?<br><br>This will deduct stock from inventory.`;
        
        // Set the form action
        const actionUrl = `/admin/requests/${requestId}/claim`;
        form.action = actionUrl;
        
        // Show modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Prevent body scrolling
        document.body.style.overflow = 'hidden';
    }
    
    function closeAdminNotesModal() {
        const modal = document.getElementById('adminNotesModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        currentRequestId = null;
        currentStatus = null;
        
        // Restore body scrolling
        document.body.style.overflow = '';
    }
    
    function closeRejectModal() {
        const modal = document.getElementById('rejectModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        currentRequestId = null;
        
        // Restore body scrolling
        document.body.style.overflow = '';
    }
    
    function closeClaimModal() {
        const modal = document.getElementById('claimModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        
        // Restore body scrolling
        document.body.style.overflow = '';
    }
    
    // Close modal when clicking outside or pressing Escape
    document.addEventListener('click', function(e) {
        const adminNotesModal = document.getElementById('adminNotesModal');
        const rejectModal = document.getElementById('rejectModal');
        const claimModal = document.getElementById('claimModal');
        
        if (adminNotesModal && !adminNotesModal.classList.contains('hidden') && e.target === adminNotesModal) {
            closeAdminNotesModal();
        }
        
        if (rejectModal && !rejectModal.classList.contains('hidden') && e.target === rejectModal) {
            closeRejectModal();
        }
        
        if (claimModal && !claimModal.classList.contains('hidden') && e.target === claimModal) {
            closeClaimModal();
        }
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const adminNotesModal = document.getElementById('adminNotesModal');
            const rejectModal = document.getElementById('rejectModal');
            const claimModal = document.getElementById('claimModal');
            
            if (adminNotesModal && !adminNotesModal.classList.contains('hidden')) {
                closeAdminNotesModal();
            }
            
            if (rejectModal && !rejectModal.classList.contains('hidden')) {
                closeRejectModal();
            }
            
            if (claimModal && !claimModal.classList.contains('hidden')) {
                closeClaimModal();
            }
        }
    });
    
    // Handle admin notes form submission - REMOVED CONFIRM DIALOG
    document.getElementById('adminNotesForm')?.addEventListener('submit', function(e) {
        const status = document.getElementById('modalStatus').value;
        const notes = document.getElementById('modalAdminNotes').value;
        
        console.log('Submitting form:', {
            action: this.action,
            status: status,
            admin_notes: notes
        });
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75');
        }
    });
    
    // Handle reject form submission - REMOVED CONFIRM DIALOG
    document.getElementById('rejectForm')?.addEventListener('submit', function(e) {
        console.log('Submitting reject form:', {
            action: this.action,
            status: 'rejected'
        });
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75');
        }
    });
    
    // Handle claim form submission - REMOVED CONFIRM DIALOG
    document.getElementById('claimForm')?.addEventListener('submit', function(e) {
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Claiming...';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75');
        }
    });
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Requests page loaded');
        
        // Check if modals exist
        const adminNotesModal = document.getElementById('adminNotesModal');
        const rejectModal = document.getElementById('rejectModal');
        const claimModal = document.getElementById('claimModal');
        
        if (adminNotesModal) {
            console.log('Admin notes modal found');
        }
        
        if (rejectModal) {
            console.log('Reject modal found');
        }
        
        if (claimModal) {
            console.log('Claim modal found');
        }
    });
    </script>
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
<?php endif; ?><?php /**PATH C:\Users\Jhon Rhey\Downloads\Land Bank Inventory System\Land Bank Inventory System\inventory-system\resources\views/requests/index.blade.php ENDPATH**/ ?>