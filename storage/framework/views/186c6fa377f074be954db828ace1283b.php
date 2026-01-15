<?php $__env->startSection('title', 'Edit Item - Inventory System'); ?>

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
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    <?php echo e(__('Edit Inventory Item')); ?>

                </h2>
                <p class="text-gray-600 text-sm mt-1">Update item details and stock information</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="<?php echo e(route('admin.items.index')); ?>" class="inline-flex items-center gap-2 text-gray-700 hover:text-gray-900 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Inventory</span>
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                <div class="mb-8 animate-fade-in">
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">Item updated successfully!</p>
                                <p class="mt-1 text-sm text-green-700"><?php echo e(session('success')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div class="mb-8 animate-fade-in">
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Item Information -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <!-- Header -->
                        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <i class="fas fa-box-open text-blue-600 text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Item Information</h3>
                                    <p class="text-sm text-gray-600">Update basic item details</p>
                                </div>
                            </div>
                        </div>

                        <!-- Form -->
                        <form method="POST" action="<?php echo e(route('admin.items.update', $item)); ?>" class="p-6">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <!-- Item Name -->
                            <div class="mb-8">
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="name">
                                    Item Name <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-tag text-gray-400"></i>
                                    </div>
                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        required
                                        class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        value="<?php echo e(old('name', $item->name)); ?>"
                                        placeholder="Enter item name"
                                    >
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <!-- Unit Selection -->
                            <div class="mb-8">
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="unit">
                                    Unit of Measurement <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-ruler text-gray-400"></i>
                                    </div>
                                    <select
                                        name="unit"
                                        id="unit"
                                        required
                                        class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 appearance-none <?php $__errorArgs = ['unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        onchange="checkCustomUnit(this)"
                                    >
                                        <option value="">Select a unit</option>
                                        <option value="piece" <?php echo e(old('unit', $item->unit) == 'piece' ? 'selected' : ''); ?>>Piece</option>
                                        <option value="box" <?php echo e(old('unit', $item->unit) == 'box' ? 'selected' : ''); ?>>Box</option>
                                        <option value="pack" <?php echo e(old('unit', $item->unit) == 'pack' ? 'selected' : ''); ?>>Pack</option>
                                        <option value="set" <?php echo e(old('unit', $item->unit) == 'set' ? 'selected' : ''); ?>>Set</option>
                                        <option value="ream" <?php echo e(old('unit', $item->unit) == 'ream' ? 'selected' : ''); ?>>Ream</option>
                                        <option value="liter" <?php echo e(old('unit', $item->unit) == 'liter' ? 'selected' : ''); ?>>Liter</option>
                                        <option value="kilogram" <?php echo e(old('unit', $item->unit) == 'kilogram' ? 'selected' : ''); ?>>Kilogram</option>
                                        <option value="meter" <?php echo e(old('unit', $item->unit) == 'meter' ? 'selected' : ''); ?>>Meter</option>
                                        <option value="other" <?php echo e(!in_array(old('unit', $item->unit), ['piece', 'box', 'pack', 'set', 'ream', 'liter', 'kilogram', 'meter']) ? 'selected' : ''); ?>>Custom Unit</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                                
                                <!-- Custom Unit Input -->
                                <div id="customUnitContainer" class="mt-4 <?php echo e(!in_array(old('unit', $item->unit), ['piece', 'box', 'pack', 'set', 'ream', 'liter', 'kilogram', 'meter']) && old('unit', $item->unit) != '' ? '' : 'hidden'); ?>">
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="custom_unit">
                                        Custom Unit Name
                                    </label>
                                    <input
                                        type="text"
                                        name="custom_unit"
                                        id="customUnit"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                        placeholder="e.g., Bottle, Carton, Canister"
                                        value="<?php echo e(!in_array(old('unit', $item->unit), ['piece', 'box', 'pack', 'set', 'ream', 'liter', 'kilogram', 'meter']) && old('unit', $item->unit) != '' ? old('unit', $item->unit) : old('custom_unit')); ?>"
                                    >
                                </div>
                                
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <!-- Quantity and Minimum Stock -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="quantity">
                                        Current Quantity <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-boxes text-gray-400"></i>
                                        </div>
                                        <input
                                            type="number"
                                            name="quantity"
                                            id="quantity"
                                            required
                                            min="0"
                                            step="1"
                                            class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            value="<?php echo e(old('quantity', $item->quantity)); ?>"
                                            placeholder="0"
                                            readonly
                                        >
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="minimum_stock">
                                        Minimum Stock Level <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-exclamation-circle text-gray-400"></i>
                                        </div>
                                        <input
                                            type="number"
                                            name="minimum_stock"
                                            id="minimum_stock"
                                            required
                                            min="0"
                                            step="1"
                                            class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 <?php $__errorArgs = ['minimum_stock'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            value="<?php echo e(old('minimum_stock', $item->minimum_stock)); ?>"
                                            placeholder="0"
                                        >
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['minimum_stock'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="flex flex-col-reverse sm:flex-row gap-3 pt-6 border-t border-gray-200">
                                <a href="<?php echo e(route('admin.items.index')); ?>" class="inline-flex justify-center items-center gap-2 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-medium">
                                    <i class="fas fa-times"></i>
                                    Cancel
                                </a>
                                <button
                                    type="submit"
                                    class="inline-flex justify-center items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 font-medium shadow-sm hover:shadow"
                                >
                                    <i class="fas fa-save"></i>
                                    Update Item
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Stock Status and Info -->
                <div class="space-y-6">
                    <!-- Stock Status Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                            <h3 class="text-lg font-semibold text-gray-800">Stock Status</h3>
                        </div>
                        <div class="p-6">
                            <!-- Status Indicator -->
                            <div class="flex items-center justify-center mb-6">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->quantity <= 0): ?>
                                    <div class="text-center">
                                        <div class="w-20 h-20 mx-auto mb-3 rounded-full bg-red-100 flex items-center justify-center border-4 border-red-200">
                                            <i class="fas fa-times text-red-500 text-3xl"></i>
                                        </div>
                                        <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                            Out of Stock
                                        </span>
                                    </div>
                                <?php elseif($item->quantity <= $item->minimum_stock): ?>
                                    <div class="text-center">
                                        <div class="w-20 h-20 mx-auto mb-3 rounded-full bg-yellow-100 flex items-center justify-center border-4 border-yellow-200">
                                            <i class="fas fa-exclamation text-yellow-500 text-3xl"></i>
                                        </div>
                                        <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                            Low Stock
                                        </span>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center">
                                        <div class="w-20 h-20 mx-auto mb-3 rounded-full bg-green-100 flex items-center justify-center border-4 border-green-200">
                                            <i class="fas fa-check text-green-500 text-3xl"></i>
                                        </div>
                                        <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                            In Stock
                                        </span>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <!-- Stock Statistics -->
                            <div class="space-y-4">
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-blue-700">Current Stock</span>
                                        <span class="text-2xl font-bold text-blue-800"><?php echo e($item->quantity); ?></span>
                                    </div>
                                    <div class="text-xs text-blue-600">
                                        <i class="fas fa-cube mr-1"></i>
                                        <?php echo e($item->unit ? ucfirst($item->unit) . '(s)' : 'Units'); ?>

                                    </div>
                                </div>

                                <div class="bg-amber-50 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-amber-700">Minimum Required</span>
                                        <span class="text-xl font-bold text-amber-800"><?php echo e($item->minimum_stock); ?></span>
                                    </div>
                                    <div class="text-xs text-amber-600">
                                        <i class="fas fa-bell mr-1"></i>
                                        Alert when below this level
                                    </div>
                                </div>

                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

<style>
.animate-fade-in {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
}
</style>

<script>
function checkCustomUnit(selectElement) {
    const customUnitContainer = document.getElementById('customUnitContainer');
    const customUnitInput = document.getElementById('customUnit');
    
    if (selectElement.value === 'other') {
        customUnitContainer.classList.remove('hidden');
        customUnitInput.required = true;
        customUnitInput.focus();
    } else {
        customUnitContainer.classList.add('hidden');
        customUnitInput.required = false;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const unitSelect = document.getElementById('unit');
    const customUnitContainer = document.getElementById('customUnitContainer');
    const customUnitInput = document.getElementById('customUnit');
    
    // Show custom unit if other is selected
    if (unitSelect && unitSelect.value === 'other') {
        customUnitContainer.classList.remove('hidden');
        customUnitInput.required = true;
    }
    
    // Stock level indicator animation
    const progressBar = document.querySelector('.h-2.5 div');
    if (progressBar) {
        setTimeout(() => {
            progressBar.style.transition = 'width 1s ease-in-out';
        }, 100);
    }
    
    // Add input validation for quantity and minimum stock
    const quantityInput = document.getElementById('quantity');
    const minStockInput = document.getElementById('minimum_stock');
    
    if (quantityInput && minStockInput) {
        function validateStockLevels() {
            const quantity = parseInt(quantityInput.value) || 0;
            const minStock = parseInt(minStockInput.value) || 0;
            
            if (quantity < minStock) {
                quantityInput.classList.add('border-red-500');
                minStockInput.classList.add('border-red-500');
            } else {
                quantityInput.classList.remove('border-red-500');
                minStockInput.classList.remove('border-red-500');
            }
        }
        
        quantityInput.addEventListener('input', validateStockLevels);
        minStockInput.addEventListener('input', validateStockLevels);
    }
});
</script><?php /**PATH C:\Users\Jhon Rhey\Downloads\Land Bank Inventory System\Land Bank Inventory System\inventory-system\resources\views/items/edit.blade.php ENDPATH**/ ?>