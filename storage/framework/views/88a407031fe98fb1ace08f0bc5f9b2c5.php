<?php $__env->startSection('title', 'Inventory - Inventory System'); ?>

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
                <?php echo e(__('Inventory Items')); ?>

            </h2>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isAdmin()): ?>
                <a href="<?php echo e(route('admin.items.create')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center shadow-md transition duration-150 ease-in-out">
                    <i class="fas fa-plus mr-2"></i> Add New Item
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                    <div class="flex">
                        <div class="py-1">
                            <svg class="h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold">Success</p>
                            <p><?php echo e(session('success')); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
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

            
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <form id="filterForm" method="GET" action="<?php echo e(auth()->user()->isAdmin() ? route('admin.items.index') : route('items.index')); ?>">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="relative flex-grow">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input 
                                    type="text" 
                                    id="searchItems" 
                                    name="search"
                                    value="<?php echo e(request('search')); ?>"
                                    placeholder="Search items by name or unit..." 
                                    class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-150"
                                >
                            </div>
                            
                            <div class="flex space-x-4">
                                <select id="stockFilter" name="status" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-150">
                                    <option value="">All Stock Status</option>
                                    <option value="in-stock" <?php echo e(request('status') == 'in-stock' ? 'selected' : ''); ?>>In Stock</option>
                                    <option value="low-stock" <?php echo e(request('status') == 'low-stock' ? 'selected' : ''); ?>>Low Stock</option>
                                    <option value="out-of-stock" <?php echo e(request('status') == 'out-of-stock' ? 'selected' : ''); ?>>Out of Stock</option>
                                </select>
                                
                                
                                <?php
                                    $hasActiveFilter = request('search') || request('status');
                                ?>
                                
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasActiveFilter): ?>
                                    <a href="<?php echo e(auth()->user()->isAdmin() ? route('admin.items.index') : route('items.index')); ?>" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 flex items-center transition duration-150 ease-in-out shadow-sm">
                                        <i class="fas fa-times mr-2"></i> Clear
                                    </a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                        
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('page')): ?>
                            <input type="hidden" name="page" value="<?php echo e(request('page')); ?>">
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </form>
                </div>
            </div>

            
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($items->count() > 0 || request('search') || request('status') != 'all'): ?>
                        
                        
                        <?php
                            $hasFilter = request('search') || request('status');
                            $filterText = '';
                            $filteredCount = $items->total();
                            
                        ?>
                    
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="itemsTable">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                            Item Name
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                            Unit
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                            Available Stock
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                            Min
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                            Status
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                            Updated
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            // Calculate available stock
                                            $pendingRequests = $item->pending_quantity_sum ?? 0;
                                            $availableStock = $item->quantity - $pendingRequests;
                                            
                                            // Determine status based on AVAILABLE stock
                                            if ($availableStock <= 0) {
                                                $status = 'out-of-stock';
                                                $statusClass = 'bg-red-100 text-red-800 border-red-300';
                                                $statusIcon = 'fa-times-circle';
                                                $statusText = 'Out of Stock';
                                            } elseif ($availableStock <= $item->minimum_stock) {
                                                $status = 'low-stock';
                                                $statusClass = 'bg-yellow-100 text-yellow-800 border-yellow-300';
                                                $statusIcon = 'fa-exclamation-triangle';
                                                $statusText = 'Low Stock';
                                            } else {
                                                $status = 'in-stock';
                                                $statusClass = 'bg-green-100 text-green-800 border-green-300';
                                                $statusIcon = 'fa-check-circle';
                                                $statusText = 'In Stock';
                                            }
                                        ?>
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded flex items-center justify-center mr-3 border border-blue-200">
                                                        <i class="fas fa-box text-blue-600 text-sm"></i>
                                                    </div>
                                                    <div class="truncate max-w-xs">
                                                        <a href="<?php echo e(auth()->user()->isAdmin() ? route('admin.items.show', $item) : route('items.show', $item)); ?>" class="text-sm font-medium text-gray-900 hover:text-blue-600 transition duration-150 ease-in-out truncate block" title="<?php echo e($item->name); ?>">
                                                            <?php echo e($item->name); ?>

                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                <div class="font-medium"><?php echo e($item->unit ?: '-'); ?></div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm font-bold text-gray-900">
                                                    <?php echo e(number_format($item->available_stock)); ?>

                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                <?php echo e(number_format($item->minimum_stock)); ?>

                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?php echo e($statusClass); ?>">
                                                    <i class="fas <?php echo e($statusIcon); ?> mr-1"></i> 
                                                    <?php echo e($statusText); ?>

                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                <div class="text-xs"><?php echo e($item->updated_at->format('M d, Y')); ?></div>
                                                <div class="text-xs text-gray-400"><?php echo e($item->updated_at->diffForHumans()); ?></div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <div class="flex items-center space-x-3">
                                                    
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isAdmin()): ?>
                                                        
                                                        <a href="<?php echo e(route('admin.items.edit', $item)); ?>" 
                                                        class="text-blue-600 hover:text-blue-800 transition duration-150"
                                                        title="Edit Item">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        
                                                        <form action="<?php echo e(route('admin.items.destroy', $item)); ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this item? This action cannot be undone.')">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" 
                                                                    class="text-red-600 hover:text-red-800 transition duration-150"
                                                                    title="Delete Item">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                    
                                                    
                                                    <?php if(auth()->user()->isTeamMember() && $availableStock > 0): ?>
                                                        <a href="<?php echo e(route('requests.create')); ?>?item_id=<?php echo e($item->id); ?>" 
                                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-md text-xs font-medium flex items-center transition duration-150 ease-in-out shadow-sm whitespace-nowrap"
                                                        title="Request this Item">
                                                            <i class="fas fa-hand-paper mr-1.5 text-xs"></i>
                                                            Request
                                                        </a>
                                                    <?php elseif(auth()->user()->isTeamMember()): ?>
                                                        <button class="bg-gray-300 text-gray-500 px-3 py-1.5 rounded-md text-xs font-medium flex items-center cursor-not-allowed whitespace-nowrap"
                                                                title="No stock available for request"
                                                                disabled>
                                                            <i class="fas fa-ban mr-1.5 text-xs"></i>
                                                            Unavailable
                                                        </button>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($items->total() == 0 && (request('search') || request('status') != 'all')): ?>
                            <div id="noResults" class="text-center py-12">
                                <i class="fas fa-search text-gray-300 text-5xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No Items Found</h3>
                                <p class="text-gray-500 mb-4">Try adjusting your search or filter to find what you're looking for.</p>
                                <a href="<?php echo e(auth()->user()->isAdmin() ? route('admin.items.index') : route('items.index')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 inline-flex items-center transition duration-150 ease-in-out shadow-md">
                                    <i class="fas fa-times mr-2"></i> Clear All Filters
                                </a>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($items->hasPages()): ?>
                            <div class="mt-6">
                                <?php echo e($items->appends(request()->except('page'))->links()); ?>

                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        
                    <?php else: ?>
                        
                        <div class="text-center py-12">
                            <i class="fas fa-boxes text-gray-300 text-5xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Items in Inventory</h3>
                            <p class="text-gray-500 mb-4">Get started by adding your first inventory item.</p>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isAdmin()): ?>
                                <a href="<?php echo e(route('admin.items.create')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 inline-flex items-center transition duration-150 ease-in-out shadow-md">
                                    <i class="fas fa-plus mr-2"></i> Add First Item
                                </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div id="addStockModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4" id="modalTitle">Add Stock</h3>
                <form id="addStockForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="item_id" id="modalItemId">
                    
                    <div class="mb-4">
                        <label for="itemName" class="block text-sm font-medium text-gray-700 mb-1">Item</label>
                        <input type="text" 
                               id="itemName" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 cursor-not-allowed" 
                               readonly>
                    </div>
                    
                    <div class="mb-4">
                        <label for="currentStock" class="block text-sm font-medium text-gray-700 mb-1">Current Stock</label>
                        <input type="text" 
                               id="currentStock" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 cursor-not-allowed" 
                               readonly>
                    </div>
                    
                    <div class="mb-6">
                        <label for="quantityToAdd" class="block text-sm font-medium text-gray-700 mb-1">Quantity to Add *</label>
                        <div class="relative">
                            <input type="number" 
                                   id="quantityToAdd" 
                                   name="quantity"
                                   min="1"
                                   max="999999"
                                   step="1"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter quantity"
                                   autofocus>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" 
                                        onclick="incrementQuantity()" 
                                        class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Enter the quantity you want to add to current stock.</p>
                    </div>
                    
                    <div class="mb-4 p-3 bg-blue-50 rounded-md">
                        <p class="text-sm font-medium text-blue-900 mb-1">New Stock will be:</p>
                        <p class="text-lg font-bold text-blue-700" id="newStockPreview">0</p>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                onclick="hideAddStockModal()"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-plus-circle mr-2"></i> Add Stock
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchItems');
            const stockFilter = document.getElementById('stockFilter');
            const filterForm = document.getElementById('filterForm');
            
            function submitFilterForm() {
                const pageInput = filterForm.querySelector('input[name="page"]');
                if (pageInput) pageInput.remove();
                filterForm.submit();
            }
            
            stockFilter.addEventListener('change', submitFilterForm);
            
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(submitFilterForm, 500);
            });
            
            // Quantity to Add input event
            const quantityInput = document.getElementById('quantityToAdd');
            if (quantityInput) {
                quantityInput.addEventListener('input', updateNewStockPreview);
            }
            
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                    e.preventDefault();
                    if (searchInput) {
                        searchInput.focus();
                        searchInput.select();
                    }
                }
                
                if (e.key === 'Escape' && document.activeElement === searchInput) {
                    if (searchInput.value !== '' || stockFilter.value !== 'all') {
                        e.preventDefault();
                        searchInput.value = '';
                        stockFilter.value = 'all';
                        submitFilterForm();
                    }
                }
                
                // Close modal with Escape key
                if (e.key === 'Escape') {
                    const modal = document.getElementById('addStockModal');
                    if (!modal.classList.contains('hidden')) {
                        hideAddStockModal();
                    }
                }
            });
        });
        
        // Modal Functions
        function showAddStockModal(itemId, itemName, currentStock) {
            const modal = document.getElementById('addStockModal');
            const form = document.getElementById('addStockForm');
            const itemNameInput = document.getElementById('itemName');
            const currentStockInput = document.getElementById('currentStock');
            const quantityInput = document.getElementById('quantityToAdd');
            
            // Set form action
            form.action = `/admin/items/${itemId}/add-stock`;
            
            // Set modal values
            document.getElementById('modalItemId').value = itemId;
            itemNameInput.value = itemName;
            currentStockInput.value = currentStock.toLocaleString();
            
            // Reset quantity input
            quantityInput.value = '';
            quantityInput.focus();
            
            // Update preview
            updateNewStockPreview();
            
            // Show modal
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        
        function hideAddStockModal() {
            const modal = document.getElementById('addStockModal');
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        
        function updateNewStockPreview() {
            const quantityInput = document.getElementById('quantityToAdd');
            const currentStockInput = document.getElementById('currentStock');
            const preview = document.getElementById('newStockPreview');
            
            if (!quantityInput || !currentStockInput || !preview) return;
            
            const quantity = parseInt(quantityInput.value) || 0;
            const currentStock = parseInt(currentStockInput.value.replace(/,/g, '')) || 0;
            const newStock = currentStock + quantity;
            
            preview.textContent = newStock.toLocaleString();
        }
        
        function incrementQuantity() {
            const quantityInput = document.getElementById('quantityToAdd');
            if (quantityInput) {
                let currentValue = parseInt(quantityInput.value) || 0;
                quantityInput.value = currentValue + 1;
                updateNewStockPreview();
            }
        }
        
        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('addStockModal');
            if (!modal.classList.contains('hidden')) {
                const modalContent = modal.querySelector('div.bg-white');
                if (!modalContent.contains(event.target)) {
                    hideAddStockModal();
                }
            }
        });
    </script>

    <style>
         #itemsTable {
        table-layout: fixed;
    }
    
    #itemsTable th:nth-child(1), #itemsTable td:nth-child(1) { width: 22%; } /* Reduced slightly */
    #itemsTable th:nth-child(2), #itemsTable td:nth-child(2) { width: 8%; }
    #itemsTable th:nth-child(3), #itemsTable td:nth-child(3) { width: 10%; } /* Total Stock */
    #itemsTable th:nth-child(4), #itemsTable td:nth-child(4) { width: 12%; } /* Available Stock */
    #itemsTable th:nth-child(5), #itemsTable td:nth-child(5) { width: 8%; }
    #itemsTable th:nth-child(6), #itemsTable td:nth-child(6) { width: 12%; }
    #itemsTable th:nth-child(7), #itemsTable td:nth-child(7) { width: 12%; }
    #itemsTable th:nth-child(8), #itemsTable td:nth-child(8) { width: 16%; } /* Increased for actions */
    
    #itemsTable td, #itemsTable th {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    #itemsTable td:nth-child(1) .truncate {
        max-width: 100%;
    }
        
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #bdbdbd;
            border-radius: 4px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
        
        /* Modal animation */
        #addStockModal {
            transition: opacity 0.3s ease;
        }
        
        #addStockModal.hidden {
            opacity: 0;
            pointer-events: none;
        }
        
        #addStockModal > div {
            transform: scale(0.95);
            transition: transform 0.3s ease;
        }
        
        #addStockModal:not(.hidden) > div {
            transform: scale(1);
        }
    </style>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $attributes = $__attributesOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__attributesOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $component = $__componentOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__componentOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?><?php /**PATH C:\Users\Jhon Rhey\Downloads\inventory-system\resources\views/items/index.blade.php ENDPATH**/ ?>