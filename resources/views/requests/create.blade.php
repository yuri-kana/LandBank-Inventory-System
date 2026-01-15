@section('title', 'Item Request - Inventory System')

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('New Item Request') }}
                </h2>
                <p class="text-gray-600 mt-1">
                    {{ __('For ') . auth()->user()->team->name }}
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('requests.index') }}" 
                   class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2.5 rounded-lg font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Requests
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- Debug Info - Remove in production -->
            @if(config('app.debug') && false)
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <h4 class="font-semibold text-yellow-800 mb-2">Debug Info</h4>
                <div class="text-sm text-yellow-700">
                    <p>Total Items: {{ $items->count() }}</p>
                    <p>Available Items: {{ $items->where('available_for_request', '>', 0)->count() }}</p>
                    <div class="mt-2 space-y-2">
                        @foreach($items as $item)
                        <div class="p-2 bg-yellow-100 rounded">
                            <strong>{{ $item->name }}:</strong><br>
                            - Quantity: {{ $item->quantity }}<br>
                            - Pending/Approved Requests: {{ $item->pending_quantity_sum ?? 0 }}<br>
                            - Available for Request: {{ $item->available_for_request ?? 0 }}<br>
                            - Is Available: {{ $item->is_available ? 'Yes' : 'No' }}
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Debug: Check if item is pre-selected -->
            <div class="hidden">
                <p>Request item_id: {{ request('item_id') }}</p>
                <p>Selected item object: {{ $selectedItem ? $selectedItem->id . ' - ' . $selectedItem->name : 'null' }}</p>
            </div>

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center gap-2 text-red-800 font-medium mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Please fix the following errors:
                    </div>
                    <ul class="text-sm text-red-600 list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center gap-2 text-red-800 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Confirmation Modal -->
            <div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
                <div class="relative top-20 mx-auto p-6 border w-full max-w-md shadow-xl rounded-lg bg-white">
                    <!-- Modal Header -->
                    <div class="mb-5">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">Confirm Request</h3>
                                <p class="text-sm text-gray-600">Inventory System</p>
                            </div>
                        </div>
                        
                        <!-- Message Box -->
                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 mb-5">
                            <p class="text-sm font-medium text-gray-700" id="modalMessage"></p>
                        </div>
                    </div>
                    
                    <!-- Available Quantity Section -->
                    <div class="mb-5">
                        <div class="flex items-center justify-between mb-3">
                            <label class="text-sm font-medium text-gray-700">Available:</label>
                            <span class="text-sm font-semibold text-green-600" id="modalAvailableQuantity">0 units</span>
                        </div>
                        <div class="text-xs text-gray-500 mb-2">Maximum quantity you can request:</div>
                        <div class="text-lg font-bold text-blue-600" id="modalMaxQuantityDisplay">0 units</div>
                    </div>
                    
                    <!-- Request Summary -->
                    <div class="mb-5">
                        <h4 class="font-semibold text-gray-800 mb-3 text-sm">Request Summary</h4>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg overflow-hidden">
                            <table class="w-full">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Item Selected</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Quantity Requested</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-t border-gray-200">
                                        <td class="px-4 py-3 text-sm text-gray-800" id="modalSummaryItem">-</td>
                                        <td class="px-4 py-3 text-sm font-medium text-blue-600" id="modalSummaryQuantity">-</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="border-t border-gray-200">
                                <div class="grid grid-cols-2 divide-x divide-gray-200">
                                    <div class="p-3">
                                        <p class="text-xs text-gray-600 mb-1">Availability</p>
                                        <p class="text-sm font-medium text-green-600" id="modalSummaryAvailable">-</p>
                                    </div>
                                    <div class="p-3">
                                        <p class="text-xs text-gray-600 mb-1">Remaining After Request</p>
                                        <p class="text-sm font-medium" id="modalSummaryRemaining">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="flex justify-end gap-3 pt-5 border-t border-gray-200">
                        <button onclick="closeModal()" 
                                class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300">
                            Cancel
                        </button>
                        <button onclick="submitForm()" 
                                id="confirmSubmitBtn"
                                class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Submit Request
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="p-1 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                <div class="p-8">
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-800">Request Form</h3>
                        <p class="text-gray-600 mt-2">Select an item and specify the quantity you need.</p>
                    </div>

                    <form method="POST" action="{{ route('requests.store') }}" class="space-y-8" id="requestForm">
                        @csrf

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
                                        @foreach($items as $item)
                                            @php
                                                $availableQuantity = $item->available_for_request ?? 0;
                                                // Check if this item should be selected
                                                $isSelected = old('item_id') == $item->id || 
                                                             (isset($selectedItem) && $selectedItem->id == $item->id);
                                            @endphp
                                            <option value="{{ $item->id }}" 
                                                {{ $isSelected ? 'selected' : '' }}
                                                data-quantity="{{ $availableQuantity }}"
                                                data-total-quantity="{{ $item->quantity }}"
                                                data-pending="{{ $item->pending_quantity_sum ?? 0 }}"
                                                data-name="{{ $item->name }}"
                                                class="{{ $availableQuantity > 0 ? 'text-gray-800' : 'text-gray-400' }}"
                                            >
                                                {{ $item->name }} 
                                                @if($availableQuantity > 0)
                                                    • Available: {{ $availableQuantity }} units
                                                @else
                                                    • Out of Stock
                                                @endif
                                            </option>
                                        @endforeach
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
                                @error('item_id')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
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
                                        value="{{ old('quantity_requested') }}"
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
                                @error('quantity_requested')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
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
                                <a href="{{ route('requests.index') }}" 
                                   class="inline-flex items-center justify-center gap-2 bg-white border-2 border-gray-300 hover:border-gray-400 text-gray-700 hover:text-gray-800 font-semibold py-3 px-6 rounded-xl transition-all hover:shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Cancel
                                </a>
                                <button
                                    type="button"
                                    id="submitBtn"
                                    onclick="showConfirmationModal()"
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
    
    // Modal elements
    const confirmationModal = document.getElementById('confirmationModal');
    const modalMessage = document.getElementById('modalMessage');
    const modalAvailableQuantity = document.getElementById('modalAvailableQuantity');
    const modalMaxQuantityDisplay = document.getElementById('modalMaxQuantityDisplay');
    const modalSummaryItem = document.getElementById('modalSummaryItem');
    const modalSummaryQuantity = document.getElementById('modalSummaryQuantity');
    const modalSummaryAvailable = document.getElementById('modalSummaryAvailable');
    const modalSummaryRemaining = document.getElementById('modalSummaryRemaining');
    const confirmSubmitBtn = document.getElementById('confirmSubmitBtn');
    
    let selectedItemName = '';
    let availableQuantityGlobal = 0;
    
    // Function to update stock details display
    function updateStockDetails() {
        const selectedOption = itemSelect.options[itemSelect.selectedIndex];
        
        if (selectedOption.value) {
            availableQuantityGlobal = parseInt(selectedOption.getAttribute('data-quantity') || 0);
            const totalQuantity = parseInt(selectedOption.getAttribute('data-total-quantity') || 0);
            const pendingQuantity = parseInt(selectedOption.getAttribute('data-pending') || 0);
            selectedItemName = selectedOption.getAttribute('data-name') || selectedOption.textContent.split(' • ')[0];
            
            // Show stock details card
            stockDetailsCard.classList.remove('hidden');
            
            // Update stock details
            itemNameDisplay.textContent = selectedItemName;
            totalStockDisplay.textContent = `${totalQuantity} units`;
            pendingStockDisplay.textContent = `${pendingQuantity} units`;
            availableStockDisplay.textContent = `${availableQuantityGlobal} units`;
            
            // Update availability badge
            if (availableQuantityGlobal > 0) {
                const percentage = Math.round((availableQuantityGlobal / totalQuantity) * 100);
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
            if (availableQuantityGlobal > 0) {
                availableQuantityDisplay.innerHTML = `
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        <span>Available: <strong class="text-green-600">${availableQuantityGlobal}</strong> units</span>
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
            availableQuantityGlobal = parseInt(selectedOption.getAttribute('data-quantity') || 0);
            const totalQuantity = parseInt(selectedOption.getAttribute('data-total-quantity') || 0);
            
            summaryCard.classList.remove('hidden');
            summaryItem.textContent = selectedItemName;
            summaryQuantity.textContent = `${quantity} units`;
            
            if (availableQuantityGlobal > 0) {
                const remaining = availableQuantityGlobal - quantity;
                summaryAvailability.innerHTML = `<span class="text-green-600">${availableQuantityGlobal} units available</span>`;
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
        availableQuantityGlobal = parseInt(selectedOption.getAttribute('data-quantity') || 0);
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
        if (availableQuantityGlobal <= 0) {
            showError('No available units', 
                pendingQuantity > 0 ? 
                `${pendingQuantity} units are pending in other requests` : 
                'This item is currently out of stock.');
            submitBtn.disabled = true;
            return false;
        }
        
        // Validate requested quantity
        if (requestedQuantity <= 0) {
            showError('Invalid quantity', 'Please enter a quantity between 1 and ' + availableQuantityGlobal);
            quantityInput.classList.add('border-red-500');
            submitBtn.disabled = true;
            return false;
        }
        
        // Validate if requested quantity exceeds available
        if (requestedQuantity > availableQuantityGlobal) {
            showError('Quantity exceeds available stock',
                `Available: ${availableQuantityGlobal} units. ${pendingQuantity > 0 ? 
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
    
    // Function to show confirmation modal
    function showConfirmationModal() {
        if (!validateQuantity()) {
            return;
        }
        
        const selectedOption = itemSelect.options[itemSelect.selectedIndex];
        const requestedQuantity = parseInt(quantityInput.value) || 0;
        const remaining = availableQuantityGlobal - requestedQuantity;
        
        // Update modal content
        modalMessage.textContent = `Confirm request for ${requestedQuantity} units of ${selectedItemName}?`;
        modalAvailableQuantity.textContent = `${availableQuantityGlobal} units`;
        modalMaxQuantityDisplay.textContent = `${availableQuantityGlobal} units`;
        
        // Update summary
        modalSummaryItem.textContent = selectedItemName;
        modalSummaryQuantity.textContent = `${requestedQuantity} units`;
        modalSummaryAvailable.textContent = `${availableQuantityGlobal} units available`;
        modalSummaryRemaining.textContent = remaining > 0 ? `${remaining} units` : '0 units';
        modalSummaryRemaining.className = remaining > 0 ? 'text-sm font-medium text-green-600' : 'text-sm font-medium text-amber-600';
        
        // Show modal
        confirmationModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    // Function to close modal
    function closeModal() {
        confirmationModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    // Function to submit form
    function submitForm() {
        // Disable confirm button to prevent double submission
        confirmSubmitBtn.disabled = true;
        confirmSubmitBtn.innerHTML = 'Submitting...';
        
        // Submit the form
        form.submit();
    }
    
    // Event Listeners
    itemSelect.addEventListener('change', function() {
        updateStockDetails();
        validateQuantity();
        updateSummary();
        
        // Set max attribute on quantity input
        const selectedOption = itemSelect.options[itemSelect.selectedIndex];
        availableQuantityGlobal = parseInt(selectedOption.getAttribute('data-quantity') || 0);
        
        if (selectedOption.value && availableQuantityGlobal > 0) {
            quantityInput.max = availableQuantityGlobal;
            quantityInput.placeholder = `Max: ${availableQuantityGlobal}`;
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
    
    // Close modal when clicking outside
    confirmationModal.addEventListener('click', function(e) {
        if (e.target === confirmationModal) {
            closeModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !confirmationModal.classList.contains('hidden')) {
            closeModal();
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
        
        // Set initial placeholder
        if (itemSelect.value) {
            const selectedOption = itemSelect.options[itemSelect.selectedIndex];
            availableQuantityGlobal = parseInt(selectedOption.getAttribute('data-quantity') || 0);
            if (availableQuantityGlobal > 0) {
                quantityInput.max = availableQuantityGlobal;
                quantityInput.placeholder = `Max: ${availableQuantityGlobal}`;
            }
        }
    });
    </script>
</x-app-layout>