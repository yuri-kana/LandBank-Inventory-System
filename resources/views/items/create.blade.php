@section('title', 'Create Item - Inventory System')

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New Item') }}
            </h2>
            <a href="{{ route('admin.items.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                Back to Inventory
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    {{-- Display any success/error messages --}}
                    @if(session('success'))
                        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                            <div class="flex">
                                <div class="py-1">
                                    <svg class="h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold">Success!</p>
                                    <p>{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                            <div class="flex">
                                <div class="py-1">
                                    <svg class="h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold">Error</p>
                                    <p>{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.items.store') }}" id="itemForm">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                                Item/Description 
                            </label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
                                value="{{ old('name') }}"
                                placeholder="Enter Item/Description (e.g., Apple Tree, Printer Paper)"
                                onkeyup="preserveCase(this)"
                                onblur="preserveCase(this)"
                            >
                            @error('name')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
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
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('quantity') border-red-500 @enderror"
                                    value="{{ old('quantity', 0) }}"
                                >
                                @error('quantity')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
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
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('unit') border-red-500 @enderror"
                                        onchange="checkCustomUnit(this)"
                                    >
                                        <option value="">-- Select a unit --</option>
                                        <option value="box" {{ old('unit') == 'box' ? 'selected' : '' }}>Box</option>
                                        <option value="pieces" {{ old('unit') == 'pieces' ? 'selected' : '' }}>Pieces</option>
                                        <option value="ream" {{ old('unit') == 'ream' ? 'selected' : '' }}>Ream</option>
                                        <option value="set" {{ old('unit') == 'set' ? 'selected' : '' }}>Set</option>
                                        <option value="pack" {{ old('unit') == 'pack' ? 'selected' : '' }}>Pack</option>
                                        <option value="piece" {{ old('unit') == 'piece' ? 'selected' : '' }}>Piece</option>
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
                                        value="{{ old('custom_unit') }}"
                                        onkeyup="preserveCase(this)"
                                        onblur="preserveCase(this)"
                                    >
                                </div>
                                
                                @error('unit')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
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
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('minimum_stock') border-red-500 @enderror"
                                value="{{ old('minimum_stock', 10) }}"
                            >
                            @error('minimum_stock')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('admin.items.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150">
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
            const customUnitValue = "{{ old('custom_unit') }}";
            
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
</x-app-layout>