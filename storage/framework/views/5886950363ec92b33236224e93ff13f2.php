<?php $__env->startSection('title', 'Item Request - Inventory System'); ?>

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
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    <?php echo e(__('New Item Request')); ?>

                </h2>
                <p class="text-gray-600 mt-1">
                    <?php echo e(__('For ') . auth()->user()->team->name); ?>

                </p>
            </div>
            <div class="flex gap-3">
                <a href="<?php echo e(route('requests.index')); ?>" 
                   class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2.5 rounded-lg font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Requests
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- Debug Info - Remove in production -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('app.debug') && false): ?>
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <h4 class="font-semibold text-yellow-800 mb-2">Debug Info</h4>
                <div class="text-sm text-yellow-700">
                    <p>Total Items: <?php echo e($items->count()); ?></p>
                    <p>Available Items: <?php echo e($items->where('available_for_request', '>', 0)->count()); ?></p>
                    <div class="mt-2 space-y-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-2 bg-yellow-100 rounded">
                            <strong><?php echo e($item->name); ?>:</strong><br>
                            - Quantity: <?php echo e($item->quantity); ?><br>
                            - Pending/Approved Requests: <?php echo e($item->pending_quantity_sum ?? 0); ?><br>
                            - Available for Request: <?php echo e($item->available_for_request ?? 0); ?><br>
                            - Is Available: <?php echo e($item->is_available ? 'Yes' : 'No'); ?>

                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Debug: Check if item is pre-selected -->
            <div class="hidden">
                <p>Request item_id: <?php echo e(request('item_id')); ?></p>
                <p>Selected item object: <?php echo e($selectedItem ? $selectedItem->id . ' - ' . $selectedItem->name : 'null'); ?></p>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center gap-2 text-red-800 font-medium mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Please fix the following errors:
                    </div>
                    <ul class="text-sm text-red-600 list-disc pl-5 space-y-1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center gap-2 text-red-800 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <?php echo e(session('error')); ?>

                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Main Form Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="p-1 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                <div class="p-8">
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-800">Request Form</h3>
                        <p class="text-gray-600 mt-2">Select an item and specify the quantity you need.</p>
                    </div>

                    <form method="POST" action="<?php echo e(route('requests.store')); ?>" class="space-y-8" id="requestForm">
                        <?php echo csrf_field(); ?>

                        <!-- Item Selection Section -->
                        <div class="space-y-6">
                            <div>
                                <label class="flex items-center gap-2 text-gray-700 text-base font-semibold mb-4">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    Select Item
                                </label>
                                
                                <div class="relative">
                                    <select
                                        name="item_id"
                                        id="item_id"
                                        required
                                        class="w-full pl-12 pr-10 py-4 border-2 border-gray-200 rounded-xl text-gray-700 bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all appearance-none cursor-pointer hover:border-gray-300"
                                    >
                                        <option value="" class="text-gray-400">Choose an item from the list...</option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $availableQuantity = $item->available_for_request ?? 0;
                                                // Check if this item should be selected
                                                $isSelected = old('item_id') == $item->id || 
                                                             (isset($selectedItem) && $selectedItem->id == $item->id);
                                            ?>
                                            <option value="<?php echo e($item->id); ?>" 
                                                <?php echo e($isSelected ? 'selected' : ''); ?>

                                                data-quantity="<?php echo e($availableQuantity); ?>"
                                                data-total-quantity="<?php echo e($item->quantity); ?>"
                                                data-pending="<?php echo e($item->pending_quantity_sum ?? 0); ?>"
                                                data-name="<?php echo e($item->name); ?>"
                                                class="<?php echo e($availableQuantity > 0 ? 'text-gray-800' : 'text-gray-400'); ?>"
                                            >
                                                <?php echo e($item->name); ?> 
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($availableQuantity > 0): ?>
                                                    • Available: <?php echo e($availableQuantity); ?> units
                                                <?php else: ?>
                                                    • Out of Stock
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </select>
                                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                
                                <!-- Stock Information Card -->
                                <div id="stock-details-card" class="mt-4 p-5 bg-gray-50 rounded-xl border border-gray-200 hidden">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-800 mb-2" id="item-name-display">Item Details</h4>
                                            <div class="space-y-2">
                                                <div class="flex items-center gap-3">
                                                    <span class="text-sm text-gray-600 w-32">Total Stock:</span>
                                                    <span class="font-medium text-gray-800" id="total-stock">0</span>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <span class="text-sm text-gray-600 w-32">Pending Requests:</span>
                                                    <span class="font-medium text-amber-600" id="pending-stock">0</span>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <span class="text-sm text-gray-600 w-32">Available:</span>
                                                    <span class="font-medium text-green-600" id="available-stock">0</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="availability-badge" class="px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            Select item
                                        </div>
                                    </div>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['item_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <?php echo e($message); ?>

                                    </p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <!-- Quantity Section -->
                            <div>
                                <label class="flex items-center gap-2 text-gray-700 text-base font-semibold mb-4">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    Quantity Requested
                                </label>
                                
                                <div class="relative max-w-xs">
                                    <input
                                        type="number"
                                        name="quantity_requested"
                                        id="quantity_requested"
                                        required
                                        min="1"
                                        class="w-full pl-12 pr-6 py-4 border-2 border-gray-200 rounded-xl text-lg text-gray-700 bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all hover:border-gray-300"
                                        value="<?php echo e(old('quantity_requested')); ?>"
                                        placeholder="0"
                                    >
                                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Quantity Feedback -->
                                <div id="quantity-feedback" class="mt-4 space-y-2">
                                    <p class="text-sm text-gray-500" id="available-quantity">
                                        Select an item to see available quantity
                                    </p>
                                    <div id="quantity-error" class="hidden p-4 bg-red-50 border border-red-100 rounded-lg">
                                        <div class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm text-red-700 font-medium" id="error-text"></p>
                                                <p class="text-xs text-red-600 mt-1" id="error-details"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['quantity_requested'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <?php echo e($message); ?>

                                    </p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        <!-- Summary Card -->
                        <div id="summary-card" class="hidden p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                            <h4 class="font-semibold text-gray-800 mb-3">Request Summary</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-600">Item Selected</p>
                                    <p class="font-medium text-gray-800" id="summary-item">-</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-600">Quantity Requested</p>
                                    <p class="font-medium text-gray-800" id="summary-quantity">-</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-600">Availability</p>
                                    <p class="font-medium" id="summary-availability">-</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-600">Remaining After Request</p>
                                    <p class="font-medium" id="summary-remaining">-</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-8 border-t border-gray-200">
                            <div class="flex flex-col sm:flex-row justify-end gap-4">
                                <a href="<?php echo e(route('requests.index')); ?>" 
                                   class="inline-flex items-center justify-center gap-2 bg-white border-2 border-gray-300 hover:border-gray-400 text-gray-700 hover:text-gray-800 font-semibold py-3 px-6 rounded-xl transition-all hover:shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Cancel
                                </a>
                                <button
                                    type="submit"
                                    id="submitBtn"
                                    class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Submit Request
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Text -->
            <div class="mt-8 p-6 bg-blue-50 rounded-xl border border-blue-100">
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-blue-100 rounded-lg flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800 mb-2">How to request items</h4>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-start gap-2">
                                <span class="text-blue-600 mt-1">•</span>
                                <span>Select an item from the dropdown menu to see real-time availability</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-blue-600 mt-1">•</span>
                                <span>Enter the quantity needed in the quantity field</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-blue-600 mt-1">•</span>
                                <span>The system will validate your request against current stock levels</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-blue-600 mt-1">•</span>
                                <span>Review the summary before submitting your request</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    const itemSelect = document.getElementById('item_id');
    const quantityInput = document.getElementById('quantity_requested');
    const availableQuantityDisplay = document.getElementById('available-quantity');
    const quantityErrorDisplay = document.getElementById('quantity-error');
    const errorText = document.getElementById('error-text');
    const errorDetails = document.getElementById('error-details');
    const submitBtn = document.getElementById('submitBtn');
    const stockDetailsCard = document.getElementById('stock-details-card');
    const summaryCard = document.getElementById('summary-card');
    const form = document.getElementById('requestForm');
    
    // Stock detail elements
    const itemNameDisplay = document.getElementById('item-name-display');
    const totalStockDisplay = document.getElementById('total-stock');
    const pendingStockDisplay = document.getElementById('pending-stock');
    const availableStockDisplay = document.getElementById('available-stock');
    const availabilityBadge = document.getElementById('availability-badge');
    
    // Summary elements
    const summaryItem = document.getElementById('summary-item');
    const summaryQuantity = document.getElementById('summary-quantity');
    const summaryAvailability = document.getElementById('summary-availability');
    const summaryRemaining = document.getElementById('summary-remaining');
    
    let selectedItemName = '';
    
    // Function to update stock details display
    function updateStockDetails() {
        const selectedOption = itemSelect.options[itemSelect.selectedIndex];
        
        if (selectedOption.value) {
            const availableQuantity = parseInt(selectedOption.getAttribute('data-quantity') || 0);
            const totalQuantity = parseInt(selectedOption.getAttribute('data-total-quantity') || 0);
            const pendingQuantity = parseInt(selectedOption.getAttribute('data-pending') || 0);
            selectedItemName = selectedOption.getAttribute('data-name') || selectedOption.textContent.split(' • ')[0];
            
            // Show stock details card
            stockDetailsCard.classList.remove('hidden');
            
            // Update stock details
            itemNameDisplay.textContent = selectedItemName;
            totalStockDisplay.textContent = `${totalQuantity} units`;
            pendingStockDisplay.textContent = `${pendingQuantity} units`;
            availableStockDisplay.textContent = `${availableQuantity} units`;
            
            // Update availability badge
            if (availableQuantity > 0) {
                const percentage = Math.round((availableQuantity / totalQuantity) * 100);
                if (percentage >= 50) {
                    availabilityBadge.className = 'px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800';
                    availabilityBadge.textContent = `Good Stock (${percentage}%)`;
                } else if (percentage >= 20) {
                    availabilityBadge.className = 'px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800';
                    availabilityBadge.textContent = `Low Stock (${percentage}%)`;
                } else {
                    availabilityBadge.className = 'px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800';
                    availabilityBadge.textContent = `Limited Stock (${percentage}%)`;
                }
            } else {
                availabilityBadge.className = 'px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800';
                availabilityBadge.textContent = 'Out of Stock';
            }
            
            // Update available quantity display
            if (availableQuantity > 0) {
                availableQuantityDisplay.innerHTML = `
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        <span>Available: <strong class="text-green-600">${availableQuantity}</strong> units</span>
                    </span>
                    <span class="text-gray-400 text-xs mt-1 block">Maximum quantity you can request</span>
                `;
            } else {
                availableQuantityDisplay.innerHTML = `
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                        <span class="text-red-600">Currently out of stock</span>
                    </span>
                    <span class="text-gray-400 text-xs mt-1 block">Check back later or contact administrator</span>
                `;
            }
        } else {
            // Hide stock details card
            stockDetailsCard.classList.add('hidden');
            availableQuantityDisplay.textContent = 'Select an item to see available quantity';
        }
    }
    
    // Function to update summary
    function updateSummary() {
        const selectedOption = itemSelect.options[itemSelect.selectedIndex];
        const quantity = parseInt(quantityInput.value) || 0;
        
        if (selectedOption.value && quantity > 0) {
            const availableQuantity = parseInt(selectedOption.getAttribute('data-quantity') || 0);
            const totalQuantity = parseInt(selectedOption.getAttribute('data-total-quantity') || 0);
            
            summaryCard.classList.remove('hidden');
            summaryItem.textContent = selectedItemName;
            summaryQuantity.textContent = `${quantity} units`;
            
            if (availableQuantity > 0) {
                const remaining = availableQuantity - quantity;
                summaryAvailability.innerHTML = `<span class="text-green-600">${availableQuantity} units available</span>`;
                summaryRemaining.innerHTML = remaining > 0 ? 
                    `<span class="text-green-600">${remaining} units</span>` : 
                    `<span class="text-amber-600">No units remaining</span>`;
            } else {
                summaryAvailability.innerHTML = `<span class="text-red-600">Out of stock</span>`;
                summaryRemaining.textContent = '-';
            }
        } else {
            summaryCard.classList.add('hidden');
        }
    }
    
    // Function to validate quantity
    function validateQuantity() {
        const selectedOption = itemSelect.options[itemSelect.selectedIndex];
        const availableQuantity = parseInt(selectedOption.getAttribute('data-quantity') || 0);
        const requestedQuantity = parseInt(quantityInput.value) || 0;
        const totalQuantity = parseInt(selectedOption.getAttribute('data-total-quantity') || 0);
        const pendingQuantity = parseInt(selectedOption.getAttribute('data-pending') || 0);
        
        // Reset states
        quantityErrorDisplay.classList.add('hidden');
        quantityInput.classList.remove('border-red-500', 'border-green-500');
        submitBtn.disabled = false;
        
        // Visual feedback for quantity input
        if (requestedQuantity > 0) {
            quantityInput.classList.add('border-green-500');
        }
        
        // Validate if item is selected
        if (!selectedOption.value) {
            showError('Please select an item', 'Choose an item from the dropdown list to proceed.');
            quantityInput.classList.add('border-red-500');
            submitBtn.disabled = true;
            return false;
        }
        
        // Validate if item has available quantity
        if (availableQuantity <= 0) {
            showError('No available units', 
                pendingQuantity > 0 ? 
                `${pendingQuantity} units are pending in other requests` : 
                'This item is currently out of stock.');
            submitBtn.disabled = true;
            return false;
        }
        
        // Validate requested quantity
        if (requestedQuantity <= 0) {
            showError('Invalid quantity', 'Please enter a quantity between 1 and ' + availableQuantity);
            quantityInput.classList.add('border-red-500');
            submitBtn.disabled = true;
            return false;
        }
        
        // Validate if requested quantity exceeds available
        if (requestedQuantity > availableQuantity) {
            showError('Quantity exceeds available stock',
                `Available: ${availableQuantity} units. ${pendingQuantity > 0 ? 
                `(${totalQuantity} total - ${pendingQuantity} pending)` : ''}`);
            quantityInput.classList.add('border-red-500');
            submitBtn.disabled = true;
            return false;
        }
        
        // Validate if requested quantity is within reasonable limits
        if (requestedQuantity > 1000) {
            showError('Maximum limit exceeded', 'Maximum quantity per request is 1000 units');
            quantityInput.classList.add('border-red-500');
            submitBtn.disabled = true;
            return false;
        }
        
        // Success state
        quantityErrorDisplay.classList.add('hidden');
        quantityInput.classList.remove('border-red-500');
        quantityInput.classList.add('border-green-500');
        submitBtn.disabled = false;
        
        return true;
    }
    
    // Function to show error
    function showError(title, details) {
        errorText.textContent = title;
        errorDetails.textContent = details;
        quantityErrorDisplay.classList.remove('hidden');
    }
    
    // Function to handle form submission
    function handleFormSubmit(e) {
        const selectedOption = itemSelect.options[itemSelect.selectedIndex];
        const quantity = parseInt(quantityInput.value) || 0;
        const availableQuantity = parseInt(selectedOption.getAttribute('data-quantity') || 0);
        
        // Validate before confirmation
        if (!validateQuantity()) {
            e.preventDefault();
            return;
        }
        
        // Simple confirmation (remove if you want the fancy dialog)
        const confirmed = confirm(`Confirm request for ${quantity} units of ${selectedItemName}?`);
        
        if (!confirmed) {
            e.preventDefault();
        }
    }
    
    // Event Listeners
    itemSelect.addEventListener('change', function() {
        updateStockDetails();
        validateQuantity();
        updateSummary();
        
        // Set max attribute on quantity input
        const selectedOption = itemSelect.options[itemSelect.selectedIndex];
        const availableQuantity = parseInt(selectedOption.getAttribute('data-quantity') || 0);
        
        if (selectedOption.value && availableQuantity > 0) {
            quantityInput.max = availableQuantity;
            quantityInput.placeholder = `Max: ${availableQuantity}`;
        } else {
            quantityInput.max = '';
            quantityInput.placeholder = 'Enter quantity';
        }
    });
    
    quantityInput.addEventListener('input', function() {
        validateQuantity();
        updateSummary();
        
        // Visual feedback
        const value = parseInt(this.value) || 0;
        if (value > 0) {
            this.classList.remove('border-red-500');
            this.classList.add('border-green-500');
        } else {
            this.classList.remove('border-green-500');
            this.classList.add('border-red-500');
        }
    });
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // If an item is pre-selected, trigger the change event immediately
        if (itemSelect.value) {
            itemSelect.dispatchEvent(new Event('change'));
        }
        
        updateStockDetails();
        validateQuantity();
        updateSummary();
        
        // Set up form submission handler
        if (form) {
            form.addEventListener('submit', handleFormSubmit);
        }
        
        // Set initial placeholder
        if (itemSelect.value) {
            const selectedOption = itemSelect.options[itemSelect.selectedIndex];
            const availableQuantity = parseInt(selectedOption.getAttribute('data-quantity') || 0);
            if (availableQuantity > 0) {
                quantityInput.max = availableQuantity;
                quantityInput.placeholder = `Max: ${availableQuantity}`;
            }
        }
    });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $attributes = $__attributesOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__attributesOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $component = $__componentOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__componentOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?><?php /**PATH C:\Users\Jhon Rhey\Downloads\inventory-system\resources\views/requests/create.blade.php ENDPATH**/ ?>