<?php $__env->startSection('title', 'Create Item - Inventory System'); ?>

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
                <?php echo e(__('Add New Item')); ?>

            </h2>
            <a href="<?php echo e(route('admin.items.index')); ?>" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                Back to Inventory
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                            <div class="flex">
                                <div class="py-1">
                                    <svg class="h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold">Success!</p>
                                    <p><?php echo e(session('success')); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                            <div class="flex">
                                <div class="py-1">
                                    <svg class="h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold">Error</p>
                                    <p><?php echo e(session('error')); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <form method="POST" action="<?php echo e(route('admin.items.store')); ?>" id="itemForm">
                        <?php echo csrf_field(); ?>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                                Item/Description 
                            </label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e(old('name')); ?>"
                                placeholder="Enter Item/Description (e.g., Apple Tree, Printer Paper)"
                                onkeyup="preserveCase(this)"
                                onblur="preserveCase(this)"
                            >
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs italic mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="quantity">
                                    Quantity 
                                </label>
                                <input
                                    type="number"
                                    name="quantity"
                                    id="quantity"
                                    required
                                    min="0"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e(old('quantity', 0)); ?>"
                                >
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs italic mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="unit">
                                    Unit 
                                </label>
                                
                                <div class="mb-2">
                                    <select
                                        name="unit"
                                        id="unit"
                                        required
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline <?php $__errorArgs = ['unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        onchange="checkCustomUnit(this)"
                                    >
                                        <option value="">-- Select a unit --</option>
                                        <option value="box" <?php echo e(old('unit') == 'box' ? 'selected' : ''); ?>>Box</option>
                                        <option value="pieces" <?php echo e(old('unit') == 'pieces' ? 'selected' : ''); ?>>Pieces</option>
                                        <option value="ream" <?php echo e(old('unit') == 'ream' ? 'selected' : ''); ?>>Ream</option>
                                        <option value="set" <?php echo e(old('unit') == 'set' ? 'selected' : ''); ?>>Set</option>
                                        <option value="pack" <?php echo e(old('unit') == 'pack' ? 'selected' : ''); ?>>Pack</option>
                                        <option value="piece" <?php echo e(old('unit') == 'piece' ? 'selected' : ''); ?>>Piece</option>
                                        <option value="other">Other (custom)...</option>
                                    </select>
                                </div>
                                
                                <div id="customUnitContainer" class="hidden">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="custom_unit">
                                        Custom Unit
                                    </label>
                                    <input
                                        type="text"
                                        name="custom_unit"
                                        id="customUnit"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        placeholder="Enter your custom unit"
                                        value="<?php echo e(old('custom_unit')); ?>"
                                        onkeyup="preserveCase(this)"
                                        onblur="preserveCase(this)"
                                    >
                                </div>
                                
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs italic mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="minimum_stock">
                                Minimum Stock Level
                            </label>
                            <input
                                type="number"
                                name="minimum_stock"
                                id="minimum_stock"
                                required
                                min="0"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline <?php $__errorArgs = ['minimum_stock'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e(old('minimum_stock', 10)); ?>"
                            >
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['minimum_stock'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs italic mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <a href="<?php echo e(route('admin.items.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150">
                                Cancel
                            </a>
                            <button
                                type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150"
                            >
                                Add Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to preserve the original case of input
        function preserveCase(input) {
            // Store the original cursor position
            const start = input.selectionStart;
            const end = input.selectionEnd;
            
            // Get the value and keep it as-is (no auto-capitalization)
            // Only trim whitespace
            const value = input.value;
            
            // If you want to capitalize only the first letter of each word (optional):
            // Uncomment below if you want "Apple Tree" format
            /*
            if (value && value.length > 0) {
                // Capitalize first letter of each word, but preserve other letters
                const words = value.split(' ');
                const capitalizedWords = words.map(word => {
                    if (word.length > 0) {
                        return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
                    }
                    return word;
                });
                input.value = capitalizedWords.join(' ');
            }
            */
            
            // Restore cursor position
            input.setSelectionRange(start, end);
        }

        function checkCustomUnit(selectElement) {
            const customContainer = document.getElementById('customUnitContainer');
            const customUnitInput = document.getElementById('customUnit');
            
            if (selectElement.value === 'other') {
                customContainer.classList.remove('hidden');
                customUnitInput.required = true;
                customUnitInput.focus();
            } else {
                customContainer.classList.add('hidden');
                customUnitInput.required = false;
                customUnitInput.value = '';
            }
        }
        
        function validateForm(event) {
            const form = document.getElementById('itemForm');
            const unitSelect = document.getElementById('unit');
            const customUnitInput = document.getElementById('customUnit');
            
            // Preserve case before submission
            const nameInput = document.getElementById('name');
            preserveCase(nameInput);
            
            if (customUnitInput) {
                preserveCase(customUnitInput);
            }
            
            // If "other" is selected and custom unit is visible
            if (unitSelect.value === 'other') {
                if (!customUnitInput.value.trim()) {
                    event.preventDefault();
                    alert('Please enter a custom unit.');
                    customUnitInput.focus();
                    return false;
                }
                
                // Create a hidden input with the custom unit value
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'unit';
                hiddenInput.value = customUnitInput.value.trim();
                form.appendChild(hiddenInput);
                
                // Disable the original select to avoid conflict
                unitSelect.disabled = true;
            }
            
            // Basic validation
            const name = document.getElementById('name').value.trim();
            const quantity = document.getElementById('quantity').value;
            const minStock = document.getElementById('minimum_stock').value;
            
            if (!name) {
                event.preventDefault();
                alert('Please enter an item name.');
                document.getElementById('name').focus();
                return false;
            }
            
            if (!unitSelect.value || unitSelect.value === '') {
                event.preventDefault();
                alert('Please select or enter a unit.');
                unitSelect.focus();
                return false;
            }
            
            if (!quantity || quantity < 0) {
                event.preventDefault();
                alert('Please enter a valid quantity (0 or greater).');
                document.getElementById('quantity').focus();
                return false;
            }
            
            if (!minStock || minStock < 0) {
                event.preventDefault();
                alert('Please enter a valid minimum stock level (0 or greater).');
                document.getElementById('minimum_stock').focus();
                return false;
            }
            
            return true;
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // If returning with validation errors, check if custom unit was being used
            const unitSelect = document.getElementById('unit');
            const customUnitValue = "<?php echo e(old('custom_unit')); ?>";
            
            if (customUnitValue && !unitSelect.value) {
                // Show custom unit input
                document.getElementById('customUnitContainer').classList.remove('hidden');
                document.getElementById('customUnit').value = customUnitValue;
                unitSelect.value = 'other';
            }
            
            // Add form validation before submission
            const form = document.getElementById('itemForm');
            form.addEventListener('submit', function(e) {
                return validateForm(e);
            });
            
            // Prevent browser auto-capitalization
            const nameInput = document.getElementById('name');
            nameInput.setAttribute('autocomplete', 'off');
            nameInput.setAttribute('autocorrect', 'off');
            nameInput.setAttribute('autocapitalize', 'none');
            nameInput.setAttribute('spellcheck', 'false');
            
            // Also for custom unit input if it exists
            const customUnitInput = document.getElementById('customUnit');
            if (customUnitInput) {
                customUnitInput.setAttribute('autocomplete', 'off');
                customUnitInput.setAttribute('autocorrect', 'off');
                customUnitInput.setAttribute('autocapitalize', 'none');
                customUnitInput.setAttribute('spellcheck', 'false');
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
<?php endif; ?><?php /**PATH C:\Users\Jhon Rhey\Downloads\Land Bank Inventory System\Land Bank Inventory System\inventory-system\resources\views\items\create.blade.php ENDPATH**/ ?>